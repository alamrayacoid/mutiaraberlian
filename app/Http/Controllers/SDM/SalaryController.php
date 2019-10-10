<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\AksesUser;

class SalaryController extends Controller
{
    public function getDataSalaryPegawai(Request $request){
        if (!AksesUser::checkAkses(28, 'read')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        $data = DB::table('m_employee')
            ->leftJoin('d_employeesalarydt as esd', function($q) use ($periode){
                $q->on('esd_employee', '=', 'e_id');
                $q->join('d_employeesalary as es', function($w) use ($periode){
                    $w->on('es_id', '=', 'esd_employeesalary');
                    $w->whereMonth('es_date', '=', $periode->format('m'));
                    $w->whereYear('es_date', '=', $periode->format('Y'));
                });
            })
            ->leftJoin('d_employeebenefits as emben', function($q) use ($periode){
                $q->on('eb_employee', '=', 'esd_employee');
                $q->whereMonth('eb_date', '=', $periode->format('m'));
                $q->whereYear('eb_date', '=', $periode->format('Y'));
            })
            ->leftJoin('d_employeebenefitsdt', 'ebd_employeebenefits', '=', 'eb_id')
            ->select('e_name', 'e_nip', 'e_id', DB::raw('round(e_salary) as e_salary'), DB::raw('round(e_meal) as e_meal'),
            DB::raw('coalesce(es_issubmitted, "N") as es_issubmitted'), 'es_id', 'esd_employeesalary',
                DB::raw('
                        coalesce(round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emprew.ebd_employeebenefits = emben.eb_id and b_type = "T" group by b_type)), 0) as tunjangan
                        '),
                DB::raw('
                        coalesce(round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emprew.ebd_employeebenefits = emben.eb_id and b_type = "R" group by b_type)), 0) as reward
                        '),
                DB::raw('
                        coalesce(round((select sum(ebd_value) from d_employeebenefitsdt emprew
                        LEFT JOIN m_benefits ON b_id = ebd_benefits
                        where emprew.ebd_employeebenefits = emben.eb_id and b_type = "P" group by b_type)), 0) as punishment
                        '),
                DB::raw('round(esd_salary) as esd_salary'), DB::raw('coalesce(date_format(esd_submittedon, "%d-%m-%Y"), "") as esd_submittedon')
            )
            ->where('e_company', '=', $pusat->c_id)
            ->where('e_isactive', '=', 'Y')
            ->groupBy('e_id')
            ->get();

        return response()->json($data);
    }

    public function masterGajiPokok(Request $request){
        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        $data = DB::table('m_employee')
            ->select('e_nip', 'e_name', 'e_id', DB::raw('round(e_salary) as e_salary'),DB::raw('round(e_meal) as e_meal'))
            ->where('e_company', '=', $pusat->c_id)
            ->where('e_isactive', '=', 'Y')
            ->get();

        return view('sdm/penggajian/salary/mastergaji', compact('data'));
    }

    public function saveGajiPokok(Request $request){
        if (!AksesUser::checkAkses(28, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        $e_id = $request->e_id;
        $gaji = $request->gaji;
        $makan = $request->meal;
        dd($request->all());
        DB::beginTransaction();
        try {

            for ($i=0; $i < count($e_id); $i++) {
                DB::table('m_employee')
                    ->where('e_id', '=', $e_id[$i])
                    ->update([
                        'e_salary' => $gaji[$i],
                        'e_meal' => $makan[$i]
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

    public function getMasterGajiPokok(){
        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();
        $data = DB::table('m_employee')
            ->select('e_nip', 'e_name', 'e_id', DB::raw('round(e_salary) as e_salary'),DB::raw('round(e_meal) as e_meal'))
            ->where('e_company', '=', $pusat->c_id)
            ->where('e_isactive', '=', 'Y')
            ->get();
        return response()->json($data);
    }

    public function saveGajiPegawai(Request $request){
        if (!AksesUser::checkAkses(28, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }

        DB::beginTransaction();
        try {
            $e_id = $request->e_id;
            $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);
            $diserahkan = $request->diserahkan;
            $total = $request->total;
            $type = $request->type;
            if ($type == 'draft') {
                $type = 'N';
            } elseif ($type == 'save') {
                $type = 'Y';
            }

            $id = 0;
            $cek = DB::table('d_employeesalary')
                ->whereMonth('es_date', '=', $periode->format('m'))
                ->whereYear('es_date', '=', $periode->format('Y'))
                ->first();

            if ($cek !== null) {
                //jika data sudah ada, maka akan dihapus terlebih dahulu
                $id = $cek->es_id;
                DB::table('d_employeesalary')
                    ->whereMonth('es_date', '=', $periode->format('m'))
                    ->whereYear('es_date', '=', $periode->format('Y'))
                    ->delete();

                DB::table('d_employeesalarydt')
                    ->where('esd_employeesalary', '=', $id)
                    ->delete();

            } else {
                $id = DB::table('d_employeesalary')
                    ->max('es_id');
                ++$id;
            }

            $insert = [
                'es_id' => $id,
                'es_date' => $periode->format('Y-m-d'),
                'es_issubmitted' => $type
            ];

            $insertdt = [];
            for ($i=0; $i < count($e_id); $i++) {
                $submit = null;
                if ($diserahkan[$i] !== null) {
                    $submit = Carbon::createFromFormat('d-m-Y', $diserahkan[$i])->format('Y-m-d');
                }
                $tempdt = [
                    'esd_employeesalary' => $id,
                    'esd_detailid' => $i + 1,
                    'esd_employee' => $e_id[$i],
                    'esd_salary' => $total[$i],
                    'esd_submittedon' => $submit
                ];
                array_push($insertdt, $tempdt);
            }

            DB::table('d_employeesalary')
                ->insert($insert);

            DB::table('d_employeesalarydt')
                ->insert($insertdt);

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

    public function detailGajiPegawai(Request $request){
        $e_id = $request->id;
        $periode = Carbon::createFromFormat('d-m-Y', "01-" . $request->periode);

        $salary = DB::table('m_employee')
            ->select(DB::raw('round(e_salary) as e_salary'), 'e_name')
            ->where('e_id', '=', $e_id)
            ->first();

        $punishment = DB::table('d_employeebenefits')
            ->join('d_employeebenefitsdt', 'eb_id', '=', 'ebd_employeebenefits')
            ->join('m_benefits', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
                $q->where('b_type', '=', 'P');
            })
            ->select('b_name', DB::raw('round(ebd_value) as ebd_value'))
            ->whereMonth('eb_date', '=', $periode->format('m'))
            ->whereYear('eb_date', '=', $periode->format('Y'))
            ->where('eb_employee', '=', $e_id)
            ->get();

        $reward = DB::table('d_employeebenefits')
            ->join('d_employeebenefitsdt', 'eb_id', '=', 'ebd_employeebenefits')
            ->join('m_benefits', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
                $q->where('b_type', '=', 'R');
            })
            ->select('b_name', DB::raw('round(ebd_value) as ebd_value'))
            ->whereMonth('eb_date', '=', $periode->format('m'))
            ->whereYear('eb_date', '=', $periode->format('Y'))
            ->where('eb_employee', '=', $e_id)
            ->get();

        $tunjangan = DB::table('d_employeebenefits')
            ->join('d_employeebenefitsdt', 'eb_id', '=', 'ebd_employeebenefits')
            ->join('m_benefits', function($q){
                $q->on('b_id', '=', 'ebd_benefits');
                $q->where('b_type', '=', 'T');
            })
            ->select('b_name', DB::raw('round(ebd_value) as ebd_value'))
            ->whereMonth('eb_date', '=', $periode->format('m'))
            ->whereYear('eb_date', '=', $periode->format('Y'))
            ->where('eb_employee', '=', $e_id)
            ->get();

        return response()->json([
            "salary" => $salary,
            "punishment" => $punishment,
            "reward" => $reward,
            "tunjangan" => $tunjangan
        ]);
    }
}
