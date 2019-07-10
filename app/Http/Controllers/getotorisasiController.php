<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\otorisasiController as otorisasi;

use DB;

use Carbon\Carbon;

class getotorisasiController extends Controller
{
    public $countparrent = 0;
    public $data = [];

    public function get(Request $request){

      $this->cek($request->name, $request->qty, $request->link);

      return response()->json([
        'count' => $this->countparrent,
        'data' => $this->data
      ]);
    }

    public function pushdata($name, $count, $date, $link){
      Carbon::setlocale('id');
      if ($count != 0) {
        $this->countparrent += $count;

        $this->data[] = array('name' => $name, 'isi' => 'Membutuhkan otorisasi sebanyak ', 'count' => $count, 'date' => Carbon::parse($date)->diffForHumans(), 'link' => $link);
      }
    }

    public function set(){
      $get = DB::table('d_notification')->where('n_name', 'LIKE', '%otorisasi%')->get();

      for ($i=0; $i < count($get); $i++) {
        // $count = DB::table($get[$i]->n_name)
        //               ->select('n_qty')
        //               ->first();
        //
        // dd($count);

        $this->pushdata($get[$i]->n_name, $get[$i]->n_qty, $get[$i]->n_date, $get[$i]->n_link);
      }
    }

    public function cek($name, $qty, $link){
      $cek = DB::table('d_notification')->where('n_name', 'LIKE', '%otorisasi%')->where('n_name', $name)->first();
      // dd(count($date));
      if (count($cek) != 0) {
        DB::table('d_notification')
              ->where('n_name', 'LIKE', '%otorisasi%')
              ->where('n_name', $name)
              ->update([
                'n_qty' => (int)$cek->n_qty + (int)$qty,
                'n_date' => Carbon::now('Asia/Jakarta'),
                'n_link' => $link
              ]);
      } else {
        $id = DB::table('d_notification')->max('n_id')+1;
        DB::table('d_notification')
              ->insert([
                'n_id' => $id,
                'n_name' => $name,
                'n_qty' => (int)$cek->n_qty + (int)$qty,
                'n_date' => Carbon::now('Asia/Jakarta'),
                'n_link' => $link
              ]);
      }

      $this->set();
    }

    public function gettmpoto(){
      $cek = DB::table('d_notification')->where('n_name', 'LIKE', '%otorisasi%')->get();

      return response()->json($cek);
    }
}
