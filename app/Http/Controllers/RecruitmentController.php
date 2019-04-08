<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;
use Validator;
use carbon\Carbon;
use Image;

class RecruitmentController extends Controller
{
    /**
     * Validate request before execute command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
      // start: validate data before execute
      $validator = Validator::make($request->all(), [
        'name'          => 'required',
        'nik'           => 'required|numeric',
        'address'       => 'required',
        'addressnow'    => 'required',
        'birthplace'    => 'required',
        'birthdate'     => 'required',
        'birthmonth'    => 'required',
        'birthyear'     => 'required',
        'lasteducation' => 'required',
        'email'         => 'required|email',
        'telp'          => 'required|numeric',
        'religion'      => 'required',
        'partner'       => 'required_if:status,M',
        'schoolname'    => 'required',
        'yearin'        => 'required',
        'yearout'       => 'required',
        'majors'        => 'required',
        'filephoto'     => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'filektp'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'fileijazah'    => 'sometimes|mimes:jpeg,png,jpg,pdf|max:2048',
        'fileanother'   => 'sometimes|mimes:jpeg,png,jpg,pdf|max:2048'
      ],
      [
        'name.required'          => 'Nama masih kosong !',
        'nik.required'           => 'NIK masih kosong !',
        'nik.numeric'            => 'NIK hanya boleh berisi angka !',
        'address.required'       => 'Alamat masih kosong !',
        'addressnow.required'    => 'Alamat sekarang masih kosong !',
        'birthplace.required'    => 'Tempat lahir masih kosong !',
        'birthdate.required'     => 'Tanggal kelahiran masih kosong !',
        'birthmonth.required'    => 'Bulan kelahiran masih kosong !',
        'birthyear.required'     => 'Tahun kelahiran masih kosong !',
        'lasteducation.required' => 'Jenjang pendidikan terakhir masih kosong !',
        'email.required'         => 'Email masih kosong !',
        'email.email'            => 'Format email tidak valid !',
        'telp.required'          => 'No telp masih kosong !',
        'telp.numeric'           => 'No telp hanya boleh berisi angka !',
        'religion.required'      => 'Agama masih kosong !',
        'partner.required_if'    => 'Nama suami/istri masih kosong !',
        'schoolname.required'    => 'Nama sekolah masih kosong !',
        'yearin.required'        => 'Tahun masuk masih kosong !',
        'yearout.required'       => 'Tahun keluar masih kosong !',
        'majors.required'        => 'Jurusan sekolah masih kosong !',
        'filephoto.max'          => 'Ukuran file maksimal 2 MB !',
        'filektp.max'            => 'Ukuran file maksimal 2 MB !',
        'fileijazah.max'         => 'Ukuran file maksimal 2 MB !',
        'fileanother.max'        => 'Ukuran file maksimal 2 MB !',
        'fileijazah.mimes'       => 'Type file ijasah harus sesuai !',
        'fileanother.mimes'      => 'Type file harus sesuai !'
      ]);
      if($validator->fails())
      {
        return $validator->errors()->first();
      }
      else
      {
        return '1';
      }
    }

    /**
     * validate is there any same value or not in db.
     *
     * @param string $field field name
     * @param string $str value
     * @return JSON response
     */
    public function isDuplicated($field, $str)
    {
      if ($field == 'email') {
        $field = 'p_email';
      } elseif ($field == 'telp') {
        $field = 'p_tlp';
      }
      $query = DB::table('d_pelamar')
        ->where($field, $str)
        ->first();
      if ($query == null) {
        return response()->json([
          'status' => 'valid'
        ]);
      } else {
        return response()->json([
          'status' => 'invalid'
        ]);
      }
    }

    /**
    * uploads images to storage_path and return image name.
    *
    * @param file $image
    * @param string $nik (9271928xxx)
    * @param string $type (photo, ktp, others)
    * @return string $imageName (18276-ktp)
    */
    public function uploadImage($image, $nik, $type)
    {
      if ($image != null) {
        $imageExt = $image->getClientOriginalExtension();
        $imageName = $nik . '-' . $type . '.' .$imageExt;
        Image::make($image)->save(storage_path('/uploads/recruitment/' . $imageName));
        return $imageName;
      }
    }

    /**
     * Check user is already registered or not.
     *
     * @param string $nik (71829xxx)
     * @return bool $isRegistered
     */
    public function isRegistered($nik)
    {
      $query = DB::table('d_pelamar')
        ->where('p_state', 'P')
        ->where('p_nik', $nik)
        ->first();
      if ($query == null) {
        return false;
      } else {
        return true;
      }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('recruitment');
    }

    public function loading()
    {
      return view('loading');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd($request->request);
      // is user registered ?
      if ($this->isRegistered($request->nik) == true) {
        return response()->json([
          'status' => 'invalid',
          'message' => 'Anda telah terdaftar !'
        ]);
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
        if (DB::table('d_pelamar')->first() == null) {
          $id = 1;
        } else {
          $id = DB::table('d_pelamar')->max('p_id') + 1;
        }
        $birthday = $request->birthyear . '/' . $request->birthmonth . '/' . $request->birthdate;
        $birthday = Carbon::parse($birthday);

        $filephoto   = $request->file('filephoto');
        $filektp     = $request->file('filektp');
        $fileijazah  = $request->file('fileijazah');
        $fileanother = $request->file('fileanother');
        $photo       = $this->uploadImage($filephoto, $request->nik, 'photo');
        $ktp         = $this->uploadImage($filektp, $request->nik, 'ktp');
        $ijazah      = $this->uploadImage($fileijazah, $request->nik, 'ijazah');
        $another     = $this->uploadImage($fileanother, $request->nik, 'other');

        DB::table('d_pelamar')
          ->insert([
            'p_id'          => $id,
            'p_date'        => Carbon::now('Asia/Jakarta'),
            'p_nik'         => $request->nik,
            'p_name'        => $request->name,
            'p_address'     => $request->address,
            'p_address_now' => $request->addressnow,
            'p_birth_place' => $request->birthplace,
            'p_birthday'    => $birthday,
            'p_education'   => $request->lasteducation,
            'p_schoolname'  => $request->schoolname,
            'p_yearin'      => $request->yearin,
            'p_yearout'     => $request->yearout,
            'p_jurusan'     => $request->majors,
            'p_nilai'       => $request->finalscore,
            'p_email'       => $request->email,
            'p_tlp'         => $request->telp,
            'p_religion'    => $request->religion,
            'p_status'      => $request->status,
            'p_promo'       => "",
            'p_wife_name'   => $request->partner,
            'p_child'       => $request->childcount,
            'p_jobcompany1' => $request->companyname1,
            'p_jobyear1'    => $request->yearsofwork1,
            'p_jobdesc1'    => $request->jobdesc1,
            'p_jobcompany2' => $request->companyname2,
            'p_jobyear2'    => $request->yearsofwork2,
            'p_jobdesc2'    => $request->jobdesc2,
            'p_imgfoto'     => $photo,
            'p_imgktp'      => $ktp,
            'img_ijazah'    => $ijazah,
            'img_other'     => $another,
            'p_created'     => Carbon::now(),
            'p_updated'     => Carbon::now()
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
