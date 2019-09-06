<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;

class getnotifikasiController extends Controller
{
    public $countparrent = 0;
    public $data = [];

    // create or update notifikasi
    public function get(Request $request){

        $this->cek($request->name, $request->qty, $request->link);

        return response()->json([
            'count' => $this->countparrent,
            'data' => $this->data
        ]);
    }

    // format teks for 'notifikasi'
    public function pushdata($name, $count, $date, $link){
        Carbon::setlocale('id');
        if ($count != 0) {
            $this->countparrent += $count;

            $this->data[] = array('name' => $name, 'isi' => 'Membutuhkan notifikasi sebanyak ', 'count' => $count, 'date' => Carbon::parse($date)->diffForHumans(), 'link' => $link);
        }
    }

    // filter data to set teks in 'notifikasi'
    public function set(){
        $get = DB::table('d_notification')
            ->where('n_name', 'LIKE', '%notifikasi%')
            ->get();

        for ($i=0; $i < count($get); $i++) {
            $this->pushdata($get[$i]->n_name, $get[$i]->n_qty, $get[$i]->n_date, $get[$i]->n_link);
        }
    }


    public function cek($name, $qty, $link){
        $cek = DB::table('d_notification')
            // ->where('n_name', 'LIKE', '%notifikasi%')
            ->where('n_name', $name)
            ->first();

        // dd(count($date));
        if (!is_null($cek)) {
            DB::table('d_notification')
                ->where('n_name', 'LIKE', '%notifikasi%')
                ->where('n_name', $name)
                ->update([
                    'n_qty' => (int)$cek->n_qty + (int)$qty,
                    'n_date' => Carbon::now('Asia/Jakarta'),
                    'n_link' => $link
                ]);
        }
        else {
            $id = DB::table('d_notification')->max('n_id') + 1;
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

    // get list 'notifikasi'
    public function gettmpnotif(){
        $cek = DB::table('d_notification')
            ->where('n_name', 'LIKE', '%notifikasi%')
            ->get();

        return response()->json($cek);
    }
}
