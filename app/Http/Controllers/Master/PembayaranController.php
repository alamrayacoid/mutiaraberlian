<?php

namespace App\Http\Controllers\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Response;
use Crypt;
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
            $id = DB::table('m_paymentmethod')
                ->max('pm_id');
            ++$id;

            DB::table('m_paymentmethod')
                ->insert([
                    'pm_id' => $id,
                    'pm_name' => $nama,
                    'pm_note' => $note,
                    'pm_akun' => $akun,
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

    public function getDataPembayaran(Request $request)
    {
        $data = DB::table('m_paymentmethod')
            ->join('dk_akun', 'ak_id', '=', 'pm_akun')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($data){
                if ($data->pm_isactive == 'Y'){
                    return "Aktif";
                } else {
                    return "Tidak Aktif";
                }
            })
            ->addColumn('aksi', function ($data){
                if ($data->pm_isactive == 'Y'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-warning detail hint--top hint--warning" type="button"
                                    aria-label="Edit" onclick="edit(\'' . Crypt::encrypt($data->pm_id) . '\')"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-warning detail hint--top hint--warning" type="button"
                                    aria-label="Disable" onclick="disable(\'' . Crypt::encrypt($data->pm_id) . '\')"><i class="fa fa-close"></i></button>
                            <button class="btn btn-danger detail hint--top hint--danger" aria-label="Hapus"
                                    type="button" onclick="hapus(\'' . Crypt::encrypt($data->pm_id) . '\')"><i class="fa fa-trash"></i>
                            </button>
                        </div></center>';
                } else {
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-success detail hint--top hint--success" type="button"
                                    aria-label="Enable" onclick="enable(\'' . Crypt::encrypt($data->pm_id) . '\')"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger detail hint--top hint--danger" aria-label="Hapus"
                                    type="button" onclick="hapus(\'' . Crypt::encrypt($data->pm_id) . '\')"><i class="fa fa-trash"></i>
                            </button>
                        </div></center>';
                }
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function delete(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        DB::beginTransaction();
        try {

            DB::table('m_paymentmethod')
                ->where('pm_id', '=', $id)
                ->delete();

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

    public function enable(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        DB::beginTransaction();
        try {

            DB::table('m_paymentmethod')
                ->where('pm_id', '=', $id)
                ->update([
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

    public function disable(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        DB::beginTransaction();
        try {

            DB::table('m_paymentmethod')
                ->where('pm_id', '=', $id)
                ->update([
                    'pm_isactive' => 'N'
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

    public function detail(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        $data = DB::table('m_paymentmethod')
            ->where('pm_id', '=', $id)
            ->first();

        return json_encode($data);
    }

    public function update(Request $request)
    {
        $nama = $request->nama;
        $note = $request->note;
        $akun = $request->akun;
        $pm_id = $request->id;

        DB::beginTransaction();
        try {
            DB::table('m_paymentmethod')
                ->where('pm_id', '=', $pm_id)
                ->update([
                    'pm_name' => $nama,
                    'pm_note' => $note,
                    'pm_akun' => $akun,
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

}
