<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\otorisasiController as otorisasi;

use DB;

class getotorisasiController extends Controller
{
    public $countparrent = 0;
    public $data = [];

    public function get(Request $request){

      $this->cek($request->table, $request->menu, $request->url);

      return response()->json([
        'count' => $this->countparrent,
        'data' => $this->data
      ]);
    }

    public function pushdata($menu, $count, $url){
      if ($count != 0) {
        $this->countparrent += $count;

        $this->data[] = array('menu' => $menu, 'isi' => 'Membutuhkan otorisasi sebanyak ', 'count' => $count , 'link' => $url);
      }
    }

    public function set(){
      $get = DB::table('tmpotorisasi')->get();

      for ($i=0; $i < count($get); $i++) {
        $count = DB::table($get[$i]->table)
                      ->count();

        $this->pushdata($get[$i]->menu, $count, $get[$i]->url);
      }
    }

    public function cek($string, $string1, $string2){
      $cek = DB::table('tmpotorisasi')->where('table', $string)->count();

      if ($cek == 0) {
        DB::table('tmpotorisasi')
              ->insert([
                'table' => $string,
                'menu' => $string1,
                'url' => $string2
              ]);
      }

      $this->set();
    }

    public function gettmpoto(){
      $cek = DB::table('tmpotorisasi')->get();

      return response()->json($cek);
    }
}
