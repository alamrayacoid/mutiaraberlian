<?php

namespace App\Http\Controllers;

use App\d_username;
use App\m_employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $login = d_username::where(DB::raw('BINARY u_username'), $request->username)
            ->join('m_company', 'c_id', '=', 'u_company')
            ->first();


            // get user status is-active
            if ($login->u_user == 'E') {
                $isActive = m_employee::where('e_id', $login->u_code)->select('e_isactive')->first();
                $isActive = $isActive->e_isactive;
            }
            elseif ($login->u_user == 'A') {
                $isActive = $login->c_isactive;
            }

            if ($isActive == 'N') {
                return redirect()->route('login')->with([
                    'status' => 'gagal',
                    'message' => 'user non-aktif, tidak bisa melakukan login. Hubungi Admin !'
                ]);
            }
            // else {
            //     return redirect()->route('login')->with([
            //         'status' => 'berhasil',
            //         'message' => 'user aktif, bisa melakukan login. '
            //     ]);
            // }

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
        catch (\Exception $e) {
            return redirect()->route('login')->with([
                'gagal' => 'gagal'
            ]);
        }

    }

    public function logout()
    {

        // return json_encode('aa');

        DB::table('d_username')
            ->where('u_username', '=', Auth::user()->u_username)
            ->update([
                'u_lastlogout' => Carbon::now('Asia/Jakarta')
            ]);

        // return 'ss';

        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}
