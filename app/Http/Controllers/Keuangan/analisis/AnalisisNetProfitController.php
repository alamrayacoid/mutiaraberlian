<?php

namespace App\Http\Controllers\Keuangan\analisis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalisisNetProfitController extends Controller
{
    public function index()
    {
        return view('keuangan.analisis.netprofit.index');
    }
}
