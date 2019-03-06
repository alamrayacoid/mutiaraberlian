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
