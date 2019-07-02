<?php

namespace App\Http\Controllers\Keuangan\transaksi\mutasi_kas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Session;

class mutasi_kas_controller extends Controller
{
    public function create(){
    	return view('keuangan.transaksi.mutasi_kas.create');
    }
}
