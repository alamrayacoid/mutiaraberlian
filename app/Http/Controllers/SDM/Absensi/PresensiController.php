<?php

namespace App\Http\Controllers\SDM\Absensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\d_presence;
use App\m_company;
use App\m_divisi;
use App\m_employee;
use DB;
use DataTables;

class PresensiController extends Controller
{
    // get presence-summary
    public function getPresenceSummary(Request $request)
    {
        $dateFrom = Carbon::parse($request->filterDateFromPr);
        $dateTo = Carbon::parse($request->filterDateToPr);
        $branch = $request->filterByBranch;

        // get result of presence-query
        $datas = d_presence::whereBetween('p_date', [$dateFrom, $dateTo])
        ->whereHas('getEmployee', function ($q) use ($branch) {
            $q->where('e_company', $branch);
        })
        ->groupBy('p_date')
        ->get();

        // count each status
        $listCountH = array();
        $listCountI = array();
        $listCountT = array();
        $listCountC = array();
        foreach ($datas as $key => $val) {
            $presences = d_presence::where('p_date', $val->p_date)
            ->whereHas('getEmployee', function ($q) use ($branch) {
                $q->where('e_company', $branch);
            })
            ->select(
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

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('date', function ($datas) {
                return '<td>' . Carbon::parse($datas->p_date)->format('d M Y') . '</td>';
            })
            ->addColumn('hadir', function ($datas) {
                if (is_null($datas->countH)) {
                    return '<td>0</td>';
                }
                else {
                    return '<td>' . $datas->countH . '</td>';
                }
            })
            ->addColumn('ijin', function ($datas) {
                if (is_null($datas->countI)) {
                    return '<td>0</td>';
                }
                else {
                    return '<td>' . $datas->countI . '</td>';
                }
            })
            ->addColumn('tidakMasuk', function ($datas) {
                if (is_null($datas->countT)) {
                    return '<td>0</td>';
                }
                else {
                    return '<td>' . $datas->countT . '</td>';
                }
            })
            ->addColumn('cuti', function ($datas) {
                if (is_null($datas->countC)) {
                    return '<td>0</td>';
                }
                else {
                    return '<td>' . $datas->countC . '</td>';
                }
            })
            ->addColumn('action', function ($datas) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detail" type="button" onclick="showDetailPresence(' . $datas->p_id . ')" title="Detail Presensi"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning btn-edit" type="button" onclick="editDetailPresence(' . $datas->p_id . ')"  title="Edit Presensi"><i class="fa fa-arrow-right"></i></button>
                </div>';
            })
            ->rawColumns(['date', 'hadir', 'ijin', 'tidakMasuk', 'cuti', 'action'])
            ->make(true);
    }
    // get list branch for select-option
    public function getBranch()
    {
        try {
            $branchs = m_company::where('c_type', 'PUSAT')
            ->orWhere('c_type', 'CABANG')
            ->orderBy('c_name', 'asc')
            ->select('c_id', 'c_name')
            ->get();

            return response()->json($branchs);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // get presence
    public function getDetailPresence(Request $request)
    {
        try {
            $prId = $request->id;

            $press = d_presence::where('p_id', $prId)->first();

            $presences = d_presence::whereDate('p_date', $press->p_date)
            ->with(['getEmployee' => function ($q) {
                $q->with('getDivision')->with('getCompany');
            }])
            ->join('m_employee', 'p_employee', 'e_id')
            ->orderBy('e_name', 'asc')
            ->get();

            return response()->json($presences);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // get presence
    public function getPresence(Request $request)
    {
        try {
            $date = Carbon::parse($request->datePr);
            $branchId = $request->branch;

            $presences = d_presence::whereDate('p_date', $date)
            ->whereHas('getEmployee', function ($q) use ($branchId) {
                $q->where('e_company', $branchId);
            })
            ->with('getEmployee.getDivision')
            ->join('m_employee', 'p_employee', 'e_id')
            ->orderBy('e_name', 'asc')
            ->get();

            return response()->json($presences);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // get employee for autocomplete
    public function getEmployee(Request $request)
    {
        try {
            $branchId = $request->branchId;
            $term = $request->term;
            $listEmpId = $request->listEmpId;
            $listEmpId = array_filter($listEmpId);

            $employees = m_employee::where('e_name', 'like', '%'. $term .'%')
            ->where('e_company', $branchId)
            ->whereNotIn('e_id', $listEmpId)
            // ->orWhere('e_id', 'like', '%'. $term .'%')
            ->with('getDivision')
            ->orderBy('e_name', 'asc')
            ->get();

            if (count($employees) == 0) {
                $results[] = ['id' => null, 'label' => 'Tidak ditemukan karyawan terkait'];
            } else {
                foreach ($employees as $query) {
                    $results[] = [
                        'id' => $query->e_id,
                        'label' => $query->e_name . ' ( ' . strtoupper($query->getDivision->m_name) . ' ) / ' . $query->e_id
                        // 'data' => $query, 'stock' => $query->s_id
                    ];
                }
            }
            return response()->json($results);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // store data
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeesId = $request->employeePrId;
            $arriveTime = $request->arriveTimePr;
            $returnTime = $request->returnTimePr;
            $status = $request->statusPr;
            $notes = $request->notePr;
            $date = Carbon::parse($request->datePr);
            $branchId = $request->branch;

            // delete all related data
            $oldData = d_presence::whereDate('p_date', $date)
            ->whereHas('getEmployee', function ($q) use ($branchId) {
                $q->where('e_company', $branchId);
            })
            ->get();
            if (count($oldData) != 0) {
                $id = $oldData[0]->p_id;
                foreach ($oldData as $key => $od) {
                    $od->delete();
                }
            }
            else {
                $id = d_presence::max('p_id') + 1;
            }
            // dd(count($oldData), $id);
            // re-insert data
            // dd($employeesId);
            foreach ($employeesId as $key => $empId) {
                if (is_null($empId)) {
                    continue;
                }
                $detailId = d_presence::where('p_id', $id)
                ->max('p_detailid') + 1;

                $newPresence = new d_presence;
                $newPresence->p_id = $id;
                $newPresence->p_detailid = $detailId;
                $newPresence->p_date = $date;
                $newPresence->p_employee = $empId;
                $newPresence->p_entry = $arriveTime[$key];
                $newPresence->p_out = $returnTime[$key];
                $newPresence->p_status = $status[$key];
                $newPresence->p_note = $notes[$key];
                $newPresence->save();
            }

            DB::commit();
            return response()->json([
                'status'  => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
}
