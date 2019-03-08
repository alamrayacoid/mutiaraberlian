<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;

use Yajra\DataTables\DataTables;

use Carbon\Carbon;

class SettingController extends Controller
{
    public function perubahanhargajual_index()
    {
        return view('pengaturan.otoritas.perubahanhargajual.index');
    }

    public function pengaturanpengguna_index()
    {
        return view('pengaturan.pengaturanpengguna.index');
    }

    public function getUser()
    {
        $pegawai = DB::table('m_employee')
            ->join('d_username', 'u_code', '=', 'e_id');
        $user = collect($user);
        $cekUpdate = Plasmafone::checkAkses(42, 'update');
        $cekDelete = Plasmafone::checkAkses(42, 'delete');
        return DataTables::of($user)
            ->addColumn('aksi', function ($user) use ($cekDelete, $cekUpdate) {
                if ($user->m_state == "ACTIVE") {
                    return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" onclick="akses(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" onclick="edit(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-edit"></i></button>
                        <a href="#modalPass" id="passM" data-toggle="modal" style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" data-id="' . Crypt::encrypt($user->m_id) . '"><i class="fa fa-exchange"></i></a>
                        <button style="margin-left:5px;" title="Set Nonactive" type="button" class="btn btn-danger btn-circle btn-xs" onclick="trigger(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-trash"></i></button>
                        </div>';
                    /*if ($cekUpdate == true && $cekDelete == true) {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" onclick="akses(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" onclick="edit(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-edit"></i></button>
                        <a href="#modalPass" id="passM" data-toggle="modal" style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" data-id="' . Crypt::encrypt($user->m_id) . '"><i class="fa fa-exchange"></i></a>
                        <button style="margin-left:5px;" title="Set Nonactive" type="button" class="btn btn-danger btn-circle btn-xs" onclick="trigger(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-trash"></i></button>
                        </div>';
                    } else if ($cekUpdate == false && $cekDelete == true) {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" disabled><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" disabled><i class="glyphicon glyphicon-edit"></i></button>
                        <button style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" disabled><i class="fa fa-exchange"></i></button>
                        <button style="margin-left:5px;" title="Set Nonactive" type="button" class="btn btn-danger btn-circle btn-xs" onclick="trigger(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-trash"></i></button>
                        </div>';
                    } else if ($cekDelete == false && $cekUpdate == true) {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" onclick="akses(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" onclick="edit(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-edit"></i></button>
                        <a href="#modalPass" id="passM" data-toggle="modal" style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" data-id="' . Crypt::encrypt($user->m_id) . '"><i class="fa fa-exchange"></i></a>
                        <button style="margin-left:5px;" title="Set Nonactive" type="button" class="btn btn-danger btn-circle btn-xs" disabled><i class="glyphicon glyphicon-trash"></i></button>
                        </div>';
                    } else {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" disabled><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" disabled><i class="glyphicon glyphicon-edit"></i></button>
                        <button style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" disabled><i class="fa fa-exchange"></i></button>
                        <button style="margin-left:5px;" title="Set Nonactive" type="button" class="btn btn-danger btn-circle btn-xs" disabled><i class="glyphicon glyphicon-trash"></i></button>
                        </div>';
                    }*/
                } else {
                    /*if (Plasmafone::checkAkses(42, 'delete') == false) {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" onclick="akses(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" onclick="edit(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="glyphicon glyphicon-edit"></i></button>
                        <button style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" onclick="pass(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="fa fa-exchange"></i></button>
                        <button style="margin-left:5px;" title="Set Active" type="button" class="btn btn-primary btn-circle btn-xs" onclick="trigger(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="glyphicon glyphicon-check"></i></button>
                        </div>';
                    } else {
                        return '<div class="text-center">
                        <button style="margin-left:5px;" title="Akses" type="button" class="btn btn-warning btn-circle btn-xs edit" onclick="akses(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="glyphicon glyphicon-wrench"></i></button>
                        <button style="margin-left:5px;" title="Edit" type="button" class="btn btn-success btn-circle btn-xs edit" onclick="edit(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="glyphicon glyphicon-edit"></i></button>
                        <button style="margin-left:5px;" title="Ganti Password" type="button" class="btn btn-primary btn-circle btn-xs edit" onclick="pass(\'' . Crypt::encrypt($user->m_id) . '\')" disabled><i class="fa fa-exchange"></i></button>
                        <button style="margin-left:5px;" title="Set Active" type="button" class="btn btn-primary btn-circle btn-xs" onclick="trigger(\'' . Crypt::encrypt($user->m_id) . '\')"><i class="glyphicon glyphicon-check"></i></button>
                        </div>';
                    }*/
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function pengaturanpengguna_akses()
    {
        $level = DB::table('m_level')->where('m_id', Auth::user()->u_level)->first();

        if (Auth::user()->u_user == 'E') {
          $tmp = DB::table('m_employee')->where('e_id', Auth::user()->u_code)->first();
          $address = $tmp->e_address;
          $nama = $tmp->e_name;
        } else {
          $tmp = DB::table('m_agen')->where('a_id', Auth::user()->u_code)->first();
          $address = $tmp->a_address;
          $nama = $tmp->a_name;
        }

        $menu = DB::table('m_access')->get();

        return view('pengaturan.pengaturanpengguna.akses', compact('nama', 'level', 'address', 'menu'));
    }

    public function pengaturanpengguna_create()
    {
        $agen = DB::table('m_agen')->get();

        $employee = DB::table('m_employee')->get();

        $company = DB::table('m_company')->get();

        $level = DB::table('m_level')->get();

        return view('pengaturan.pengaturanpengguna.create',compact('agen', 'employee', 'company', 'level'));
    }

    public function pengaturanpengguna_edit()
    {
        return view('pengaturan.pengaturanpengguna.edit');
    }
    public function pengaturanpengguna_simpan(Request $request){
      DB::beginTransaction();
      try {

        if ($request->type == "agen") {
          $user = 'A';
          $code = $request->agen;
        } else {
          $user = 'E';
          $code = $request->pegawai;
        }

        $id = DB::table('d_username')->max('u_id')+1;
        DB::table('d_username')
            ->insert([
              'u_id' => $id,
              'u_company' => $request->cabang,
              'u_username' => $request->username,
              'u_password' => sha1(md5('islamjaya') . $request->password),
              'u_level' => $request->level,
              'u_user' => $user,
              'u_code' => $code,
              'u_created_at' => Carbon::now('Asia/Jakarta'),
              'u_update_at' => Carbon::now('Asia/Jakarta')
            ]);

        $access = DB::table('m_access')
                    ->get();          

        $isi = [];
        for ($i=0; $i < count($access); $i++) {
          $array = ([
            'ua_username' => $id,
            'ua_access' => $access[$i]->a_id,
            'ua_read' => 'N',
            'ua_create' => 'N',
            'ua_update' => 'N',
            'ua_delete' => 'N'
          ]);
          array_push($isi, $array);
        }

        DB::table('d_useraccess')->insert($isi);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal'
        ]);
      }

    }
    public function datatable(){
      $agen = DB::table('d_username')
                ->join('m_company', 'c_id', '=', 'u_company')
                ->join('m_level', 'm_id', '=', 'u_level')
                ->join('m_agen', 'a_code', '=', 'u_code')
                ->where('u_user', 'A')
                ->get();

      $employee = DB::table('d_username')
                    ->join('m_company', 'c_id', '=', 'u_company')
                    ->join('m_level', 'm_id', '=', 'u_level')
                    ->join('m_employee', 'e_id', '=', 'u_code')
                    ->where('u_user', 'E')
                    ->get();

      $datas = $agen->merge($employee);

      return Datatables::of($datas)
          ->addIndexColumn()
          ->addColumn('name', function ($datas){
            if ($datas->u_user == 'A') {
              $nama = $datas->a_name;
            } else {
              $nama = $datas->e_name;
            }
            return $nama;
          })
          ->addColumn('jenis', function ($datas){
            if ($datas->u_user == 'A') {
              $jenis = 'Agen';
            } else {
              $jenis = 'Employee';
            }
            return $jenis;
          })
          ->addColumn('action', function ($datas) {
              return '<center><div class="btn-group btn-group-sm">
                <button class="btn btn-success btn-akses" onclick="window.location.href='.route('pengaturanpengguna.akses').'?id='.$datas->u_id.'" title="Akses"><i class="fa fa-wrench"></i></button>
                <button class="btn btn-warning btn-edit" onclick="window.location.href='.route('pengaturanpengguna.edit').'?id='.$datas->u_id.'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-primary btn-change" data-id="'.$datas->u_id.'" data-toggle="modal" data-target="#change" type="button" title="Ganti Password"><i class="fa fa-exchange"></i></button>
                <button class="btn btn-danger btn-nonaktif" onclick="hapus('.$datas->u_id.')" type="button" title="Nonaktif"><i class="fa fa-times-circle"></i></button>
                </div></center>';
          })
          ->rawColumns(['action'])
          ->make(true);
    }
}
