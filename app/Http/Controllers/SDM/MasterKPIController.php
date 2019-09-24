<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use DataTables;
use Response;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class MasterKPIController extends Controller
{
    public function create(Request $request)
    {
        // dd($request->all());
        $indikator = $request->indikator;
        $unit = $request->unit;

        DB::beginTransaction();
        try {

            $cek = DB::table('m_kpi')
                ->where('k_indicator', '=', $indikator)
                ->where('k_unit', '=', $unit)
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
                    'k_indicator' => $indikator,
                    'k_unit' => $unit
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

    // public function deleteKpi($id)
    // {
    //     try {
    //         $id = Crypt::decrypt($id);
    //     } catch (\Exception $e) {
    //         return view('errors.404');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         DB::table('m_kpi')
    //             ->where('k_id', $id)
    //             ->delete();

    //         DB::commit();
    //         return response()->json([
    //             'status' => 'sukses'
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return response()->json([
    //             'status'  => 'Gagal',
    //             'message' => $e
    //         ]);
    //     }
    // }

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

            DB::table('d_kpiemp')
                ->where('ke_kpi', $id)
                ->delete();

            DB::table('d_kpidt')
                ->where('kd_indikator', $id)
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
        $depart = $request->divisi;

        DB::beginTransaction();
        try {

            DB::table('d_kpiemp')->where('ke_department', $depart)->delete();

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
            // ->where('ke_department', '=', $divs)->offset(1)->take(100)->get();
            ->where('ke_department', '=', $divs)->get();

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
        // dd($request->all());
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
            // ->where('ke_employee', '=', $emp)->offset(1)->take(100)->get();
            ->where('ke_employee', '=', $emp)->get();

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

    public function getIndikatorKpiPegawai(Request $request)
    {
        // dd($request->all());
        $data = $request->data;
        $datas = DB::table('d_kpiemp')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('k_id', 'k_indicator', 'k_isactive', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi')
            ->where('ke_employee', '=', $data)
            ->get();
        // dd($datas);
        return response()->json([
            'data' => $datas
        ]);
        // $status = $request->status;
        // $data = DB::table('m_kpi');
        // if ($status != 'all'){
        //     $data = $data->where('k_isactive', '=', $status);
        // }
        // $datas = $data->get();
        // return Datatables::of($datas)
        //     ->addIndexColumn()
        //     ->addColumn('action', function ($datas) {
        //         if ($datas->k_isactive == 'Y'){
        //             return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
        //                     <button class="btn btn-warning btn-edit-masterkpi btn-sm hint--top-left hint--error" type="button" onclick="nonKpi(\''.Crypt::encrypt($datas->k_id).'\')" aria-label="Non-aktifkan"><i class="fa fa-close"></i></button>
        //                     <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
        //                 </div>';
        //         } else {
        //             return '<div class="btn-group btn-group-sm text-center" style="width: 100%">
        //                     <button class="btn btn-success btn-edit-masterkpi btn-sm hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activeKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-check"></i></button>
        //                     <button class="btn btn-danger btn-disable-masterkpi btn-sm hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteKpi(\''.Crypt::encrypt($datas->k_id).'\')"><i class="fa fa-trash"></i></button>
        //                 </div>';
        //         }
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);
    }

    public function getIndikatorKpiDivisi(Request $request)
    {
        $data = $request->data;
        // dd($data);
        $datas = DB::table('d_kpiemp')
            ->join('m_kpi', 'k_id', 'ke_kpi')
            ->select('k_id', 'k_indicator', 'k_isactive', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi')
            ->where('ke_department', '=', $data)
            ->get();
        // dd($datas);
        return response()->json([
            'data' => $datas
        ]);
    }

    public function createInputKpi(Request $request)
    {
        if (!$request->isMethod('post')) {
            $employee = DB::table('m_employee')
                ->select('e_id', 'e_nip', 'e_name')
                ->get();

            $divisi = DB::table('m_divisi')->get();
            return view('sdm/kinerjasdm/inputkpi/create')->with(compact('employee', 'divisi'));
        // } else {
        //     $data = $request->all();
        //     $productionorderauth = [];
        //     $productionorderdt = [];
        //     $productionorderpayment = [];
        //     DB::beginTransaction();
        //     try {
        //         // dd($request);
        //         $idpo = (DB::table('d_productionorderdt')->max('pod_productionorder')) ? (DB::table('d_productionorderdt')->max('pod_productionorder')) + 1 : 1;
        //         // $nota = CodeGenerator::codeWithSeparator('d_productionorderauth', 'poa_nota', 8, 10, 3, 'PO', '-');
        //         // $cekNota = DB::table('d_productionorder')
        //         //     ->where('po_nota', '=', $nota)
        //         //     ->get();
        //         //
        //         // if (count($cekNota) > 0){
        //         //     $nota = CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-');
        //         // }

        //         $notaProductionAuth = CodeGenerator::codeWithSeparator('d_productionorderauth', 'poa_nota', 8, 10, 3, 'PO', '-');
        //         $notaProduction = CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-');
        //         if (strcmp($notaProduction, $notaProductionAuth) > 0) {
        //             $nota = $notaProduction;
        //         }
        //         else {
        //             $nota = $notaProductionAuth;
        //         };


        //         $productionorderauth[] = [
        //             'poa_id' => $idpo,
        //             'poa_nota' => $nota,
        //             'poa_date' => date('Y-m-d', strtotime($data['po_date'])),
        //             'poa_supplier' => $data['supplier'],
        //             'poa_totalnet' => $data['tot_hrg'],
        //             'poa_status' => 'BELUM'
        //         ];

        //         $poddetail = (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) ? (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) + 1 : 1;
        //         $detailpod = $poddetail;
        //         for ($i = 0; $i < count($data['idItem']); $i++) {
        //             $productionorderdt[] = [
        //                 'pod_productionorder' => $idpo,
        //                 'pod_detailid' => $detailpod,
        //                 'pod_item' => $data['idItem'][$i],
        //                 'pod_qty' => $data['jumlah'][$i],
        //                 'pod_unit' => $data['satuan'][$i],
        //                 'pod_value' => $this->removeCurrency($data['harga'][$i]),
        //                 'pod_totalnet' => $this->removeCurrency($data['subtotal'][$i])
        //             ];
        //             $detailpod++;
        //         }

        //         for ($i = 0; $i < count($data['termin']); $i++) {
        //             $productionorderpayment[] = [
        //                 'pop_productionorder' => $idpo,
        //                 'pop_termin' => $data['termin'][$i],
        //                 'pop_datetop' => date('Y-m-d', strtotime($data['estimasi'][$i])),
        //                 'pop_value' => $this->removeCurrency($data['nominal'][$i]),
        //             ];
        //         }

        //         // dd($productionorderpayment);
        //         DB::table('d_productionorderauth')->insert($productionorderauth);
        //         DB::table('d_productionorderdt')->insert($productionorderdt);
        //         DB::table('d_productionorderpayment')->insert($productionorderpayment);
        //         DB::commit();
        //         return json_encode([
        //             'status' => 'Success'
        //         ]);
        //     } catch (\Exception $e) {
        //         DB::rollBack();
        //         return json_encode([
        //             'status' => 'Failed',
        //             'msg' => $e
        //         ]);
        //     }
        }
    }

    public function getDataIndikatorKpiDivisi(Request $request)
    {
        $data = $request->data;

        $periode = "01-" . $request->periode;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->where('k_department', $data)
                    ->where('k_type', 'D')
                    ->first();
                    // ->get();

        // if ($periodes == true) {
        //     $datas = DB::table('d_kpiemp')
        //         ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
        //         ->join('d_kpi', function($q){
        //             $q->on('k_type', 'ke_type');
        //             $q->on('k_department', 'ke_department');
        //         })
        //         ->leftjoin('d_kpidt', 'kd_kpi', 'd_kpi.k_id')
        //         ->groupBy('d_kpiemp.ke_kpi')
        //         ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total')
        //         ->where('ke_department', '=', $data)
        //         // ->where('ke_department', '=', $periodes)
        //         ->get();

        if ($periodes == true) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
                ->join('d_kpi', function($q){
                    $q->on('k_type', 'ke_type');
                    $q->on('k_department', 'ke_department');
                })
                ->leftjoin('d_kpidt', function($q){
                    $q->on('kd_kpi', 'd_kpi.k_id');
                    $q->on('kd_indikator', 'ke_kpi');
                })
                // ->groupBy('d_kpiemp.ke_kpi')
                ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total', 'kd_kpi')
                ->where('ke_department', '=', $data)
                ->whereMonth('k_periode', $periode->month)
                ->whereYear('k_periode', $periode->year)
                // ->where('ke_department', '=', $periodes)
                ->get();

        }
        elseif ($periodes == false) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'k_id', 'ke_kpi')
                // ->join('d_kpidt', 'kd_kpi', 'ke_kpi')
                ->select('k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi')
                ->where('ke_department', '=', $data)
                ->get();

        }

        $datas2 = DB::table('d_kpiemp')
            ->select('ke_department',
                DB::RAW('SUM(ke_weight) as sum')
            )
            ->where('ke_department', '=', $data)
            ->groupBy('ke_department')
            ->first();

        return response()->json([
            'data' => $datas,
            'total' => $datas2,
        ]);
    }

    public function getDataIndikatorKpiPegawai(Request $request)
    {
        $data = $request->data;
        // dd($data);

        $periode = "01-" . $request->periode;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->where('k_employee', $data)
                    // ->where('k_type', 'P')
                    ->first();
        // dd($periodes);

        // if ($periodes == true) {
        //     $datas = DB::table('d_kpiemp')
        //         ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
        //         ->join('d_kpi', function($q){
        //             $q->on('k_type', 'ke_type');
        //             $q->on('k_employee', 'ke_employee');
        //         })
        //         ->leftjoin('d_kpidt', 'kd_kpi', 'd_kpi.k_id')
        //         ->groupBy('d_kpiemp.ke_kpi')
        //         ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total')
        //         ->where('ke_employee', '=', $data)
        //         // ->where('ke_employee', '=', $periodes)
        //         ->get();
        if ($periodes == true) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
                ->join('d_kpi', function($q){
                    $q->on('k_type', 'ke_type');
                    $q->on('k_employee', 'ke_employee');
                })
                ->leftjoin('d_kpidt', function($q){
                    $q->on('kd_kpi', 'd_kpi.k_id');
                    $q->on('kd_indikator', 'ke_kpi');
                })
                // // ->groupBy('d_kpiemp.ke_kpi')
                ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total', 'k_periode', 'kd_kpi')
                ->where('ke_employee', '=', $data)
                ->whereMonth('k_periode', $periode->month)
                ->whereYear('k_periode', $periode->year)
                // ->where('ke_department', '=', $periodes)
                ->get();
                // dd($datas);
        }
        elseif ($periodes == false) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'k_id', 'ke_kpi')
                // ->join('d_kpidt', 'kd_kpi', 'ke_kpi')
                ->select('k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi')
                ->where('ke_employee', '=', $data)
                ->get();
        }

        // $datas = DB::table('d_kpiemp')
        //     ->join('m_kpi', 'k_id', 'ke_kpi')
        //     ->select('k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi')
        //     ->where('ke_employee', '=', $data)
        //     ->get();
        // dd($datas);
        //
        $datas2 = DB::table('d_kpiemp')
            ->select('ke_employee',
                DB::RAW('SUM(ke_weight) as sum')
            )
            ->where('ke_employee', '=', $data)
            ->groupBy('ke_employee')
            ->first();

        return response()->json([
            'data' => $datas,
            'total' => $datas2
        ]);
    }

    public function saveInputKpi(Request $request)
    {
        // dd($request->all());
        $kd_kpiD = $request->kd_kpiD;
        $kd_kpiP = $request->kd_kpiP;
        // dd($kd_kpiP);
        $periode = "01-" . $request->periode_kpi;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        // $periode = $request->periode_kpi;
        $departement = $request->divisi;
        $pegawai = $request->pegawai;
        // dd($departement);

        DB::beginTransaction();
        try {

            if ($request->tipe == 'D') {
                DB::table('d_kpi')->whereMonth('k_periode', $periode->month)->whereYear('k_periode', $periode->year)->where('k_department', $departement)->delete();

                if ($kd_kpiD == true) {
                    DB::table('d_kpidt')->where('kd_kpi', $kd_kpiD)->delete();
                }

                $kid = DB::table('d_kpi')->max('k_id') + 1;
                DB::table('d_kpi')->insert([
                    'k_id'          => $kid,
                    'k_type'        => $request->tipe,
                    'k_periode'     => $periode,
                    'k_department'  => $request->divisi
                ]);

                $indicator = $request->kd_indikatorD;
                for ($i=0; $i < count($indicator); $i++) {
                    DB::table('d_kpidt')->insert([
                        'kd_kpi'        => $kid,
                        // 'kd_detailid'   => DB::table('d_kpidt')->where('kd_kpi', $indicator[$i])->max('kd_detailid') + 1,
                        'kd_detailid'   => $i+1,
                        'kd_indikator'  => $request->kd_indikatorD[$i],
                        'kd_weight'     => $request->bobotD[$i],
                        'kd_target'     => $request->targetD[$i],
                        'kd_result'     => $request->hasilD[$i],
                        'kd_point'      => $request->pointD[$i],
                        'kd_total'      => $request->nilaiD[$i]
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data'   => ''
                ]);
            } elseif ($request->tipe == 'P') {
                DB::table('d_kpi')->whereMonth('k_periode', $periode->month)->whereYear('k_periode', $periode->year)->where('k_employee', $pegawai)->delete();

                if ($kd_kpiP == true) {
                    DB::table('d_kpidt')->where('kd_kpi', $kd_kpiP)->delete();
                }

                $kid = DB::table('d_kpi')->max('k_id') + 1;

                DB::table('d_kpi')->insert([
                    'k_id'          => $kid,
                    'k_type'        => $request->tipe,
                    'k_periode'     => $periode,
                    'k_employee'    => $request->pegawai
                ]);

                $indicator = $request->kd_indikatorP;
                for ($i=0; $i < count($indicator); $i++) {
                    DB::table('d_kpidt')->insert([
                        'kd_kpi'        => $kid,
                        // 'kd_detailid'   => DB::table('d_kpidt')->where('kd_kpi', $indicator[$i])->max('kd_detailid') + 1,
                        'kd_detailid'   => $i+1,
                        'kd_indikator'  => $request->kd_indikatorP[$i],
                        'kd_weight'     => $request->bobotP[$i],
                        'kd_target'     => $request->targetP[$i],
                        'kd_result'     => $request->hasilP[$i],
                        'kd_point'      => $request->pointP[$i],
                        'kd_total'      => $request->nilaiP[$i]
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'data'   => ''
                ]);
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function getKelolaKpiPegawai(Request $request)
    {
        // dd('coba');
        $pegawai = $request->pegawai;
        // dd($pegawai);
        //
        $periode = "01-" . $request->periode;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->where('k_employee', $pegawai)
                    ->first();

        if ($periodes == true) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
                ->join('d_kpi', function($q){
                    $q->on('k_type', 'ke_type');
                    $q->on('k_employee', 'ke_employee');
                })
                ->leftjoin('d_kpidt', function($q){
                    $q->on('kd_kpi', 'd_kpi.k_id');
                    $q->on('kd_indikator', 'ke_kpi');
                })
                ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_employee', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total', 'k_periode')
                ->where('ke_employee', '=', $pegawai)
                ->whereMonth('k_periode', $periode->month)
                ->whereYear('k_periode', $periode->year)
                ->get();
                // dd($datas);
        } elseif ($periodes == false) {
            $datas = [];
        }
        // dd($datas);
        return Datatables::of($datas)
            ->addIndexColumn()
            ->make(true);

        // return response()->json([
        //     'pegawai' => $datas
        // ]);
    }

    public function getKelolaKpiDivisi(Request $request)
    {
        // dd('coba');
        $divisi = $request->divisi;
        // dd($pegawai);
        //
        $periode = "01-" . $request->periode;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->where('k_department', $divisi)
                    ->first();

        if ($periodes == true) {
            $datas = DB::table('d_kpiemp')
                ->join('m_kpi', 'm_kpi.k_id', 'ke_kpi')
                ->join('d_kpi', function($q){
                    $q->on('k_type', 'ke_type');
                    $q->on('k_department', 'ke_department');
                })
                ->leftjoin('d_kpidt', function($q){
                    $q->on('kd_kpi', 'd_kpi.k_id');
                    $q->on('kd_indikator', 'ke_kpi');
                })
                ->select('d_kpi.k_id', 'k_indicator', 'k_unit', 'k_isactive', 'ke_type', 'ke_department', 'ke_weight', 'ke_target', 'ke_kpi', 'kd_result', 'kd_point', 'kd_total', 'k_periode')
                ->where('ke_department', '=', $divisi)
                ->whereMonth('k_periode', $periode->month)
                ->whereYear('k_periode', $periode->year)
                ->get();
                // dd($datas);
        } elseif ($periodes == false) {
            $datas = [];
        }
        // dd($datas);
        return Datatables::of($datas)
            ->addIndexColumn()
            ->make(true);

        // return response()->json([
        //     'pegawai' => $datas
        // ]);
    }

    public function getKpiDashboardPegawai(Request $request)
    {
        $periode = "01-" . $request->periode_dashboard;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->first();

        if ($periodes == true) {
            $datas_pegawai = DB::table('d_kpidt')
            ->join('d_kpi', 'k_id', 'kd_kpi')
            ->join('m_employee', 'e_id', 'k_employee')
            ->select('kd_kpi', 'e_name',
                DB::RAW('ROUND(AVG(kd_point), 2) as sum_point_pegawai')
            )
            ->whereMonth('k_periode', $periode->month)
            ->whereYear('k_periode', $periode->year)
            ->groupBy('kd_kpi')
            ->get();
        } elseif ($periodes == false) {
            $datas_pegawai = [];
        }
        return Datatables::of($datas_pegawai)
            ->addIndexColumn()
            ->make(true);
    }

    public function getKpiDashboardDivisi(Request $request)
    {
        $periode = "01-" . $request->periode_dashboard;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $periodes = DB::table('d_kpi')
                    ->select('k_id', 'k_type', 'k_periode', 'k_employee', 'k_department')
                    ->whereMonth('k_periode', $periode->month)
                    ->whereYear('k_periode', $periode->year)
                    ->first();

        if ($periodes == true) {
            $datas_divisi = DB::table('d_kpidt')
            ->join('d_kpi', 'k_id', 'kd_kpi')
            ->join('m_divisi', 'm_id', 'k_department')
            ->select('kd_kpi', 'm_name',
                DB::RAW('ROUND(AVG(kd_point), 2) as sum_point_divisi')
            )
            ->whereMonth('k_periode', $periode->month)
            ->whereYear('k_periode', $periode->year)
            ->groupBy('kd_kpi')
            ->get();
        } elseif ($periodes == false) {
            $datas_divisi = [];
        }

        return Datatables::of($datas_divisi)
            ->addIndexColumn()
            ->make(true);
    }

    public function getDataCopy(Request $request)
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
}
