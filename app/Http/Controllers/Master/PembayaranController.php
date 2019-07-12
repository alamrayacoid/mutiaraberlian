<?php

namespace App\Http\Controllers\Master;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
    //
    public function index()
    {
        $akun = DB::table('dk_akun')
            ->where('ak_comp', '=', 'MB0000001')
            ->get();
        return view('masterdatautama.pembayaran.index', compact('akun'));
    }
}
