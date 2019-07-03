<?php

namespace App\Http\Controllers\Keuangan\transaksi\mutasi_kas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Session;

class mutasi_kas_controller extends Controller
{
    protected function create(){
    	return view('keuangan.transaksi.mutasi_kas.create');
    }

    protected function resource(){
    	$akun = DB::table('dk_akun')
    				->where('ak_comp', Auth::user()->u_company)
    				->where('ak_setara_kas', 1)
    				->select('ak_id as id', 'ak_id', DB::raw('concat(ak_nomor, " - ", ak_nama) as text'))->get();

    	return json_encode([
    		"akun"	=> $akun
    	]);
    }

    protected function save(Request $request){
    	return json_encode($request->all());

    	DB::beginTransaction();

    	try {
    		
    		
    		
    	} catch (Exception $e) {
    		DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
    	}
    }


}
