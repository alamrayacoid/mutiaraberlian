<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use Auth;
use Validator;
use DataTables;
use CodeGenerator;
use Carbon\Carbon;

class CabangController extends Controller
{
    public function index()
    {
        return view('masterdatautama.cabang.index');
    }

    public function getData()
    {
      $datas = DB::table('m_company')
        ->orderBy('c_name', 'asc');
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('status', function($datas){
          if ($datas->c_isactive == "Y") {
            return '<div class="text-center">
                      <span class="badge badge-success btn-block py-2">AKTIF</span>
                    </div>';
          } else {
            return '<div class="text-center">
                      <span class="badge badge-danger btn-block py-2">NON AKTIF</span>
                    </div>';
          }
        })
        ->addColumn('action', function($datas) {
          if ($datas->c_isactive == "Y") {
            if ($datas->c_type == "PUSAT") {
              return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-warning" onclick="EditCabang(\''.Crypt::encrypt($datas->c_id).'\')" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-disabled disabled" onclick="nonActive(\''.Crypt::encrypt($datas->c_id).'\')" data-toggle="tooltip" data-placement="top" disabled><i class="fa fa-times"></i></button>
                        </div>
                      </div>';
            } else {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                          <button class="btn btn-warning" onclick="EditCabang(\''.Crypt::encrypt($datas->c_id).'\')" rel="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></button>
                          <button class="btn btn-danger" onclick="nonActive(\''.Crypt::encrypt($datas->c_id).'\')" data-toggle="tooltip" data-placement="top" title="Nonaktifkan"><i class="fa fa-times"></i></button>
                          </div>
                        </div>';
            }
          } else {
              return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-disabled disabled" onclick="EditCabang(\''.Crypt::encrypt($datas->c_id).'\')" data-toggle="tooltip" data-placement="top" disabled><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-success" onclick="active(\''.Crypt::encrypt($datas->c_id).'\')" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="fa fa-check"></i></button>
                        </div>
                      </div>';        
          }
        })
        ->rawColumns(['status','action'])
        ->make(true);
    }

    public function create()
    {
      $employe = DB::table('m_employee')->select('e_id', 'e_name')->get();
      $company = DB::table('m_company')->select('c_id', 'c_name')->get();
      return view('masterdatautama.cabang.create', compact('employe', 'company'));
    }

    public function store(Request $request)
    {
      $messages = [
        'cabang_name.required'    => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
        'cabang_telp.required'    => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'cabang_name'    => 'required',
        'cabang_address' => 'required',
        'cabang_telp'    => 'required'
      ], $messages);

      if($validator->fails())
      {
        $errors = $validator->errors()->first();
        return response()->json([
          'status'  => 'invalid',
          'message' => $errors
        ]);
      }
      
      DB::beginTransaction();
      try {
        DB::table('m_company')
        ->insert([
          'c_id'      => CodeGenerator::code('m_company', 'c_id', 7, 'MB'),
          'c_name'    => strtoupper($request->cabang_name),
          'c_address' => $request->cabang_address,
          'c_tlp'     => $request->cabang_telp,
          'c_type'    => $request->cabang_type,
          'c_user'    => $request->cabang_user,
          'c_insert'  => Carbon::now('Asia/Jakarta'),
          'c_update'  => Carbon::now('Asia/Jakarta')
        ]);
        DB::commit();
        return response()->json([
          'status' => 'sukses'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'Gagal',
          'message' => $e
        ]);
      }

    }

    public function edit($id = null, Request $request)
    {
        if (!$request->isMethod('post')) {
            try{
                $id = Crypt::decrypt($id);
            }catch (\Exception $e){
                return view('errors.404');
            }
            $data = DB::table('m_company')
              ->leftJoin('m_employee', 'c_user', 'e_id')
              ->select('m_company.*', 'e_id', 'e_name')
              ->where('c_id', '=', $id)
              ->first();
            $employe = DB::table('m_employee')->select('m_employee.*')->get();
            return view('masterdatautama.cabang.edit', compact('data', 'employe'));
        } else {
          try{
              $id = Crypt::decrypt($id);
          }catch (\Exception $e){
              return view('errors.404');
          }
          $messages = [
            'cabang_name.required'    => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
            'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
            'cabang_telp.required'    => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
          ];
          $validator = Validator::make($request->all(), [
            'cabang_name'    => 'required',
            'cabang_address' => 'required',
            'cabang_telp'    => 'required'
          ], $messages);

          if($validator->fails())
          {
            $errors = $validator->errors()->first();
            return response()->json([
              'status'  => 'invalid',
              'message' => $errors
            ]);
          }
          DB::beginTransaction();
          try {
            DB::table('m_company')
              ->where('c_id', $id)
              ->update([
                'c_name'    => strtoupper($request->cabang_name),
                'c_address' => $request->cabang_address,
                'c_tlp'     => $request->cabang_telp,
                'c_type'    => $request->cabang_type,
                'c_user'    => $request->cabang_user,
                'c_update'  => Carbon::now('Asia/Jakarta')
              ]);
            DB::commit();
            return response()->json([
              'status' => 'sukses'
            ]);
          } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'gagal',
              'message' => $e
            ]);
          }
        }
    }

    public function nonActive($id)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
        DB::beginTransaction();
        try {
            DB::table('m_company')
                ->where('c_id', $id)
                ->update([
                  'c_isactive' => "N"
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

    public function actived($id)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (\Exception $e){
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
        DB::beginTransaction();
        try {
            DB::table('m_company')
                ->where('c_id', $id)
                ->update([
                  'c_isactive' => "Y"
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

}
