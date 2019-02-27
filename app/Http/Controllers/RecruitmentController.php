<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;
use Validator;
use carbon\Carbon;

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
        'name' => 'required',
        'nik' => 'required|numeric',
        'address' => 'required',
        'addressnow' => 'required',
        'birthplace' => 'required',
        'birthdate' => 'required',
        'birthmonth' => 'required',
        'birthyear' => 'required',
        'lasteducation' => 'required',
        'email' => 'sometimes|nullable|email',
        'telp' => 'required|numeric',
        'religion' => 'required',
        'partner' => 'required_if:status,M',
        'schoolname' => 'required',
        'yearin' => 'required|numeric|digits:4',
        'yearout' => 'required|numeric|digits:4',
        'majors' => 'required'
      ],
      [
        'name.required' => 'Nama masih kosong !',
        'nik.required' => 'NIK masih kosong !',
        'nik.numeric' => 'NIK hanya boleh berisi angka !',
        'address.required' => 'Alamat masih kosong !',
        'addressnow.required' => 'Alamat sekarang masih kosong !',
        'birthplace.required' => 'Tempat lahir masih kosong !',
        'birthdate.required' => 'Tanggal kelahiran masih kosong !',
        'birthmonth.required' => 'Bulan kelahiran masih kosong !',
        'birthyear.required' => 'Tahun kelahiran masih kosong !',
        'lasteducation.required' => 'Jenjang pendidikan terakhir masih kosong !',
        'email.email' => 'Format email tidak valid !',
        'telp.required' => 'No telp masih kosong !',
        'telp.numeric' => 'No telp hanya boleh berisi angka !',
        'religion.required' => 'Agama masih kosong !',
        'partner.required_if' => 'Nama suami/istri masih kosong !',
        'schoolname.required' => 'Nama sekolah masih kosong !',
        'yearin.required' => 'Tahun masuk masih kosong !',
        'yearin.numeric' => 'Tahun masuk hanya boleh berisi angka !',
        'yearin.digits' => 'Tahun masuk maksimal 4 digit !',
        'yearout.required' => 'Tahun keluar masih kosong !',
        'yearout.numeric' => 'Tahun keluar hanya boleh berisi angka !',
        'yearout.digits' => 'Tahun keluar maksimal 4 digit !',
        'majors.required' => 'Jurusan sekolah masih kosong !'
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('recruitment');
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
        DB::table('d_pelamar')
          ->insert([
            'p_id' => $id,
            // 'p_date' => "",
            'p_nip' => $request->nik,
            'p_name' => $request->name,
            'p_address' => $request->address,
            'p_address_now' => $request->addressnow,
            'p_birth_place' => $request->birthplace,
            'p_birthday' => $birthday,
            'p_education' => $request->lasteducation,
            'p_schoolname' => $request->schoolname,
            'p_yearin' => $request->yearin,
            'p_yearout' => $request->yearout,
            'p_jurusan' => $request->majors,
            'p_nilai' => $request->finalscore,
            'p_email' => $request->email,
            'p_tlp' => $request->telp,
            'p_religion' => $request->religion,
            'p_status' => $request->status,
            'p_promo' => "",
            'p_wife_name' => $request->partner,
            'p_child' => $request->childcount,
            'p_created' => Carbon::now(),
            'p_updated' => Carbon::now()
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
