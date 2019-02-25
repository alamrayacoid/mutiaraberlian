<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use Validator;
use carbon\Carbon;
use Yajra\Datatables\Datatables;

class SatuanController extends Controller
{
    public function index()
    {
      $id = DB::table('m_unit')->max('u_id') + 1;
      return view('masterdatautama/datasatuan/index', compact('id'));
    }

    public function list_satuan()
    {
      $datas = DB::table('m_unit')->orderBy('u_name', 'asc')->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
          return '<div class="text-center">
                  <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-warning" onclick="editSatuan(\''.$datas->u_id.'\',\''.$datas->u_name.'\')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-danger" onclick="deleteSatuan('.$datas->u_id.')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
                  </div>
                  </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function tambahSatuan(Request $request)
    {
      $s_id = $request->id;
      $s_name = $request->name;

      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->insert([
            'u_id' => $s_id,
            'u_name' => $s_name
          ]);

        DB::commit();
        return response()->json([
          'status' => 'sukses'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }

    public function updateSatuan(Request $request)
    {
      $s_id = $request->id;
      $s_name = $request->name;

      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->where('u_id', '=', $s_id)
          ->update([
            'u_name' => $s_name
          ]);

        DB::commit();
        return response()->json([
          'status' => 'sukses'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }

    public function deleteSatuan($id)
    {
      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->where('u_id', $id)
          ->delete();

        DB::commit();
        return response()->json([
          'status' => 'sukses'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }


    // =================================== End Master Data Utama ===================================


}
