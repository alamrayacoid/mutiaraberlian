<?php

namespace App\Http\Controllers\Keuangan\master\akun;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\keuangan\dk_hierarki_satu as level_satu;
use App\Helper\keuangan\saldo\saldo_akun as saldo_akun;
use App\d_username as user;

use DB;
use Auth;
use Session;

class akun_controller extends Controller
{
    protected function index(){
    	return view('keuangan.master.akun.index');
    }

    protected function create(){
    	return view('keuangan.master.akun.create');
    }

    protected function resource(){
    	$data = level_satu::select('hs_id', 'hs_id as id', DB::raw('concat(hs_id, " - ", hs_nama) as text'))
					->with([
						'level2' => function($query){
							$query->select(
								'hd_id',
								'hd_id as id',
								'hd_level_1',
								'hd_nomor',
								 DB::raw('concat(hd_nomor, " - ", hd_nama) as text')
							);
						}
					])
					->get();

		$akunUtama = [];
		if(!Session::get('isPusat')){
			$akunUtama = DB::table('dk_akun_utama')
							->whereNotIn('au_id', function($query){
								$query->select('ak_akun_utama')->from('dk_akun')
											->where('ak_comp', Auth::user()->u_company);
							})
							->orderBy('au_nomor', 'asc')
							->select('au_id', 'au_nomor', 'au_nama', 'au_posisi')->get();
		}

    	return json_encode([
    		"hierarki" 	=> $data,
    		"akun"		=> $this->grapData(),
    		"akunUtama"	=> $akunUtama
    	]);
    }

    protected function save(Request $request){
    	// return json_encode($request->all());

    	$kelompok = DB::table('dk_hierarki_dua')->where('hd_id', $request->ak_kelompok)->first();

    	if(!$kelompok){
    		return json_encode([
                'status'    => 'error',
                'text'      => 'Kelompok yang anda pilih tidak ada. Harap muat ulang halaman untuk update data terbaru.',
            ]);
    	}

    	$nomor = $kelompok->hd_nomor.'.'.$request->ak_nomor;

    	if(DB::table('dk_akun')->where('ak_nomor', $nomor)->where('ak_comp', Auth::user()->u_company)->first()){
    		return json_encode([
                'status'    => 'error',
                'text'      => 'Nomor COA sudah digunakan. Harap memasukkan nomor COA lain.',
            ]);
    	}

    	DB::beginTransaction();

    	try {
    		
    		$feeder = [];
    		$id = DB::table('dk_akun')->max('ak_id') + 1;
    		$ids = DB::table('dk_akun_utama')->max('au_id') + 1;

    		array_push($feeder, [
    			'ak_id'				=> $id,
    			'ak_nomor'			=> $nomor,
    			'ak_tahun'			=> date('Y'),
    			'ak_comp'			=> Auth::user()->u_company,
    			'ak_nama'			=> $request->ak_nama,
    			'ak_sub_id'			=> $request->ak_nomor,
    			'ak_kelompok'		=> $request->ak_kelompok,
    			'ak_posisi'			=> $request->ak_posisi,
    			'ak_opening_date'	=> date('Y-m-d'),
    			'ak_opening'		=> (float) str_replace(',', '', $request->ak_saldo),
    			'ak_setara_kas'		=> (isset($request->setara_kas)) ? '1' : '0',
    			'ak_akun_utama'		=> (isset($request->utama)) ? $ids : null,
    		]);

    		if(isset($request->utama)){
    			DB::table('dk_akun_utama')->insert([
    				'au_id'				=> $ids,
	    			'au_nomor'			=> $nomor,
	    			'au_nama'			=> $request->ak_nama,
	    			'au_sub_id'			=> $request->ak_nomor,
	    			'au_kelompok'		=> $request->ak_kelompok,
	    			'au_posisi'			=> $request->ak_posisi,
	    			'au_setara_kas'		=> (isset($request->setara_kas)) ? '1' : '0',
    			]);
    		}

    		DB::table('dk_akun')->insert($feeder);

    		foreach ($feeder as $key => $data) {
	    		saldo_akun::add($data["ak_id"], $data['ak_opening'], $data['ak_comp']);
	    	}

    		DB::commit();

    		return json_encode([
                'status'    => 'success',
                'text'      => 'Data COA baru berhasil disimpan',
                "akun"		=> $this->grapData(),
            ]);

    	} catch (Exception $e) {
    		DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
    	}
    }

    protected function saveAkunUtama(Request $request){
    	// return json_encode($request->all());

    	DB::beginTransaction();

    	try {
    		
    		$ids = DB::table('dk_akun')->max('ak_id') + 1;
    		$feeder = [];

    		foreach ($request->ak_id as $key => $id) {
    			$akun_utama = DB::table('dk_akun_utama')->where('au_id', $id)->first();

    			if($akun_utama){
    				array_push($feeder, [
		    			'ak_id'				=> $ids,
		    			'ak_nomor'			=> $akun_utama->au_nomor,
		    			'ak_tahun'			=> date('Y'),
		    			'ak_comp'			=> Auth::user()->u_company,
		    			'ak_nama'			=> $akun_utama->au_nama,
		    			'ak_sub_id'			=> $akun_utama->au_sub_id,
		    			'ak_kelompok'		=> $akun_utama->au_kelompok,
		    			'ak_posisi'			=> $akun_utama->au_posisi,
		    			'ak_opening_date'	=> date('Y-m-d'),
		    			'ak_opening'		=> (float) str_replace(',', '', $request->ak_saldo[$key]),
		    			'ak_setara_kas'		=> $akun_utama->au_setara_kas,
		    			'ak_akun_utama'		=> $id,
		    		]);

		    		$ids++;
    			}
    		}

    		DB::table('dk_akun')->insert($feeder);

    		foreach ($feeder as $key => $data) {
	    		saldo_akun::add($data["ak_id"], $data['ak_opening'], $data['ak_comp']);
	    	}

    		DB::commit();

    		$akunUtama = DB::table('dk_akun_utama')
							->whereNotIn('au_id', function($query){
								$query->select('ak_akun_utama')->from('dk_akun')
											->where('ak_comp', Auth::user()->u_company);
							})
							->orderBy('au_nomor', 'asc')
							->select('au_id', 'au_nomor', 'au_nama', 'au_posisi')->get();

    		return json_encode([
                'status'    => 'success',
                'text'      => 'Data COA baru berdasarkan COA utama berhasil disimpan',
                "akun"		=> $this->grapData(),
                'akunUtama'	=> $akunUtama
            ]);

    		// return json_encode($feeder);

    	} catch (Exception $e) {
    		DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
    	}
    }

    protected function update(Request $request){
    	// return json_encode($request->all());

    	$akun = DB::table('dk_akun')->where('ak_id', $request->ak_id);
    	$kelompok = DB::table('dk_hierarki_dua')->where('hd_id', $request->ak_kelompok)->first();

		if(!$dataAkun = $akun->first()){
			return json_encode([
                'status'    => 'error',
                'text'      => 'COA yang dimaksud tidak bisa ditemukan di database. Cobalah untuk memuat ulang halaman untuk mendapatkan update data terbaru.',
            ]);
		}

		if(!$dataAkun->ak_akun_utama){
	    	if(!$kelompok){
	    		return json_encode([
	                'status'    => 'error',
	                'text'      => 'Kelompok yang anda pilih tidak ada. Harap muat ulang halaman untuk update data terbaru.',
	            ]);
	    	}

	    	$nomor = $kelompok->hd_nomor.'.'.$request->ak_nomor;
	    	$cekNomor = DB::table('dk_akun')->where('ak_nomor', $nomor)->where('ak_id', '!=', $request->ak_id)->first();

	    	if($cekNomor){
	    		return json_encode([
	                'status'    => 'error',
	                'text'      => 'Nomor COA sudah digunakan. Harap memasukkan nomor COA lain.',
	            ]);
	    	}
	    }

    	DB::beginTransaction();

    	try {

    		$lastSaldo = $dataAkun->ak_opening;

    		if(!$dataAkun->ak_akun_utama){
	    		$akun->update([
	    			'ak_nomor'		=> $nomor,
	    			'ak_nama'		=> $request->ak_nama,
	    			'ak_kelompok'	=> $request->ak_kelompok,
	    			'ak_sub_id'		=> $request->ak_nomor,
	    			'ak_posisi'		=> $request->ak_posisi,
	    			'ak_opening'	=> (float) str_replace(',', '', $request->ak_saldo) 
	    		]);

	    		if($lastSaldo != (float) str_replace(',', '', $request->ak_saldo) || $dataAkun->ak_posisi != $request->ak_posisi){
	    			saldo_akun::update($request->ak_id, $request->ak_posisi, $lastSaldo, (float) str_replace(',', '', $request->ak_saldo));
	    		}
	    	}else{
	    		$akun->update([
	    			'ak_opening'	=> (float) str_replace(',', '', $request->ak_saldo) 
	    		]);

	    		if($lastSaldo != (float) str_replace(',', '', $request->ak_saldo)){
	    			saldo_akun::update($request->ak_id, $dataAkun->ak_posisi, $lastSaldo, (float) str_replace(',', '', $request->ak_saldo));
	    		}
	    	}

    		DB::commit();

    		return json_encode([
                'status'    => 'success',
                'text'      => 'Data COA Berhasil Diperbarui',
                "akun"		=> $this->grapData(),
            ]);

    	} catch (Exception $e) {
    		DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
    	}
    }

    protected function grap(){
    	return json_encode([
    		'akun'	=> $this->grapData()
    	]);
    }

    private function registerSaldo(array $feeder){
    	foreach ($feeder as $key => $data) {
    		saldo_akun::add($data["ak_id"], $data['ak_opening'], $data['ak_comp']);
    	}
    }

    private function grapData(){
    	$data = DB::table('dk_akun')
    				->where('ak_comp', Auth::user()->u_company)
    				->select('ak_id', 'ak_nama', 'ak_posisi', 'ak_sub_id', 'ak_opening', 'ak_kelompok', 'ak_nomor', 'ak_akun_utama')
    				->orderBy('ak_nomor', 'asc')
    				->get();

    	return $data;
    }
}
