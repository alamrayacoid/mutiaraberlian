<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher;
use DB;

class pushotorisasiController extends Controller
{
    static function otorisasiup($table, $menu, $url)
    {
        $data = array('table' => $table, 'menu' => $menu, 'url' => $url );

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
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}