<?php

namespace App\Http\Controllers;
use DB;
use DataTables;
use Illuminate\Http\Request;

class PenerimaanProduksiController extends Controller
{
    public function penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/index');
    }
    public function create_penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/create');
    }

    public function getNotaPO()
    {
        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('pod_received', '=', 'N')
            ->groupBy('po_id')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.$datas->po_id.'\')"><i class="fa fa-folder"></i>
                        </button>
                    </div>';
            })
            ->make(true);
    }
}
