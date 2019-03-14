<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class OtorisasiController extends Controller
{
    public function otorisasi(){
        return view('notifikasiotorisasi.otorisasi.index');
    }
    public function perubahanhargajual(){
        return view('notifikasiotorisasi.otorisasi.perubahanhargajual.index');
    }
    public function pengeluaranlebih(){
        return view('notifikasiotorisasi.otorisasi.pengeluaranlebih.index');
    }
    public function opname_otorisasi(){
        return view('notifikasiotorisasi.otorisasi.opname.index');
    }
    public function adjustment(){
        return view('notifikasiotorisasi.otorisasi.adjustment.index');
    }
    public function revisi(){
        return view('notifikasiotorisasi.otorisasi.revisi.index');
use Pusher;
use DB;
class pushotorisasiController extends Controller
{
    static function otorisasiup(){
      $options = array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true
      );
      $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
      );
      $pusher->trigger('my-channel', 'my-event', true);
    }
}
