<?php

namespace App\Http\Controllers\Keuangan\transaksi\mutasi_kas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\keuangan\jurnal\jurnal;
use App\Model\keuangan\dk_transaksi as transaksi;

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
    		"akun"		=> $akun,
    		"transaksi"	=> $this->grapData()
    	]);
    }

    protected function save(Request $request){
    	// return json_encode($request->all());

    	$tanggal = explode('/', $request->tr_tanggal)[2].'-'.explode('/', $request->tr_tanggal)[1].'-'.explode('/', $request->tr_tanggal)[0];

    	$tsl = date('Y-m', strtotime($tanggal)).'-01';

    	$tglCek = DB::table('dk_periode_keuangan')
    					->where('pk_periode', $tsl)
    					->where('pk_comp', Auth::user()->u_company)->first();

    	if(!$tglCek){
    		return json_encode([
                'status'  => 'error',
                'text'    => 'Periode kuangan untuk tanggal transaksi yang di input tidak bisa ditemukan. Data gagal tersimpan.'
            ]);
    	}

    	$akCek = DB::table('dk_akun')->where('ak_id', $request->tr_akun_kas)->first();

    	if(!$akCek){
    		return json_encode([
                'status'  => 'error',
                'text'    => 'Beberapa COA yang dipilih tidak ditemukan di database, Coba muat ulang halaman lalu inputkan ulang.'
            ]);
    	}

    	DB::beginTransaction();

    	try {

    		$nomor = $this->getNomor($tanggal);
    		$id = DB::table('dk_transaksi')->max('tr_id') + 1;

    		DB::table('dk_transaksi')->insert([
    			'tr_id'				=> $id,
    			'tr_type'			=> 'MK',
    			'tr_comp'			=> Auth::user()->u_company,
    			'tr_nomor'			=> $nomor,
    			'tr_tanggal_trans'	=> $tanggal,
    			'tr_keterangan'		=> $request->tr_keterangan,
    		]);

    		$feeder = []; $counter = 2; $detail = [];

    		if($request->tr_nominal != '0.00'){
    			$feeder[0] = [
    				"trdt_transaksi"	=> $id,
    				"trdt_nomor"		=> 1,
    				"trdt_akun"			=> $request->tr_akun_kas,
    				"trdt_value"		=> str_replace(',', '', $request->tr_nominal),
    				"trdt_dk"			=> $request->tr_jenis,
					"trdt_keterangan" 	=> $request->dt_keterangan[0],
    			];

    			$detail[0] = [
    				"jrdt_nomor"		=> 1,
    				"jrdt_akun"			=> $request->tr_akun_kas,
    				"jrdt_value"		=> str_replace(',', '', $request->tr_nominal),
    				"jrdt_dk"			=> $request->tr_jenis,
					"jrdt_keterangan" 	=> $request->dt_keterangan[0],
    			];
    		}

    		foreach ($request->dt_akun as $key => $akun) {
    			$akCek = DB::table('dk_akun')->where('ak_id', $akun)->first();

		    	if(!$akCek){
		    		return json_encode([
		                'status'  => 'error',
		                'text'    => 'Beberapa COA yang dipilih tidak ditemukan di database, Coba muat ulang halaman lalu inputkan ulang.'
		            ]);
		    	}

    			$value = str_replace(',', '', $request->dt_debet[$key+1]);

    			if(!is_null($request->dt_debet[$key + 1]) || !is_null($request->dt_kredit[$key + 1])){
    				$nominal = (is_null($request->dt_debet[$key + 1])) ? str_replace(',', '', $request->dt_kredit[$key + 1]) : str_replace(',', '', $request->dt_debet[$key + 1]);

    				array_push($feeder, [
	    				"trdt_transaksi"	=> $id,
	    				"trdt_nomor"		=> $counter,
	    				"trdt_akun"			=> $akun,
	    				"trdt_value"		=> $nominal,
	    				"trdt_dk"			=> (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
	    				"trdt_keterangan"	=> $request->dt_keterangan[$key + 1]
	    			]);

	    			array_push($detail, [
	    				"jrdt_nomor"		=> $counter,
	    				"jrdt_akun"			=> $akun,
	    				"jrdt_value"		=> $nominal,
	    				"jrdt_dk"			=> (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
						"jrdt_keterangan" 	=> $request->dt_keterangan[$key + 1],
	    			]);
    			}

    			$counter++;
    		}

    		// return json_encode($detail);

    		DB::table('dk_transaksi_detail')->insert($feeder);

    		jurnal::jurnalTransaksi($detail, $tanggal, $nomor, $request->tr_keterangan, 'MK', Auth::user()->u_company);

    		DB::commit();

    		return json_encode([
                'status'    => 'success',
                'text'      => 'Data mutasi antar kas baru berhasil disimpan',
                // "akun"		=> $this->grapData(),
            ]);
    		
    	} catch (Exception $e) {
    		DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
    	}
    }

    private function getNomor(String $date){

    	$keyword = date('Y-m', strtotime($date));

    	$data = DB::table('dk_transaksi')
    				->where(DB::raw('DATE_FORMAT(tr_tanggal_trans, "%Y-%m")'), $keyword)
    				->orderBy('tr_id', 'desc')
    				->select(DB::raw('substring(tr_nomor, 13) as nomor'))
    				->first();

    	$data = ($data) ? ($data->nomor + 1) : 1;
    	$nomor = 'MK-'.date('d', strtotime($date)).'/'.date('m', strtotime($date)).'/'.date('y', strtotime($date)).'/'.$data;

    	return $nomor;
    }

    private function grapData(){
    	$data = transaksi::where('tr_comp', Auth::user()->u_company)
    				->with('detail')
    				->where('tr_type', 'MK')
    				->get();

    	return $data;
    }


}
