<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use DataTables;
use Response;
use App\Http\Controllers\Controller;

class MasterKPIController extends Controller
{
    public function create(Request $request)
    {
        $indikator = $request->indikator;

        DB::beginTransaction();
        try {

            $cek = DB::table('m_kpi')
                ->where('k_indicator', '=', $indikator)
                ->get();

            if (count($cek) > 0){
                DB::rollBack();
                return Response::json([
                    'status' => 'gagal',
                    'message' => 'data sudah ada'
                ]);
            }

            $id = DB::table('m_kpi')
                ->max('k_id');

            ++$id;

            DB::table('m_kpi')
                ->insert([
                    'k_id' => $id,
                    'k_indicator' => $indikator
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
        $status = $request->status;
        $data = DB::table('m_kpi');
        if ($status != 'all'){
            $data = $data->where('k_isactive', '=', $status);
        }

        $datas = $data->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                if ($datas->k_isactive == 'Y'){
                    return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
                            <button class="btn btn-warning btn-edit-masterkpi btn-sm hint--top-left hint--error" type="button" onclick="nonKpi(\''.Crypt::encrypt($datas->k_id).'\')" aria-label="Non-aktifkan"><i class="fa fa-close"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
                } else {
                    return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
                            <button class="btn btn-success btn-edit-masterkpi btn-sm hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activeKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function activeKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->update([
                    'k_isactive' => "Y"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function nonKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->update([
                    'k_isactive' => "N"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function deleteKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function kpi_create_p()
    {
        return view('sdm.kinerjasdm.kpipegawai.create');
    }
    public function kpi_create_d()
    {
        return view('sdm.kinerjasdm.kpidivisi.create');
    }
}
