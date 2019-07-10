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

class PresensiController extends Controller
{
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
