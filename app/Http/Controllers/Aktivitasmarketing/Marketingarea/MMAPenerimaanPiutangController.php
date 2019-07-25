<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class MMAPenerimaanPiutangController extends Controller
{
    public function getData(Request $request)
    {
        $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
        $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
        $status = $request->status;
        $agen = $request->agen;
        $user = Auth::user();

        $data = DB::table('d_salescomp scc')
            ->select('')
            ->get();
        dd($data);
    }
}
