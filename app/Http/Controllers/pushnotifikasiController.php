<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher;
use DB;
use Carbon\Carbon;

class pushnotifikasiController extends Controller
{
  static function notifikasiup($name, $qty, $link)
  {
      $data = array(
          'name' => $name,
          'qty' => $qty,
          'link' => $link
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
      $pusher->trigger('my-channel1', 'my-event1', $data);
  }
}
