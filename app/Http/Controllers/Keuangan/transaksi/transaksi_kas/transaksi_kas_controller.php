<?php

namespace App\Http\Controllers\Keuangan\transaksi\transaksi_kas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\keuangan\jurnal\jurnal;
use App\Model\keuangan\dk_transaksi as transaksi;

use DB;
use Auth;
use Session;

class transaksi_kas_controller extends Controller
{
    protected function create(){
    	return view('keuangan.transaksi.transaksi_kas.create');
    }

    protected function resource(){
    	$akun = DB::table('dk_akun')
    				->where('ak_comp', Auth::user()->u_company)
    				->where('ak_setara_kas', '1')
    				->select('ak_id as id', 'ak_id', DB::raw('concat(ak_nomor, " - ", ak_nama) as text'))->get();

    	$akunLawan = DB::table('dk_akun')
    				->where('ak_comp', Auth::user()->u_company)
    				->where('ak_setara_kas', '0')
    				->select('ak_id as id', 'ak_id', DB::raw('concat(ak_nomor, " - ", ak_nama) as text'))->get();

    	$cashflow = DB::table('dk_akun_cashflow')
    					->select('ac_id as id', 'ac_nama as text', 'ac_type as type')
    					->orderBy('ac_nama', 'asc')->get();

    	return json_encode([
    		"akun"		  => $akun,
    		"akunLawan"	  => $akunLawan,
    		"cashflow"	  => $cashflow,
    		"transaksi"	  => $this->grapData(),
            "keterangan"  => $keterangan = DB::table('dk_transaksi')->distinct('tr_keterangan')->select('tr_keterangan')->get()
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

    	DB::beginTransaction();

    	try {

    		$nomor = $this->getNomor($tanggal);
    		$id = DB::table('dk_transaksi')->max('tr_id') + 1;

    		DB::table('dk_transaksi')->insert([
    			'tr_id'				=> $id,
    			'tr_type'			=> 'TK',
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
					"trdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null
    			];

    			$detail[0] = [
    				"jrdt_nomor"		=> 1,
    				"jrdt_akun"			=> $request->tr_akun_kas,
    				"jrdt_value"		=> str_replace(',', '', $request->tr_nominal),
    				"jrdt_dk"			=> $request->tr_jenis,
					"jrdt_keterangan" 	=> $request->dt_keterangan[0],
					"jrdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null
    			];
    		}

    		foreach ($request->dt_akun as $key => $akun) {

    			$value = str_replace(',', '', $request->dt_debet[$key+1]);

    			if(!is_null($request->dt_debet[$key + 1]) || !is_null($request->dt_kredit[$key + 1])){
    				$nominal = (is_null($request->dt_debet[$key + 1])) ? str_replace(',', '', $request->dt_kredit[$key + 1]) : str_replace(',', '', $request->dt_debet[$key + 1]);

    				array_push($feeder, [
	    				"trdt_transaksi"	=> $id,
	    				"trdt_nomor"		=> $counter,
	    				"trdt_akun"			=> $akun,
	    				"trdt_value"		=> $nominal,
	    				"trdt_dk"			=> (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
	    				"trdt_keterangan"	=> $request->dt_keterangan[$key + 1],
	    				"trdt_cashflow"		=> null
	    			]);

	    			array_push($detail, [
	    				"jrdt_nomor"		=> $counter,
	    				"jrdt_akun"			=> $akun,
	    				"jrdt_value"		=> $nominal,
	    				"jrdt_dk"			=> (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
						"jrdt_keterangan" 	=> $request->dt_keterangan[$key + 1],
						"jrdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null
	    			]);
    			}

    			$counter++;
    		}

    		DB::table('dk_transaksi_detail')->insert($feeder);

    		$jurnal = jurnal::jurnalTransaksi($detail, $tanggal, $nomor, $request->tr_keterangan, 'TK', Auth::user()->u_company);

            if($jurnal['status'] == 'error'){
                return json_encode($jurnal);
            }

    		DB::commit();

    		return json_encode([
                'status'        => 'success',
                'text'          => 'Data transaksi kas berhasil disimpan',
                "transaksi"		=> $this->grapData(),
                "keterangan"    => $keterangan = DB::table('dk_transaksi')->distinct('tr_keterangan')->select('tr_keterangan')->get()
            ]);
    		
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

        try {
            
            $transaksi = DB::table('dk_transaksi')->where('tr_id', $request->tr_id);

            if(!$transaksi->first()){
                return json_encode([
                    'status'  => 'error',
                    'text'    => 'Transaksi tidak bisa ditemukan. Coba muat ulang halaman untuk mendapatkan data terbaru '
                ]);
            }

            $transaksi->update([
                "tr_keterangan" => $request->tr_keterangan
            ]);

            DB::table('dk_transaksi_detail')->where('trdt_transaksi', $request->tr_id)->delete();
            $feeder = []; $counter = 2; $detail = [];

            if($request->tr_nominal != '0.00'){
                $feeder[0] = [
                    "trdt_transaksi"    => $transaksi->first()->tr_id,
                    "trdt_nomor"        => 1,
                    "trdt_akun"         => $request->tr_akun_kas,
                    "trdt_value"        => str_replace(',', '', $request->tr_nominal),
                    "trdt_dk"           => $request->tr_jenis,
                    "trdt_keterangan"   => $request->dt_keterangan[0],
					"trdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null

                ];

                $detail[0] = [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $request->tr_akun_kas,
                    "jrdt_value"        => str_replace(',', '', $request->tr_nominal),
                    "jrdt_dk"           => $request->tr_jenis,
                    "jrdt_keterangan"   => $request->dt_keterangan[0],
					"jrdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null
                ];
            }

            foreach ($request->dt_akun as $key => $akun) {

                $value = str_replace(',', '', $request->dt_debet[$key+1]);

                if(!is_null($request->dt_debet[$key + 1]) || !is_null($request->dt_kredit[$key + 1])){
                    $nominal = (is_null($request->dt_debet[$key + 1])) ? str_replace(',', '', $request->dt_kredit[$key + 1]) : str_replace(',', '', $request->dt_debet[$key + 1]);

                    array_push($feeder, [
                        "trdt_transaksi"    => $transaksi->first()->tr_id,
                        "trdt_nomor"        => $counter,
                        "trdt_akun"         => $akun,
                        "trdt_value"        => $nominal,
                        "trdt_dk"           => (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
                        "trdt_keterangan"   => $request->dt_keterangan[$key + 1],
						"trdt_cashflow"		=> null

                    ]);

                    array_push($detail, [
                        "jrdt_nomor"        => $counter,
                        "jrdt_akun"         => $akun,
                        "jrdt_value"        => $nominal,
                        "jrdt_dk"           => (is_null($request->dt_debet[$key + 1])) ? "K" : "D",
                        "jrdt_keterangan"   => $request->dt_keterangan[$key + 1],
						"jrdt_cashflow"		=> (isset($request->tr_ac_cashflow)) ? $request->tr_ac_cashflow : null
                    ]);
                }

                $counter++;
            }

            DB::table('dk_transaksi_detail')->insert($feeder);

            $jurnal = jurnal::updateJurnal($detail, $transaksi->first()->tr_tanggal_trans, $transaksi->first()->tr_nomor, $request->tr_keterangan, 'TK', Auth::user()->u_company);

            if($jurnal['status'] == 'error'){
                return json_encode($jurnal);
            }

            DB::commit();

            return json_encode([
                'status'        => 'success',
                'text'          => 'Data transaksi kas berhasil diperbarui',
                "transaksi"     => $this->grapData(),
                "keterangan"    => $keterangan = DB::table('dk_transaksi')->distinct('tr_keterangan')->select('tr_keterangan')->get()
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
        }
    }

    protected function delete(Request $request){

        try {

            $transaksi = DB::table('dk_transaksi')->where('tr_id', $request->tr_id);

            if(!$transaksi->first()){
                return json_encode([
                    'status'  => 'error',
                    'text'    => 'Transaksi tidak bisa ditemukan. Coba muat ulang halaman untuk mendapatkan data terbaru '
                ]);
            }

            $jurnal = DB::table('dk_jurnal')->where('jr_nota_ref', $transaksi->first()->tr_nomor)->first();

            if($jurnal){
                $dropJurnal = jurnal::dropJurnal($jurnal->jr_id);

                if($dropJurnal['status'] == 'error'){
                    return $dropJurnal;
                }
            }

            $transaksi->delete();

            DB::commit();

            return json_encode([
                'status'        => 'success',
                'text'          => 'Data transaksi kas berhasil dihapus',
                "transaksi"     => $this->grapData(),
                "keterangan"    => $keterangan = DB::table('dk_transaksi')->distinct('tr_keterangan')->select('tr_keterangan')->get()
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
                    ->where('tr_type', 'TK')
    				->select(DB::raw('substring(tr_nomor, 13) as nomor'))
    				->orderBy('tr_id', 'desc')
    				->first();

    	$data = ($data) ? ($data->nomor + 1) : 1;
    	$nomor = 'TK-'.date('d', strtotime($date)).'/'.date('m', strtotime($date)).'/'.date('y', strtotime($date)).'/'.$data;

    	return $nomor;
    }

    private function grapData(){
    	$data = transaksi::where('tr_comp', Auth::user()->u_company)
    				->with([
                        'detail' => function($query){
                            $query->join('dk_akun', 'dk_akun.ak_id', '=', 'dk_transaksi_detail.trdt_akun')
                            			->leftJoin('dk_akun_cashflow', 'dk_akun_cashflow.ac_id', '=', 'dk_transaksi_detail.trdt_cashflow')
                                        ->select('dk_transaksi_detail.*', DB::raw('concat(ak_nomor, " - ", ak_nama) as akun_nama'), 'dk_akun_cashflow.ac_type');
                        }
                    ])
    				->where('tr_type', 'TK')
    				->get();

    	return $data;
    }
}
