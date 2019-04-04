<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
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
    return view('sdm/prosesrekruitmen/index', compact('jabatan'));
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
        ->orderBy('p_name', 'asc')
        ->get();
    } else if($edu != null && $status == null) {
      $datas = DB::table('d_pelamar')
        ->whereBetween('p_created', [$from, $to])
        ->where('p_education', '=', $edu)
        ->orderBy('p_name', 'asc')
        ->get();
    } else if($edu == null && $status != null) {
      $datas = DB::table('d_pelamar')
        ->whereBetween('p_created', [$from, $to])
        ->where('p_stateapprove', '=', $status)
        ->orderBy('p_name', 'asc')
        ->get();
    } else{
      $datas = DB::table('d_pelamar')
        ->whereBetween('p_created', [$from, $to])
        ->where('p_education', '=', $edu)
        ->where('p_stateapprove', '=', $status)
        ->orderBy('p_name', 'asc')
        ->get();
    }
    return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('tgl_apply', function($datas) {
        return '<td>'. Carbon::parse($datas->p_created)->format('d M Y') .'</td>';
      })
      ->addColumn('status', function($datas) {
        if ($datas->p_state == 'Y') {
          return '<td>Diterima</td>';
        } else if ($datas->p_state == 'P') {
          return '<td>Pending</td>';
        } else {
          return '<td>Ditolak</td>';
        }
      })
      ->addColumn('approval', function($datas) {
        return '<td>---</td>';
      })
      ->addColumn('action', function($datas) {
        return '<div class="btn-group btn-group-sm">
                  <button class="btn btn-primary btn-preview-rekruitmen hint--top-left hint--info" type="button" aria-label="Detail Pelamar" onclick="detail(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file"></i></button>
                  <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Proses Data" onclick="proses(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file-powerpoint-o"></i></button>
                  <button class="btn btn-danger btn-disable-rekruitmen hint--top-left hint--error" type="button" aria-label="Nonaktifkan" onclick="nonActivate(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-times-circle"></i></button>
                </div>';
      })
      ->rawColumns(['tgl_apply', 'status', 'approval', 'action'])
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

    return view('sdm/prosesrekruitmen/preview', compact('data'));
  }

  public function proses($id)
  {
    try {
        $id = Crypt::decrypt($id);
    } catch (\Exception $e) {
        return view('errors.404');
    }

    $data = DB::table('d_pelamar')
      ->where('p_id', $id)
      ->first();

    return view('sdm/prosesrekruitmen/process', compact('data'));
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
    $status  = $request->status;

    $datas = DB::table('d_pelamar')
      ->where('p_state', 'Y')
      ->whereBetween('p_created', [$from, $to])
      ->orderBy('p_name', 'asc')
      ->get();
    return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('tgl_apply', function($datas) {
        return '<td>'. Carbon::parse($datas->p_created)->format('d M Y') .'</td>';
      })
      ->addColumn('status', function($datas) {
        if ($datas->p_state == 'Y') {
          return '<td>Diterima</td>';
        } else if ($datas->p_state == 'P') {
          return '<td>Pending</td>';
        } else {
          return '<td>Ditolak</td>';
        }
      })
      ->addColumn('approval', function($datas) {
        return '<td>---</td>';
      })
      ->addColumn('action', function($datas) {
        return '<div class="btn-group btn-group-sm">
                  <button class="btn btn-primary btn-preview-rekruitmen hint--top-left hint--info" type="button" aria-label="Detail Pelamar" onclick="detail(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file"></i></button>
                  <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Proses Data" onclick="proses(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-file-powerpoint-o"></i></button>
                  <button class="btn btn-danger btn-disable-rekruitmen hint--top-left hint--error" type="button" aria-label="Nonaktifkan" onclick="nonActivate(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-times-circle"></i></button>
                </div>';
      })
      ->rawColumns(['tgl_apply', 'status', 'approval', 'action'])
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
                      <button class="btn btn-danger btn-disable-rekruitmen hint--top-left hint--error" type="button" aria-label="Nonaktifkan" onclick="nonLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-times"></i></button>
                      <button class="btn btn-warning btn-proses-rekruitmen hint--top-left hint--warning" type="button" aria-label="Edit" onclick="editLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-pencil"></i></button>
                    </div>
                  </div>';
        } else {
          return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-success hint--top-left hint--success" type="button" aria-label="Aktifkan" onclick="activateLoker(\''.Crypt::encrypt($loker->a_id).'\')"><i class="fa fa-fw fa-check"></i></button>
                      <button class="btn btn-disabled" type="button" onclick="nonLoker(\''.Crypt::encrypt($loker->a_id).'\')" disabled><i class="fa fa-fw fa-times"></i></button>
                      <button class="btn btn-disabled" type="button" onclick="editLoker(\''.Crypt::encrypt($loker->a_id).'\')" disabled><i class="fa fa-fw fa-pencil"></i></button>
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
    $data2 = DB::table('m_jabatan')->select('m_jabatan.*')->where('j_id', '!=', $data1->a_position)->get();

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
