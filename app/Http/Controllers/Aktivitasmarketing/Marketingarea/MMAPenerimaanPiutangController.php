<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use App\d_salescomp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class MMAPenerimaanPiutangController extends Controller
{
    public function getData(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $agen = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $info = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name', DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as sc_total'), 'sc_id')
            ->where('sc_paidoffbranch', '=', 'N')
            ->groupBy('sc_id')
            ->where('sc_comp', '=', $user->u_company);

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $info = $info->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $info = $info->where('scc.sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->agen) && $request->agen != '' && $request->agen !== null){
            $agen = $request->agen;
            $info = $info->where('sc_member', '=', $agen);
        }

        return DataTables::of($info)
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary" onclick="detailnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="bayarnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                return "Rp. " . number_format($info->sisa, '0', ',', '.');
            })
            ->rawColumns(['aksi'])
            ->make();
    }
}
