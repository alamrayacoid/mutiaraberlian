<?php

namespace App\Http\Controllers\Keuangan\laporan\jurnal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\keuangan\dk_jurnal as jurnal;

use DB;
use Auth;

class laporan_jurnal_controller extends Controller
{
    protected function index(){
    	$cabang = json_encode(DB::table('m_company')
                    ->where('c_id', Auth::user()->u_company)
                    ->select('c_id as id', 'c_name as text')
                    ->get());

    	return view('keuangan.laporan.jurnal.index', compact('cabang'));
    }

    protected function resource(Request $request){
    	// return json_encode($request->all());

    	try {
    		$d1 = explode('/', $request->lap_tanggal_awal)[2]."-".explode('/', $request->lap_tanggal_awal)[1].'-'.explode('/', $request->lap_tanggal_awal)[0];
    		$d2 = explode('/', $request->lap_tanggal_akhir)[2]."-".explode('/', $request->lap_tanggal_akhir)[1].'-'.explode('/', $request->lap_tanggal_akhir)[0];

    		$data = []; $namaCabang = 'Semua Cabang';

    		if($request->lap_cabang == 'all'){
    			$data = jurnal::select('jr_tanggal_trans', 'jr_nota_ref', 'jr_keterangan')->get();
    		}else{
    			$data = jurnal::where('jr_comp', $request->lap_cabang)
    							->leftJoin('m_company', 'm_company.c_id', '=', 'dk_jurnal.jr_comp')
    							->join('dk_jurnal_detail as a', 'a.jrdt_jurnal', '=', 'dk_jurnal.jr_id')
    							->join('dk_jurnal_detail as b', 'b.jrdt_jurnal', '=', 'dk_jurnal.jr_id')
    							->where('a.jrdt_dk', 'D')
    							->where('b.jrdt_dk', 'K')
    							->where('jr_tanggal_trans', ">=", $d1)
                            	->where('jr_tanggal_trans', "<=", $d2)
                            	->where('jr_type', $request->lap_jenis)
    							->with([
	                                    'detail' => function($query){
	                                        $query->select('jrdt_jurnal', 'jrdt_akun', 'jrdt_value', 'jrdt_dk', 'ak_nama', 'ak_nomor')
	                                                ->join('dk_akun', 'dk_akun.ak_id', '=', 'dk_jurnal_detail.jrdt_akun')
	                                                ->orderBy('jrdt_dk', 'asc')
	                                                ->get();
	                                    }
	                            ])
    							->select(
    								'jr_id',
    								'jr_tanggal_trans',
    								'jr_nota_ref',
    								'jr_keterangan',
    								'c_name',
    								DB::raw('sum(a.jrdt_value) as debet'), 
    								DB::raw('sum(b.jrdt_value) as kredit'))
    							->groupBy('jr_id')->get();

    			if($cabang = DB::table('m_company')->where('c_id', Auth::user()->u_company)->first()){
    				$namaCabang = $cabang->c_name;
    			}
    		}

    		return json_encode([
	    		"data"		 => $data,
	    		"namaCabang" => $namaCabang,
	    	]);

    	} catch (Exception $e) {
    		return json_encode([
    			'status'	=> 'error',
    			'message'	=> 'System Error '.$e
    		]);
    	}
    }
}
