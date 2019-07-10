<?php

namespace App\Helper\keuangan\jurnal;
use App\Helper\keuangan\saldo\saldo_akun;
use DB;

class jurnal{
	static function jurnalTransaksi(Array $detail, String $tanggal, String $nomor, String $keterangan, String $type, String $comp){

		$det = []; $num = 1;
		$id = DB::table('dk_jurnal')->max('jr_id') + 1;

		DB::table('dk_jurnal')->insert([
			'jr_id'				=> $id,
			'jr_type'			=> $type,
			'jr_comp'			=> $comp,
			'jr_ref'			=> 'Transaksi',
			'jr_nota_ref'		=> $nomor,
			'jr_tanggal_trans'	=> $tanggal,
			'jr_keterangan'		=> $keterangan,
		]);

		foreach ($detail as $key => $dt) {
			$akCek = DB::table('dk_akun')->where('ak_id', $dt['jrdt_akun'])->first();

	    	if(!$akCek){
	    		return [
	                'status'  => 'error',
	                'text'    => 'Beberapa COA yang dipilih tidak ditemukan di database, Coba muat ulang halaman lalu inputkan ulang.'
	            ];
	    	}

			array_push($det, [
				"jrdt_jurnal"		=> $id,
				"jrdt_nomor"		=> $key + 1,
				"jrdt_akun"			=> $dt['jrdt_akun'],
				"jrdt_value"		=> $dt['jrdt_value'],
				"jrdt_dk"			=> $dt['jrdt_dk'],
				"jrdt_keterangan" 	=> $dt['jrdt_keterangan'],
				"jrdt_cashflow"		=> (isset($dt['jrdt_cashflow']) && $akCek->ak_setara_kas == '1') ? $dt['jrdt_cashflow'] : null,
			]);
		}

		DB::table('dk_jurnal_detail')->insert($det);
		
		$balancingSaldo = saldo_akun::balancingSaldoFromJurnal($detail, $tanggal, $type);

		if($balancingSaldo['status'] == 'success'){
			return [
                'status'  => 'success',
                'text'    => ''
            ];
		}else{
			return $balancingSaldo;
		}
	}

	static function updateJurnal(Array $detail, String $tanggal, String $nomor, String $keterangan, String $type, String $comp){
		
		$jurnal = DB::table('dk_jurnal')->where('jr_nota_ref', $nomor);

		if($jurnal->first()){

			$dropJurnal = self::dropJurnal($jurnal->first()->jr_id);

			if($dropJurnal['status'] == 'error'){
				return $dropJurnal;
			}
		}

		$jurnalTransaksi = self::jurnalTransaksi($detail, $tanggal, $nomor, $keterangan, $type, $comp);

		if($jurnalTransaksi['status'] == 'error'){
			return $jurnalTransaksi;
		}else{
			return [
                'status'  => 'success',
                'text'    => ''
            ];
		}
	}

	static function dropJurnal(String $idJurnal){
		$data = DB::table('dk_jurnal')->where('jr_id', $idJurnal);

		if($data->first()){
			$detail = DB::table('dk_jurnal_detail')->where('jrdt_jurnal', $data->first()->jr_id)->get();
			$balancingSaldo = saldo_akun::decrease($detail, $data->first()->jr_tanggal_trans, $data->first()->jr_type);

			if($balancingSaldo['status'] == 'error'){
				return $balancingSaldo;
			}else{
				$data->delete();
				return [
		            'status'  => 'success',
		            'text'    => ''
		        ];
			}
		}
	}
}

?>