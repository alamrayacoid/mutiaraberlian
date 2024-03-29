<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher;
use DB;
use Carbon\Carbon;

class pushotorisasiController extends Controller
{
    static function otorisasiup($name)
    {
        $data = array(
            'name' => $name
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

        $x = $pusher->trigger('channel-otorisasi', 'event-otorisasi', $data);
    }
}
