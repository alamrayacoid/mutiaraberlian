<?php

namespace App\Http\Controllers\Keuangan\pajak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class pajakController extends Controller
{
    public function index()
    {
        return view('keuangan.pajak.pajak');
    }
}
