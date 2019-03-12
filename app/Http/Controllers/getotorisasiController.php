<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\otorisasiController as otorisasi;

use DB;

class getotorisasiController extends Controller
{
    public function get(){
      $count = DB::table('m_item_auth')
                ->count();

      $data[] = array('menu' => 'Master Produk', 'isi' => 'Membutuhkan otorisasi sebanyak ', 'count' => $count , 'link' => '#');

      $countparrent = $count;

      return response()->json([
        'count' => $countparrent,
        'data' => $data
      ]);
    }
}
