<?php

namespace App\Http\Controllers\SDM;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;

use DB;
use File;
use App\d_sdmsubmission;
use Auth;
use Currency;
use Response;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;

class RecruitmentController extends Controller
{
    public function index()
    {
        $jabatan = DB::table('m_jabatan')
            ->select('m_jabatan.*')
            ->where('j_id', '<', 7)
            ->get();
        $applicant = DB::table('d_sdmsubmission')
            ->join('m_jabatan', 'ss_position', 'j_id')
            ->where('ss_isactive', '=', 'Y')
            ->get();
        $divisi = DB::table('m_divisi')
            ->select('m_divisi.*')
            ->select('m_id','m_name')
            ->get();
        return view('sdm/prosesrekruitmen/index', compact('jabatan', 'applicant','divisi'));
    }

    // Recruitment ============================================================================================
    public function getList(Request $request)
    {
        // change the date format request
        $date_fr = strtotime($request->date_from);
        $from    = date('Y-m-d', $date_fr);
        $date_to = strtotime($request->date_to);
        $to      = date('Y-m-d', $date_to);
        $edu     = $request->education;
        $status  = $request->status;

        if ($edu == null && $status == null) {
            $datas = DB::table('d_pelamar')
                ->whereBetween('p_created', [$from, $to])
                ->leftJoin('d_pelamarlanjutan', function($a){
                    $a->on('p_id', '=', 'pl_id');
                    $a->on('pl_approve', '=', 'p_stateapprove');
                    $a->on('pl_isapproved', '=', 'p_state');
                })
                ->groupBy("p_id")
                ->orderBy('p_name', 'asc')
                ->get();
        }
        else if($edu != null && $status == null) {
            $datas = DB::table('d_pelamar')
                ->leftJoin('d_pelamarlanjutan', function($a){
                    $a->on('p_id', '=', 'pl_id');
                    $a->on('pl_isapproved', '=', 'p_state');
                    $a->on('pl_approve', '=', 'p_stateapprove');
                })
                ->whereBetween('p_created', [$from, $to])
                ->where('p_education', '=', $edu)
                ->groupBy("p_id")
                ->orderBy('p_name', 'asc')
                ->get();
        }
        else if($edu == null && $status != null) {
            $datas = DB::table('d_pelamar')
                ->leftJoin('d_pelamarlanjutan', function($a){
                    $a->on('p_id', '=', 'pl_id');
                    $a->on('pl_isapproved', '=', 'p_state');
                    $a->on('pl_approve', '=', 'p_stateapprove');
                })
                ->whereBetween('p_created', [$from, $to])
                ->where('p_stateapprove', '=', $status)
                ->groupBy("p_id")
                ->orderBy('p_name', 'asc')
                ->get();
        }
        else {
            $datas = DB::table('d_pelamar')
                ->leftJoin('d_pelamarlanjutan', function($a){
                    $a->on('p_id', '=', 'pl_id');
                    $a->on('pl_isapproved', '=', 'p_state');
                    $a->on('pl_approve', '=', 'p_stateapprove');
                })
                ->whereBetween('p_created', [$from, $to])
                ->where('p_education', '=', $edu)
                ->where('p_stateapprove', '=', $status)
                ->groupBy("p_id")
                ->orderBy('p_name', 'asc')
                ->get();
        }
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('tgl_apply', function($datas) {
                return '<td>'. Carbon::parse($datas->p_created)->format('d M Y') .'</td>';
            })
            ->addColumn('status', function($datas) {
                if ($datas->p_state == 'Y' && $datas->p_stateapprove == 1) {
                    return '<div class="text-success">Test Interview</div>';
                } else if ($datas->p_state == 'P' && $datas->p_stateapprove == 1) {
                    return '<div class="text-primary">Pending Tahap 1</div>';
                } else if ($datas->p_state == 'N' && $datas->p_stateapprove == 1) {
                    return '<div class="text-danger">Ditolak Administrasi</div>';
                } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 2) {
                    return '<div class="text-success">Test Presentasi</div>';
                } else if ($datas->p_state == 'P' && $datas->p_stateapprove == 2) {
                    return '<div class="text-primary">Pending Tahap 2</div>';
                } else if ($datas->p_state == 'N' && $datas->p_stateapprove == 2) {
                    return '<div class="text-danger">Ditolak Administrasi</div>';
                } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 3) {
                    return '<div class="text-success">Diterima Kerja</div>';
                }else if ($datas->p_state == 'N' && $datas->p_stateapprove == 3) {
                    return '<div class="text-danger">Ditolak Final</div>';
                }
            })
            ->addColumn('tanggal', function($datas) {
                if ($datas->p_state == 'Y' && $datas->p_stateapprove == 1) {
                    return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
                } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 2) {
                    return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
                } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 3) {
                    return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
                } else {
                    return '<div class="text-danger text-center">-</div>';
                }
            })
            ->addColumn('action', function($datas) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-primary btn-preview-rekruitmen hint--top-left hint--info" type="button" aria-label="Detail Pelamar" onclick="detail(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file-text"></i></button>
                    <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Proses Pelamar" onclick="proses(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file-powerpoint-o"></i></button>
                    <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Hapus Data" onclick="deletePelamar(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['tgl_apply', 'status', 'tanggal', 'action'])
            ->make(true);
    }

    public function detail($id)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (\Exception $e) {
            return view('errors.404');
        }

        $data = DB::table('d_pelamar')
            ->where('p_id', $id)
            ->first();

        return view('sdm/prosesrekruitmen/rekrutmen/preview', compact('data'));
    }

    public function proses($id)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (\Exception $e) {
            return view('errors.404');
        }

        $data = DB::table('d_pelamar')
            ->join('d_sdmsubmission', 'p_sdmsubmission', 'ss_id')
            ->join('m_jabatan', 'd_sdmsubmission.ss_position', 'j_id')
            ->where('p_id', $id)
            ->first();

        $dateApp1 = DB::table('d_pelamarlanjutan')
            ->select(DB::raw('date_format(pl_date, "%d, %M %Y") as pl_date'))
            ->where('pl_id', $id)
            ->where('pl_approve', '=', 1)
            ->where('pl_isapproved', '=', 'Y')
            ->first();

        $dateApp2 = DB::table('d_pelamarlanjutan')
            ->select(DB::raw('date_format(pl_date, "%d, %M %Y") as pl_date'))
            ->where('pl_id', $id)
            ->where('pl_approve', '=', 2)
            ->where('pl_isapproved', '=', 'Y')
            ->first();

        return view('sdm/prosesrekruitmen/rekrutmen/process', compact('data', 'dateApp1', 'dateApp2'));
    }

    public function addProses($id, Request $request)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (\Exception $e) {
            return view('errors.404');
        }

        $statusApp = $request->p_stateapprove;
        $status    = $request->p_state;
        $approve3  = $request->approve3;
        $status3   = $request->p_state3;
        $date      = $request->p_date;
        $date_fr   = strtotime($date);
        $time      = date('Y-m-d', $date_fr);
        $accDate   = Carbon::now('Asia/Jakarta');

        $dtId = DB::table('d_pelamarlanjutan')->where('pl_id', '=', $id)->max('pl_detailid');
        DB::beginTransaction();
        try {

            if ($approve3 != null && $status3 == null) {
                DB::table('d_pelamar')
                    ->where('p_id', '=', $id)
                    ->update([
                        'p_state'   => "Y",
                        'p_stateapprove'  => $approve3
                    ]);

                DB::table('d_pelamarlanjutan')
                    ->insert([
                        'pl_id'         => $id,
                        'pl_detailid'   => $dtId+1,
                        'pl_approve'    => $approve3,
                        'pl_isapproved' => "Y",
                        'pl_date'       => $accDate
                    ]);
            }
            else if ($approve3 != null && $status3 != null) {
                DB::table('d_pelamar')
                    ->where('p_id', '=', $id)
                    ->update([
                        'p_state'        => $status3,
                        'p_stateapprove' => $approve3
                    ]);

                DB::table('d_pelamarlanjutan')
                    ->insert([
                        'pl_id'         => $id,
                        'pl_detailid'   => $dtId+1,
                        'pl_approve'    => $approve3,
                        'pl_isapproved' => $status3,
                        'pl_date'       => $accDate
                    ]);
            }
            else {
                DB::table('d_pelamar')
                    ->where('p_id', '=', $id)
                    ->update([
                        'p_state'   => $status,
                        'p_stateapprove'  => $statusApp
                    ]);
                if ($date != null) {
                    DB::table('d_pelamarlanjutan')
                        ->insert([
                            'pl_id'         => $id,
                            'pl_detailid'   => $dtId+1,
                            'pl_approve'    => $statusApp,
                            'pl_isapproved' => $status,
                            'pl_date'       => $time
                        ]);
                }
                else {
                    DB::table('d_pelamarlanjutan')
                        ->insert([
                            'pl_id'         => $id,
                            'pl_detailid'   => $dtId+1,
                            'pl_approve'    => $statusApp,
                            'pl_isapproved' => $status
                        ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function deletePelamar($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            $destroy = DB::table('d_pelamar')->where('p_id', $id)->first();
            //dd($destroy);
            if ($destroy) {
                file::delete(storage_path('/uploads/recruitment/' . $destroy->p_imgktp));
                file::delete(storage_path('/uploads/recruitment/' . $destroy->p_imgfoto));
                file::delete(storage_path('/uploads/recruitment/' . $destroy->img_ijazah));
                file::delete(storage_path('/uploads/recruitment/' . $destroy->img_other));
            }

            DB::table('d_pelamar')->where('p_id', $id)->delete();

            DB::table('d_pelamarlanjutan')
            ->where('pl_id', $id)
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
    // End Code =============================================================================================

    // Recruitment Diterima =================================================================================
    public function getListTerima(Request $request)
    {
        $date_fr = strtotime($request->date_from);
        $from    = date('Y-m-d', $date_fr);
        $date_to = strtotime($request->date_to);
        $to      = date('Y-m-d', $date_to);
        $edu     = $request->education;
        $posisi  = $request->position;

        if ($edu != null && $posisi != null) {
            $datas = DB::table('d_pelamar')
            ->leftJoin('d_pelamarlanjutan', function($a){
                $a->on('p_id', '=', 'pl_id');
                $a->on('pl_isapproved', '=', 'p_state');
                $a->on('pl_approve', '=', 'p_stateapprove');
            })
            ->where('p_state', 'Y')
            ->whereBetween('p_created', [$from, $to])
            ->where('p_education', '=', $edu)
            ->where('p_sdmsubmission', '=', $posisi)
            ->groupBy("p_id")
            ->orderBy('p_name', 'asc')
            ->get();
        } else if ($edu != null && $posisi == null) {
            $datas = DB::table('d_pelamar')
            ->leftJoin('d_pelamarlanjutan', function($a){
                $a->on('p_id', '=', 'pl_id');
                $a->on('pl_isapproved', '=', 'p_state');
                $a->on('pl_approve', '=', 'p_stateapprove');
            })
            ->where('p_state', 'Y')
            ->whereBetween('p_created', [$from, $to])
            ->where('p_education', '=', $edu)
            ->groupBy("p_id")
            ->orderBy('p_name', 'asc')
            ->get();
        } else if ($edu == null && $posisi != null) {
            $datas = DB::table('d_pelamar')
            ->leftJoin('d_pelamarlanjutan', function($a){
                $a->on('p_id', '=', 'pl_id');
                $a->on('pl_isapproved', '=', 'p_state');
                $a->on('pl_approve', '=', 'p_stateapprove');
            })
            ->where('p_state', 'Y')
            ->whereBetween('p_created', [$from, $to])
            ->where('p_sdmsubmission', '=', $posisi)
            ->groupBy("p_id")
            ->orderBy('p_name', 'asc')
            ->get();
        } else {
            $datas = DB::table('d_pelamar')
            ->leftJoin('d_pelamarlanjutan', function($a){
                $a->on('p_id', '=', 'pl_id');
                $a->on('pl_isapproved', '=', 'p_state');
                $a->on('pl_approve', '=', 'p_stateapprove');
            })
            ->where('p_state', 'Y')
            ->whereBetween('p_created', [$from, $to])
            ->groupBy("p_id")
            ->orderBy('p_name', 'asc')
            ->get();
        }

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('tgl_apply', function($datas) {
            return '<td>'. Carbon::parse($datas->p_created)->format('d M Y') .'</td>';
        })
        ->addColumn('status', function($datas) {
            if ($datas->p_state == 'Y' && $datas->p_stateapprove == 1) {
                return '<div class="text-success">Test Interview</div>';
            } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 2) {
                return '<div class="text-success">Test Presentasi</div>';
            } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 3) {
                return '<div class="text-success">Diterima Kerja</div>';
            }
        })
        ->addColumn('approval', function($datas) {
            if ($datas->p_state == 'Y' && $datas->p_stateapprove == 1) {
                return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
            } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 2) {
                return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
            } else if ($datas->p_state == 'Y' && $datas->p_stateapprove == 3) {
                return '<div class="text-center">'.Carbon::parse($datas->pl_date)->format('d M Y').'</div>';
            }
        })
        ->rawColumns(['tgl_apply', 'status', 'approval'])
        ->make(true);
    }
    // End Code =============================================================================================

    // Penggajuan SDM =======================================================================================
    public function getListPengajuan()
    {
        $pengajuan = DB::table('d_sdmsubmission')
            ->join('m_divisi', 'ss_department','m_id')
            ->join('m_jabatan', 'ss_position', 'j_id')
            ->select('d_sdmsubmission.*', 'j_name', 'm_name')
            ->get();

        return Datatables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($pengajuan) {
                return '<td>'. Carbon::parse($pengajuan->ss_date)->format('d M Y') .'</td>';
            })
            ->addColumn('status', function($pengajuan) {
                if ($pengajuan->ss_isapproved == "P") {
                    return '<span class="btn-sm btn-block btn-disabled bg-danger text-light text-center" disabled>Pending</span>';
                } else if ($pengajuan->ss_isapproved == "Y") {
                    return '<span class="btn-sm btn-block btn-disabled bg-success text-light text-center" disabled>Disetujui</span>';
                } else if ($pengajuan->ss_isapproved == "N") {
                    return '<span class="btn-sm btn-block btn-disabled bg-danger text-light text-center" disabled>Ditolak</span>';
                }

            })
            ->addColumn('action', function($pengajuan) {
                if ($pengajuan->ss_isactive == "Y") {
                    return '<div class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-disabled" type="button" onclick="detailLoker(\''.Crypt::encrypt($pengajuan->ss_id).'\')" disabled><i class="fa fa-fw fa-check"></i></button>
                            <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Nonaktifkan" onclick="nonPengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-fw fa-times"></i></button>
                            <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Edit" onclick="editPengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-fw fa-pencil"></i></button>
                            <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deletePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-fw fa-trash"></i></button>
                        </div>
                    </div>';
                } else {
                    return '<div class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-success hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activatePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-fw fa-check"></i></button>
                            <button class="btn btn-disabled" type="button" onclick="nonPengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')" disabled><i class="fa fa-fw fa-times"></i></button>
                            <button class="btn btn-disabled" type="button" onclick="editPengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')" disabled><i class="fa fa-fw fa-pencil"></i></button>
                            <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deletePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-fw fa-trash"></i></button>
                        </div>
                    </div>';
                }
            })
            ->rawColumns(['tanggal', 'status', 'action'])
            ->make(true);
    }

    public function simpanPengajuan(Request $request)
    {
        $date     = Carbon::now('Asia/Jakarta');
        $ids      = DB::table('d_sdmsubmission')->max('ss_id');
        $id       = $ids + 1;
        $reff     = str_pad($id,3,"0",STR_PAD_LEFT); // 001
        $reff2    = 'PK-'.$reff.'/'.$date->format('d/m/Y');

        $idSdmSubmission = DB::table('d_sdmsubmission')->max('ss_id');
        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')->insert([
                'ss_id'           => $idSdmSubmission+1,
                'ss_date'         => $date,
                'ss_reff'         => $reff2,
                'ss_department'   => $request->ss_department,
                'ss_position'     => $request->ss_position,
                'ss_qtyneed'      => $request->ss_qtyneed
            ]);

            pushOtorisasi::otorisasiup('Otorisasi SDM');


            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }

    }

    public function activatePengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get detail submission
            $sdm = d_sdmsubmission::where('ss_id', $id)->first();
            if ($sdm->ss_isapproved == 'P') {
                pushOtorisasi::otorisasiup('Otorisasi SDM');
            }

            DB::table('d_sdmsubmission')
                ->where('ss_id', $id)
                ->update([
                    'ss_isactive' => "Y"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function nonPengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get detail submission
            $sdm = d_sdmsubmission::where('ss_id', $id)->first();
            if ($sdm->ss_isapproved == 'P') {
                pushOtorisasi::otorisasiup('Otorisasi SDM');
            }

            DB::table('d_sdmsubmission')
                ->where('ss_id', $id)
                ->update([
                    'ss_isactive' => "N"
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deletePengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get detail submission
            $sdm = d_sdmsubmission::where('ss_id', $id)->first();

            DB::table('d_sdmsubmission')
            ->where('ss_id', $id)
            ->delete();
            
            if ($sdm->ss_isapproved == 'P') {
                pushOtorisasi::otorisasiup('Otorisasi SDM');
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editPengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $data1 = DB::table('d_sdmsubmission')
            ->join('m_jabatan', 'ss_position', 'j_id')
            ->join('m_divisi', 'ss_department', 'm_id')
            ->select('d_sdmsubmission.*', DB::raw('date_format(ss_date, "%d-%m-%Y") as date'), 'm_jabatan.*', 'm_divisi.*')
            ->where('ss_id', $id)
            ->first();
        $data2 = DB::table('m_jabatan')
        ->select('m_jabatan.*')
        ->where('j_id', '!=', $data1->ss_position)
        ->where('j_id', '<', 7)
        ->get();
        $data3 = DB::table('m_divisi')
        ->select('m_divisi.*')
        ->where('m_id', '!=', $data1->ss_department)
        ->get();

        return Response::json(array(
            'success' => true,
            'data1'   => $data1,
            'data2'   => $data2,
            'data3'   => $data3
        ));
    }

    public function updatePengajuan(Request $request)
    {
        $date     = Carbon::now('Asia/Jakarta');
        $ids      = DB::table('d_sdmsubmission')->max('ss_id');
        $id       = $ids+1;
        $reff     = str_pad($id,3,"0",STR_PAD_LEFT); // 001
        $reff2    = 'PK-'.$reff.'/'.$date->format('d/m/Y');
        $id       = $request->id_pengajuan;

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
            ->where('ss_id', $id)
            ->update([
                'ss_date'         => $date,
                'ss_reff'         => $reff2,
                'ss_department'   => $request->ss_department,
                'ss_position'     => $request->ss_position,
                'ss_qtyneed'      => $request->ss_qtyneed
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

    // End Code =============================================================================================

    // Publikasi Recruitment ===================================================================================
    public function getListPublish()
    {
        $publish = DB::table('d_sdmsubmission')
        ->join('m_divisi', 'ss_department','m_id')
        ->join('m_jabatan', 'ss_position', 'j_id')
        ->where('ss_isapproved', '=', 'Y')
        ->get();

        return Datatables::of($publish)
        ->addIndexColumn()
        ->addColumn('start', function($publish) {
            return '<td>'. Carbon::parse($publish->ss_startdate)->format('d M Y') .'</td>';
        })
        ->addColumn('end', function($publish) {
            return '<td>'. Carbon::parse($publish->ss_enddate)->format('d M Y') .'</td>';
        })
        ->addColumn('action', function($publish) {
            if ($publish->ss_publish == "Y") {
                return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Tolak Publikasi" onclick="rejectPublish(\''.Crypt::encrypt($publish->ss_id).'\')"><i class="fa fa-fw fa-times"></i></button>
                    </div>
                </div>';
            } else {
                return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-success hint--top-left hint--success" type="button" aria-label="Terima Publikasi" onclick="approvePublish(\''.Crypt::encrypt($publish->ss_id).'\')"><i class="fa fa-share-square" aria-hidden="true"></i></button>
                    </div>
                </div>';
            }
        })
        ->rawColumns(['start', 'end', 'status', 'action'])
        ->make(true);
    }

    public function simpanLoker(Request $request)
    {
        $date1      = strtotime($request->a_startdate);
        $start_date = date('Y-m-d', $date1);
        $date2      = strtotime($request->a_enddate);
        $end_date   = date('Y-m-d', $date2);

        $idLoker = DB::table('d_sdmsubmission')->max('ss_id');
        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')->insert([
            'ss_id'        => $idLoker+1,
            'ss_startdate' => $start_date,
            'ss_enddate'   => $end_date,
            'ss_position'  => $request->a_position
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

    public function approvePublish($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
            ->where('ss_id', $id)
            ->update([
            'ss_publish' => "Y"
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

    public function rejectPublish($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
            ->where('ss_id', $id)
            ->update([
            'ss_publish' => "N"
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

    public function deleteLoker($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            $query1 = DB::table('d_sdmsubmission')
            ->join('d_pelamar', function($p){
                $p->on('ss_id', '=', 'p_sdmsubmission');
            })
            ->where('p_sdmsubmission', '=', $id)
            ->count();

            $query2 = DB::table('d_sdmsubmission')
            ->where('ss_id', '=', $id)
            ->where('ss_isactive', '=', "Y")
            ->count();

            if ($query1 > 0) {
                DB::commit();
                return response()->json([
                'status' => 'warning'
                ]);
            } else if ($query2 > 0) {
                DB::commit();
                return response()->json([
                'status' => 'warning'
                ]);
            } else {
                // get detail submission
                $sdm = d_sdmsubmission::where('ss_id', $id)->first();
                if ($sdm->ss_isapproved == 'P') {
                    pushOtorisasi::otorisasiup('Otorisasi SDM');
                }

                DB::table('d_sdmsubmission')
                ->where('ss_id', $id)
                ->delete();
            }

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

    public function editLoker($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $data1 = DB::table('d_sdmsubmission')
        ->join('m_jabatan', 'ss_position', 'j_id')
        ->select('d_sdmsubmission.*', DB::raw('date_format(ss_startdate, "%d-%m-%Y") as start_date'), DB::raw('date_format(ss_enddate, "%d-%m-%Y") as end_date'), 'm_jabatan.*')
        ->where('ss_id', $id)
        ->first();
        $data2 = DB::table('m_jabatan')
        ->select('m_jabatan.*')
        ->where('j_id', '!=', $data1->a_position)
        ->where('j_id', '<', 7)
        ->get();

        return Response::json(array(
        'success' => true,
        'data1'   => $data1,
        'data2'   => $data2
        ));
    }

    public function updateLoker(Request $request)
    {
        $date1      = strtotime($request->a_startdate);
        $start_date = date('Y-m-d', $date1);
        $date2      = strtotime($request->a_enddate);
        $end_date   = date('Y-m-d', $date2);
        $id = $request->id_loker;

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
            ->where('ss_id', $id)
            ->update([
                'ss_startdate' => $start_date,
                'ss_enddate' => $end_date,
                'ss_position' => $request->a_position
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
    // End Code =============================================================================================
}
