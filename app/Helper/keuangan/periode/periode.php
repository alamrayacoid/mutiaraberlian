<?php
	
namespace App\Helper\keuangan\periode;

use DB;
use Auth;

class periode {

	static function tes(){
		return 'periode siapp.';
	}

	static function generate(String $date){
		return $date;
	}

	static function emptyData(){
		$data = DB::table('dk_periode_keuangan')->where('pk_comp', Auth::user()->u_company)->first();

		if(!$data)
			return true;

		return false;
	}

	static function missing(){
		$tanggal = date('Y-m').'-01';
		$data = DB::table('dk_periode_keuangan')->where('pk_periode', $tanggal)->where('pk_comp', Auth::user()->u_company)->first();

		if(!$data)
			return true;

		return false;
	}

}

?>