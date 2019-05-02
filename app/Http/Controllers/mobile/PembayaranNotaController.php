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
            ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->select('s_company as s_name', 'po_nota', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_date'), DB::raw('replace(format(round(pop_value),0), ",", ".") as pop_value'))
            ->where('po_status', '=', 'BELUM')
            ->orderBy('pop_datetop', 'desc')
            ->groupBy('po_id')
            ->get();

        return json_encode(["NotaOrderProduksi" => $data]);
    }
}
