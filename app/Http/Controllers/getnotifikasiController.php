<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\d_notification;
use DB;
use Carbon\Carbon;

class getnotifikasiController extends Controller
{
    public $countparrent = 0;
    public $data = [];

    // create or update notifikasi
    public function get(Request $request)
    {
        // insert to table 'd_notification'
        // if notif payment-po
            // insert new notif payment-po
        // else
            // run switch-case below
        $name = $request->name;
        $po_nota = $request->po_nota;

        if ($name == 'Notifikasi_payment') {
          $link = route('masterdatautama.masterpembayaran');
          $message = 'Batas maksimal termin untuk no nota  !'.$po_nota;
          $this->insertNotif($name, $link, $message);
        }else{

        switch ($name) {
            case 'Notifikasi Perubahan Order Produksi':
                $link = route('order.index');
                $message = 'Order Produksi berhasil diperbaharui !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Pembuatan Return Produksi':
                $link = route('return.index');
                $message = 'Return Produksi berhasil dibuat !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Pembayaran Produksi':
                $link = route('pembayaran.index');
                $message = 'Pembayaran Produksi berhasil dilakukan !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Pembuatan Distribusi':
                $link = route('distribusibarang.index');
                $message = 'Distribusi Barang berhasil dibuat !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Perubahan Distribusi':
                $link = route('distribusibarang.index');
                $message = 'Distribusi Barang berhasil diperbaharui !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Terima Order Distribusi':
                $link = route('distribusibarang.index');
                $message = 'Order Distribusi dari Agen/Cabang berhasil di-approve !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Pembuatan Perencanaan Budgeting':
                $link = route('budgeting.index');
                $message = 'Perencanaan Budgeting berhasil dibuat !';
                $this->insertNotif($name, $link, $message);
                break;

            case 'Notifikasi Pembuatan Pengaturan Pengguna':
                $link = route('pengaturanpengguna.index');
                $message = 'Pengaturan Pengguna baru berhasil dibuat !';
                $this->insertNotif($name, $link, $message);
                break;

            default:
                break;
        }
      }

        // filter data to set teks in 'notifikasi'
        $this->set();

        return response()->json([
            'count' => $this->countparrent,
            'data' => $this->data
        ]);
    }

    // filter data to set teks in 'notifikasi'
    public function set(){
        $term = 'Notifikasi';
        $get = d_notification::where('n_name', 'LIKE', '%'. $term .'%')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        foreach ($get as $key => $val) {
            $this->countparrent += $val->n_qty;
            $this->pushdata(
                $val->n_name, // name
                $val->n_qty, // qty
                $val->created_at, // date
                $val->n_link, // link / url
                $val->n_message // link / url
            );
        }
    }
    // check date payment
    public function checkDatePayment()
    {
      $date = DB::table('d_productionorderpayment')
      ->select('po_nota')
      ->join('d_productionorder','pop_productionorder','=','po_id')
      ->whereBetween('pop_datetop',array(Carbon::today(),Carbon::tomorrow()))
      ->groupBy('po_nota')
      ->get();
      return $date;
    }
    // format teks for 'notifikasi'
    public function pushdata($name, $count, $date, $link, $message)
    {
        Carbon::setlocale('id');
        if ($count != 0) {
            $this->data[] = array(
                'name' => $name,
                'isi' => $message,
                'date' => Carbon::parse($date)->diffForHumans(),
                'link' => $link
            );
        }
    }

    public function insertNotif($name, $link, $message)
    {
        $notif = d_notification::where('n_name', 'LIKE', '%Notifikasi%')
            ->get();

        // limit notif to 100, delete old notification
        if (count($notif) > 200) {
            $oldNotif = d_notification::where('n_name', 'LIKE', '%Notifikasi%')
                ->latest()
                ->take(count($notif))
                ->skip(200)
                ->get()
                ->each(function($row) {
                    $row->delete();
                });
        }

        // create notif if null
        $id = d_notification::max('n_id') + 1;
        $insert = DB::table('d_notification')->insert([
            'n_id' => $id,
            'n_name' => $name,
            'n_qty' => 1,
            'n_date' => Carbon::now('Asia/Jakarta'),
            'n_link' => $link,
            'n_message' => $message
        ]);
    }

    // get list 'notifikasi'
    public function gettmpnotif(){
        $notif = d_notification::where('n_name', 'LIKE', '%Notifikasi%')
        ->where('n_date',Carbon::today())
        ->get();

        return response()->json($notif);
    }


    // clear notifikasi
    public function clearNotif()
    {
        DB::beginTransaction();
        try {
            $term = 'Notifikasi';
            $listNotif = d_notification::where('n_name', 'LIKE', $term .'%')
            ->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }


    }
}
