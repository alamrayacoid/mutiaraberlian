<?php

namespace App\Http\Controllers\mobile;

use Illuminate\Http\Request;
use DB;
use Mutasi;
use Carbon\Carbon;
use Response;
use App\Http\Controllers\Controller;

class PenerimaanBarangController extends Controller
{
    public function getData()
    {
        $data = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->leftjoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
            ->leftjoin('d_itemreceiptdt', function($x){
                $x->on('ird_itemreceipt', '=', 'ir_id');
            })
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->where('pod_received', '=', 'N')
            ->where('d_productionorder.po_status', '=', 'BELUM')
            ->groupBy('po_id')
            ->select('po_id', 'po_nota', 's_company', 'po_date')
            ->get();

        return json_encode([
            "item" => $data
        ]);
    }

    public function getDataNota(Request $request)
    {
        $nota = $request->nota;
        $data = DB::table('d_productionorder')
            ->where('d_productionorder.po_nota', '=', $nota)
            ->join('d_productionorderdt', 'd_productionorderdt.pod_productionorder', '=', 'd_productionorder.po_id')
            ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
            ->join('m_unit', 'd_productionorderdt.pod_unit', '=', 'm_unit.u_id')
            ->select('d_productionorder.po_nota', 'm_item.i_name', 'm_item.i_id', 'm_unit.u_name', 'd_productionorderdt.pod_qty')
            ->get();

        return json_encode([
            "BarangPenerimaan" => $data
        ]);
    }

    public function getDataNotaItem(Request $request){
        $nota = $request->nota;
        $item = $request->barang;

        $order = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->where('po_nota', '=', $nota)
            ->where('pod_item', '=', $item)
            ->first();

        $data = DB::table('d_itemreceipt')
            ->join('d_itemreceiptdt', 'ir_id', '=', 'ird_itemreceipt')
            ->join('m_item', 'i_id', '=', 'ird_item')
            ->select(DB::raw('sum(ird_qty) as terima'), 'ird_unit', 'm_item.*')
            ->where('ir_notapo', '=', $nota)
            ->where('ird_item', '=', $item)
            ->groupBy('ird_item')
            ->first();

        $qty = 0;
        $satuan = $order->pod_unit;

        if ($data != null){
            // konversi terima ke unit order
            // ird_unit pasti unit1 di m_item
            if ($order->pod_unit == $data->i_unit1){
                $qty = $data->terima / $data->i_unitcompare1;
            } elseif ($order->pod_unit == $data->i_unit2){
                $qty = $data->terima / $data->i_unitcompare2;
            } elseif ($order->pod_unit == $data->i_unit3){
                $qty = $data->terima / $data->i_unitcompare3;
            }
        }
dd($nota);
        return json_encode(["qtyTerimaBarang" => $qty, "satuanTerimaBarang" => $satuan]);
    }

    public function TerimaItem(Request $request)
    {
        $nota   = $request->nota;
        $notaDo = $request->notado;
        $item    = $request->item;
        $username = $request->username;

        $data = DB::table('d_productionorder')
            ->select('po_id')
            ->where('po_nota', '=', $nota)
            ->get();

        DB::beginTransaction();
        try{
            $order = $data[0]->po_id;
            $data_check = DB::table('d_productionorder')
                ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
                    'm_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                    'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                    'm_item.i_unit3 as unit3', 'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_value as value')
                ->join('d_productionorderdt', function ($x) use ($item){
                    $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
                    $x->where('d_productionorderdt.pod_item', '=', $item);
                })
                ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
                ->where('d_productionorder.po_id', '=', $order)
                ->first();

            $nota_receipt = DB::table('d_itemreceipt')
                ->where('ir_notapo', '=', $data_check->nota);

            $idUser = DB::table('d_username')
                ->where('u_username', '=', $username)
                ->first();

            if ($nota_receipt->count() > 0) {
                $detail_receipt = (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $nota_receipt->first()->ir_id)->max('ird_detailid')) ? (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $nota_receipt->first()->ir_id)->max('ird_detailid')) + 1 : 1;

                $qty_compare = 0;
                if ($request->satuan == $data_check->unit1) {
                    $qty_compare = $request->qty;
                } else if ($request->satuan == $data_check->unit2) {
                    $qty_compare = $request->qty * $data_check->compare2;
                } else if ($request->satuan == $data_check->unit3) {
                    $qty_compare = $request->qty * $data_check->compare3;
                }

                $values = [
                    'ird_itemreceipt'  => $nota_receipt->first()->ir_id,
                    'ird_detailid'      => $detail_receipt,
                    'ird_date'          => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'ird_item'          => $item,
                    'ird_qty'           => $qty_compare,
                    'ird_unit'          => $data_check->unit1,
                    'ird_user'          => $idUser->u_id
                ];

                DB::table('d_itemreceiptdt')->insert($values);

                Mutasi::mutasimasuk(1,$idUser->u_company,$idUser->u_company, $item, $qty_compare, 'ON DESTINATION', 'FINE', $data_check->value, $data_check->value, $data_check->nota, $notaDo);
            } else {
                $id = (DB::table('d_itemreceipt')->max('ir_id')) ? (DB::table('d_itemreceipt')->max('ir_id'))+1 : 1;

                $receipt = [
                    'ir_id'     => $id,
                    'ir_notapo' => $data_check->nota
                ];

                $detail_receipt = (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $id)->max('ird_detailid')) ? (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $id)->max('ird_detailid')) + 1 : 1;

                $qty_compare = 0;
                if ($request->satuan == $data_check->unit1) {
                    $qty_compare = $request->qty;
                } else if ($request->satuan == $data_check->unit2) {
                    $qty_compare = $request->qty * $data_check->compare2;
                } else if ($request->satuan == $data_check->unit3) {
                    $qty_compare = $request->qty * $data_check->compare3;
                }

                $values = [
                    'ird_itemreceipt'  => $id,
                    'ird_detailid'      => $detail_receipt,
                    'ird_date'          => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'ird_item'          => $item,
                    'ird_qty'           => $qty_compare,
                    'ird_unit'          => $data_check->unit1,
                    'ird_user'          => $idUser->u_id
                ];

                DB::table('d_itemreceipt')->insert($receipt);
                DB::table('d_itemreceiptdt')->insert($values);
                Mutasi::mutasimasuk(1,$idUser->u_company,$idUser->u_company, $item, $qty_compare, 'ON DESTINATION', 'FINE', $data_check->value, $data_check->value, $data_check->nota, $notaDo);
            }
            $this->UpdateStatus($order, $item);
            DB::commit();
            return Response::json(['status' => 'Success']);
        }catch (\Exception $e){
            DB::rollback();
            return Response::json(['status' => 'Failed']);
        }
    }

    public function UpdateStatus($id, $item)
    {
        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'po_id', '=', 'pod_productionorder')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->where('po_id', '=', $id)
            ->where('pod_item', '=', $item)
            ->get();

        $terima = DB::table('d_itemreceipt')
            ->join('d_itemreceiptdt', 'ir_id', '=', 'ird_itemreceipt')
            ->select(DB::raw('sum(ird_qty) as diterima'), 'ird_unit')
            ->where('ir_notapo', '=', $data[0]->po_nota)
            ->where('ird_item', '=', $item)
            ->get();

        //konversi ke satuan terkecil
        $pesan = 0;
        if ($data[0]->pod_unit == $data[0]->i_unit1){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare1;
        }
        elseif ($data[0]->pod_unit == $data[0]->i_unit2){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare2;
        }
        elseif ($data[0]->pod_unit == $data[0]->i_unit3){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare3;
        }

        if ($pesan == $terima[0]->diterima){
            DB::table('d_productionorderdt')
                ->where('pod_item', '=', $item)
                ->where('pod_productionorder', '=', $id)
                ->update([
                    'pod_received' => 'Y'
                ]);

            $data = DB::table('d_productionorderdt')
                ->where('pod_productionorder', '=', $id)
                ->where('pod_received', '=', 'N')
                ->get();

            if (count($data) == 0){
                DB::table('d_productionorder')
                    ->where('po_id', '=', $id)
                    ->update([
                        'po_status' => 'Y'
                    ]);
            }
        }

    }

}
