<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use App\m_agen;
use App\m_company;
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
use Response;
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
        $data['mma'] = [];

        $user = Auth::user();
        $comp = $user->u_company;
        $info = DB::table('m_company')
            ->where('c_id', '=', $comp)
            ->first();

        if ($info->c_type == 'PUSAT' || $info->c_type == 'CABANG'){
            $data['mma'] = m_company::where('c_isactive', 'Y')
            ->where(function ($q) use ($info){
                $q  ->where('c_type', '=', 'PUSAT')
                    ->orWhere('c_type', '=', 'CABANG');
            })
            ->get();
        } else {
            $data['mma'] = m_company::where('c_isactive', 'Y')
            ->where(function ($q) use ($info){
                $q  ->where('c_type', '=', 'PUSAT')
                    ->orWhere('c_type', '=', 'CABANG')
                    ->orWhere('c_id', '=', $info->c_id);
            })
            ->get();
        }
        $agenController = new AgenController();
        $data['provinces'] = $agenController->getProvinces();
        $data['classes'] = $agenController->getClasses();
        $data['salesPrice'] = $agenController->getSalesPrice();

        return view('masterdatautama.cabang.create', compact('data'));
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
        $agenController = new AgenController();
        
        // validate request
        $isValidRequest = $agenController->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }
        // start: execute insert data
        DB::beginTransaction();
        try {
            // insert to table m_agen
            $codeAgen = CodeGenerator::code('m_agen', 'a_code', 7, 'A');
            $id = DB::table('m_agen')->max('a_id') + 1;

            // $photo = $this->uploadImage(
            //     $request->file('photo'),
            //     $codeAgen,
            //     'photo'
            // );

            if ($request->hasFile('photo')) {
                // $photo = $this->uploadImage(
                //     $request->file('photo'),
                //     $request->code,
                //     'photo'
                // );

                $imageName = $codeAgen . '-photo';
                // delete current photo
                // Storage::delete('Agents/'.$imageName);
                // insert new photo
                $photo = $request->file('photo')->storeAs('Agents', $imageName);
            } else {
                $photo = null;
            }
            if ($request->type_hidden == 'APOTEK'){
                $request->type_hidden = 'APOTEK/RADIO';
            }
            DB::table('m_agen')
                ->insert([
                    'a_id'        => $id,
                    'a_code'      => $codeAgen,
                    'a_area'      => $request->area_city,
                    'a_name'      => $request->name,
                    'a_mma'       => $request->mma,
                    'a_sex'       => $request->jekel,
                    'a_birthday'  => Carbon::parse($request->birthday),
                    'a_email'     => $request->email,
                    'a_telp'      => $request->telp,
                    'a_provinsi'  => $request->address_prov,
                    'a_kabupaten' => $request->address_city,
                    'a_kecamatan' => $request->address_district,
                    'a_desa'      => $request->address_village,
                    'a_address'   => $request->address,
                    'a_class'     => $request->a_class,
                    'a_salesprice'=> $request->a_salesprice,
                    'a_type'      => $request->type_hidden,
                    'a_parent'    => $request->parent,
                    'a_img'       => $photo,
                    'a_insert'    => Carbon::now(),
                    'a_update'    => Carbon::now()
                ]);

            // insert to table m_company
            $codeCompany = CodeGenerator::code('m_company', 'c_id', 7, 'MB');

            $c_type = $request->type_hidden;

            if ($c_type == 'MMA'){
                $c_type = 'CABANG';
            }

            DB::table('m_company')
                ->insert([
                    'c_id'      => $codeCompany,
                    'c_name'    => $request->name,
                    'c_address' => $request->address,
                    'c_tlp'     => $request->telp,
                    'c_type'    => $c_type,
                    'c_area'    => $request->area_city,
                    'c_user'    => $codeAgen,
                    'c_insert'  => Carbon::now(),
                    'c_update'  => Carbon::now()
                ]);

            if ($c_type != 'APOTEK/RADIO'){
                $cek = DB::table('d_username')
                    ->where('u_username', '=', $request->username)
                    ->first();

                if ($cek !== null){
                    return Response::json([
                        'status' => 'gagal',
                        'message' => 'username sudah pernah digunakan'
                    ]);
                }

                $password = sha1(md5('islamjaya') . $request->password);

                $id = DB::table('d_username')
                    ->max('u_id');
                ++$id;

                DB::table('d_username')
                    ->insert([
                        "u_id" => $id,
                        "u_company" => $codeCompany,
                        "u_username" => $request->username,
                        "u_password" => $password,
                        "u_level" => 3,
                        "u_user" => "A",
                        "u_code" => $codeAgen
                    ]);

                $akses = DB::table('m_access')
                    ->get();

                $insert = [];
                if ($c_type == 'CABANG'){
                    for ($i = 0; $i < count($akses); $i++) {
                        if ($akses[$i]->a_id == 7 || $akses[$i]->a_id == 22 || $akses[$i]->a_id == 23){
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'Y',
                                'ua_create' => 'Y',
                                'ua_update' => 'Y',
                                'ua_delete' => 'Y'
                            );
                            array_push($insert, $temp);
                        } else {
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'N',
                                'ua_create' => 'N',
                                'ua_update' => 'N',
                                'ua_delete' => 'N'
                            );
                            array_push($insert, $temp);
                        }
                    }
                } elseif ($c_type == 'AGEN'){
                    for ($i = 0; $i < count($akses); $i++) {
                        if ($akses[$i]->a_id == 7 || $akses[$i]->a_id == 23){
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'Y',
                                'ua_create' => 'Y',
                                'ua_update' => 'Y',
                                'ua_delete' => 'Y'
                            );
                            array_push($insert, $temp);
                        } else {
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'N',
                                'ua_create' => 'N',
                                'ua_update' => 'N',
                                'ua_delete' => 'N'
                            );
                            array_push($insert, $temp);
                        }
                    }
                } elseif ($c_type == 'SUB AGEN'){
                    for ($i = 0; $i < count($akses); $i++) {
                        if ($akses[$i]->a_id == 7 || $akses[$i]->a_id == 23){
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'Y',
                                'ua_create' => 'Y',
                                'ua_update' => 'Y',
                                'ua_delete' => 'Y'
                            );
                            array_push($insert, $temp);
                        } else {
                            $temp = array(
                                'ua_access' => $akses[$i]->a_id,
                                'ua_username' => $id,
                                'ua_read' => 'N',
                                'ua_create' => 'N',
                                'ua_update' => 'N',
                                'ua_delete' => 'N'
                            );
                            array_push($insert, $temp);
                        }
                    }
                }

                DB::table('d_useraccess')
                    ->insert($insert);
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
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
                $infoComp = DB::table('m_company')
                    ->where('c_id', '=', Crypt::decrypt($id))
                    ->first();

                $id = $infoComp->c_user;
            }
            catch (\Exception $e) {
                abort(404);
                // return view('errors.404');
            }
            $data['agen'] = DB::table('m_agen')
                ->where('a_code', $id)
                ->first();

            $data['mma'] = [];

            $user = Auth::user();
            $comp = $user->u_company;
            $info = DB::table('m_company')
                ->where('c_id', '=', $comp)
                ->first();

            if ($info->c_type == 'PUSAT' || $info->c_type == 'CABANG'){
                $data['mma'] = m_company::where(function ($q) use ($info){
                    $q->orWhere('c_type', '=', 'PUSAT');
                    $q->orWhere('c_type', '=', 'CABANG');
                })->get();
            } else {
                $data['mma'] = m_company::where(function ($q) use ($info){
                    $q->orWhere('c_type', '=', 'PUSAT');
                    $q->orWhere('c_type', '=', 'CABANG');
                    $q->orWhere('c_id', '=', $info->c_id);
                })->get();
            }
            $agenController = new AgenController();
            $provinceId = $agenController->getProvinceByCity($data['agen']->a_area);

            $data['provinces'] = $agenController->getProvinces();
            $data['classes'] = $agenController->getClasses();
            $data['salesPrice'] = $agenController->getSalesPrice();
            $data['area_prov'] = $provinceId;
            $data['area_cities'] = $agenController->getCities($provinceId);
            $data['address_cities'] = $agenController->getCities($data['agen']->a_provinsi);
            $data['address_districts'] = $agenController->getDistricts($data['agen']->a_kabupaten);
            $data['address_villages'] = $agenController->getVillages($data['agen']->a_kecamatan);
            $data['has_subagent'] = $agenController->hasSubAgent($data['agen']->a_code);

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

            $agenController = new AgenController();
            // validate request
            $isValidRequest = $agenController->validate_req($request);
            if ($isValidRequest != '1') {
                $errors = $isValidRequest;
                return response()->json([
                    'status' => 'invalid',
                    'message' => $errors
                ]);
            }

            // start: execute update data
            DB::beginTransaction();
            try {
                // get agent-code
                $agentCode = m_agen::where('a_id', $id)->select('a_code')->first();

                if ($request->hasFile('photo')) {
                    // $photo = $this->uploadImage(
                    //     $request->file('photo'),
                    //     $request->code,
                    //     'photo'
                    // );

                    $imageName = $agentCode->a_code . '-photo';
                    // delete current photo
                    // Storage::delete('Agents/'.$imageName);
                    // insert new photo
                    $photo = $request->file('photo')->storeAs('Agents', $imageName);
                } else {
                    $photo = $request->current_photo;
                }

                // update data in table m_agen
                DB::table('m_agen')
                    ->where('a_id', $id)
                    ->update([
                        'a_area'      => $request->area_city,
                        'a_name'      => $request->name,
                        'a_mma'       => $request->mma,
                        'a_sex'       => $request->jekel,
                        'a_birthday'  => Carbon::parse($request->birthday),
                        'a_email'     => $request->email,
                        'a_telp'      => $request->telp,
                        'a_provinsi'  => $request->address_prov,
                        'a_kabupaten' => $request->address_city,
                        'a_kecamatan' => $request->address_district,
                        'a_desa'      => $request->address_village,
                        'a_address'   => $request->address,
                        'a_class'     => $request->a_class,
                        'a_salesprice'=> $request->a_salesprice,
                        'a_type'      => $request->type_hidden,
                        'a_parent'    => $request->parent,
                        'a_img'       => $photo,
                        'a_update'    => Carbon::now()
                    ]);

                DB::table('m_company')
                    ->where('c_user', $agentCode->a_code)
                    ->update([
                        'c_name'    => $request->name,
                        'c_address' => $request->address,
                        'c_tlp'     => $request->telp,
                        'c_type'    => 'AGEN',
                        'c_area'    => $request->area_city,
                        'c_update'  => Carbon::now()
                    ]);

                DB::commit();
                return response()->json([
                    'status' => 'berhasil'
                ]);
            } catch (\Exception $e) {
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
