<?php

namespace App\Http\Controllers\keuangan\periode;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\keuangan\periode\periode;
use App\Helper\keuangan\saldo\saldo_akun as saldo;

use DB;
use Auth;
use Session;

class periode_controller extends Controller
{
    protected function proses(Request $request){
    	DB::beginTransaction();
    	
    	try {
    		
    		// return json_encode($request->all());

    		$bucket = [];
    		$periode = strtotime($request->tahun.'-'.$request->bulan.'-01');
    		$dateNow = strtotime(date('Y-m').'-01');
    		$ret = date('Y-m-d', $periode);

    		$cek = DB::table('dk_periode_keuangan')
                    ->where('pk_comp', Auth::user()->company)
                    ->where('pk_periode', $periode)->first();

	    	if($cek){
	    		Session::flash('message', 'Periode Keuangan Sudah Dibuat Sebelumnya.');
	    		return redirect()->back();
	    	}

	    	$id = (DB::table('dk_periode_keuangan')->max('pk_id') + 1);

            if(DB::table('dk_periode_keuangan')->where('pk_comp', Auth::user()->u_company)->first()){
                $dateNow = $periode;
                $periode = strtotime('+1 months', strtotime(DB::table('dk_periode_keuangan')->where('pk_comp', Auth::user()->u_company)->max('pk_periode')));

                DB::table('dk_periode_keuangan')->where('pk_comp', Auth::user()->u_company)->update([
                    'pk_status' => '0'
                ]);
            }

            while($periode <= $dateNow){

    			array_push($bucket, [
    				"pk_id"			=> $id,
                    "pk_comp"       => Auth::user()->u_company,
		    		"pk_periode"	=> date('Y-m-d', $periode),
		    		"pk_status"		=> ($periode == $dateNow) ? '1' : '0'
    			]);

    			$newSaldo = saldo::newPeriode(date('Y-m-d', $periode));

    			// return json_encode($newSaldo);

                if($newSaldo['status'] != 'success'){
                	return $newSaldo;
                }

    			$periode = strtotime("+1 month", $periode);
    			$id++;

    		}

    		// return json_encode($bucket);

    		DB::table('dk_periode_keuangan')->insert($bucket);
    	    DB::commit();

    	    Session::flash('cutoff_status', 'berhasil');
    	    Session::flash('cutoff_tgl', $ret);

	    	return redirect()->back();
    	
    	} catch (Exception $e) {
    	
    	    DB::rollBack();
    	    
    	    return Response::json([
    	        'status' => 'error',
    	        'message' => $e->getMessage()
    	    ]);
    	
    	}
    }
}
