<?php

namespace App\Http\Controllers\mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use CodeGenerator;

class ReturnProduksiController extends Controller
{
    public function getSupplier()
    {
        $data = DB::table('m_supplier')
            ->join('d_productionorder', 'po_supplier', '=', 's_id')
            ->select('s_name', 's_company', 's_id')
            ->groupBy('s_id')
            ->get();

        return json_encode(["SupplierProduksi" => $data]);
    }

    public function getNotaProduksi(Request $request)
    {
        $tglawal = Carbon::createFromFormat("d-m-Y", $request->tglawal)->format("Y-m-d");
        $tglakhir = Carbon::createFromFormat("d-m-Y", $request->tglakhir)->format("Y-m-d");
        $supplier = $request->supplier;

        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->select('po_nota', DB::raw('date_format(po_date, "%d-%m-%Y") as po_date'), DB::raw('concat(count(po_id), " Barang") as jumlah'))
            ->where('po_supplier', '=', $supplier)
            ->where('po_date', '<=', $tglakhir)
            ->where('po_date', '>=', $tglawal)
            ->groupBy('po_id')
            ->get();

        return json_encode(["NotaProduksi" => $data]);
    }

    public function getItemNota(Request $request)
    {
        $nota = $request->nota;
        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'po_id', '=', 'pod_productionorder')
            ->join('m_item', 'pod_item', '=', 'i_id')
            ->join('m_unit', 'pod_unit', '=', 'u_id')
            ->select('i_name', 'u_name', 'pod_qty', 'i_id')
            ->where('po_nota', '=', $nota)
            ->where('pod_received', '=', 'Y')
            ->get();

        return json_encode(["ListBarangNotaProduksi" => $data]);
    }

    public function getDataItem(Request $request)
    {
        $item = $request->item;
        $data = DB::table('m_item')
            ->join('m_unit as u1', 'u1.u_id', '=', 'i_unit1')
            ->join('m_unit as u2', 'u2.u_id', '=', 'i_unit2')
            ->join('m_unit as u3', 'u3.u_id', '=', 'i_unit3')
            ->select('u1.u_name as satuan1', 'u2.u_name as satuan2', 'u3.u_name as satuan3', 'i_unit1', 'i_unit2', 'i_unit3')
            ->first();

        return json_encode(["SatuanBarangReturnProduksi" => $data]);
    }

    public function addReturn(Request $request)
    {
        $notaPo = $request->nota;
        $item = $request->item;
        $qty = $request->kuantitas;
        $jenis = $request->jenis;
        $note = $request->note;
        $unit = $request->satuan;

        $po = DB::table('d_productionorder')->where('po_nota', '=', $notaPo)->first();
        $poid = $po->po_id;
        $detailid = (DB::table('d_returnproductionorder')
            ->where('rpo_productionorder', $poid)
            ->max('rpo_detailid')) ? DB::table('d_returnproductionorder')
                ->where('rpo_productionorder', $poid)
                ->max('rpo_detailid') + 1 : 1;

        $nota = CodeGenerator::codeWithSeparator('d_returnproductionorder', 'rpo_nota', 15, 10, 3, 'RETURN-PO', '/');

        $data_check = DB::table('d_productionorder')
            ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
                'm_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3', 'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_value as value')
            ->join('d_productionorderdt', function ($x) use ($item) {
                $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
                $x->where('d_productionorderdt.pod_item', '=', $item);
            })
            ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
            ->where('d_productionorder.po_id', '=', $poid)
            ->first();

        $qty_compare = 0;
        if ($unit == $data_check->unit1) {
            $qty_compare = $qty;
        } else if ($unit == $data_check->unit2) {
            $qty_compare = $qty * $data_check->compare2;
        } else if ($unit == $data_check->unit3) {
            $qty_compare = $qty * $data_check->compare3;
        }

        DB::beginTransaction();
        try {
            $values = [
                'rpo_productionorder' => $poid,
                'rpo_detailid' => $detailid,
                'rpo_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                'rpo_nota' => $nota,
                'rpo_item' => $item,
                'rpo_qty' => $qty_compare,
                'rpo_action' => $jenis,
                'rpo_note' => $note
            ];

            //            update stock
            $comp = "MB0000001";
            $get_stock = Stock::where('s_comp', $comp)
                ->where('s_position', $comp)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE');

            $get_stockmutation = StockMutation::where('sm_stock', $get_stock->first()->s_id)
                ->where('sm_nota', $notaPo);

            if ($get_stock->count() > 0) {
                $val_stock = [
                    's_qty' => $get_stock->first()->s_qty - $qty_compare
                ];
            } else {
                return Response::json([
                    'status' => "Failed",
                    'message' => "Stock tidak ditemukan"
                ]);
            }

            if ($get_stockmutation->count() > 0) {
                if ($get_stockmutation->first()->sm_use == $get_stockmutation->first()->sm_qty || $get_stockmutation->first()->sm_residue == 0) {
                    return Response::json([
                        'status' => "Failed",
                        'message' => "Jumlah barang tidak tersedia"
                    ]);
                } else if ($get_stockmutation->first()->sm_use < $get_stockmutation->first()->sm_qty) {
                    Mutasi::MutasiKeluarWithReff(15, $comp, $comp, $item, $qty, $nota, $notaPo);
                }
            } else {
                return Response::json([
                    'status' => "Failed",
                    'message' => "Stock mutasi tidak ditemukan"
                ]);
            }

            //            insert return
            DB::table('d_returnproductionorder')->insert($values);
            $get_stock->update($val_stock);
            //            $get_stockmutation->update($val_stockmutation);
            DB::commit();

            return Response::json([
                'status' => "Success"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => "Failed"
            ]);
        }
    }
}
