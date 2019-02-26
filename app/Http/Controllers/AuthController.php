<?php

namespace App\Http\Controllers;

use App\d_username;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $login = d_username::where(DB::raw('BINARY u_username'), $request->username)->first();
        if ($login && sha1(md5('islamjaya') . $request->password) == $login->u_password){
            Auth::login($login);
            DB::table('d_username')
                ->where('u_username', '=', $request->username)
                ->update([
                    'u_lastlogin' => Carbon::now('Asia/Jakarta')
                ]);
            return redirect()->route('home');
        } else {
            Session::flash('gagal', 'Kombinasi Username dan Password Tidak Bisa Kami Temukan Di Dalam Database. Silahkan Coba Lagi !');
            return redirect()->route('login')->withInput();
        }
    }

    public function logout()
    {
        DB::table('d_username')
            ->where('u_username', '=', Auth::user()->u_username)
            ->update([
                'u_lastlogout' => Carbon::now('Asia/Jakarta')
            ]);
        Auth::logout();
        return redirect()->route('login');
    }
}
