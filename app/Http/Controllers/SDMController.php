<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Auth;
use Response;
use App\Http\Controllers\AksesUser;

class SDMController extends Controller
{
    public function kinerja()
    {
    	return view('sdm/kinerjasdm/index');
    }
    public function absensi()
    {
    	return view('sdm/absensisdm/index');
    }
    public function penggajian()
    {
    	return view('sdm/penggajian/index');
    }
    public function create_manajemen()
    {
        return view('sdm/penggajian/manajemen/tambah_manajemen');
    }
    public function edit_manajemen()
    {
        return view('sdm/penggajian/manajemen/edit_manajemen');
    }
    public function create_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/tambah_tunjangan');
    }
    public function edit_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/edit_tunjangan');
    }
    public function set_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/set_tunjangan');
    }
    public function edit_set_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/edit_set_tunjangan');
    }
    public function create_produksi()
    {
        return view('sdm/penggajian/produksi/tambah_produksi');
    }
    public function edit_produksi()
    {
        return view('sdm/penggajian/produksi/edit_produksi');
    }

// Kelola Hari Libur
    public function saveHariLibur(Request $request){
        if (!AksesUser::checkAkses(27, 'create')) {
            return Response::json([
                'status' => "gagal",
                'message' => "Anda tidak memiliki akses"
            ]);
        }

        DB::beginTransaction();
        try {
            $tgl = Carbon::createFromFormat('d-m-Y', $request->tgl);
            $note = $request->note;

            $cek = DB::table('m_holyday')
                ->where('hd_date', '=', $tgl->format('Y-m-d'))
                ->first();

            if ($cek !== null) {
                return Response::json([
                    'status' => "gagal",
                    'message' => "Data sudah ada"
                ]);
            }

            $id = DB::table('m_holyday')
                ->max('hd_id');
            ++$id;

            DB::table('m_holyday')
                ->insert([
                    'hd_id' => $id,
                    'hd_date' => $tgl->format('Y-m-d'),
                    'hd_note' => $note
                ]);

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }
}
