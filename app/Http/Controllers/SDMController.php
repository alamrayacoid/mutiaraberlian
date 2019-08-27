<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Auth;
use Crypt;
use Response;
use App\Http\Controllers\AksesUser;
use Yajra\DataTables\DataTables;

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

    public function cariHariLibur(Request $request){
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        DB::beginTransaction();
        try {
            $data = DB::table('m_holyday')
                ->select('hd_id', DB::raw('date_format(hd_date, "%d-%m-%Y") as tanggal'), 'hd_note');

            if ($bulan != 'all') {
                $data = $data->whereMonth('hd_date', '=', $bulan);
            }
            if ($tahun != '' || $tahun !== null) {
                $data = $data->whereYear('hd_date', '=', $tahun);
            }

            $data = $data->orderBy('hd_date', 'asc')->get();

            DB::commit();
            return response()->json($data);
        } catch(\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }

    public function getDetailHariLibur(Request $request){
        $id = $request->id;

        $data = DB::table('m_holyday')
            ->select('hd_id', DB::raw('date_format(hd_date, "%d-%m-%Y") as hd_date'), 'hd_note')
            ->where('hd_id', '=', $id)
            ->first();

        return response()->json($data);
    }

    public function updateDetailHariLibur(Request $request){
        if (!AksesUser::checkAkses(27, 'update')) {
            return Response::json([
                'status' => "gagal",
                'message' => "Anda tidak memiliki akses"
            ]);
        }

        $tanggal = Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
        $note = $request->note;

        DB::beginTransaction();
        try {
            DB::table('m_holyday')
                ->where('hd_date', '=', $tanggal)
                ->update([
                    'hd_note' => $note
                ]);

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }

    public function hapusHariLibur(Request $request){
        if (!AksesUser::checkAkses(27, 'delete')) {
            return Response::json([
                'status' => "gagal",
                'message' => "Anda tidak memiliki akses"
            ]);
        }

        $id = $request->id;

        DB::beginTransaction();
        try {
            $data = DB::table('m_holyday')
                ->where('hd_id', '=', $id)
                ->delete();

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch(\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }


    // Aturan kehadiran
    public function getDataAturanKehadiran(){
        $data = DB::table('m_attendancerules')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($data){
                return '<center>
                <button class="btn btn-primary btn-sm btn-terima" onclick="editAturanKehadiran(\''.Crypt::encrypt($data->ar_id).'\')">Edit</button>
                <button class="btn btn-warning btn-sm btn-bayar" onclick="hapusAturanKehadiran(\''.Crypt::encrypt($data->ar_id).'\')">Hapus</button>
                </center>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function saveAturanKehadiran(Request $request){
        $aturan = $request->rule;
        $punishment = $request->punishment;
        $note = $request->note;

        DB::beginTransaction();
        try {
            $id = DB::table('m_attendancerules')
                ->max('ar_id');

            ++$id;

            DB::table('m_attendancerules')
                ->insert([
                    'ar_id' => $id,
                    'ar_rules' => $aturan,
                    'ar_punishment' => $punishment,
                    'ar_note' => $note
                ]);

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }

    public function getDetailAturanKehadiran(Request $request){
        $id = Crypt::decrypt($request->id);

        $data = DB::table('m_attendancerules')
            ->where('ar_id', '=', $id)
            ->first();

        return response()->json($data);
    }

    public function updateAturanKehadiran(Request $request){
        $aturan = $request->rule;
        $punishment = $request->punishment;
        $note = $request->note;
        $id = $request->id;

        DB::beginTransaction();
        try {

            DB::table('m_attendancerules')
                ->where('ar_id', '=', $id)
                ->update([
                    'ar_rules' => $aturan,
                    'ar_punishment' => $punishment,
                    'ar_note' => $note
                ]);

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }

    public function hapusAturanKehadiran(Request $request){
        $id = Crypt::decrypt($request->id);

        DB::beginTransaction();
        try {

            DB::table('m_attendancerules')
                ->where('ar_id', '=', $id)
                ->delete();

            DB::commit();
            return Response::json([
                'status' => "sukses"
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return Response::json([
                'status' => "gagal",
                'message' => $e
            ]);
        }
    }

}
