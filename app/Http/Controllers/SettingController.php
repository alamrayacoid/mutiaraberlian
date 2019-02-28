<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function perubahanhargajual_index()
    {
        return view('pengaturan.otoritas.perubahanhargajual.index');
    }
}
