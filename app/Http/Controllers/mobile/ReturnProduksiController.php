<?php

namespace App\Http\Controllers\mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ReturnProduksiController extends Controller
{
    public function getSupplier()
    {
        $data = DB::table('m_supplier')
            ->join('d_productionorder', 'po_supplier', '=', 's_id')
            ->select('s_name', 's_company', 's_id')
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
}
