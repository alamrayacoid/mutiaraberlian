<?php

namespace App\Http\Controllers\SDM\SOP;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_regulationaction;
use App\d_regulationactiondt;
use App\m_regulations;
use App\m_employee;
use Crypt;
use DB;

class SOPController extends Controller
{
    // get list master-sop
    public function getListMaster(Request $request)
    {
        $datas = m_regulations::orderBy('r_name', 'asc')->get();
        return $datas;
    }
    // store new master-sop
    public function storeMaster(Request $request)
    {
        DB::beginTransaction();
        try {
            $records = [
                'r_name' => $request->name,
                'r_level' => $request->level
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
            $delete = m_regulations::where('r_id', $id)->delete();

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
    // store new record-sop
    public function storeRecord(Request $request)
    {
        dd($request->all());
    }
    public function deleteRecord(Request $request)
    {
        dd($request->all());
    }
}
