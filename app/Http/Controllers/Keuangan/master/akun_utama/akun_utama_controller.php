<?php

namespace App\Http\Controllers\Keuangan\master\akun_utama;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\keuangan\dk_hierarki_satu as level_satu;
use App\Helper\keuangan\saldo\saldo_akun as saldo_akun;
use App\d_username as user;

use DB;
use Auth;
use Session;

class akun_utama_controller extends Controller
{

    public function __construct(){
    	$this->middleware('Pusat');
    }

    protected function index(){
    	return view("keuangan.master.akun-utama.index");
    }

    protected function create(){
    	return view("keuangan.master.akun-utama.create");
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

		return json_encode([
    		"hierarki" 		=> $data,
    		"akunDetail"	=> $this->grapDataAkun(),
    		"akun"			=> $this->grapData(),
    	]);
    }

    protected function save(Request $request){
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
    		
    		$id = DB::table('dk_akun_utama')->max('au_id') + 1;
    		$ids = DB::table('dk_akun')->max('ak_id') + 1;

    		DB::table('dk_akun_utama')->insert([
				'au_id'				=> $id,
    			'au_nomor'			=> $nomor,
    			'au_nama'			=> $request->ak_nama,
    			'au_sub_id'			=> $request->ak_nomor,
    			'au_kelompok'		=> $request->ak_kelompok,
    			'au_posisi'			=> $request->ak_posisi,
    			'au_setara_kas'		=> $request->ak_setara_kas,
			]);

			DB::table('dk_akun')->insert([
				'ak_id'				=> $ids,
    			'ak_nomor'			=> $nomor,
    			'ak_tahun'			=> date('Y'),
    			'ak_comp'			=> Auth::user()->u_company,
    			'ak_nama'			=> $request->ak_nama,
    			'ak_sub_id'			=> $request->ak_nomor,
    			'ak_kelompok'		=> $request->ak_kelompok,
    			'ak_posisi'			=> $request->ak_posisi,
    			'ak_opening_date'	=> date('Y-m-d'),
    			'ak_opening'		=> 0,
    			'ak_setara_kas'		=> $request->ak_setara_kas,
    			'ak_akun_utama'		=> $id,
			]);

			saldo_akun::add($ids, 0, Auth::user()->u_company);

			DB::commit();

    		return json_encode([
                'status'    => 'success',
                'text'      => 'Data COA utama baru berhasil disimpan',
                "akunDetail"	=> $this->grapDataAkun(),
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

    protected function update(Request $request){
    	$akun = DB::table('dk_akun_utama')->where('au_id', $request->ak_id);
    	$kelompok = DB::table('dk_hierarki_dua')->where('hd_id', $request->ak_kelompok)->first();

		if(!$dataAkun = $akun->first()){
			return json_encode([
                'status'    => 'error',
                'text'      => 'COA yang dimaksud tidak bisa ditemukan di database. Cobalah untuk memuat ulang halaman untuk mendapatkan update data terbaru.',
            ]);
		}

    	if(!$kelompok){
    		return json_encode([
                'status'    => 'error',
                'text'      => 'Kelompok yang anda pilih tidak ada. Harap muat ulang halaman untuk update data terbaru.',
            ]);
    	}

    	$nomor = $kelompok->hd_nomor.'.'.$request->ak_nomor;
    	$cekNomor = DB::table('dk_akun')->where('ak_nomor', $nomor)->where('ak_akun_utama', $request->ak_id)->first();

    	if($cekNomor && $cekNomor->au_id != $request->ak_id){
    		return json_encode([
                'status'    => 'error',
                'text'      => 'Nomor COA sudah digunakan. Harap memasukkan nomor COA lain.',
            ]);
    	}

    	DB::beginTransaction();

    	try {
    		
    		$akun->update([
    			'au_nomor'			=> $nomor,
    			'au_nama'			=> $request->ak_nama,
    			'au_sub_id'			=> $request->ak_nomor,
    			'au_kelompok'		=> $request->ak_kelompok,
    			'au_posisi'			=> $request->ak_posisi,
    		]);

    		DB::table('dk_akun')->where('ak_akun_utama', $request->ak_id)->update([
    			'ak_nomor'		=> $nomor,
    			'ak_nama'		=> $request->ak_nama,
    			'ak_kelompok'	=> $request->ak_kelompok,
    			'ak_sub_id'		=> $request->ak_nomor,
    			'ak_posisi'		=> $request->ak_posisi,
    		]);

    		DB::commit();

    		return json_encode([
                'status'    	=> 'success',
                'text'      	=> 'Data COA utama Berhasil Diperbarui',
                "akun"			=> $this->grapData(),
                "akunDetail"	=> $this->grapDataAkun(),
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

        return response()->json([
            'akun'  => $this->grapData()
        ]);
    }

    private function grapData(){
    	$data = DB::table('dk_akun_utama')
    				->select('au_id as ak_id', 'au_nama as ak_nama', 'au_posisi as ak_posisi', 'au_sub_id as ak_sub_id', 'au_kelompok as ak_kelompok', 'au_nomor as ak_nomor', 'au_setara_kas as ak_setara_kas')
    				->orderBy('au_nomor', 'asc')
    				->get();

    	return $data;
    }

    private function grapDataAkun(){
    	$data = DB::table('dk_akun')
    				->select('ak_id', 'ak_nama', 'ak_posisi', 'ak_sub_id', 'ak_kelompok', 'ak_nomor', 'ak_setara_kas')
    				->orderBy('ak_nomor', 'asc')
    				->get();

    	return $data;
    }
}
