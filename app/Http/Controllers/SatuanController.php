<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Validator;
use carbon\Carbon;
use Yajra\Datatables\Datatables;

class SatuanController extends Controller
{
    // =============================== Master Data Utama ===============================

    //  * Master Data Pegawai

    public function kelolapegawai()
    {
        return view('masterdatautama.datapegawai.index');
    }
    public function edit_kelolapegawai()
    {
        return view('masterdatautama.datapegawai.edit');
    }
    public function create_kelolapegawai()
    {
        return view('masterdatautama.datapegawai.create');
    }

    // Master Variasi Satuan Produk

    public function variasisatuanproduk()
    {
        return view('masterdatautama.variasisatuan.index');
    }

    public function create_variasisatuanproduk()
    {
        return view('masterdatautama.variasisatuan.create');
    }

    public function edit_variasisatuanproduk()
    {
        return view('masterdatautama.variasisatuan.edit');
    }

    // * Master Harga

    public function dataharga()
    {
        return view('masterdatautama.harga.index');
    }

    public function create_dataharga()
    {
        return view('masterdatautama.harga.create');
    }

    public function edit_dataharga()
    {
        return view('masterdatautama.harga.edit');
    }

    // * Master Suplier

    public function suplier()
    {
        return view('masterdatautama.suplier.index');
    }

    public function create_suplier()
    {
        return view('masterdatautama.suplier.create');
    }

    public function edit_suplier()
    {
        return view('masterdatautama.suplier.edit');
    }

    public function produkpersuplier()
    {
        return view('masterdatautama.suplier.produkpersuplier.index');
    }

    // * Master Cabang

    public function cabang()
    {
        return view('masterdatautama.cabang.index');
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
