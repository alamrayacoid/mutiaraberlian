<?php
/**
 * Created by PhpStorm.
 * User: Ilham Rolis
 * Date: 30/08/2018
 * Time: 14:56
 */

namespace App\Http\Controllers;
use Auth;
use DB;

class AksesUser
{
    public static function checkAkses($a_id, $aksi)
    {
        $m_id = Auth::user()->u_id;
        $cek = null;
        if ($aksi == 'read'){
            $cek = DB::table('d_useraccess')
                ->where('ua_username', '=', $m_id)
                ->where('ua_access', '=', $a_id)
                ->where('ua_read', '=', 'Y')
                ->get();
        } elseif ($aksi == 'create'){
            $cek = DB::table('d_useraccess')
                ->where('ua_username', '=', $m_id)
                ->where('ua_access', '=', $a_id)
                ->where('ua_create', '=', 'Y')
                ->get();
        } elseif ($aksi == 'update'){
            $cek = DB::table('d_useraccess')
                ->where('ua_username', '=', $m_id)
                ->where('ua_access', '=', $a_id)
                ->where('ua_update', '=', 'Y')
                ->get();
        } elseif ($aksi == 'delete'){
            $cek = DB::table('d_useraccess')
                ->where('ua_username', '=', $m_id)
                ->where('ua_access', '=', $a_id)
                ->where('ua_delete', '=', 'Y')
                ->get();
        }

        if (count($cek) > 0){
            return true;
        } else {
            return false;
        }
    }

    public static function aksesSidebar()
    {
        $m_id = Auth::user()->u_id;
        $cek = DB::table('d_useraccess')
                  ->join('m_access', 'a_id', '=', 'ua_access')
                  ->select('ua_username', 'ua_read', 'a_name', 'a_order')
                  ->where('ua_username', '=', $m_id)
                  ->get();

        return $cek;
    }
}
