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

	static function balancingSaldoFromJurnal(Array $detail, String $tanggal, String $type){

		$year = date('Y-m', strtotime($tanggal)).'-01';

		foreach($detail as $key => $data){
			$construct = DB::table('dk_akun_saldo')
							->join('dk_akun', 'dk_akun_saldo.as_akun', '=', 'dk_akun.ak_id')
							->where('as_akun', $data['jrdt_akun'])
							->where('as_periode', $year)
							->select('dk_akun_saldo.*', 'dk_akun.ak_posisi');

			$kolomUpdate =  DB::table('dk_akun_saldo')
								->where('as_periode', $year);

			if($construct->first()){


				$debet = $kredit = $calculation = 0;

				if($data['jrdt_dk'] == 'D'){
					$debet = $calculation = $data['jrdt_value'];

					if($construct->first()->ak_posisi == "K")
						$calculation *= -1;

				}else{
					$kredit = $calculation = $data['jrdt_value'];

					if($construct->first()->ak_posisi == "D")
						$calculation *= -1;
				}

				switch ($type) {
					case 'MK':
						$kolomUpdate->update([
							"as_mut_kas_debet"	=> DB::raw('as_mut_kas_debet + '.$debet),
							"as_mut_kas_kredit"	=> DB::raw('as_mut_kas_kredit + '.$kredit),
							"as_saldo_akhir"	=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;

					case 'TK':
						$kolomUpdate->update([
							"as_trans_kas_debet"	=> DB::raw('as_trans_kas_debet + '.$debet),
							"as_trans_kas_kredit"	=> DB::raw('as_trans_kas_kredit + '.$kredit),
							"as_saldo_akhir"		=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;
					
					case 'TM':
						$kolomUpdate->update([
							"as_trans_memorial_debet"	=> DB::raw('as_trans_memorial_debet + '.$debet),
							"as_trans_memorial_kredit"	=> DB::raw('as_trans_memorial_kredit + '.$kredit),
							"as_saldo_akhir"			=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;
				}

				DB::table('dk_akun_saldo')
					->where('as_periode', '>', $year)
					->where('as_akun', $data['jrdt_akun'])
					->update([
						'as_saldo_awal' 	=> DB::raw('as_saldo_awal + '.$calculation),
						'as_saldo_akhir' 	=> DB::raw('as_saldo_akhir + '.$calculation),
					]);

			}
		}

		return [
			'status' => 'success'
		];
	}

	static function decrease(Object $detail, String $tanggal, String $type){

		$year = date('Y-m', strtotime($tanggal)).'-01';

		foreach($detail as $key => $data){
			$construct = DB::table('dk_akun_saldo')
							->join('dk_akun', 'dk_akun_saldo.as_akun', '=', 'dk_akun.ak_id')
							->where('as_akun', $data->jrdt_akun)
							->where('as_periode', $year)
							->select('dk_akun_saldo.*', 'dk_akun.ak_posisi');

			if($construct->first()){


				$debet = $kredit = $calculation = 0;

				if($data->jrdt_dk == 'D'){
					$debet = $calculation = $data->jrdt_value;

					if($construct->first()->ak_posisi == "D")
						$calculation *= -1;

				}else{
					$kredit = $calculation = $data->jrdt_value;

					if($construct->first()->ak_posisi == "K")
						$calculation *= -1;
				}

				switch ($type) {
					case 'MK':
						$construct->update([
							"as_mut_kas_debet"	=> DB::raw('as_mut_kas_debet - '.$debet),
							"as_mut_kas_kredit"	=> DB::raw('as_mut_kas_kredit - '.$kredit),
							"as_saldo_akhir"	=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;

					case 'TK':
						$construct->update([
							"as_trans_kas_debet"	=> DB::raw('as_trans_kas_debet - '.$debet),
							"as_trans_kas_kredit"	=> DB::raw('as_trans_kas_kredit - '.$kredit),
							"as_saldo_akhir"		=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;
					
					case 'TM':
						$construct->update([
							"as_trans_memorial_debet"	=> DB::raw('as_trans_memorial_debet - '.$debet),
							"as_trans_memorial_kredit"	=> DB::raw('as_trans_memorial_kredit - '.$kredit),
							"as_saldo_akhir"			=> DB::raw('as_saldo_akhir + '.$calculation)
						]);
						break;
				}

				DB::table('dk_akun_saldo')
					->where('as_periode', '>', $year)
					->where('as_akun', $data->jrdt_akun)
					->update([
						'as_saldo_awal' 	=> DB::raw('as_saldo_awal + '.$calculation),
						'as_saldo_akhir' 	=> DB::raw('as_saldo_akhir + '.$calculation),
					]);

			}
		}

		return [
			'status' => 'success'
		];
	}

}

?>