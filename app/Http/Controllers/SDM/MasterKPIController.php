<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use DataTables;
use Response;
use App\Http\Controllers\Controller;

class MasterKPIController extends Controller
{
    public function create(Request $request)
    {
        $indikator = $request->indikator;

        DB::beginTransaction();
        try {

            $cek = DB::table('m_kpi')
                ->where('k_indicator', '=', $indikator)
                ->get();

            if (count($cek) > 0){
                DB::rollBack();
                return Response::json([
                    'status' => 'gagal',
                    'message' => 'data sudah ada'
                ]);
            }

            $id = DB::table('m_kpi')
                ->max('k_id');

            ++$id;

            DB::table('m_kpi')
                ->insert([
                    'k_id' => $id,
                    'k_indicator' => $indikator
                ]);

            DB::commit();
            return Response::json([
                'status' => 'sukses'
            ]);
        } catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getData(Request $request)
    {
        $status = $request->status;
        $data = DB::table('m_kpi');
        if ($status != 'all'){
            $data = $data->where('k_isactive', '=', $status);
        }

        $datas = $data->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                if ($datas->k_isactive == 'Y'){
                    return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
                            <button class="btn btn-warning btn-edit-masterkpi btn-sm hint--top-left hint--error" type="button" onclick="nonKpi(\''.Crypt::encrypt($datas->k_id).'\')" aria-label="Non-aktifkan"><i class="fa fa-close"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
                } else {
                    return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
                            <button class="btn btn-success btn-edit-masterkpi btn-sm hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activeKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function activeKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->update([
                    'k_isactive' => "Y"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function nonKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->update([
                    'k_isactive' => "N"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function deleteKpi($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('m_kpi')
                ->where('k_id', $id)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function kpi_create_d()
    {
        return view('sdm.kinerjasdm.kpidivisi.create');
    }

    public function get_kpi_divisi_d()
    {
        $datas = DB::table('m_divisi')
            ->whereIn('m_id', function($query){
                $query->select('ke_department')->from('d_kpiemp')->get();
            })->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-edit-masterkpi btn-sm hint--top-left hint--info" type="button" onclick="deatilKpiDivisi(\''.Crypt::encrypt($datas->m_id).'\')" aria-label="Detail"><i class="fa fa-folder-open"></i></button>
                            <button class="btn btn-warning btn-disable-masterkpi btn-sm hint--top-left hint--warning" type="button" aria-label="Edit" onclick="editKpiDivisi(\''.Crypt::encrypt($datas->m_id).'\')"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="delKpiDivisi(\''.Crypt::encrypt($datas->m_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_kpi_divisi()
    {
        $data = DB::table('m_divisi')
            ->select('m_divisi.*')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function save_kpi_divisi(Request $request)
    {
        DB::beginTransaction();
        try {
        
            $indicator = $request->indicator;
            for ($i=0; $i < count($indicator); $i++) { 
                DB::table('d_kpiemp')->insert([
                    'ke_kpi'      => $indicator[$i],
                    'ke_detailid' => DB::table('d_kpiemp')->where('ke_kpi', $indicator[$i])->max('ke_detailid') + 1,
                    'ke_type'     => 'D',
                    'ke_department' => $request->divisi,
                    'ke_weight'   => $request->bobot[$i],
                    'ke_target'   => $request->target[$i]
                ]);
            }
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function get_detail_kpi_divisi(Request $request)
    {
        try {
            $divs = Crypt::decrypt($request->divisi);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $datas = DB::table('d_kpiemp')
            ->join('m_divisi', 'm_id', 'ke_department')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('ke_department', 'm_name', 'k_indicator', 'k_isactive', 'ke_weight', 'ke_target')
            ->where('ke_department', '=', $divs)->get();

        return response()->json([
            'data' => $datas
        ]);
    }

    public function delete_kpi_divisi($divs)
    {
        try {
            $divs = Crypt::decrypt($divs);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
        
            DB::table('d_kpiemp')->where('ke_department', $divs)->delete();
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function edit_kpi_divisi(Request $request)
    {
        try {
            $divs = Crypt::decrypt($request->divisi);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $divisi = DB::table('m_divisi')
            ->select('m_divisi.*')
            ->get();

        $kpi = DB::table('m_kpi')->get();

        $kpiemp_first = DB::table('d_kpiemp')
            ->join('m_divisi', 'm_divisi.m_id', 'ke_department')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('m_name', 'k_indicator', 'k_isactive', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi')
            // ->where('k_isactive', '=', 'Y')
            ->where('ke_department', '=', $divs)->first();

        $kpiemp = DB::table('d_kpiemp')
            ->join('m_divisi', 'm_divisi.m_id', 'ke_department')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('m_name', 'k_indicator', 'k_isactive', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi')
            // ->where('k_isactive', '=', 'Y')
            ->where('ke_department', '=', $divs)->offset(1)->take(100)->get();

        return view('sdm.kinerjasdm.kpidivisi.edit', compact('divisi', 'kpi', 'kpiemp_first', 'kpiemp'));
    }

    public function update_kpi_divisi(Request $request)
    {
        // return json_encode($request->all());
        $divs = $request->divisi;

        DB::beginTransaction();
        try {
            
            DB::table('d_kpiemp')->where('ke_department', $divs)->delete();
        
            $indicator = $request->indicator;
            for ($i=0; $i < count($indicator); $i++) { 
                DB::table('d_kpiemp')->insert([
                    'ke_kpi'      => $indicator[$i],
                    'ke_detailid' => DB::table('d_kpiemp')->where('ke_kpi', $indicator[$i])->max('ke_detailid') + 1,
                    'ke_type'     => 'D',
                    'ke_department' => $request->ke_department,
                    'ke_weight'   => $request->bobot[$i],
                    'ke_target'   => $request->target[$i]
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    // KPI Pegawai --> --> --> --> --> --> --> --> -->
    public function kpi_create_p()
    {
        return view('sdm.kinerjasdm.kpipegawai.create');
    }

    public function get_kpi_pegawai()
    {
        $datas = DB::table('m_employee')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->whereIn('e_id', function($query){
                $query->select('ke_employee')->from('d_kpiemp')->get();
            })->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-edit-masterkpi btn-sm hint--top-left hint--info" type="button" onclick="deatilKpiPegawai(\''.Crypt::encrypt($datas->e_id).'\')" aria-label="Detail"><i class="fa fa-folder-open"></i></button>
                            <button class="btn btn-warning btn-disable-masterkpi btn-sm hint--top-left hint--warning" type="button" aria-label="Edit" onclick="editKpiPegawai(\''.Crypt::encrypt($datas->e_id).'\')"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="delKpiPegawai(\''.Crypt::encrypt($datas->e_id).'\')"><i class="fa fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_kpi_employee()
    {
        $data = DB::table('m_employee')
            ->select('m_employee.*', 'm_company.*', 'm_divisi.m_name as d_name', 'm_jabatan.*')
            ->join('m_company', 'c_id', 'e_company')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->where('m_company.c_type', '=', 'PUSAT')->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function get_kpi_indikator()
    {
        $data = DB::table('m_kpi')
            ->where('k_isactive', '=', 'Y')->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function save_kpi_pegawai(Request $request)
    {
        DB::beginTransaction();
        try {
        
            $indicator = $request->indicator;
            for ($i=0; $i < count($indicator); $i++) { 
                DB::table('d_kpiemp')->insert([
                    'ke_kpi'      => $indicator[$i],
                    'ke_detailid' => DB::table('d_kpiemp')->where('ke_kpi', $indicator[$i])->max('ke_detailid') + 1,
                    'ke_type'     => 'P',
                    'ke_employee' => $request->employee,
                    'ke_weight'   => $request->bobot[$i],
                    'ke_target'   => $request->target[$i]
                ]);
            }
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function get_detail_kpi_pegawai(Request $request)
    {
        try {
            $emp = Crypt::decrypt($request->employee);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $datas = DB::table('d_kpiemp')
            ->join('m_employee', 'e_id', 'ke_employee')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('e_name', 'm_name', 'j_name', 'k_indicator', 'k_isactive', 'ke_weight', 'ke_target')
            ->where('ke_employee', '=', $emp)->get();

        return response()->json([
            'data' => $datas
        ]);
    }

    public function delete_kpi_pegawai($emp)
    {
        try {
            $emp = Crypt::decrypt($emp);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
        
            DB::table('d_kpiemp')->where('ke_employee', $emp)->delete();
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function edit_kpi_pegawai(Request $request)
    {
        try {
            $emp = Crypt::decrypt($request->employee);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $employee = DB::table('m_employee')
            ->select('m_employee.*', 'm_company.*', 'm_divisi.m_name as d_name', 'm_jabatan.*')
            ->join('m_company', 'c_id', 'e_company')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->where('m_company.c_type', '=', 'PUSAT')->get();

        $kpi = DB::table('m_kpi')->get();

        $kpiemp_first = DB::table('d_kpiemp')
            ->join('m_employee', 'e_id', 'ke_employee')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('e_name', 'm_name', 'j_name', 'k_indicator', 'k_isactive', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi')
            ->where('ke_employee', '=', $emp)->first();

        $kpiemp = DB::table('d_kpiemp')
            ->join('m_employee', 'e_id', 'ke_employee')
            ->join('m_divisi', 'm_divisi.m_id', 'e_department')
            ->join('m_jabatan', 'j_id', 'e_position')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('e_name', 'm_name', 'j_name', 'k_indicator', 'k_isactive', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi')
            ->where('ke_employee', '=', $emp)->offset(1)->take(100)->get();
        
        return view('sdm.kinerjasdm.kpipegawai.edit', compact('employee', 'kpi', 'kpiemp_first', 'kpiemp'));
    }

    public function update_kpi_pegawai(Request $request)
    {
        // return json_encode($request->all());
        $emp = $request->employee;

        DB::beginTransaction();
        try {
            
            DB::table('d_kpiemp')->where('ke_employee', $emp)->delete();
        
            $indicator = $request->indicator;
            for ($i=0; $i < count($indicator); $i++) { 
                DB::table('d_kpiemp')->insert([
                    'ke_kpi'      => $indicator[$i],
                    'ke_detailid' => DB::table('d_kpiemp')->where('ke_kpi', $indicator[$i])->max('ke_detailid') + 1,
                    'ke_type'     => 'P',
                    'ke_employee' => $request->ke_employee,
                    'ke_weight'   => $request->bobot[$i],
                    'ke_target'   => $request->target[$i]
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }
}
