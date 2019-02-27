<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use Validator;
use carbon\Carbon;
use Yajra\Datatables\Datatables;

class MasterController extends Controller
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

    public function create_golonganharga()
    {
        return view('masterdatautama.harga.golongan.create');
    }

    public function edit_golonganharga()
    {
        return view('masterdatautama.harga.golongan.edit');
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
    public function get_list_cabang()
    {
      // start: get data from db and return it using DataTable
      $datas = DB::table('m_company')->orderBy('c_nama', 'asc')->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
          return '<div class="btn-group btn-group-sm">
          <button class="btn btn-warning" onclick="EditCabang('.$datas->c_id.')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
          <button class="btn btn-danger" onclick="DeleteCabang('.$datas->c_id.')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
          </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
        // end: return DataTable
    }
    public function get_codecabang()
    {

    }
    public function create_cabang()
    {
      $id = DB::table('m_company')->max('c_id') + 1;
      return view('masterdatautama.cabang.create', compact('id'));
    }
    public function store_cabang(Request $request)
    {
      // \LogActivity::addToLog('store-datasatuan');
      // start: validate data before execute
      $messages = [
        'cabang_name.required' => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_telp.required' => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'cabang_name' => 'required',
        'cabang_address' => 'required',
        'cabang_telp' => 'required'
      ], $messages);
      if($validator->fails())
      {
        $errors = $validator->errors()->first();
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // end: validate
      // start: execute insert data
      DB::beginTransaction();
      try {
        $id = DB::table('m_company')->max('c_id') + 1;
        DB::table('m_company')
          ->insert([
            'c_id' => $id,
            'c_nama' => $request->cabang_name,
            'c_alamat' => $request->cabang_address,
            'c_no_telp' => $request->cabang_telp,
            'c_type' => $request->cabang_type,
            'c_insert' => Carbon::now(),
            'c_update' => Carbon::now()
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute insert data
    }
    public function edit_cabang($id)
    {
      $data['cabang'] = DB::table('m_company')
      ->where('c_id', $id)
      ->first();
      return view('masterdatautama.cabang.edit', compact('data'));
    }
    public function update_cabang(Request $request, $id)
    {
      // start: validate data before execute
      $messages = [
        'cabang_name.required' => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_telp.required' => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'cabang_name' => 'required',
        'cabang_address' => 'required',
        'cabang_telp' => 'required'
      ], $messages);
      if($validator->fails())
      {
        $errors = $validator->errors()->first();
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // end: validate
      // start: execute update data
      DB::beginTransaction();
      try {
        DB::table('m_company')
          ->where('c_id', $id)
          ->update([
            'c_nama' => $request->cabang_name,
            'c_alamat' => $request->cabang_address,
            'c_no_telp' => $request->cabang_telp,
            'c_type' => $request->cabang_type,
            'c_update' => Carbon::now()
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute update data
    }
    public function delete_cabang($id)
    {
      // start: execute delete data
      DB::beginTransaction();
      try {
        DB::table('m_company')
          ->where('c_id', $id)
          ->delete();

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute delete data
    }

    // * Master Satuan

    public function datasatuan()
    {
      $id = DB::table('m_unit')->max('u_id') + 1;
      return view('masterdatautama/datasatuan/index', compact('id'));
    }
    public function create_datasatuan()
    {
      $id = DB::table('m_unit')->max('u_id') + 1;
      return view('masterdatautama/datasatuan/create', compact('id'));
    }
    public function store_datasatuan(Request $request)
    {
      // \LogActivity::addToLog('store-datasatuan');
      // start: validate data before execute
      $messages = [
        'satuan_name.required' => 'Nama satuan masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'satuan_name' => 'required'
      ], $messages);
      if($validator->fails())
      {
        $errors = $validator->errors()->first();
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // end: validate
      // start: execute insert data
      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->insert([
            'u_id' => $request->satuan_id,
            'u_name' => $request->satuan_name
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute insert data
    }
    public function edit_datasatuan($id)
    {
      $data['datasatuan'] = DB::table('m_unit')
      ->where('u_id', $id)
      ->first();
      return view('masterdatautama/datasatuan/edit', compact('data'));
    }
    public function update_datasatuan(Request $request, $id)
    {
      // \LogActivity::addToLog('store-datasatuan');
      // start: validate data before execute
      $messages = [
        'satuan_name.required' => 'Nama satuan masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'satuan_name' => 'required'
      ], $messages);
      if($validator->fails())
      {
        $errors = $validator->errors()->first();
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // end: validate
      // start: execute update data
      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->where('u_id', $id)
          ->update([
            'u_name' => $request->satuan_name
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute insert data
    }
    public function delete_datasatuan($id)
    {
      // start: execute delete data
      DB::beginTransaction();
      try {
        DB::table('m_unit')
          ->where('u_id', $id)
          ->delete();

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
      // end: execute delete data
    }


    // =================================== End Master Data Utama ===================================


}
