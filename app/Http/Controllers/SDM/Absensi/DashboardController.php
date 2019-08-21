<?php

namespace App\Http\Controllers\SDM\Absensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_presence;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    // get presence data for charts
    public function getPresence(Request $request)
    {
        // $dateFrom = Carbon::parse($request->filterDateFromPr);
        // $dateTo = Carbon::parse($request->filterDateToPr);
        // $branch = $request->filterByBranch;
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $datas = d_presence::whereMonth('p_date', $monthNow)->get();
        // dd($monthNow, $datas);
        switch ($request->filter) {
            case 'HR':
                // get result of presence-query
                $datas = d_presence::whereMonth('p_date', [$dateFrom, $dateTo]);
                // // filter by branchId
                // if (!is_null($branch)) {
                //     $datas = $datas->whereHas('getEmployee', function ($q) use ($branch) {
                //         $q->where('e_company', $branch);
                //     });
                // }
                // get data
                $datas = $datas->groupBy('p_date')
                ->get();

                // count each status
                $listCountH = array();
                $listCountI = array();
                $listCountT = array();
                $listCountC = array();
                foreach ($datas as $key => $val) {
                    $presences = d_presence::where('p_date', $val->p_date);
                    // filter presence by branch
                    if (!is_null($branch)) {
                        $presences = $presences->whereHas('getEmployee', function ($q) use ($branch) {
                            $q->where('e_company', $branch);
                        });
                    }
                    // get presence
                    $presences = $presences->select(
                        'p_status',
                        DB::RAW('COUNT(*) as count')
                    )
                    ->groupBy('p_status')
                    ->get();

                    // insert count to each array
                    foreach ($presences as $idx => $prs) {
                        if ($prs->p_status == 'H') {
                            $val->countH = $prs->count;
                        }
                        elseif ($prs->p_status == 'I') {
                            $val->countI = $prs->count;
                        }
                        elseif ($prs->p_status == 'T') {
                            $val->countT = $prs->count;
                        }
                        elseif ($prs->p_status == 'C') {
                            $val->countC = $prs->count;
                        }
                    }
                    // dd($val->countH, $val->countC, $val);
                }
                break;

            default:
                // code...
                break;
        }

        $charts = $charts;
        return $charts;
    }
}
