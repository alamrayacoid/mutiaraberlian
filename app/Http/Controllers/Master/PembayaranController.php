<?php

namespace App\Http\Controllers\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
    //
    public function index()
    {
        $akun = DB::table('dk_akun')
            ->where('ak_comp', '=', 'MB0000001')
            ->get();
        return view('masterdatautama.pembayaran.index', compact('akun'));
    }

    public function save(Request $request)
    {
        $nama = $request->nama;
        $note = $request->note;
        $akun = $request->akun;

        DB::beginTransaction();
        try {
            $id = DB::table('m_paymentmedthod')
                ->max('pm_id');
            ++$id;

            DB::table('m_paymentmethod')
                ->insert([
                    'pm_id' => $id,
                    'pm_name' => $nama,
                    'note' => $note,
                    'akun' => $akun,
                    'pm_isactive' => 'Y'
                ]);

            DB::commit();
            return Response::json([
                'status' => 'sukses'
            ]);
        } catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getData(Request $request)
    {
        $data = DB::table('m_paymentmethod')->get();


    }

}
