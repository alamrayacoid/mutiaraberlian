<?php

namespace App\Http\Controllers\mobile;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class PembayaranNotaController extends Controller
{
    public function getSupplier()
    {
        $data = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->select( 's_name', DB::raw('count(po_supplier) as jumlah'))
            ->where('po_status', '=', 'BELUM')
            ->groupBy('po_supplier')
            ->get();

        return json_encode($data);
    }
}
