<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\m_jabatan;
use Carbon\Carbon;
use DB;
use Yajra\Datatables\Datatables;

class KpsController extends Controller
{
    // retrieve dataTable for list of position
    public function getTableKPS(Request $request)
    {
        $datas = m_jabatan::orderBy('j_name', 'asc')->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('position', function ($datas) {
                return $datas->j_name;
            })
            ->addColumn('action', function ($datas) {
                if ($datas->j_id < 10) {
                    return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-warning hint--top hint--warning fa-disabled" onclick="editKPS('. $datas->j_id .')" rel="tooltip" data-placement="top" aria-label="Edit data" disabled><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-danger hint--top hint--error fa-disabled" onclick="deleteKPS('. $datas->j_id .')" rel="tooltip" data-placement="top" aria-label="Hapus data" disabled><i class="fa fa-close"></i></button>
                    </div>
                    </div>';
                }
                else {
                    return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-warning hint--top hint--warning" onclick="editKPS('. $datas->j_id .')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-danger hint--top hint--error" onclick="deleteKPS('. $datas->j_id .')" rel="tooltip" data-placement="top" aria-label="Hapus data"><i class="fa fa-close"></i></button>
                    </div>
                    </div>';
                }
            })
            ->rawColumns(['position', 'action'])
            ->make(true);
        ;

    }
    // store data
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $newPoss = new m_jabatan;
            $newPoss->j_id = m_jabatan::max('j_id') + 1;
            $newPoss->j_name = $request->positionName;
            $newPoss->j_web = 'N';
            $newPoss->j_mobile = 'N';
            $newPoss->save();

            DB::commit();
            return response()->json([
              'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'gagal',
              'message' => $e->getMessage()
            ]);
        }
    }
    // edit data
    public function edit($id)
    {
        DB::beginTransaction();
        try {
            $data = m_jabatan::where('j_id', $id)->first();

            DB::commit();
            return response()->json([
                'status' => 'berhasil',
                'positionName' => $data->j_name
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'gagal',
              'message' => $e->getMessage()
            ]);
        }
    }
    // update data
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = m_jabatan::where('j_id', $id)->first();
            $data->j_name = $request->positionName;
            $data->save();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'gagal',
              'message' => $e->getMessage()
            ]);
        }
    }
    // delete data
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = m_jabatan::where('j_id', $id)->first();
            $data->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'gagal',
              'message' => $e->getMessage()
            ]);
        }
    }
}
