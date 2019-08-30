<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\AksesUser;

class BenefitsController extends Controller
{
    public function getDataMasterReward(){
        $data = DB::table('m_benefits')
            ->where('b_type', '=', 'R')
            ->get();

        return response()->json($data);
    }

    public function getDataMasterPunishment(){
        $data = DB::table('m_benefits')
            ->where('b_type', '=', 'P')
            ->get();

        return response()->json($data);
    }

    public function saveMasterBenefits(Request $request){
        if (!AksesUser::checkAkses(28, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $nama = $request->nama;
        $type = $request->type;

        DB::beginTransaction();
        try {

            $id = DB::table('m_benefits')
                ->max('b_id');

            ++$id;

            DB::table('m_benefits')
                ->insert([
                    'b_id' => $id,
                    'b_type' => $type,
                    'b_name' => $nama
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
    }

    public function getDataRewardPunishment(Request $request){
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        $data = DB::table('m_employee')
            ->leftJoin('d_employeebenefits as emben', function($q) use ($periode){
                $q->on('eb_employee', '=', 'e_id');
                $q->whereMonth('eb_date', '=', $periode->format('m'));
                $q->whereYear('eb_date', '=', $periode->format('Y'));
            })
            ->leftJoin('d_employeebenefitsdt', 'ebd_employeebenefits', '=', 'eb_id')
            ->leftJoin('m_benefits as benefit', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
            })
            ->select('e_name', 'e_nip', 'e_id',
                DB::raw('
                        round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        where emprew.ebd_employeebenefits = emben.eb_id and benefit.b_type = "R")) as reward
                        '),
                DB::raw('
                        round((select sum(ebd_value * (-1)) from d_employeebenefitsdt emppun
                        where emppun.ebd_employeebenefits = emben.eb_id and benefit.b_type = "P")) as punishment
                        ')
            )
            ->where('e_company', '=', $pusat->c_id)
            ->groupBy('e_id')
            ->get();

        return response()->json($data);
    }

    public function getDetailRewardPunishment(Request $request){
        $e_id = $request->e_id;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        $data = DB::table('m_employee')
            ->leftJoin('d_employeebenefits as emben', function($q) use ($periode){
                $q->on('eb_employee', '=', 'e_id');
                $q->whereMonth('eb_date', '=', $periode->format('m'));
                $q->whereYear('eb_date', '=', $periode->format('Y'));
            })
            ->leftJoin('d_employeebenefitsdt', 'ebd_employeebenefits', '=', 'eb_id')
            ->leftJoin('m_benefits as benefit', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
                $q->where('b_type', '!=', 'T');
            })
            ->select('e_name', 'e_nip', 'b_name', DB::raw('round(ebd_value) as ebd_value'), 'ebd_note')
            ->where('e_company', '=', $pusat->c_id)
            ->where('e_id', '=', $e_id)
            ->get();

        return response()->json($data);
    }
}
