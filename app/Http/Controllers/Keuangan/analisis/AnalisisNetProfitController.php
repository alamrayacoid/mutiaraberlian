<?php

namespace App\Http\Controllers\Keuangan\analisis;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalisisNetProfitController extends Controller
{
    public function index()
    {
        $cabang = json_encode(DB::table('m_company')
            ->where('c_id', Auth::user()->u_company)
            ->select('c_id as id', 'c_name as text')
            ->get());

        return view('keuangan.analisis.netprofit.index', compact('cabang'));
    }

    public function getData(Request $request){
        dd($request);
    }
}
