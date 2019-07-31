<?php

namespace App\Http\Controllers\Keuangan\penerimaanpiutang;

use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Response;

class PenerimaanPiutangController extends Controller
{
    public function index()
    {
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');

        return view('keuangan.penerimaan_piutang.index', compact('start', 'end'));
    }

    public function getDataListCabang(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $cabang = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $infoSalescomp = DB::table('d_salescomp as scc')
            ->join('m_company as comp', 'comp.c_id', '=', 'scc.sc_comp')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name', DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as sc_total'), 'sc_id', 'comp.c_name as cabang')
            ->where('sc_paidoffbranch', '=', 'N')
            ->groupBy('sc_id')
            ->where('sc_comp', '!=', 'MB0000001')
            ->where('comp.c_type', '=', 'CABANG');

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('scc.sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSalescomp = $infoSalescomp->where('sc_comp', '=', $cabang);
        }

        $infoSales = DB::table('d_sales')
            ->join('m_company', 's_comp', '=', 'c_id')
            ->join('m_member', 'm_code', '=', 's_member')
            ->select(DB::raw('floor(s_total) as sisa'),
                DB::raw('"-" AS pembayaran'),
                DB::raw('"-" AS status'),
                'c_name', DB::raw('date_format(s_date, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(s_total) as sc_total'), 's_id as sc_id', 'c_name as cabang')
            ->where('s_paidoffbranch', '=', 'N')
            ->groupBy('s_id')
            ->where('s_comp', '!=', 'MB0000001')
            ->where('c_type', '=', 'CABANG');

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '<=', $end);
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSales = $infoSales->where('s_comp', '=', $cabang);
        }

        $info = $infoSalescomp->union($infoSales);

        return DataTables::of($info)
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="bayarnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                return "Rp. " . number_format($info->sisa, '0', ',', '.');
            })
            ->rawColumns(['aksi'])
            ->make();
    }
}
