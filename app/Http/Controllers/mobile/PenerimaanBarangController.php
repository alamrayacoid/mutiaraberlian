<?php

namespace App\Http\Controllers\mobile;

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
}
