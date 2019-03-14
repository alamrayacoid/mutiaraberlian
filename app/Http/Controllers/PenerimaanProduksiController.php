<?php

namespace App\Http\Controllers;
use DB;
use DataTables;
use Illuminate\Http\Request;
use Crypt;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;

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
            ->addColumn('nota', function($data){
                return $data->po_nota;
            })
            ->addColumn('supplier', function($data){
                return $data->s_name;
            })
            ->addColumn('tanggal', function($data){
                return Carbon::createFromFormat('Y-m-d', $data->po_date)->format('d-m-Y');;
            })
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-folder"></i>
                        </button>
                        <button class="btn btn-info hint--bottom-left hint--info" aria-label="Terima" onclick="terima(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-check"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['nota', 'supplier', 'tanggal', 'action'])
            ->make(true);
    }
}
