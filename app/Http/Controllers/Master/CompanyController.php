<?php

namespace App\Http\Controllers\master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
use DB;
use Auth;
use CodeGenerator;

class CompanyController extends Controller
{
    public function index()
    {
        return view('masterdatautama.cabang.index');
    }

    public function getData()
    {
        $datas = DB::table('m_company')->orderBy('c_name', 'asc');
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-warning" onclick="EditCabang(\''.Crypt::encrypt($datas->c_id).'\')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger" onclick="DeleteCabang(\''.Crypt::encrypt($datas->c_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
                        </div></div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        if (!$request->isMethod('post')) {
            return view('masterdatautama.cabang.create');
        } else {
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
                DB::table('m_company')
                    ->insert([
                        'c_id' => CodeGenerator::code('m_company', 'c_id', 7, 'MB'),
                        'c_name' => strtoupper($request->cabang_name),
                        'c_address' => $request->cabang_address,
                        'c_tlp' => $request->cabang_telp,
                        'c_type' => $request->cabang_type,
                        'c_insert' => Carbon::now('Asia/Jakarta'),
                        'c_update' => Carbon::now('Asia/Jakarta')
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
                ->where('c_id', $id)
                ->first();
            return view('masterdatautama.cabang.edit', compact('data'));
        } else {
            try{
                $id = Crypt::decrypt($id);
            }catch (\Exception $e){
                return view('errors.404');
            }
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
                        'c_name' => strtoupper($request->cabang_name),
                        'c_address' => $request->cabang_address,
                        'c_tlp' => $request->cabang_telp,
                        'c_type' => $request->cabang_type,
                        'c_update' => Carbon::now('Asia/Jakarta')
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
        }
    }

    public function delete($id)
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
    }
}
