<?php
	
namespace App\Helper\keuangan\saldo;

use DB;

class saldo_akun {

	static function add(int $id_akun, float $saldo, string $comp){
		$feeder = [];
		$periode = DB::table('dk_periode_keuangan')->where('pk_comp', $comp)->get();
		$lastKey = DB::table('dk_akun_saldo')->max('as_id') + 1;

		foreach($periode as $key => $periode){
			$feeder[$key] = [
				'as_id'						=> $lastKey,
				"as_akun"					=> $id_akun,
				"as_periode"				=> $periode->pk_periode,
				"as_saldo_awal"				=> ($saldo) ? str_replace(',', '', $saldo) : 0,
				"as_mut_kas_debet"			=> 0,
				"as_mut_kas_kredit"			=> 0,
				"as_trans_kas_debet"		=> 0,
				"as_trans_kas_kredit"		=> 0,
				"as_trans_memorial_debet"	=> 0,
				"as_trans_memorial_kredit"	=> 0,
				"as_saldo_akhir"			=> ($saldo) ? str_replace(',', '', $saldo) : 0,
			];

			$lastKey++;
		}
		
		DB::table('dk_akun_saldo')->insert($feeder);
	}

	static function update(String $akun_id, String $akun_posisi, float $saldoLama, float $saldo){
		$data = DB::table('dk_akun_saldo')->where('as_akun', $akun_id)->get();

		$cek = [];
		$different = $saldo;

		foreach($data as $key => $saldo){
			if($akun_posisi == 'D'){
				$plus = ($saldo->as_mut_kas_debet + $saldo->as_trans_kas_debet + $saldo->as_trans_memorial_debet);
				$minus = ($saldo->as_mut_kas_kredit + $saldo->as_trans_kas_kredit + $saldo->as_trans_memorial_kredit);
			}else{
				$plus = ($saldo->as_mut_kas_kredit + $saldo->as_trans_kas_kredit + $saldo->as_trans_memorial_kredit);
				$minus = ($saldo->as_mut_kas_debet + $saldo->as_trans_kas_debet + $saldo->as_trans_memorial_debet);
			}

			$divider = $plus - $minus;

			// return json_encode($akun_posisi.' -> '.$plus.' - '.$minus);

			DB::table('dk_akun_saldo')->where('as_id', $saldo->as_id)->update([
				'as_saldo_awal'		=> $different,
				'as_saldo_akhir'	=> $different + $divider,
			]);

			$different += $divider;
		}
	}

}

?>