<?php

namespace App\Http\Controllers\Keuangan\pengaturan\hierarki_akun;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Auth;
use Session;

class hierarki_akun_controller extends Controller
{
	public function __construct(){
		// $this->middleware('Pusat');
	}

    protected function index(){
    	return view("keuangan.pengaturan.hierarki-akun.index");
    }

    protected function resource(){
    	return json_encode([
    		'level_1'	=> $this->grapData('level_1'),
            'subclass'  => $this->grapData('subclass'),
            'level_2'   => $this->grapData('level_2'),
    	]);
    }

    protected function save_level_1(Request $request){
        // return json_encode($request->all());

        DB::beginTransaction();

        try {
            
            foreach($request->hs_id as $key => $id){
                DB::table('dk_hierarki_satu')->where('hs_id', $id)->update([
                    'hs_nama'   => $request->hs_nama[$key]
                ]);
            }

            DB::commit();

            return json_encode([
                'status'    => 'success',
                'text'      => 'Data hierarki level 1 berhasil diperbarui',
                "level_1"   => $this->grapData('level_1'),
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
        }
    }

    protected function save_subclass(Request $request){
        // return json_encode($request->all());

        DB::beginTransaction();

        try {

            DB::table('dk_hierarki_subclass')
                ->whereNotIn('hs_id', $request->hs_id)
                ->where('hs_level_1', $request->level_1)->delete();

            $ids = DB::table('dk_hierarki_subclass')->max('hs_id') + 1;

            foreach ($request->hs_id as $key => $id) {
                if($id == 'addition'){
                    if(!is_null($request->hs_nama[$key])){
                        DB::table('dk_hierarki_subclass')->insert([
                            'hs_id'         => $id,
                            'hs_nama'       => $request->hs_nama[$key],
                            'hs_level_1'    => $request->level_1
                        ]);

                        $id++;
                    }
                }else{
                    DB::table('dk_hierarki_subclass')->where('hs_id', $id)->update([
                        'hs_nama'   => $request->hs_nama[$key]
                    ]);
                }
            }

            DB::commit();
            return json_encode([
                'status'    => 'success',
                'text'      => 'Data hierarki subclass berhasil diperbarui',
                "subclass"  => $this->grapData('subclass'),
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
        }
    }

    protected function save_level_2(Request $request){
        // return json_encode($request->all());

        DB::beginTransaction();

        try {

            DB::table('dk_hierarki_dua')
                ->whereNotIn('hd_id', $request->hd_id)
                ->where('hd_level_1', $request->level_1)->delete();
            
            $id = DB::table('dk_hierarki_dua')->max('hd_id') + 1;

            foreach ($request->hd_id as $key => $id) {
                if($id == 'addition'){
                    if(!is_null($request->hd_nama[$key])){
                        // return 'kk';
                        DB::table('dk_hierarki_dua')->insert([
                            'hd_id'            => $id,
                            'hd_nama'          => $request->hd_nama[$key],
                            'hd_level_1'       => $request->level_1,
                            'hd_subclass'      => $request->hd_subclass[$key]
                        ]);

                        $id++;
                    }
                }else{
                    DB::table('dk_hierarki_dua')->where('hd_id', $id)->update([
                        'hd_nama'       => $request->hd_nama[$key],
                        'hd_subclass'   => $request->hd_subclass[$key]
                    ]);
                }
            }

            DB::commit();
            return json_encode([
                'status'    => 'success',
                'text'      => 'Data hierarki level 2 berhasil diperbarui',
                "level_2"   => $this->grapData('level_2'),
            ]);

        } catch (Exception $e) {
            DB::rollback();

            return json_encode([
                'status'  => 'error',
                'text'    => 'System Mengalami Masalah. '.$e
            ]);
        }
    }

    private function grapData(String $state = ''){
    	$data = [];

    	if($state == "level_1"){
    		$data = DB::table('dk_hierarki_satu')->select('hs_id', 'hs_nama', 'hs_id as id', 'hs_nama as text')->get();
    	}else if($state == 'subclass'){
            $data = DB::table('dk_hierarki_subclass')->select('hs_id', 'hs_nama', 'hs_id as id', 'hs_nama as text', 'hs_level_1', 'hs_status')->get();
        }else if($state == 'level_2'){
            $data = DB::table('dk_hierarki_dua')->select('hd_id', 'hd_nama', 'hd_id as id', 'hd_nama as text', 'hd_level_1', 'hd_status', 'hd_subclass')->get();
        }

    	return $data;
    }
}
