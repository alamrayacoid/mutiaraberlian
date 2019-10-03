<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use carbon\Carbon;
use CodeGenerator;
use App\m_agen;
use App\m_company;
use DB;
use File;
use Image;
use function PHPSTORM_META\type;
use Session;
use Auth;
use Validator;
use Yajra\DataTables\DataTables;
use Response;

class AgenController extends Controller
{
    /**
     * Validate request before execute command.
     *
     * @param  \Illuminate\Http\Request $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'area_prov' => 'required',
            'area_city' => 'required',
            'name' => 'required',
            'email' => 'sometimes|nullable|email',
            'telp' => 'required',
            'a_salesprice' => 'required'
        ],
        [
            'area_prov.required' => 'Area Provinsi masih kosong !',
            'area_city.required' => 'Area Kota masih kosong !',
            'name.required' => 'Nama agen masih kosong !',
            'email.email' => 'Format email tidak valid !',
            'telp.required' => 'No Telp masih kosong !',
            'a_salesprice.required' => 'Harga penjualan tidak boleh kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    /**
     * Return list of 'price class'.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClasses()
    {
        $classes = DB::table('m_priceclass')
            ->orderBy('pc_name', 'asc')
            ->get();
        return $classes;
    }

    public function getSalesPrice()
    {
        $classes = DB::table('d_salesprice')
            ->orderBy('sp_name', 'asc')
            ->get();
        return $classes;
    }

    /**
     * Return list of provinces.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProvinces()
    {
        $provinces = DB::table('m_wil_provinsi')
            ->orderBy('wp_name', 'asc')
            ->get();
        return $provinces;
    }

    /**
     * Return a province id.
     *
     * @return char provinceId
     */
    public function getProvinceByCity($city_id)
    {
        $provinceId = DB::table('m_wil_kota')
            ->where('wc_id', $city_id)
            ->select('wc_provinsi')
            ->first();
        return $provinceId->wc_provinsi;
    }

    /**
     * Return list of cities based on provinces.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCities($id)
    {
        $cities = DB::table('m_wil_kota')
            ->where('wc_provinsi', $id)
            ->orderBy('wc_name', 'asc')
            ->get();
        return $cities;
    }

    /**
     * Return list of districts based on cities.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDistricts($id)
    {
        $districts = DB::table('m_wil_kecamatan')
            ->where('wk_kota', $id)
            ->orderBy('wk_name', 'asc')
            ->get();
        return $districts;
    }

    /**
     * Return list of villages based on districts.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVillages($id)
    {
        $villages = DB::table('m_wil_desa')
            ->where('wd_kecamatan', $id)
            ->orderBy('wd_name', 'asc')
            ->get();
        return $villages;
    }

    /**
     * Return list agents where type is 'AGEN'.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAgents()
    {
        $agents = DB::table('m_agen')
            ->where('a_type', 'AGEN')
            ->orderBy('a_name', 'asc')
            ->get();
        return $agents;
    }

    public function getAgenByCity($id)
    {
        $data = DB::table('m_company')
            ->where('c_isactive', 'Y')
            ->where('c_type', '!=', 'APOTEK/RADIO')
            ->join('m_agen', 'c_user', 'a_code')
            ->where('a_area', '=', $id)
            ->get();

        return Response::json([
            "data" => $data
        ]);
    }

    /**
     * Return true if the agent has sub-agent
     *
     * @param code $codeAgent
     * @return bool hasSubAgent
     */
    public function hasSubAgent($code)
    {
        $subAgent = DB::table('m_agen')
            ->where('a_parent', $code)
            ->first();

        if ($subAgent != null) {
            return true;
        } else {
            return false;
        }
    }


    // /**
    //  * uploads images to storage_path and return image name.
    //  *
    //  * @param file $image
    //  * @param string $nik (9271928xxx)
    //  * @param string $type (photo, ktp, others)
    //  * @return string $imageName (18276-ktp)
    //  */
    // public function uploadImage($image, $nik, $type)
    // {
    //     if ($image != null) {
    //         $imageExt = $image->getClientOriginalExtension();
    //         $imageName = $nik . '-' . $type . '.' . $imageExt;
    //         $path = storage_path('/uploads/agen/' . $imageName);
    //         if (File::exists($path)) {
    //             File::delete($path);
    //         }
    //         Image::make($image)->resize(300, null, function ($constraint) {
    //             $constraint->aspectRatio();
    //         })->save(storage_path('/uploads/agen/' . $imageName));
    //         return $imageName;
    //     }
    // }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList(Request $request)
    {
        $user = Auth::user();
        $comp = $user->u_company;
        $info = DB::table('m_company')
            ->where('c_id', '=', $comp)
            ->first();

        $status = $request->status;

        $datas = DB::table('m_agen');
        if ($status != '') {
            $datas = $datas->where('a_isactive', $status)
                ->where('a_type', '!=', 'MMA');
        }
        $datas = $datas->join('m_wil_kota', 'a_area', 'wc_id')
            ->orderBy('a_isactive', 'asc')
            ->orderBy('a_code', 'asc');

        if ($info->c_type == 'PUSAT'){
            $datas = $datas->get();
        } elseif ($info->c_type == 'CABANG') {
            $datas = $datas
                ->where(function ($q) use ($info){
                    $q->orWhere('a_mma', '=', $info->c_id);
                    $q->orWhere('a_parent', '=', $info->c_id);
                })
                ->get();
        } elseif ($info->c_type == 'AGEN' || $info->c_type == 'SUB AGEN'){
            $datas = $datas->where('a_parent', '=', $info->c_user)->get();
        } else {
            return false;
        }

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('area', function ($datas) {
                return $datas->wc_name;
            })
            ->addColumn('action', function ($datas) {
                if ($datas->a_isactive == 'Y') {
                    return '<div class="btn-group btn-group-sm">
            <button class="btn btn-warning hint--top hint--warning" onclick="EditAgen(' . $datas->a_id . ')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-danger hint--top hint--error" onclick="DisableAgen(' . $datas->a_id . ')" rel="tooltip" data-placement="top" aria-label="Nonaktifkan data"><i class="fa fa-times-circle"></i></button>
            </div>';
                } elseif ($datas->a_isactive == 'N') {
                    return '<div class="btn-group btn-group-sm">
            <button class="btn btn-success btn-enable hint--top hint--error" onclick="EnableAgen(' . $datas->a_id . ')" rel="tooltip" data-placement="top" aria-label="Aktifkan data"><i class="fa fa-check-circle"></i></button>
            </div>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masterdatautama.agen.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!AksesUser::checkAkses(7, 'create')){
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
                    $q->orWhere('c_type', '=', 'PUSAT');
                    $q->orWhere('c_type', '=', 'CABANG');
                })
                ->get();
        } else {
            $data['mma'] = m_company::where('c_isactive', 'Y')
            ->where(function ($q) use ($info){
                $q->orWhere('c_type', '=', 'PUSAT');
                $q->orWhere('c_type', '=', 'CABANG');
                $q->orWhere('c_id', '=', $info->c_id);
            })->get();
        }

        $data['provinces'] = $this->getProvinces();
        $data['classes'] = $this->getClasses();
        $data['salesPrice'] = $this->getSalesPrice();

        return view('masterdatautama.agen.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!AksesUser::checkAkses(7, 'create')){
            abort(401);
        }

        // validate request
        $isValidRequest = $this->validate_req($request);
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

            // prevent duplicated no-telp
            $telpIsUsed = m_agen::where('a_telp', $request->telp)->first();
            if ($telpIsUsed !== null) {
                throw new \Exception("Nomor telp sudah digunakan, gunakan nomor yang lain", 1);
            }

            if ($request->hasFile('photo')) {
                $imageName = $codeAgen . '-photo';

                $photo = $request->file('photo')->storeAs('Agents', $imageName);
            } else {
                $photo = null;
            }
            // if ($request->type_hidden == 'APOTEK'){
            //     $request->type_hidden = 'APOTEK/RADIO';
            // }
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

            if ($c_type != 'APOTEK/RADIO') {
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!AksesUser::checkAkses(7, 'create')){
            abort(401);
        }

        $data['agen'] = DB::table('m_agen')
            ->where('a_id', $id)
            ->first();
        //
        // if ($data['agen']->a_type == "APOTEK/RADIO"){
        //     $data['agen']->a_type = "APOTEK";
        // }

        if ($data['agen']->a_type == "APOTEK/RADIO" || $data['agen']->a_type == "SUB AGEN"){
            $data['infoparent'] = DB::table('m_agen')
                ->where('a_code', '=', $data['agen']->a_parent)
                ->first();

            $data['parentProv'] = $this->getProvinceByCity($data['infoparent']->a_area);
            $data['parentCity'] = $this->getCities($data['parentProv']);
            $data['parentAgen'] = DB::table('m_company')
                ->join('m_agen', 'c_user', 'a_code')
                ->where('a_code', '!=', $data['agen']->a_code)
                ->where('a_area', '=', $data['infoparent']->a_area)
                ->where('c_type', '!=', 'APOTEK/RADIO')
                ->where('c_isactive', 'Y')
                ->get();
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
                $q->orWhere('c_type', '=', 'PUSAT');
                $q->orWhere('c_type', '=', 'CABANG');
            })->get();
        } else {
            $data['mma'] = m_company::where('c_isactive', 'Y')
            ->where(function ($q) use ($info){
                $q->orWhere('c_type', '=', 'PUSAT');
                $q->orWhere('c_type', '=', 'CABANG');
                $q->orWhere('c_id', '=', $info->c_id);
            })->get();
        }

        $provinceId = $this->getProvinceByCity($data['agen']->a_area);

        $data['provinces'] = $this->getProvinces();
        $data['classes'] = $this->getClasses();
        $data['salesPrice'] = $this->getSalesPrice();
        $data['area_prov'] = $provinceId;
        $data['area_cities'] = $this->getCities($provinceId);
        $data['address_cities'] = $this->getCities($data['agen']->a_provinsi);
        $data['address_districts'] = $this->getDistricts($data['agen']->a_kabupaten);
        $data['address_villages'] = $this->getVillages($data['agen']->a_kecamatan);
        $data['has_subagent'] = $this->hasSubAgent($data['agen']->a_code);

        return view('masterdatautama.agen.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!AksesUser::checkAkses(7, 'create')){
            abort(401);
        }

        // validate request
        $isValidRequest = $this->validate_req($request);
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
                $imageName = $agentCode->a_code . '-photo';

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

                $c_type = $request->type_hidden;
                if ($c_type == 'MMA'){
                    $c_type = 'CABANG';
                }

                DB::table('m_company')
                    ->where('c_user', $agentCode->a_code)
                    ->update([
                        'c_name'    => $request->name,
                        'c_address' => $request->address,
                        'c_tlp'     => $request->telp,
                        'c_type'    => $c_type,
                        'c_area'    => $request->area_city,
                        'c_update'  => Carbon::now()
                    ]);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
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

    /**
     * Disable the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        if (!AksesUser::checkAkses(7, 'create')){
            abort(401);
        }

        // start: execute delete data
        DB::beginTransaction();
        try {
            $agent = m_agen::where('a_id', $id)->first();
            $agent->a_isactive = 'N';
            $agent->save();

            $company = m_company::where('c_user', $agent->a_code)->first();
            $company->c_isactive = 'N';
            $company->save();

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

    /**
     * Enable the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        if (!AksesUser::checkAkses(7, 'create')){
            abort(401);
        }

        // start: execute delete data
        DB::beginTransaction();
        try {
            $agent = m_agen::where('a_id', $id)->first();
            $agent->a_isactive = 'Y';
            $agent->save();

            $company = m_company::where('c_user', $agent->a_code)->first();
            $company->c_isactive = 'Y';
            $company->save();

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
