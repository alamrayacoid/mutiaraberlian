<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use File;
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
    $applicant = DB::table('d_applicant')
      ->join('m_jabatan', 'a_position', 'j_id')
      ->where('a_isactive', '=', 'Y')
      ->get();
    return view('sdm/prosesrekruitmen/index', compact('jabatan', 'applicant'));
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
    } else if($edu != null && $status == null) {
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
    } else if($edu == null && $status != null) {
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
    } else{
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
    } catch (\Exception $e) {
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
    } catch (\Exception $e) {
        return view('errors.404');
    }

    $data = DB::table('d_pelamar')
      ->join('d_applicant', 'p_applicant', 'a_id')
      ->join('m_jabatan', 'd_applicant.a_position', 'j_id')
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
    } catch (\Exception $e) {
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
        DB::table('d_pelamar')->where('p_id', '=', $id)->update([
          'p_state'   => "Y",
          'p_stateapprove'  => $approve3
        ]);

        DB::table('d_pelamarlanjutan')->insert([
          'pl_id'         => $id,
          'pl_detailid'   => $dtId+1,
          'pl_approve'    => $approve3,
          'pl_isapproved' => "Y",
          'pl_date'       => $accDate
        ]);
      } else if ($approve3 != null && $status3 != null) {
        DB::table('d_pelamar')->where('p_id', '=', $id)->update([
          'p_state'        => $status3,
          'p_stateapprove' => $approve3
        ]);

        DB::table('d_pelamarlanjutan')->insert([
          'pl_id'         => $id,
          'pl_detailid'   => $dtId+1,
          'pl_approve'    => $approve3,
          'pl_isapproved' => $status3,
          'pl_date'       => $accDate
        ]);
      } else {
        DB::table('d_pelamar')->where('p_id', '=', $id)->update([
          'p_state'   => $status,
          'p_stateapprove'  => $statusApp
        ]);
        if ($date != null) {
          DB::table('d_pelamarlanjutan')->insert([
            'pl_id'         => $id,
            'pl_detailid'   => $dtId+1,
            'pl_approve'    => $statusApp,
            'pl_isapproved' => $status,
            'pl_date'       => $time
          ]);
        } else {
          DB::table('d_pelamarlanjutan')->insert([
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
    } catch (\Exception $e) {
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
        ->where('p_applicant', '=', $posisi)
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
        ->where('p_applicant', '=', $posisi)
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

  // Kelola Recruitment ===================================================================================
  public function getListLoker()
  {
    $loker = DB::table('d_applicant')
      ->join('m_jabatan', 'a_position', 'j_id')
      ->select('d_applicant.*', 'j_name')
      ->get();

    return Datatables::of($loker)
      ->addIndexColumn()
      ->addColumn('start', function($loker) {
        return '<td>'. Carbon::parse($loker->a_startdate)->format('d M Y') .'</td>';
      })
      ->addColumn('end', function($loker) {
        return '<td>'. Carbon::parse($loker->a_enddate)->format('d M Y') .'</td>';
      })
      ->addColumn('status', function($loker) {
        if ($loker->a_isactive == "Y") {
          return '<span class="btn-sm btn-block btn-disabled bg-success text-light text-center" disabled>Activated</span>';
        } else {
          return '<span class="btn-sm btn-block btn-disabled bg-danger text-light text-center" disabled>Non Activated</span>';
        }
        
      })
      ->addColumn('action', function($loker) {
        if ($loker->a_isactive == "Y") {
          return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-disabled" type="button" onclick="detailLoker(\''.Crypt::encrypt($loker->a_id).'\')" disabled><i class="fa fa-fw fa-check"></i></button>
                      <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Nonaktifkan" onclick="nonLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-times"></i></button>
                      <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Edit" onclick="editLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-pencil"></i></button>
                      <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-trash"></i></button>
                    </div>
                  </div>';
        } else {
          return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-success hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activateLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-check"></i></button>
                      <button class="btn btn-disabled" type="button" onclick="nonLoker(\''.Crypt::encrypt($loker->a_id).'\')" disabled><i class="fa fa-fw fa-times"></i></button>
                      <button class="btn btn-disabled" type="button" onclick="editLoker(\''.Crypt::encrypt($loker->a_id).'\')" disabled><i class="fa fa-fw fa-pencil"></i></button>
                      <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Hapus" onclick="deleteLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-trash"></i></button>
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
    
    $idLoker = DB::table('d_applicant')->max('a_id');
    DB::beginTransaction();
    try {
      DB::table('d_applicant')->insert([
        'a_id'        => $idLoker+1,
        'a_startdate' => $start_date,
        'a_enddate'   => $end_date,
        'a_position'  => $request->a_position
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

  public function activateLoker($id)
  {
    try {
        $id = Crypt::decrypt($id);
    } catch (\Exception $e) {
        return view('errors.404');
    }

    DB::beginTransaction();
    try {
      DB::table('d_applicant')
        ->where('a_id', $id)
        ->update([
          'a_isactive' => "Y"
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

  public function nonLoker($id)
  {
    try {
        $id = Crypt::decrypt($id);
    } catch (\Exception $e) {
        return view('errors.404');
    }

    DB::beginTransaction();
    try {
      DB::table('d_applicant')
        ->where('a_id', $id)
        ->update([
          'a_isactive' => "N"
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
      $query = DB::table('d_applicant')
        ->join('d_pelamar', function($p){
          $p->on('a_id', '=', 'p_applicant');
        })
        ->where('a_id', '=', $id)
        ->where('a_isactive', '=', "Y")
        ->where('p_applicant', '=', $id)
        ->count();

      dd($query);

      if ($query > 0) {
        DB::commit();
        return response()->json([
          'status' => 'warning'
        ]);
      } else {
        DB::table('d_applicant')
          ->where('a_id', $id)
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

    $data1 = DB::table('d_applicant')
      ->join('m_jabatan', 'a_position', 'j_id')
      ->select('d_applicant.*', DB::raw('date_format(a_startdate, "%d-%m-%Y") as start_date'), DB::raw('date_format(a_enddate, "%d-%m-%Y") as end_date'), 'm_jabatan.*')
      ->where('a_id', $id)
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
      DB::table('d_applicant')
        ->where('a_id', $id)
        ->update([
          'a_startdate' => $start_date,
          'a_enddate' => $end_date,
          'a_position' => $request->a_position
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
