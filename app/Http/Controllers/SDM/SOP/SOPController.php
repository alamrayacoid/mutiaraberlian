<?php

namespace App\Http\Controllers\SDM\SOP;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_regulationaction;
use App\d_regulationactiondt;
use App\m_regulations;
use App\m_employee;
use Carbon\Carbon;
use Crypt;
use DB;
use Yajra\DataTables\DataTables;

class SOPController extends Controller
{
    // get list master-sop
    public function getListMaster(Request $request)
    {
        $datas = m_regulations::orderBy('r_isactive', 'asc')
            ->orderBy('r_name', 'asc')
            ->get();
        return $datas;
    }
    // store new master-sop
    public function storeMaster(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = m_regulations::max('r_id') + 1;
            $records = [
                'r_id' => $id,
                'r_name' => $request->name,
                'r_level' => $request->level,
                'r_isactive' => 'Y'
            ];
            $insert = m_regulations::insert($records);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // delete master-sop
    public function deleteMaster(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            // $delete = m_regulations::where('r_id', $id)->delete();
            $data = m_regulations::where('r_id', $id)->first();
            $data->r_isactive = 'N';
            $data->save();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // delete master-sop
    public function reActivateMaster(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            // $delete = m_regulations::where('r_id', $id)->delete();
            $data = m_regulations::where('r_id', $id)->first();
            $data->r_isactive = 'Y';
            $data->save();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // get list employee
    public function getListEmployee()
    {
        $emp = m_employee::where('e_isactive', 'Y')
            ->orderBy('e_name')
            ->get();

        $listEmp = [];
        foreach ($emp as $key => $value) {
            $em = array(
                'id' => $value->e_id,
                'text' => $value->e_name
            );
            array_push($listEmp, $em);
        }
        return $listEmp;
    }
    // get list master-sop for create record
    public function getListMasterForRecord()
    {
        $datas = m_regulations::orderBy('r_name', 'asc')
            ->where('r_isactive', 'Y')
            ->get();
        $listMasterSOP = [];
        foreach ($datas as $key => $value) {
            $x = [
                'id' => $value->r_id,
                'text' => $value->r_name
            ];
            array_push($listMasterSOP, $x);
        }
        return $listMasterSOP;
    }
    // store new record-sop
    public function storeRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $dateX = Carbon::parse($request->fil_sopr_date);
            $empId = $request->fil_sopr_emp;
            $tresPass = $request->fil_sopr_trespass;
            $reaction = $request->fil_sopr_react;
            $note = $request->fil_sopr_note;

            // check is already exist
            $check = d_regulationaction::where('ra_date', $dateX)
                ->where('ra_employee', $empId)
                ->first();
            // if not exist
            if (is_null($check)) {
                $regActId = d_regulationaction::max('ra_id') + 1;
                $regAct = [
                    'ra_id' => $regActId,
                    'ra_date' => $dateX,
                    'ra_employee' => $empId
                ];
                $insertRegAct = d_regulationaction::insert($regAct);
            }
            else {
                $regActId = $check->ra_id;
                // trigger updated_at
                $check->touch();
            }

            // check detail is already exist
            $checkDetail = d_regulationactiondt::where('rad_regulationaction', $regActId)
                ->where('rad_regulation', $tresPass)
                ->first();

            // if not exist
            if (is_null($checkDetail)) {
                $raDetailId = d_regulationactiondt::where('rad_regulationaction', $regActId)
                    ->max('rad_detailid') + 1;
                $regActDt = [
                    'rad_regulationaction' => $regActId,
                    'rad_detailid' => $raDetailId,
                    'rad_regulation' => $tresPass,
                    'rad_action' => $reaction,
                    'rad_status' => 'Y',
                    'rad_note' => $note
                ];
                $insertRegActDt = d_regulationactiondt::insert($regActDt);
            }
            else {
                // trigger update_at
                $checkDetail->touch();
                ($checkDetail->rad_status == 'Y') ? $status = 'Berjalan' : $status = 'Selesai';
                return response()->json([
                    'status' => 'exist',
                    'action' => $checkDetail->rad_action,
                    'note' => $checkDetail->rad_note,
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // get list SOP
    public function getListSOP(Request $request)
    {
        $dateFrom = Carbon::parse($request->dateFrom);
        $dateTo = Carbon::parse($request->dateTo);
        // $emp = $request->employee;
        // $regulation = $request->regulation;

        $datas = d_regulationactiondt::select('rad_regulationaction', 'rad_detailid', 'rad_regulation')
            ->whereHas('getRegAct', function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('ra_date', [$dateFrom, $dateTo]);
            })
            ->with(['getRegAct' => function ($q) {
                $q->select('ra_id', 'ra_date', 'ra_employee')
                    ->with('getEmployee');
            }])
            ->with(['getRegulation' => function ($q) {
                $q->select('r_id', 'r_name');
            }])
            ->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('employee', function ($datas) {
                return $datas->getRegAct->getEmployee->e_name;
            })
            ->addColumn('date', function ($datas) {
                $dateX = Carbon::parse($datas->getRegAct->ra_date)->format('d M Y');
                return $dateX;
            })
            ->editColumn('regulation', function ($datas) {
                return $datas->getRegulation->r_name;
            })
            ->addColumn('action', function ($datas) {
                $detail = '<button class="btn btn-sm btn-info" type="button" title="Detail catatan pelanggaran" onclick="detailRecord(\'' . Crypt::encrypt($datas->rad_regulationaction) . '\', \'' . Crypt::encrypt($datas->rad_detailid) . '\')"><i class="fa fa-folder"></i></button>';
                $edit = '<button class="btn btn-sm btn-warning" type="button" title="Edit catatan pelanggaran" onclick="editRecord(\'' . Crypt::encrypt($datas->rad_regulationaction) . '\', \'' . Crypt::encrypt($datas->rad_detailid) . '\')"><i class="fa fa-pencil"></i></button>';
                $delete = '<button class="btn btn-sm btn-danger" type="button" title="Hapus catatan pelanggaran" onclick="deleteRecord(\'' . Crypt::encrypt($datas->rad_regulationaction) . '\', \'' . Crypt::encrypt($datas->rad_detailid) . '\')"><i class="fa fa-trash"></i></button>';
                $row = '<div class="btn-group btn-group-sm">'. $detail . $edit . $delete .'</div>';
                return $row;
            })
            ->rawColumns(['employee', 'date', 'action'])
            ->make(true);
    }
    // get detail-sop
    public function getDetailSOP(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $detailId = Crypt::decrypt($request->detailid);
        }
        catch (DecryptException $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try {
            $detail = d_regulationactiondt::where('rad_regulationaction', $id)
                ->where('rad_detailid', $detailId)
                ->with(['getRegAct' => function ($q) {
                    $q->select('ra_id', 'ra_date', 'ra_employee')
                        ->with(['getEmployee' => function ($q) {
                            $q->select('e_id', 'e_name');
                        }]);
                }])
                ->with(['getRegulation' => function ($que) {
                    $que->select('r_id', 'r_name');
                }])
                ->first();
            // get date formated
            $formatedDate = Carbon::parse($detail->getRegAct->ra_date);
            $detail->dateD = $formatedDate->format('d');
            $detail->dateM = $formatedDate->format('m');
            $detail->dateY = $formatedDate->format('Y');

            DB::commit();
            return response()->json([
                'status' => 'berhasil',
                'data' => $detail
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // delete sop
    public function deleteSOP(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $detailId = Crypt::decrypt($request->detailid);
        }
        catch (DecryptException $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try {
            $detail = d_regulationactiondt::where('rad_regulationaction', $id)
                ->where('rad_detailid', $detailId)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // update sop
    public function updateSOP(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->fil_sopr_id);
            $detailId = Crypt::decrypt($request->fil_sopr_detailid);
        }
        catch (DecryptException $e) {
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try {
            $dateX = Carbon::parse($request->fil_sopr_date);
            $reaction = $request->fil_sopr_react;
            $note = $request->fil_sopr_note;

            // update_at
            $check = d_regulationaction::where('ra_id', $id)
                ->first();
            $check->touch();

            // check detail is already exist
            $checkDetail = d_regulationactiondt::where('rad_regulationaction', $id)
                ->where('rad_detailid', $detailId)
                ->first();

            $checkDetail->rad_note = $note;
            $checkDetail->rad_action = $reaction;
            $checkDetail->save();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}
