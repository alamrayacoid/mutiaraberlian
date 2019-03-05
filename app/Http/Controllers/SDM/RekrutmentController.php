<?php

namespace App\Http\Controllers\SDM;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use carbon\Carbon;
use Yajra\DataTables\DataTables;

class RekrutmentController extends Controller
{
  /**
   * Return DataTable list for view.
   *
   * @param string 'All'/'Y' (status)
   * @return Yajra/DataTables
   */
  public function getList(Request $request, $status)
  {
    // change the date format request
    $date_fr = strtotime($request->date_from);
    $from = date('Y-m-d', $date_fr);
    $date_to = strtotime($request->date_to);
    $to = date('Y-m-d', $date_to);

    if ($status == 'All') {
      $datas = DB::table('d_pelamar')
        ->whereBetween('p_created', [$from, $to])
        ->orderBy('p_name', 'asc')
        ->get();
    } elseif ($status == 'Y') {
      $datas = DB::table('d_pelamar')
        ->where('p_state', 'Y')
        ->whereBetween('p_created', [$from, $to])
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
        } elseif ($datas->p_state == 'P') {
          return '<td>Pending</td>';
        } elseif ($datas->p_state == 'N') {
          return '<td>Ditolak</td>';
        }
      })
      ->addColumn('approval', function($datas) {
        return '<td>---</td>';
      })
      ->addColumn('action', function($datas) {
        return '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-preview-rekruitmen hint--bottom-left hint--info" type="button" aria-label="Lihat Data"><i class="fa fa-search"></i></button>
            <button class="btn btn-warning btn-proses-rekruitmen hint--bottom-left hint--warning" type="button" aria-label="Proses Data"><i class="fa fa-file-powerpoint-o"></i></button>
            <button class="btn btn-danger btn-disable-rekruitmen hint--bottom-left hint--error" type="button" aria-label="Nonaktifkan Data"><i class="fa fa-times-circle"></i></button>
            </div>';
      })
      ->rawColumns(['tgl_apply', 'status', 'approval', 'action'])
      ->make(true);
  }
}
