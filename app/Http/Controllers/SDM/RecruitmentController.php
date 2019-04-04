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
  /**
   * Return DataTable list for view.
   *
   * @param string 'All'/'Y' (status)
   * @return Yajra/DataTables
   */
  public function getList(Request $request, $st)
  {
    // change the date format request
    $date_fr = strtotime($request->date_from);
    $from    = date('Y-m-d', $date_fr);
    $date_to = strtotime($request->date_to);
    $to      = date('Y-m-d', $date_to);
    $edu     = $request->education;
    $status   = $request->status;

    if ($status == 'A') {
      $datas = DB::table('d_pelamar')
        ->whereBetween('p_created', [$from, $to])
        ->orderBy('p_name', 'asc')
        ->get();
    } elseif ($st == 'Y') {
      $datas = DB::table('d_pelamar')
        ->where('p_state', 'Y')
        ->whereBetween('p_created', [$from, $to])
        ->orderBy('p_name', 'asc')
        ->get();
    } else if($st == 'F'){
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
      
    }
    return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('tgl_apply', function($datas) {
        return '<td>'. Carbon::parse($datas->p_created)->format('D, d M Y') .'</td>';
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
                  <button class="btn btn-danger btn-disable-rekruitmen hint--top-left hint--error" type="button" aria-label="Nonaktifkan Data" onclick="nonActivate(\''.Crypt::encrypt($datas->p_id).'\')"><i class="fa fa-fw fa-times-circle"></i></button>
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

  public function kelola_rekruitment()
  {
    return view('sdm/prosesrekruitmen/kelola_rekruitment');
  }
}
