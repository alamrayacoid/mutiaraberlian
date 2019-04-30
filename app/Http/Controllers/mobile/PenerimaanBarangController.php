<?php

namespace App\Http\Controllers\mobile;

use function GuzzleHttp\Promise\iter_for;
use Illuminate\Http\Request;
use DB;
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

        return json_encode(["qtyTerimaBarang" => $qty]);
    }
}
