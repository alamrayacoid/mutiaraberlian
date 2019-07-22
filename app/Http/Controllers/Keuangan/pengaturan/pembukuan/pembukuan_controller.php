<?php

namespace App\Http\Controllers\Keuangan\pengaturan\pembukuan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\keuangan\dk_akun_cashflow as cashflow;
use App\Model\keuangan\dk_pembukuan as pembukuan;

use DB;
use Auth;

class pembukuan_controller extends Controller
{
    public function index(){
    	return view('keuangan.pengaturan.pembukuan.index');
    }

    public function resource(){
		
    	$cashflow = cashflow::distinct('ac_type')
    					->with(['detail' => function($query){
    						$query->select('ac_type', 'ac_id as id', 'ac_nama as text');
    					}])
    					->select('ac_type as label', 'ac_type')
    					->get();

    	$akun = DB::table('dk_akun')
    				->where('ak_comp', Auth::user()->u_company)
    				->select('ak_id as id', DB::raw("concat(ak_nomor, ' - ', ak_nama) as text"), 'ak_setara_kas as kas')
    				->orderBy('ak_nomor', 'asc')
    				->get();

		return json_encode([
			'akun'		=> $akun,
			'cashflow' 	=> $cashflow,
			'pembukuan'	=> $this->grapData()
		]);  		
    }

    public function store(Request $request){
    	DB::beginTransaction();
    	
    	try {
    	
    		// return json_encode($request->all());

    		foreach ($request->pe_id as $key => $pembukuan) {
    			foreach ($request->akun[$pembukuan] as $key => $detail) {

    				$cashflow = ($request->cashflow[$pembukuan][$key] == 'null') ? null : $request->cashflow[$pembukuan][$key];
    				$acc = ($detail == 'null') ? null : $detail;

    				// return json_encode($cashflow);

    				DB::table('dk_pembukuan_detail')->where('pd_pembukuan', $pembukuan)->where('pd_nomor', ($key+1))->update([
    					'pd_keterangan'	=> $request->pd_keterangan[$pembukuan][$key],
    					'pd_cashflow'	=> $cashflow,
    					'pd_acc'		=> $acc
    				]);
    			}
    		}

    	    DB::commit();
    	
    	    return json_encode([
    	        'status' => 'success',
    	        'text' => 'Data Pembukuan Berhasil Disimpan',
				'pembukuan'	=> $this->grapData()
    	    ]);
    	
    	} catch (Exception $e) {
    	
    	    DB::rollBack();
    	    
    	    return Response::json([
    	        'status' => 'gagal',
    	        'text' => $e->getMessage()
    	    ]);
    	
    	}
    }

    private function grapData(){
    	return $data = pembukuan::where('pe_comp', Auth::user()->u_company)
    				->with('detail')
    				->get();
    }
}
