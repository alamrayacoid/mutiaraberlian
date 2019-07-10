<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
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
                            <button class="btn btn-warning btn-edit-masterkpi btn-sm" type="button" onclick="nonakt" title="Non-aktifkan"><i class="fa fa-close"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm" type="button" title="Hapus"><i class="fa fa-trash"></i></button>
                        </div>';
                } else {
                    return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
                            <button class="btn btn-success btn-edit-masterkpi btn-sm" type="button" title="Aktifkan"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm" type="button" title="Hapus"><i class="fa fa-trash"></i></button>
                        </div>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
