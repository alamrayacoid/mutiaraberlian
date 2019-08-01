<?php

namespace App\Http\Controllers;

use App\d_username;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $login = d_username::where(DB::raw('BINARY u_username'), $request->username)
                            ->join('m_company', 'c_id', '=', 'u_company')
                            ->first();

        if ($login && sha1(md5('islamjaya') . $request->password) == $login->u_password){
            // return json_encode($login)
            Auth::login($login);
            Session::put('isPusat', $login->c_type == 'PUSAT');

            DB::table('d_username')
                ->where('u_username', '=', $request->username)
                ->update([
                    'u_lastlogin' => Carbon::now('Asia/Jakarta')
                ]);
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with(['gagal' => 'gagal']);
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
        Session::flush();
        return redirect()->route('login');
    }
}
