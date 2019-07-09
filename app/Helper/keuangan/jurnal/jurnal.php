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
			array_push($det, [
				"jrdt_jurnal"		=> $id,
				"jrdt_nomor"		=> $key + 1,
				"jrdt_akun"			=> $dt['jrdt_akun'],
				"jrdt_value"		=> $dt['jrdt_value'],
				"jrdt_dk"			=> $dt['jrdt_dk'],
				"jrdt_keterangan" 	=> $dt['jrdt_keterangan'],
			]);
		}

		DB::table('dk_jurnal_detail')->insert($det);
		
		return saldo_akun::balancingSaldoFromJurnal($detail, $tanggal, $type);
	}
}

?>