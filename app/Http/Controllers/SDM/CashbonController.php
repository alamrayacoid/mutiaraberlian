<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\DataTables\DataTables;
use DB;
use Crypt;
use Response;

class CashbonController extends Controller
{
    public function getData(Request $request){
        $pegawai = $request->pegawai;
        $cashbontop = $request->cashbontop;
        $cashbonbot = $request->cashbonbot;

        $data = DB::table('m_employee')
            ->select('e_name', 'e_nip', DB::raw('CONCAT("Rp ", REPLACE(FORMAT(e_cashbon, 0), ",", ".")) AS e_cashbon'), 'e_id', DB::raw('round(e_cashbon) as cashbon'))
            ->where('e_isactive', '=', 'Y');

        if ($pegawai !== null && $pegawai !== '') {
            $data = $data->where('e_id', '=', $pegawai);
        }
        if ($cashbontop !== null && $cashbontop !== '') {
            $data = $data->where('e_cashbon', '<=', $cashbontop);
        }
        if ($cashbonbot !== null && $cashbonbot !== '') {
            $data = $data->where('e_cashbon', '>=', $cashbonbot);
        }
        $data = $data->orderBy('e_name')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($data){
                return '<center>
                <button class="btn btn-primary btn-sm btn-terima" onclick="terimaCashbon(\''.Crypt::encrypt($data->e_id).'\', \''.$data->cashbon.'\')">Terima</button>
                <button class="btn btn-warning btn-sm btn-bayar" onclick="tambahCashbon(\''.Crypt::encrypt($data->e_id).'\', \''.$data->cashbon.'\')">Tambah</button>
                </center>';
            })
            ->editColumn('e_cashbon', function($data){
                return "<span class='float-right'>".$data->e_cashbon."</span>";
            })
            ->rawColumns(['aksi', 'e_cashbon'])
            ->make(true);
    }

    public function getDataPegawai(Request $request){
        $cari = $request->term;

        $data = DB::table('m_employee')
            ->select('e_name', 'e_nip', 'e_id')
            ->where(function($q) use ($cari){
                $q->orWhere('e_nip', 'like', '%'.$cari.'%');
                $q->orWhere('e_name', 'like', '%'.$cari.'%');
            })
            ->where('e_isactive', '=', 'Y')
            ->get();

        if (count($data) < 1) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($data as $query) {
                $results[] = ['id' => $query->e_id, 'label' => $query->e_nip . ' - ' . strtoupper($query->e_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }
}
