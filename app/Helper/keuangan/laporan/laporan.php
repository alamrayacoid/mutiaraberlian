<?php
	
namespace App\Helper\keuangan\laporan;

use Illuminate\Http\Request;
use App\Model\keuangan\dk_akun as akun;

use DB;
use Auth;

class laporan {
	
	static function status(){
		return 'siap';
	}

	static function getSaldoLaba(Request $request){
		try {
    		$d1 = $periode = 0;

    		if($request->lap_jenis == 'B'){
            	$d1 = explode('/', $request->lap_tanggal_awal)[1].'-'.explode('/', $request->lap_tanggal_awal)[0].'-01';
            	$periode = date('F Y', strtotime($d1));

	        }

	        if($request->lap_cabang == 'all'){
    			$data = [];
    		}else{
		        $data = akun::leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                            ->where(DB::raw('SUBSTRING(ak_nomor, 1, 1)'), '>', 3)
                            ->where('as_periode', $d1)
                            ->select(
                                'ak_id',
                                'ak_nomor',
                                'ak_kelompok',
                                'ak_nama',
                                'ak_posisi',
                                DB::raw('coalesce((as_saldo_akhir - as_saldo_awal), 2) as saldo_akhir')
                            )->orderBy('ak_nomor', 'asc')->get();
		    }

		    $pendapatan = 0;

		    foreach($data as $key => $lr){
		    	$pendapatan += ($lr->ak_posisi == 'D') ? ($lr->saldo_akhir * -1) : $lr->saldo_akhir;
		    }


	        return [
    			'status'	=> 'success',
    			'data'		=> $pendapatan
    		];

    	} catch (Exception $e) {
    		return [
    			'status'	=> 'error',
    			'message'	=> 'System Error '.$e
    		];
    	}
	}

}

?>