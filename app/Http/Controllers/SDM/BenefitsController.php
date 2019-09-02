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

    public function getDataEditReward(Request $request){
        $id = $request->id;

        $data = DB::table('m_benefits')
            ->where('b_id', '=', $id)
            ->first();

        return response()->json($data);
    }

    public function getDataEditPunishment(Request $request){
        $id = $request->id;

        $data = DB::table('m_benefits')
            ->where('b_id', '=', $id)
            ->first();

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

    public function updateMasterBenefits(Request $request){
        if (!AksesUser::checkAkses(28, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $nama = $request->nama;
        $type = $request->type;
        $id = $request->id;

        DB::beginTransaction();
        try {

            DB::table('m_benefits')
                ->where('b_id', '=', $id)
                ->update([
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
            ->select('e_name', 'e_nip', 'e_id',
                DB::raw('
                        round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emprew.ebd_employeebenefits = emben.eb_id and b_type = "R" group by b_type)) as reward
                        '),
                DB::raw('
                        round((select sum(ebd_value) from d_employeebenefitsdt emppun
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emppun.ebd_employeebenefits = emben.eb_id and b_type = "P" group by b_type)) as punishment
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
            ->select('e_name', 'e_nip', 'b_name', DB::raw('round(ebd_value) as ebd_value'), 'ebd_note', 'b_type')
            ->where('e_company', '=', $pusat->c_id)
            ->where('e_id', '=', $e_id)
            ->get();

        return response()->json($data);
    }

    public function editRewardPunishment($id, $periode){
        $tanggal = $periode;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $periode);

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
            ->select('e_name', 'e_nip', 'e_id', 'b_name', DB::raw('(case when b_type = "R" then "Reward" when b_type = "P" then "Punishment" end) as b_type'),
                DB::raw('round(ebd_value) as ebd_value'))
            ->where('e_id', '=', $id)
            ->get();

        $reward = DB::table('m_benefits')
            ->where('b_type', '=', 'R')
            ->get();

        $punishment = DB::table('m_benefits')
            ->where('b_type', '=', 'P')
            ->get();

        return view('sdm/penggajian/reward/edit', compact('data', 'tanggal', 'reward', 'punishment'));
    }

    public function saveEmployeeBenefits(Request $request){
        if (!AksesUser::checkAkses(28, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $employee = $request->employe;
        $b_id = $request->b_id;
        $type = $request->type;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        DB::beginTransaction();
        try {
            $dataeb = DB::table('d_employeebenefits')
                ->where('eb_date', '=', $periode->format('Y-m-d'))
                ->where('eb_employee', '=', $employee)
                ->first();

            if ($dataeb === null) {
                // jika header belum ada, maka akan create dari awal
                $ideb = DB::table('d_employeebenefits')
                    ->max('eb_id');

                ++$ideb;

                DB::table('d_employeebenefits')
                    ->insert([
                        'eb_id' => $ideb,
                        'eb_date' => $periode->format('Y-m-d'),
                        'eb_employee' => $employee
                    ]);

                DB::table('d_employeebenefitsdt')
                    ->insert([
                        'ebd_employeebenefits' => $ideb,
                        'ebd_detailid' => 1,
                        'ebd_benefits' => $b_id,
                        'ebd_value' => 0
                    ]);

            } else {
                //jika sudah ada header
                $cek = DB::table('d_employeebenefitsdt')
                    ->where('ebd_employeebenefits', '=', $dataeb->eb_id)
                    ->where('ebd_benefits', '=', $b_id)
                    ->first();

                $detailid = 1;
                if ($cek != null) {
                    //data sudah ada
                    DB::commit();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'data sudah ada..'
                    ]);
                }

                //create detailid baru
                $detailid = DB::table('d_employeebenefitsdt')
                    ->where('ebd_employeebenefits', '=', $dataeb->eb_id)
                    ->max('ebd_detailid');

                ++$detailid;

                DB::table('d_employeebenefitsdt')
                    ->insert([
                        'ebd_employeebenefits' => $dataeb->eb_id,
                        'ebd_detailid' => $detailid,
                        'ebd_benefits' => $b_id,
                        'ebd_value' => 0,
                    ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
    }

    public function getDataRewardPunishmentPegawai(Request $request){
        $id = $request->employe;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $data = DB::table('d_employeebenefits')
            ->leftJoin('d_employeebenefitsdt', 'eb_id', '=', 'ebd_employeebenefits')
            ->leftJoin('m_benefits', function ($q) {
                $q->on('b_id', '=', 'ebd_benefits');
            })
            ->select('eb_employee', 'b_name', 'eb_id', 'ebd_detailid', 'b_id', DB::raw('round(ebd_value) as ebd_value'),
                DB::raw('(case when b_type = "R" then "Reward" when b_type = "P" then "Punishment" end) as b_type'))
            ->where('eb_employee', '=', $id)
            ->whereMonth('eb_date', '=', $periode->format('m'))
            ->whereYear('eb_date', '=', $periode->format('Y'))
            ->where('b_type', '!=', 'T')
            ->get();

       return response()->json($data);
    }

    public function saveDataRewardPunishmentPegawai(Request $request){

        if (!AksesUser::checkAkses(28, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $eb_id = $request->eb_id;
        $ebd_detailid = $request->ebd_detailid;
        $ebd_value = $request->value;

        DB::beginTransaction();
        try {
            for ($i=0; $i < count($eb_id); $i++) {
                DB::table('d_employeebenefitsdt')
                    ->where('ebd_employeebenefits', '=', $eb_id[$i])
                    ->where('ebd_detailid', '=', $ebd_detailid[$i])
                    ->update([
                        'ebd_value' => $ebd_value[$i]
                    ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e
            ]);
        }
    }

    public function deleteDataRewardPunishmentPegawai(Request $request){
        $id = $request->id;
        $detailid = $request->detailid;

        DB::beginTransaction();
        try {

            DB::table('d_employeebenefitsdt')
                ->where('ebd_employeebenefits', '=', $id)
                ->where('ebd_detailid', '=', $detailid)
                ->delete();

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

// Tunjangan
    public function getDataTunjangan(Request $request){
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
            ->select('e_name', 'e_nip', 'e_id',
                DB::raw('
                        round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emprew.ebd_employeebenefits = emben.eb_id and b_type = "T" group by b_type)) as tunjangan
                        ')
            )
            ->where('e_company', '=', $pusat->c_id)
            ->groupBy('e_id')
            ->get();

        return response()->json($data);
    }

    public function getDataMasterTunjangan(){
        $data = DB::table('m_benefits')
            ->where('b_type', '=', 'T')
            ->get();

        return response()->json($data);
    }

    public function getDataEditTunjangan(Request $request){
        $id = $request->id;

        $data = DB::table('m_benefits')
            ->where('b_id', '=', $id)
            ->first();

        return response()->json($data);
    }

    public function editTunjanganPegawai($id, $periode){
        $tanggal = $periode;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $periode);

        $data = DB::table('m_employee')
            ->leftJoin('d_employeebenefits as emben', function($q) use ($periode){
                $q->on('eb_employee', '=', 'e_id');
                $q->whereMonth('eb_date', '=', $periode->format('m'));
                $q->whereYear('eb_date', '=', $periode->format('Y'));
            })
            ->leftJoin('d_employeebenefitsdt', 'ebd_employeebenefits', '=', 'eb_id')
            ->leftJoin('m_benefits as benefit', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
                $q->where('b_type', '=', 'T');
            })
            ->select('e_name', 'e_nip', 'e_id', 'b_name', DB::raw('(case when b_type = "T" then "Tunjangan" end) as b_type'),
                DB::raw('round(ebd_value) as ebd_value'))
            ->where('e_id', '=', $id)
            ->get();

        $tunjangan = DB::table('m_benefits')
            ->where('b_type', '=', 'T')
            ->get();

        return view('sdm/penggajian/tunjangan/edit', compact('data', 'tanggal', 'tunjangan'));
    }

    public function getDataTunjanganPegawai(Request $request){
        $id = $request->employe;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $data = DB::table('d_employeebenefits')
            ->leftJoin('d_employeebenefitsdt', 'eb_id', '=', 'ebd_employeebenefits')
            ->leftJoin('m_benefits', function ($q) {
                $q->on('b_id', '=', 'ebd_benefits');
            })
            ->select('eb_employee', 'b_name', 'eb_id', 'ebd_detailid', 'b_id', DB::raw('round(ebd_value) as ebd_value'),
                DB::raw('(case when b_type = "T" then "Tunjangan" end) as b_type'))
            ->where('eb_employee', '=', $id)
            ->whereMonth('eb_date', '=', $periode->format('m'))
            ->whereYear('eb_date', '=', $periode->format('Y'))
            ->where('b_type', '=', 'T')
            ->get();

       return response()->json($data);
    }

}
