<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('pengaturan.pengaturanpengguna.akses');
    }

    public function pengaturanpengguna_create()
    {
        return view('pengaturan.pengaturanpengguna.create');
    }
    
    public function pengaturanpengguna_edit()
    {
        return view('pengaturan.pengaturanpengguna.edit');
    }
}
