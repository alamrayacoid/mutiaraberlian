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

class EmployeeController extends Controller
{
    public function index()
    {
        return view('masterdatautama.datapegawai.index');
    }

    public function getData()
    {
      $datas = DB::table('m_employee')->orderBy('e_name', 'asc');
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
          return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                    <button type="button" class="btn btn-warning" onclick="editPegawai(\''.Crypt::encrypt($datas->e_id).'\')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger" onclick="deletePegawai(\''.Crypt::encrypt($datas->e_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
                    </div></div>';
          })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create()
    {
      $jabatan = DB::table('m_jabatan')->select('m_jabatan.*')->get();
      $divisi  = DB::table('m_divisi')->select('m_divisi.*')->get();
      $company = DB::table('m_company')->select('m_company.*')
        ->where('c_type', '!=', 'AGEN')
        ->where('c_isactive', '=', 'Y')
        ->get();
      return view('masterdatautama.datapegawai.create', compact('jabatan', 'divisi', 'company'));
    }

    public function store(Request $request)
    {
      $messages = [
        'e_company.required'       => 'Cabang masih kosong, silahkan isi terlebih dahulu !',
        'e_nip.required'           => 'NIP masih kosong, silahkan isi terlebih dahulu !',
        'e_name.required'          => 'Nama masih kosong, silahkan isi terlebih dahulu !',
        'e_nik.required'           => 'NIK masih kosong, silahkan isi terlebih dahulu !',
        'e_telp.required'          => 'Telepon masih kosong, silahkan isi terlebih dahulu !',
        'e_religion.required'      => 'Agama masih kosong, silahkan isi terlebih dahulu !',
        'e_gender.required'        => 'Jenis Kelamin masih kosong, silahkan isi terlebih dahulu !',
        'e_maritalstatus.required' => 'Status masih kosong, silahkan isi terlebih dahulu !',
        'e_birth.required'         => 'Tgl Lahir masih kosong, silahkan isi terlebih dahulu !',
        'e_education.required'     => 'Pendidikan masih kosong, silahkan isi terlebih dahulu !',
        'e_email.required'         => 'Email masih kosong, silahkan isi terlebih dahulu !',
        'e_position.required'      => 'Jabatan masih kosong, silahkan isi terlebih dahulu !',
        'e_department.required'    => 'Divisi masih kosong, silahkan isi terlebih dahulu !',
        'e_address.required'       => 'Alamat masih kosong, silahkan isi terlebih dahulu !',
        'e_bank.required'          => 'Bank masih kosong, silahkan isi terlebih dahulu !',
        'e_rekening.required'      => 'Rekening masih kosong, silahkan isi terlebih dahulu !',
        'e_an.required'            => 'AN masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'e_company'       => 'required',
        'e_nip'           => 'required',
        'e_name'          => 'required',
        'e_nik'           => 'required',
        'e_telp'          => 'required',
        'e_religion'      => 'required',
        'e_gender'        => 'required',
        'e_maritalstatus' => 'required',
        'e_birth'         => 'required',
        'e_education'     => 'required',
        'e_email'         => 'required',
        'e_position'      => 'required',
        'e_department'    => 'required',
        'e_address'       => 'required',
        'e_bank'          => 'required',
        'e_rekening'      => 'required',
        'e_an'            => 'required'
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
        DB::table('m_employee')
        ->insert([
          'e_id'            => CodeGenerator::code('m_employee', 'e_id', 7, 'EMP'),
          'e_company'       => $request->e_company,
          'e_nip'           => $request->e_nip,
          'e_name'          => strtoupper($request->e_name),
          'e_nik'           => $request->e_nik,
          'e_workingdays'   => $request->e_workingdays,
          'e_telp'          => $request->e_telp,
          'e_religion'      => $request->e_religion,
          'e_gender'        => $request->e_gender,
          'e_matename'      => $request->e_matename,
          'e_maritalstatus' => $request->e_maritalstatus,
          'e_child'         => $request->e_child,
          'e_birth'         => date('Y-m-d', strtotime($request->e_birth)),
          "e_workingyear"   => $request->e_workingyear,
          'e_education'     => $request->e_education,
          'e_email'         => $request->e_email,
          'e_position'      => $request->e_position,
          'e_department'    => $request->e_department,
          'e_address'       => $request->e_address,
          'e_bank'          => $request->e_bank,
          'e_rekening'      => $request->e_rekening,
          'e_an'            => $request->e_an,
          'e_isactive'      => "Y",
          'e_foto'          => $request->e_foto
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
        $jabatan = DB::table('m_jabatan')->select('m_jabatan.*')->get();
        $divisi  = DB::table('m_divisi')->select('m_divisi.*')->get();
        $company = DB::table('m_company')->select('m_company.*')
          ->where('c_type', '!=', 'AGEN')
          ->where('c_isactive', '=', 'Y')
          ->get();
        $employee = DB::table('m_employee')
          ->leftJoin('m_company', 'e_company', 'c_id')
          ->leftJoin('m_jabatan', 'e_position', 'j_id')
          ->leftJoin('m_divisi', 'e_department', 'm_id')
          ->select('m_employee.*', 'c_name', 'j_name', 'm_name')
          ->where('e_id', '=', $id)
          ->first();
        return view('masterdatautama.datapegawai.edit', compact('employee', 'company', 'jabatan', 'divisi'));
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

    // public function delete($id)
    // {
    //     try{
    //         $id = Crypt::decrypt($id);
    //     }catch (\Exception $e){
    //         return response()->json([
    //             'status' => 'gagal',
    //             'message' => $e
    //         ]);
    //     }
    //     DB::beginTransaction();
    //     try {
    //         DB::table('m_company')
    //             ->where('c_id', $id)
    //             ->delete();

    //         DB::commit();
    //         return response()->json([
    //             'status' => 'berhasil'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 'gagal',
    //             'message' => $e
    //         ]);
    //     }
    // }

}
