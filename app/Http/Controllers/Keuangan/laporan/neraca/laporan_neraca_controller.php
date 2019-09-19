<?php

namespace App\Http\Controllers\Keuangan\laporan\neraca;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\keuangan\dk_jurnal as jurnal;
use App\Model\keuangan\dk_hierarki_satu as level_1;
use App\Helper\keuangan\laporan\laporan as laporan;

use DB;
use Auth;

class laporan_neraca_controller extends Controller
{
    protected function index(){
    	$cabang = json_encode(DB::table('m_company')
                    ->where('c_id', Auth::user()->u_company)
                    ->select('c_id as id', 'c_name as text')
                    ->get());

    	return view('keuangan.laporan.neraca.index', compact('cabang'));
    }

    protected function resource(Request $request){

    	try {
    		$d1 = $periode = 0;

    		if($request->lap_jenis == 'B'){
            	$d1 = explode('/', $request->lap_tanggal_awal)[1].'-'.explode('/', $request->lap_tanggal_awal)[0].'-01';
            	$periode = date('F Y', strtotime($d1));

	        }

	        if($request->lap_cabang == 'all'){
                $data = [];
            }else{
		        $data = level_1::where('hs_id', '<=', '3')
		                    ->with([
		                        'subclass' => function($query) use ($d1){
		                            $query->select('hs_id', 'hs_nama', 'hs_level_1')
		                                    ->orderBy('hs_flag')
		                                    ->with([
		                                        'level2' => function($query) use ($d1){
		                                            $query->select('hd_id', 'hd_nama', 'hd_subclass', 'hd_nomor')
		                                                ->with([
		                                                    'akun' => function($query) use ($d1){
		                                                        $query->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
		                                                                ->where('as_periode', $d1)
		                                                                ->select(
		                                                                    'ak_id',
		                                                                    'ak_nomor',
		                                                                    'ak_kelompok',
		                                                                    'ak_nama',
		                                                                    'ak_posisi',
		                                                                    DB::raw('coalesce(as_saldo_akhir, 2) as saldo_akhir')
		                                                                );
		                                                    }
		                                                ]);
		                                        }
		                                    ]);
		                        }
		                    ])
		                    ->select('hs_id', 'hs_nama')
		                    ->get();

		        if($cabang = DB::table('m_company')->where('c_id', Auth::user()->u_company)->first()){
    				$namaCabang = $cabang->c_name;
    			}
		    }

		    $saldoLaba = laporan::getSaldoLaba($request);

		    if($saldoLaba['status'] != 'success')
		    	return $saldoLaba;

	        return json_encode([
	    		"data"	        => $data,
	            "namaCabang" 	=> $namaCabang,
	            "periode"		=> $periode,
	            'saldoLaba'		=> $saldoLaba['data']
	    	]);

    	} catch (Exception $e) {
    		return json_encode([
    			'status'	=> 'error',
    			'message'	=> 'System Error '.$e
    		]);
    	}
    }
}
