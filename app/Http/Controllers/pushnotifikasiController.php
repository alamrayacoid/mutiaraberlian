<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher;
use DB;
use Carbon\Carbon;

class pushnotifikasiController extends Controller
{
  static function notifikasiup($name)
  {
      $data = array(
          'name' => $name,
          // 'user' => $userName
          // 'qty' => $qty,
          // 'link' => $link
      );

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
      // dd($data);
      $pusher->trigger('channel-otorisasi', 'event-notif', $data);
  }
}
