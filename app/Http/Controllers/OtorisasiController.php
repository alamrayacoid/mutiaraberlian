<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtorisasiController extends Controller
{
    public function otorisasi(){
    	return view('notifikasiotorisasi.otorisasi.index');
    }
}
