<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


use DB;
use Auth;
use Response;
use Currency;
use Validator;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;

class MemberController extends Controller
{
    public function index()
    {
        return view('masterdatautama.member.index');
    }

    public function listDataMember()
    {
        $data_member = DB::table('m_member')
            ->leftJoin('m_agen', 'm_member.m_agen', 'a_code')
            ->leftJoin('m_wil_kota', 'm_city', 'wc_id')
            ->leftJoin('m_wil_provinsi', 'm_province', 'wp_id')
            ->select('m_member.*', 'a_name', 'wp_name', 'wc_name')
            ->where('m_status', '=', 'Y')
            ->get();

        return Datatables::of($data_member)
            ->addIndexColumn()
            ->addColumn('action', function ($data_member) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Member" onclick="editMember(\'' . Crypt::encrypt($data_member->m_id) . '\')"><i class="fa fa-fw fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger hint--top-left hint--error" aria-label="Nonaktifkan Member" onclick="nonActivateMember(\'' . Crypt::encrypt($data_member->m_id) . '\')"><i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $provinsi = DB::table('m_wil_provinsi')
            ->select('m_wil_provinsi.*')
            ->get();
        return view('masterdatautama.member.create', compact('provinsi'));
    }

    public function cariDataAgen(Request $request)
    {        
        $is_agen = array();
        for ($i = 0; $i < count($request->idAgen); $i++) {
            if ($request->idAgen[$i] != null) {
                array_push($is_agen, $request->idAgen[$i]);
            }
        }

        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', 'c_user')
            ->select('m_agen.*', 'm_company.*')
            ->whereNotIn('a_id', $is_agen)
            ->where(function ($q) use ($cari) {
                $q->whereRaw("a_name like '%" . $cari . "%'");
                $q->orWhereRaw("a_code like '%" . $cari . "%'");
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->c_id, 'label' => strtoupper($query->a_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getDataAgen(Request $request)
    {
        $id = $request->id;
        $agen = DB::table('m_company')
            ->join('m_agen', 'c_user', 'a_code')
            ->join('m_wil_provinsi', 'a_provinsi', 'wp_id')
            ->join('m_wil_kota', 'a_area', 'wc_id')
            ->select('m_company.*', 'm_agen.*', 'm_wil_provinsi.*', 'm_wil_kota.*')
            ->where('c_type', '=', 'AGEN')
            ->where('a_area', '=', $id)
            ->get();
        return Datatables::of($agen)
            ->addIndexColumn()
            ->addColumn('action_agen', function ($agen) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-primary hint--top-left hint--primary"  aria-label="Pilih Agen Ini" onclick="chooseAgen(\''.$agen->c_id.'\',\''.$agen->a_name.'\',\''.$agen->c_user.'\')"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                        </div>';
                
            })
            ->rawColumns(['action_agen'])
            ->make(true);
    }

    public function memberStore(Request $request)
    {
        //dd($request);
        $messages = [
            'm_name.required'    => 'Nama masih kosong, silahkan isi terlebih dahulu !',
            'm_nik.required'     => 'NIK masih kosong, silahkan isi terlebih dahulu !',
            'm_tlp.required'     => 'No. Telepon masih kosong, silahkan isi terlebih dahulu !',
            'm_address.required' => 'Alamat masih kosong, silahkan isi terlebih dahulu !',
            'm_prov.required'    => 'Provinsi masih kosong, silahkan isi terlebih dahulu !',
            'm_city.required'    => 'Kota masih kosong, silahkan isi terlebih dahulu !',
            'codeAgen.required'  => 'Agen Kelamin masih kosong, silahkan isi terlebih dahulu !'
        ];
        $validator = Validator::make($request->all(), [
            'm_name'    => 'required',
            'm_nik'     => 'required',
            'm_tlp'     => 'required',
            'm_address' => 'required',
            'm_prov'    => 'required',
            'm_city'    => 'required',
            'codeAgen'  => 'required'
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json([
              'status'  => 'invalid',
              'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {            
            $getIdMax = DB::table('m_member')->max('m_id');
            $memberId = $getIdMax + 1;

            DB::table('m_member')->insert([
                'm_id'       => $memberId,
                'm_code'     => CodeGenerator::code('m_member', 'm_code', 10, 'CUS'),
                'm_name'     => $request->input('m_name'),
                'm_tlp'      => $request->input('m_tlp'),
                'm_nik'      => $request->input('m_nik'),
                'm_address'  => $request->input('m_address'),
                'm_province' => $request->input('m_prov'),
                'm_city'     => $request->input('m_city'),
                'm_agen'     => $request->input('codeAgen')
            ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function editMember($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $member = DB::table('m_member')
            ->join('m_agen', 'm_member.m_agen', 'a_code')
            ->join('m_company', 'a_code', 'c_user')
            ->join('m_wil_provinsi', 'm_province', 'wp_id')
            ->join('m_wil_kota', 'm_city', 'wc_id')
            ->select('m_member.*', 'wp_id', 'wp_name', 'wc_id', 'wc_name', 'a_name', 'a_code', 'c_id')
            ->where('m_id', $id)
            ->first();

        $provinsi = DB::table('m_wil_provinsi')
            ->select('m_wil_provinsi.*')
            ->get();

        $city = DB::table('m_wil_kota')
            ->select('m_wil_kota.*')
            ->where('wc_provinsi', '=', $member->m_province)
            ->where('wc_id', '!=', $member->m_city)
            ->get();
        return view('masterdatautama.member.edit', compact('member', 'provinsi', 'city'));
    }

    public function updateMember($id, Request $request)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $messages = [
            'm_name.required'    => 'Nama masih kosong, silahkan isi terlebih dahulu !',
            'm_nik.required'     => 'NIK masih kosong, silahkan isi terlebih dahulu !',
            'm_tlp.required'     => 'No. Telepon masih kosong, silahkan isi terlebih dahulu !',
            'm_address.required' => 'Alamat masih kosong, silahkan isi terlebih dahulu !',
            'm_prov.required'    => 'Provinsi masih kosong, silahkan isi terlebih dahulu !',
            'm_city.required'    => 'Kota masih kosong, silahkan isi terlebih dahulu !',
            'codeAgen.required'  => 'Agen Kelamin masih kosong, silahkan isi terlebih dahulu !'
        ];
        $validator = Validator::make($request->all(), [
            'm_name'    => 'required',
            'm_nik'     => 'required',
            'm_tlp'     => 'required',
            'm_address' => 'required',
            'm_prov'    => 'required',
            'm_city'    => 'required',
            'codeAgen'  => 'required'
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json([
              'status'  => 'invalid',
              'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {

            DB::table('m_member')->where('m_id', '=', $id)->update([
                'm_name'     => $request->input('m_name'),
                'm_tlp'      => $request->input('m_tlp'),
                'm_nik'      => $request->input('m_nik'),
                'm_address'  => $request->input('m_address'),
                'm_province' => $request->input('m_prov'),
                'm_city'     => $request->input('m_city'),
                'm_agen'     => $request->input('codeAgen')
            ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function nonActivateMember($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_member')
                ->where('m_id', $id)
                ->update([
                    'm_status' => "N"
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
}
