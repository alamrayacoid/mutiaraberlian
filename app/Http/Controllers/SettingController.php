<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;

use Yajra\DataTables\DataTables;

use Carbon\Carbon;

use CodeGenerator;

class SettingController extends Controller
{
    public function perubahanhargajual_index()
    {
        return view('pengaturan.otoritas.perubahanhargajual.index');
    }

    public function pengaturanpengguna_index()
    {
        $level = DB::table('m_level')->get();
        return view('pengaturan.pengaturanpengguna.index', compact('level'));
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

    public function pengaturanpengguna_akses(Request $request)
    {
        $menu = DB::table('m_access')
            ->leftjoin('d_useraccess', 'ua_access', '=', 'a_id')
            ->where('ua_username', $request->id)
            ->orderBy('a_order')
            ->get();
        $user = DB::table('d_username')->where('u_id', '=', $request->id)->first();

        $level = DB::table('m_level')->where('m_id', $user->u_level)->first();

        if ($user->u_user == 'E') {
            $tmp = DB::table('m_employee')->where('e_id', $user->u_code)->first();
            $address = $tmp->e_address;
            $nama = $tmp->e_name;
        } else {
            $tmp = DB::table('m_agen')->where('a_code', $user->u_code)->first();
            $address = $tmp->a_address;
            $nama = $tmp->a_name;
        }

        $company = DB::table('m_company', 'c_id', '=', $user->u_company)->first();

        $id = $request->id;

        return view('pengaturan.pengaturanpengguna.akses', compact('nama', 'level', 'address', 'menu', 'akses', 'company', 'id', 'user'));
    }

    public function pengaturanpengguna_create()
    {
        $agen = DB::table('m_agen')->get();

        $employee = DB::table('m_employee')->get();

        $company = DB::table('m_company')->where('c_type', '!=', 'AGEN')->get();

        $level = DB::table('m_level')->get();

        return view('pengaturan.pengaturanpengguna.create', compact('agen', 'employee', 'company', 'level'));
    }

    public function pengaturanpengguna_edit()
    {
        return view('pengaturan.pengaturanpengguna.edit');
    }

    public function pengaturanpengguna_hapus(Request $request)
    {
        DB::beginTransaction();
        try {

            DB::table('d_username')->where('u_id', $request->id)->delete();

            DB::table('d_useraccess')->where('ua_username', $request->id)->delete();

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

    public function pengaturanpengguna_simpan(Request $request)
    {
        DB::beginTransaction();
        try {

            if ($request->type == "agen") {
                $user = 'A';
                $code = $request->agen;
            } else {
                $user = 'E';
                $code = $request->pegawai;
            }

            $cek = DB::table('d_username')->where('u_username', $request->username)->count();

            $cek1 = DB::table('d_username')->where('u_code', $code)->count();

            if ($cek != 0) {
                return response()->json([
                    'status' => 'failed',
                    'ex' => 'Username sudah digunakan!'
                ]);
            } elseif ($cek1 != 0) {
                return response()->json([
                    'status' => 'failed',
                    'ex' => 'Akun sudah digunakan!'
                ]);
            } else {

                if ($request->type == "agen"){
                    $compa = DB::table('m_company')
                        ->where('c_user', '=', 'A0000003')
                        ->first();
                    $company = $compa->c_id;
                } else {
                    $company = $request->cabang;
                }

                $id = DB::table('d_username')->max('u_id') + 1;
                DB::table('d_username')
                    ->insert([
                        'u_id' => $id,
                        'u_company' => $company,
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
                for ($i = 0; $i < count($access); $i++) {
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

    public function datatable()
    {
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
            ->addColumn('name', function ($datas) {
                if ($datas->u_user == 'A') {
                    $nama = $datas->a_name;
                } else {
                    $nama = $datas->e_name;
                }
                return $nama;
            })
            ->addColumn('jenis', function ($datas) {
                if ($datas->u_user == 'A') {
                    $jenis = 'Agen';
                } else {
                    $jenis = 'Pegawai';
                }
                return $jenis;
            })
            ->addColumn('action', function ($datas) {
                return '<center><div class="btn-group btn-group-sm">
                <button class="btn btn-success btn-akses" onclick="akses(' . $datas->u_id . ')" title="Akses"><i class="fa fa-wrench"></i></button>
                <button class="btn btn-warning btn-edit" onclick="editlevel(' . $datas->u_id . ')" type="button" title="Edit Level"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-primary btn-change" onclick="changepass(' . $datas->u_id . ')" data-toggle="modal" data-target="#change" type="button" title="Ganti Password"><i class="fa fa-exchange"></i></button>
                <button class="btn btn-danger btn-nonaktif" onclick="hapus(' . $datas->u_id . ')" type="button" title="Nonaktif"><i class="fa fa-times-circle"></i></button>
                </div></center>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function pengaturanpengguna_updatepassword(Request $request)
    {
        DB::beginTransaction();
        try {

            $cek = DB::table('d_username')->where('u_id', $request->id)->first();
            if (sha1(md5('islamjaya') . $request->lama) == $cek->u_password) {
                if ($request->baru == $request->confirm) {
                    DB::table('d_username')->where('u_id', $request->id)->update([
                        'u_password' => sha1(md5('islamjaya') . $request->baru)
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'ex' => 'Password confirmasi tidak sama dengan password baru!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'ex' => 'Password lama salah!'
                ]);
            }

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

    public function pengaturanpengguna_updatelevel(Request $request)
    {
        DB::beginTransaction();
        try {

            DB::table('d_username')->where('u_id', $request->id)->update([
                'u_level' => $request->level
            ]);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'gagal' => 'gagal'
            ]);
        }

    }

    public function pengaturanpengguna_simpanakses(Request $request)
    {
        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($request->idaccess); $i++) {
                if ($request->read[$i] != 'N') {
                    DB::table('d_useraccess')
                        ->where('ua_username', $request->id)
                        ->where('ua_access', $request->idaccess[$i])
                        ->update([
                            'ua_read' => $request->read[$i]
                        ]);
                }

                if ($request->insert[$i] != 'N') {
                    DB::table('d_useraccess')
                        ->where('ua_username', $request->id)
                        ->where('ua_access', $request->idaccess[$i])
                        ->update([
                            'ua_create' => $request->insert[$i]
                        ]);
                }

                if ($request->update[$i] != 'N') {
                    DB::table('d_useraccess')
                        ->where('ua_username', $request->id)
                        ->where('ua_access', $request->idaccess[$i])
                        ->update([
                            'ua_update' => $request->update[$i]
                        ]);
                }

                if ($request->delete[$i] != 'N') {
                    DB::table('d_useraccess')
                        ->where('ua_username', $request->id)
                        ->where('ua_access', $request->idaccess[$i])
                        ->update([
                            'ua_delete' => $request->delete[$i]
                        ]);
                }
            }

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
}
