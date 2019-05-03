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
            ->select('s_company as s_name', 'po_nota', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_date'), DB::raw('replace(format(round(pop_value - pop_pay),0), ",", ".") as pop_value'))
            ->where('po_status', '=', 'BELUM')
            ->where('pop_status', '=', 'N')
            ->orderBy('pop_datetop', 'desc')
            ->groupBy('po_id')
            ->get();

        return json_encode(["NotaOrderProduksi" => $data]);
    }

    public function getTermin(Request $request)
    {
        $nota = $request->nota;
        $data = DB::table('d_productionorderpayment')
            ->join('d_productionorder', 'po_id', '=', 'pop_productionorder')
            ->select('pop_termin', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_datetop'), 'pop_value', DB::raw('replace(format(round(pop_value - pop_pay),0), ",", ".") as pop_value'), DB::raw('replace(format(round(pop_value - pop_pay),0), ",", "") as pop_valueint'))
            ->where('po_nota', '=', $nota)
            ->where('pop_status', '=', 'N')
            ->get();

        return json_encode(["ListTerminNota" => $data]);
    }
}
