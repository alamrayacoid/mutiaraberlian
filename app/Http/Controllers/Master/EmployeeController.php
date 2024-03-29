<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use Auth;
use File;
use Validator;
use DataTables;
use CodeGenerator;
use Carbon\Carbon;
use App\m_employee;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('masterdatautama.datapegawai.index');
    }

    public function getData(Request $request)
    {
        $status = $request->status;

        $datas = DB::table('m_employee');
        if ($status != '') {
            $datas = $datas->where('e_isactive', $status);
        }
        $datas = $datas->join('m_jabatan', 'e_position', 'j_id')
            ->select('m_employee.*', 'j_name')
            ->orderBy('e_name', 'asc')
            ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('nik', function ($datas) {
                if ($datas->e_isactive == "Y") {
                    return '' . $datas->e_nik . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->e_nik . '</strike></i>';
                }
            })
            ->addColumn('name', function ($datas) {
                if ($datas->e_isactive == "Y") {
                    return '' . $datas->e_name . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->e_name . '</strike></i>';
                }
            })
            ->addColumn('jabatan', function ($datas) {
                if ($datas->e_isactive == "Y") {
                    return '' . $datas->j_name . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->j_name . '</strike></i>';
                }
            })
            ->addColumn('status', function ($datas) {
                if ($datas->e_isactive == "Y") {
                    return '<div class="text-center">
            <span class="badge badge-success btn-block py-2">AKTIF</span>
          </div>';
                } else {
                    return '<div class="text-center">
            <span class="badge badge-danger btn-block py-2">NON AKTIF</span>
          </div>';
                }
            })
            ->addColumn('action', function ($datas) {
                if ($datas->e_isactive == "Y") {
                    return '<div class="text-center">
            <div class="btn-group btn-group-sm">
              <button class="btn btn-info hint--top  hint--info" aria-label="Lihat Detail" onclick="detail(\'' . Crypt::encrypt($datas->e_id) . '\')"><i class="fa fa-folder"></i></button>
              <button type="button" class="btn btn-warning hint--top  hint--warning" aria-label="Edit Pegawai" onclick="editPegawai(\'' . Crypt::encrypt($datas->e_id) . '\')"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-danger hint--top  hint--error" aria-label="Nonaktifkan" onclick="nonActive(\'' . Crypt::encrypt($datas->e_id) . '\')"><i class="fa fa-times"></i></button>
            </div>
          </div>';
                } else {
                    return '<div class="text-center">
            <div class="btn-group btn-group-sm">
              <button class="btn btn-info hint--top  hint--info" aria-label="Lihat Detail" onclick="detail(\'' . Crypt::encrypt($datas->e_id) . '\')"><i class="fa fa-folder"></i></button>
              <button class="btn btn-disabled disabled" style="cursor:not-allowed;" onclick="editPegawai(\'' . Crypt::encrypt($datas->e_id) . '\')" disabled><i class="fa fa-pencil"></i></button>
              <button class="btn btn-success hint--top  hint--success" aria-label="Aktifkan" onclick="active(\'' . Crypt::encrypt($datas->e_id) . '\')"><i class="fa fa-check"></i></button>
            </div>
          </div>';
                }
            })
            ->rawColumns(['nik', 'name', 'jabatan', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        if (!AksesUser::checkAkses(2, 'create')) {
            abort(401);
        }
        $jabatan = DB::table('m_jabatan')->select('m_jabatan.*')->get();
        $divisi = DB::table('m_divisi')->select('m_divisi.*')->get();
        $company = DB::table('m_company')->select('m_company.*')
            ->where(function ($q) {
                $q->where('c_type', 'CABANG')
                    ->orWhere('c_type', 'PUSAT');
            })
            ->where('c_isactive', '=', 'Y')
            ->get();
        return view('masterdatautama.datapegawai.create', compact('jabatan', 'divisi', 'company'));
    }

    public function store(Request $request)
    {
        $messages = [
            'e_company.required' => 'Cabang masih kosong, silahkan isi terlebih dahulu !',
            'e_nip.required' => 'NIP masih kosong, silahkan isi terlebih dahulu !',
            'e_name.required' => 'Nama masih kosong, silahkan isi terlebih dahulu !',
            'e_nik.required' => 'NIK masih kosong, silahkan isi terlebih dahulu !',
            'e_telp.required' => 'Telepon masih kosong, silahkan isi terlebih dahulu !',
            'e_religion.required' => 'Agama masih kosong, silahkan isi terlebih dahulu !',
            'e_gender.required' => 'Jenis Kelamin masih kosong, silahkan isi terlebih dahulu !',
            'e_maritalstatus.required' => 'Status masih kosong, silahkan isi terlebih dahulu !',
            'e_birth.required' => 'Tgl Lahir masih kosong, silahkan isi terlebih dahulu !',
            'e_education.required' => 'Pendidikan masih kosong, silahkan isi terlebih dahulu !',
            'e_email.required' => 'Email masih kosong, silahkan isi terlebih dahulu !',
            'e_position.required' => 'Jabatan masih kosong, silahkan isi terlebih dahulu !',
            'e_department.required' => 'Divisi masih kosong, silahkan isi terlebih dahulu !',
            'e_address.required' => 'Alamat masih kosong, silahkan isi terlebih dahulu !',
            'e_bank.required' => 'Bank masih kosong, silahkan isi terlebih dahulu !',
            'e_rekening.required' => 'Rekening masih kosong, silahkan isi terlebih dahulu !',
            'e_an.required' => 'AN masih kosong, silahkan isi terlebih dahulu !'
        ];
        $validator = Validator::make($request->all(), [
            'e_company' => 'required',
            'e_nip' => 'required',
            'e_name' => 'required',
            'e_nik' => 'required',
            'e_telp' => 'required',
            'e_religion' => 'required',
            'e_gender' => 'required',
            'e_maritalstatus' => 'required',
            'e_birth' => 'required',
            'e_education' => 'required',
            'e_email' => 'required',
            'e_position' => 'required',
            'e_department' => 'required',
            'e_address' => 'required',
            'e_bank' => 'required',
            'e_rekening' => 'required',
            'e_an' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        $empCode = CodeGenerator::code('m_employee', 'e_id', 7, 'EMP');

        if ($request->hasFile('e_foto')) {
            $imageName = $empCode . '-photo';
            $photo = $request->file('e_foto')->storeAs('Employees', $imageName);
        }
        else {
            $photo = null;
        }

        DB::beginTransaction();
        try {
            DB::table('m_employee')->insert([
                'e_id' => $empCode,
                'e_company' => $request->e_company,
                'e_nip' => $request->e_nip,
                'e_name' => strtoupper($request->e_name),
                'e_nik' => $request->e_nik,
                'e_telp' => $request->e_telp,
                'e_religion' => $request->e_religion,
                'e_gender' => $request->e_gender,
                'e_matename' => $request->e_matename,
                'e_maritalstatus' => $request->e_maritalstatus,
                'e_child' => $request->e_child,
                'e_birth' => date('Y-m-d', strtotime($request->e_birth)),
                'e_workingyear' => date('Y-m-d', strtotime($request->e_workingyear)),
                'e_education' => $request->e_education,
                'e_email' => $request->e_email,
                'e_position' => $request->e_position,
                'e_department' => $request->e_department,
                'e_address' => $request->e_address,
                'e_bank' => $request->e_bank,
                'e_rekening' => $request->e_rekening,
                'e_an' => $request->e_an,
                'e_isactive' => "Y",
                'e_foto' => $photo
            ]);
            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($id = null, Request $request)
    {
        if (!$request->isMethod('post')) {
            if (!AksesUser::checkAkses(2, 'update')){
                abort(401);
            }
            try {
                $id = Crypt::decrypt($id);
            } catch (\Exception $e) {
                return view('errors.404');
            }
            $jabatan = DB::table('m_jabatan')->select('m_jabatan.*')->get();
            $divisi = DB::table('m_divisi')->select('m_divisi.*')->get();
            $company = DB::table('m_company')->select('m_company.*')
                ->where('c_type', '!=', 'AGEN')
                ->where('c_isactive', '=', 'Y')
                ->get();
            $employee = DB::table('m_employee')
                ->leftJoin('m_company', 'e_company', 'c_id')
                ->leftJoin('m_jabatan', 'e_position', 'j_id')
                ->leftJoin('m_divisi', 'e_department', 'm_id')
                ->select('m_employee.*', DB::raw('date_format(e_birth, "%d-%m-%Y") as e_birth'), DB::raw('date_format(e_workingyear, "%d-%m-%Y") as e_workingyear'), 'c_name', 'j_name', 'm_name')
                ->where('e_id', '=', $id)
                ->first();
            return view('masterdatautama.datapegawai.edit', compact('employee', 'company', 'jabatan', 'divisi'));
        } else {
            if (!AksesUser::checkAkses(2, 'update')){
                return response()->json(['status' => "unauth"]);
            }
            try {
                $id = Crypt::decrypt($id);
            } catch (\Exception $e) {
                return view('errors.404');
            }
            $messages = [
                'e_company.required' => 'Cabang masih kosong, silahkan isi terlebih dahulu !',
                'e_nip.required' => 'NIP masih kosong, silahkan isi terlebih dahulu !',
                'e_name.required' => 'Nama masih kosong, silahkan isi terlebih dahulu !',
                'e_nik.required' => 'NIK masih kosong, silahkan isi terlebih dahulu !',
                'e_telp.required' => 'Telepon masih kosong, silahkan isi terlebih dahulu !',
                'e_religion.required' => 'Agama masih kosong, silahkan isi terlebih dahulu !',
                'e_gender.required' => 'Jenis Kelamin masih kosong, silahkan isi terlebih dahulu !',
                'e_maritalstatus.required' => 'Status masih kosong, silahkan isi terlebih dahulu !',
                'e_birth.required' => 'Tgl Lahir masih kosong, silahkan isi terlebih dahulu !',
                'e_education.required' => 'Pendidikan masih kosong, silahkan isi terlebih dahulu !',
                'e_email.required' => 'Email masih kosong, silahkan isi terlebih dahulu !',
                'e_position.required' => 'Jabatan masih kosong, silahkan isi terlebih dahulu !',
                'e_department.required' => 'Divisi masih kosong, silahkan isi terlebih dahulu !',
                'e_address.required' => 'Alamat masih kosong, silahkan isi terlebih dahulu !',
                'e_bank.required' => 'Bank masih kosong, silahkan isi terlebih dahulu !',
                'e_rekening.required' => 'Rekening masih kosong, silahkan isi terlebih dahulu !',
                'e_an.required' => 'AN masih kosong, silahkan isi terlebih dahulu !'
            ];
            $validator = Validator::make($request->all(), [
                'e_company' => 'required',
                'e_nip' => 'required',
                'e_name' => 'required',
                'e_nik' => 'required',
                'e_telp' => 'required',
                'e_religion' => 'required',
                'e_gender' => 'required',
                'e_maritalstatus' => 'required',
                'e_birth' => 'required',
                'e_education' => 'required',
                'e_email' => 'required',
                'e_position' => 'required',
                'e_department' => 'required',
                'e_address' => 'required',
                'e_bank' => 'required',
                'e_rekening' => 'required',
                'e_an' => 'required',
            ], $messages);

            if ($validator->fails()) {
                $errors = $validator->errors()->first();
                return response()->json([
                    'status' => 'invalid',
                    'message' => $errors
                ]);
            }
            DB::beginTransaction();
            try {
                // dd($request->hasFile('e_foto'));
                if ($request->hasFile('e_foto')) {
                    $dataImg = $request->file('e_foto');

                    // if ($dataImg->isValid()) {
                    //     $file = $request->current_foto;
                    //     if ($file != "") {
                    //         $path = 'assets/uploads/pegawai/' . $file;
                    //         if (File::exists($path)) {
                    //             File::delete($path);
                    //         }
                    //     }
                    //     $imageName = $input['imageName'] = time() . '.' . $dataImg->getClientOriginalName();
                    //     $pathOri = 'assets/uploads/pegawai';
                    //     $dataImg->move($pathOri, $imageName);
                    // }
                    // $photos = $imageName;

                    $imageName = $id . '-photo';
                    // delete current photo
                    // Storage::delete('Employees/'.$imageName);
                    // insert new photo
                    $photos = $request->file('e_foto')->storeAs('Employees', $imageName);
                } else {
                    $photos = $request->current_foto;
                }

                DB::table('m_employee')
                    ->where('e_id', $id)
                    ->update([
                        'e_company' => $request->e_company,
                        'e_nip' => $request->e_nip,
                        'e_name' => strtoupper($request->e_name),
                        'e_nik' => $request->e_nik,
                        'e_workingdays' => $request->e_workingdays,
                        'e_telp' => $request->e_telp,
                        'e_religion' => $request->e_religion,
                        'e_gender' => $request->e_gender,
                        'e_matename' => $request->e_matename,
                        'e_maritalstatus' => $request->e_maritalstatus,
                        'e_child' => $request->e_child,
                        'e_birth' => date('Y-m-d', strtotime($request->e_birth)),
                        "e_workingyear" => date('Y-m-d', strtotime($request->e_workingyear)),
                        'e_education' => $request->e_education,
                        'e_email' => $request->e_email,
                        'e_position' => $request->e_position,
                        'e_department' => $request->e_department,
                        'e_address' => $request->e_address,
                        'e_bank' => $request->e_bank,
                        'e_rekening' => $request->e_rekening,
                        'e_an' => $request->e_an,
                        'e_isactive' => "Y",
                        'e_foto' => $photos
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

    // public function getImage($foto)
    // {
    //     if ($foto != null) {
    //         $imageName = $input['imageName'] = time() . '.' . $foto->getClientOriginalName();
    //         $pathOri = 'assets/uploads/pegawai';
    //         $foto->move($pathOri, $imageName);
    //         return $imageName;
    //     }
    // }

    public function nonActive($id)
    {
        if (!AksesUser::checkAkses(2, 'delete')){
            return response()->json(['status' => "unauth"]);
        }
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
        DB::beginTransaction();
        try {
            DB::table('m_employee')
                ->where('e_id', $id)
                ->update([
                    'e_isactive' => "N"
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
        if (!AksesUser::checkAkses(2, 'update')){
            return response()->json(['status' => "unauth"]);
        }
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
        DB::beginTransaction();
        try {
            DB::table('m_employee')
                ->where('e_id', $id)
                ->update([
                    'e_isactive' => "Y"
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

    public function detail($id)
    {
        if (!AksesUser::checkAkses(2, 'read')){
            return response()->json(['status' => "unauth"]);
        }
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }
        $employee = DB::table('m_employee')
            ->leftJoin('m_company', 'e_company', 'c_id')
            ->leftJoin('m_jabatan', 'e_position', 'j_id')
            ->leftJoin('m_divisi', 'e_department', 'm_id')
            ->select('m_employee.*',
                DB::raw('date_format(e_birth, "%d/%m/%Y") as e_birth'),
                DB::raw('date_format(e_workingyear, "%d/%m/%Y") as e_workingyear'),
                'c_name', 'j_name', 'm_name')
            ->where('e_id', '=', $id)
            ->first();
        return view('masterdatautama.datapegawai.detail', compact('employee'));
    }
}
