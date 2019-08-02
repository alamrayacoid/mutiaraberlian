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
use Session;
use Auth;
use Validator;
use Yajra\DataTables\DataTables;

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
            'telp' => 'required'
        ],
        [
            'area_prov.required' => 'Area Provinsi masih kosong !',
            'area_city.required' => 'Area Kota masih kosong !',
            'name.required' => 'Nama agen masih kosong !',
            'email.email' => 'Format email tidak valid !',
            'telp.required' => 'No Telp masih kosong !'
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
    public function getList()
    {
        $user = Auth::user();
        $comp = $user->u_company;
        $info = DB::table('m_company')
            ->where('c_id', '=', $comp)
            ->first();

        $datas = DB::table('m_agen')
            ->join('m_wil_kota', 'a_area', 'wc_id')
            ->orderBy('a_code', 'asc');

        if ($info->c_type == 'PUSAT'){
            $datas = $datas->get();
        } else {
            $datas = $datas->where('a_mma', '=', $info->c_id)->get();
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

        if ($info->c_type != 'PUSAT'){
            $data['mma'] = m_company::where('c_id', '=', $info->c_id)->get();
        } else {
            $data['mma'] = m_company::where('c_type', '!=', 'AGEN')->get();
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

            // $photo = $this->uploadImage(
            //     $request->file('photo'),
            //     $codeAgen,
            //     'photo'
            // );
            $imageName = $codeAgen . '-photo';
            $photo = $request->file('photo')->storeAs('Agents', $imageName);

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
            DB::table('m_company')
                ->insert([
                    'c_id'      => $codeCompany,
                    'c_name'    => $request->name,
                    'c_address' => $request->address,
                    'c_tlp'     => $request->telp,
                    'c_type'    => 'AGEN',
                    'c_area'    => $request->area_city,
                    'c_user'    => $codeAgen,
                    'c_insert'  => Carbon::now(),
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

        $data['mma'] = [];

        $user = Auth::user();
        $comp = $user->u_company;
        $info = DB::table('m_company')
            ->where('c_id', '=', $comp)
            ->first();

        if ($info->c_type != 'PUSAT'){
            $data['mma'] = m_company::where('c_id', '=', $info->c_id)->get();
        } else {
            $data['mma'] = m_company::where('c_type', '!=', 'AGEN')->get();
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
                // $photo = $this->uploadImage(
                //     $request->file('photo'),
                //     $request->code,
                //     'photo'
                // );

                $imageName = $agentCode->a_code . '-photo';
                // delete current photo
                Storage::delete('Agents/'.$imageName);
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
            DB::table('m_agen')
                ->where('a_id', $id)
                ->update([
                    'a_isactive' => 'N'
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
            DB::table('m_agen')
                ->where('a_id', $id)
                ->update([
                    'a_isactive' => 'Y'
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
