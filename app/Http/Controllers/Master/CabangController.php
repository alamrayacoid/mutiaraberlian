<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\m_wil_kota;
use App\m_wil_provinsi;
use Carbon\Carbon;
use CodeGenerator;
use DataTables;
use DB;
use Validator;

class CabangController extends Controller
{
    public function index()
    {
        return view('masterdatautama.cabang.index');
    }

    public function getData()
    {
        $datas = DB::table('m_company')
            ->leftJoin('m_wil_kota', 'wc_id', '=', 'c_area')
            ->where(function ($q){
                $q->orWhere('c_type', '=', 'CABANG');
                // $q->orWhere('c_type', '=', 'PUSAT');
            })
            ->orderBy('c_type', 'desc');

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('name', function ($datas) {
                if ($datas->c_isactive == "Y") {
                    return '' . $datas->c_name . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->c_name . '</strike></i>';
                }
            })
            ->addColumn('alamat', function ($datas) {
                if ($datas->c_isactive == "Y") {
                    return '' . $datas->c_address . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->c_address . '</strike></i>';
                }
            })
            ->addColumn('telepon', function ($datas) {
                if ($datas->c_isactive == "Y") {
                    return '' . $datas->c_tlp . '';
                } else {
                    return '<i><strike style="color:lightgrey;">' . $datas->c_tlp . '</strike></i>';
                }
            })
            ->addColumn('status', function ($datas) {
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
            ->addColumn('action', function ($datas) {
                if ($datas->c_isactive == "Y") {
                    if ($datas->c_type == "PUSAT") {
                        return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-warning hint--top hint--warning" onclick="EditCabang(\'' . Crypt::encrypt($datas->c_id) . '\')" data-toggle="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-disabled disabled" style="cursor:not-allowed;" onclick="nonActive(\'' . Crypt::encrypt($datas->c_id) . '\')" data-toggle="tooltip" data-placement="top" disabled><i class="fa fa-times"></i></button>
                        </div>
                      </div>';
                    } else {
                        return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                          <button class="btn btn-warning hint--top hint--warning" onclick="EditCabang(\'' . Crypt::encrypt($datas->c_id) . '\')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                          <button class="btn btn-danger hint--top hint--error" onclick="nonActive(\'' . Crypt::encrypt($datas->c_id) . '\')" data-toggle="tooltip" data-placement="top" aria-label="Nonaktifkan data"><i class="fa fa-times"></i></button>
                          </div>
                        </div>';
                    }
                } else {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-disabled disabled" style="cursor:not-allowed;" onclick="EditCabang(\'' . Crypt::encrypt($datas->c_id) . '\')" data-toggle="tooltip" data-placement="top" disabled><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-success hint--top hint--error" onclick="active(\'' . Crypt::encrypt($datas->c_id) . '\')" data-toggle="tooltip" data-placement="top" aria-label="Aktifkan data"><i class="fa fa-check"></i></button>
                        </div>
                      </div>';
                }
            })
            ->rawColumns(['name', 'alamat', 'telepon', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        // check user-access of the current feature
        if (!AksesUser::checkAkses(6, 'create')) {
            abort(401);
        }
        $employe = DB::table('m_employee')->select('e_id', 'e_name')->get();
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();

        $data['provinces'] = m_wil_provinsi::orderBy('wp_id')->get();

        return view('masterdatautama.cabang.create', compact('employe', 'company', 'data'));
    }

    public function getCities(Request $request)
    {
        $cities = m_wil_kota::where('wc_provinsi', $request->provId)->get();
        return response()->json($cities);
    }

    public function store(Request $request)
    {
        // check user-access of the current feature
        if (!AksesUser::checkAkses(6, 'create')) {
            abort(401);
        }

        $messages = [
            'cabang_name.required' => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
            'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
            'cabang_city.required' => 'Area (Kota) masih kosong, silahkan isi terlebih dahulu !',
            'cabang_telp.required' => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
        ];
        $validator = Validator::make($request->all(), [
            'cabang_name' => 'required',
            'cabang_address' => 'required',
            'cabang_city' => 'required',
            'cabang_telp' => 'required'
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
            DB::table('m_company')
                ->insert([
                    'c_id' => CodeGenerator::code('m_company', 'c_id', 7, 'MB'),
                    'c_name' => strtoupper($request->cabang_name),
                    'c_address' => $request->cabang_address,
                    'c_tlp' => $request->cabang_telp,
                    'c_hp' => $request->cabang_telp2,
                    'c_type' => $request->cabang_type,
                    'c_user' => $request->cabang_user,
                    'c_area' => $request->cabang_city,
                    'c_insert' => Carbon::now('Asia/Jakarta'),
                    'c_update' => Carbon::now('Asia/Jakarta')
                ]);
            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function edit($id = null, Request $request)
    {
        // check user-access of the current feature
        if (!AksesUser::checkAkses(6, 'update')) {
            abort(401);
        }

        if (!$request->isMethod('post'))
        {
            try {
                $id = Crypt::decrypt($id);
            }
            catch (\Exception $e) {
                abort(404);
                // return view('errors.404');
            }
            $data = DB::table('m_company')
                ->leftJoin('m_employee', 'c_user', 'e_id')
                ->select('m_company.*', 'e_id', 'e_name')
                ->where('c_id', '=', $id)
                ->first();

            $employe = DB::table('m_employee')->select('m_employee.*')->get();
            $provinces = m_wil_provinsi::orderBy('wp_id')->get();
            $selectedProvId = m_wil_kota::where('wc_id', $data->c_area)->select('wc_provinsi')->first();
            $cities = [];
            if ($selectedProvId != null){
                $cities = m_wil_kota::where('wc_provinsi', $selectedProvId->wc_provinsi)->get();
            }

            return view('masterdatautama.cabang.edit', compact('data', 'employe', 'provinces', 'selectedProvId', 'cities'));
        }
        else {
            try {
                $id = Crypt::decrypt($id);
            }
            catch (\Exception $e) {
                abort(404);
                // return view('errors.404');
            }
            $messages = [
                'cabang_name.required' => 'Nama cabang masih kosong, silahkan isi terlebih dahulu !',
                'cabang_address.required' => 'Alamat cabang masih kosong, silahkan isi terlebih dahulu !',
                'cabang_city.required' => 'Area (Kota) masih kosong, silahkan isi terlebih dahulu !',
                'cabang_telp.required' => 'Nomor telp masih kosong, silahkan isi terlebih dahulu !'
            ];
            $validator = Validator::make($request->all(), [
                'cabang_name' => 'required',
                'cabang_address' => 'required',
                'cabang_city' => 'required',
                'cabang_telp' => 'required'
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
                DB::table('m_company')
                    ->where('c_id', $id)
                    ->update([
                        'c_name' => strtoupper($request->cabang_name),
                        'c_address' => $request->cabang_address,
                        'c_tlp' => $request->cabang_telp,
                        'c_hp' => $request->cabang_telp2,
                        'c_type' => 'CABANG',
                        'c_user' => $request->cabang_user,
                        'c_area' => $request->cabang_city,
                        'c_update' => Carbon::now('Asia/Jakarta')
                    ]);
                DB::commit();
                return response()->json([
                    'status' => 'sukses'
                ]);
            }
            catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 'gagal',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function nonActive($id)
    {
        // check user-access of the current feature
        if (!AksesUser::checkAkses(6, 'delete')) {
            abort(401);
        }

        try {
            $id = Crypt::decrypt($id);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
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
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actived($id)
    {
        // check user-access of the current feature
        if (!AksesUser::checkAkses(6, 'delete')) {
            abort(401);
        }

        try {
            $id = Crypt::decrypt($id);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
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
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

}
