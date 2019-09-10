<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\d_adjusmentauth;
use App\d_notification;
use App\d_opnameauth;
use App\d_priceclassauthdt;
use App\d_productionorderauth;
use App\d_promotion;
use App\d_salespriceauth;
use App\d_sdmsubmission;
use App\m_item_auth;
use Carbon\Carbon;

class getotorisasiController extends Controller
{
    public $countparrent = 0;
    public $data = [];

    // create or update notifikasi
    public function get(Request $request) {
        $this->countparrent = 0;
        $this->data = [];

        // $this->cek($request->name, $request->qty, $request->link);
        $name = $request->name;
        switch ($name) {
            case 'Otorisasi Revisi Data' :
                $this->countRevisiData($name);
                break;

            case 'Otorisasi SDM' :
                $this->countSDM($name);
                break;

            case 'Otorisasi Promosi' :
                $this->countPromotion($name);
                break;

            case 'Otorisasi Perubahan Harga Jual' :
                $this->countHargaJual($name);
                break;

            case 'Otorisasi Opname' :
                $this->countOpname($name);
                break;

            case 'Otorisasi Adjustment' :
                $this->countAdjustment($name);
                break;

            default:
                // code...
                break;
        }

        // filter data to set teks in 'notifikasi'
        $this->set();

        return response()->json([
            'count' => $this->countparrent,
            'data' => $this->data
        ]);
    }

    // filter data to set teks in 'notifikasi'
    public function set() {
        $term = 'Otorisasi';
        $get = d_notification::where('n_name', 'LIKE', '%'. $term .'%')
            ->get();

        foreach ($get as $key => $val) {
            $this->countparrent += $val->n_qty;
            $this->pushdata(
                $val->n_name, // name
                $val->n_qty, // qty
                $val->n_date, // date
                $val->n_link // link / url
            );
        }
    }
    // format teks for 'notifikasi'
    public function pushdata($name, $count, $date, $link){
        Carbon::setlocale('id');
        if ($count != 0) {
            $this->data[] = array(
                'name' => $name,
                'isi' => 'Membutuhkan otorisasi sebanyak ',
                'count' => $count,
                'date' => Carbon::parse($date)->diffForHumans(),
                'link' => $link
            );
        }
    }

    // count all otorisation in Revisi-Data
    public function countRevisiData($name)
    {
        $link = route('revisi');
        // count all 'item' that need otorisation
        $countProduk = m_item_auth::count();
        // count all 'production-order' that need otorisation
        $countOrder = d_productionorderauth::count();
        // set qty
        $qty = (int)$countProduk + (int)$countOrder;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }
    // count all otorisation in SDM
    public function countSDM($name)
    {
        $link = route('sdm');
        // count all 'item' that need otorisation
        $countSDM = d_sdmsubmission::where('ss_isactive', 'Y')
            ->where('ss_isapproved', ['P', 'N'])
            ->count();
        // set qty
        $qty = (int)$countSDM;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }
    // count all otorisation in Promotion
    public function countPromotion($name)
    {
        $link = route('promotion');
        // count all 'item' that need otorisation
        $countPromotion = d_promotion::where('p_isapproved', 'P')
            ->count();
        // set qty
        $qty = (int)$countPromotion;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }
    // count all otorisation in Promotion
    public function countHargaJual($name)
    {
        $link = route('perubahanhargajual');
        // count all 'item' that need otorisation
        $countHargaJual = d_priceclassauthdt::count();
        $countHargaJualAgen = d_salespriceauth::count();
        // set qty
        $qty = (int)$countHargaJual + (int)$countHargaJualAgen;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }
    // count all otorisation in Promotion
    public function countOpname($name)
    {
        $link = route('opname_otorisasi');
        // count all 'item' that need otorisation
        $countOpname = d_opnameauth::count();
        // set qty
        $qty = (int)$countOpname;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }
    // count all otorisation in Promotion
    public function countAdjustment($name)
    {
        $link = route('adjustment');
        // count all 'item' that need otorisation
        $countAdjustment = d_adjusmentauth::count();
        // set qty
        $qty = (int)$countAdjustment;
        // update 'notification'
        $this->updateNotif($name, $qty, $link);
    }

    // update 'notification'
    public function updateNotif($name, $qty, $link)
    {
        // get notification with name 'Revisi Data'
        $notif = d_notification::where('n_name', $name)
            ->first();
        // create notif if null
        if (is_null($notif)) {
            $id = d_notification::max('n_id') + 1;
            $insert = DB::table('d_notification')->insert([
                    'n_id' => $id,
                    'n_name' => $name,
                    'n_qty' => $qty,
                    'n_date' => Carbon::now('Asia/Jakarta'),
                    'n_link' => $link
                ]);
        }
        else {
            $update = DB::table('d_notification')
                ->where('n_id', $notif->n_id)
                ->update([
                    'n_qty' => $qty,
                    'n_date' => Carbon::now('Asia/Jakarta'),
                    'n_link' => $link
                ]);
        }
    }

    // get list 'notifikasi'
    public function gettmpoto(){
        $cek = DB::table('d_notification')
            ->where('n_name', 'LIKE', '%Otorisasi%')
            ->get();

        return response()->json($cek);
    }
}
