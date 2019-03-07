<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Auth;

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
        return view('pengaturan.pengaturanpengguna.create');
    }

    public function pengaturanpengguna_edit()
    {
        return view('pengaturan.pengaturanpengguna.edit');
    }
}
