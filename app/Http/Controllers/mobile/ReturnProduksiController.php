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
            ->select('i_name', 'u_name', 'pod_qty')
            ->where('po_nota', '=', $nota)
            ->where('pod_received', '=', 'Y')
            ->get();

        return json_encode(["ListBarangNotaProduksi" => $data]);
    }
}
