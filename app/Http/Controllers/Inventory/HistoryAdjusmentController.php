<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_opnameauth;
use Yajra\DataTables\DataTables;

use DB;

use Carbon\Carbon;

class HistoryAdjusmentController extends Controller
{
    /**
     * Return list of opanme history.
     *
     * @return Yajra/DataTables
     */
    public function getList(Request $request)
    {
      $from = date("Y-m-d", strtotime($request->date_from));
      $to = date("Y-m-d", strtotime($request->date_to));
      $datas = DB::table('d_adjusment')->orderBy('a_date', 'asc')
        ->join('m_item', 'i_id', '=', 'a_item')
        ->whereBetween('a_date', [$from, $to])
        ->get();

      return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('date', function($datas) {
        return '<td>'. Carbon::parse($datas->a_date)->format('d/m/Y') .'</td>';
      })
      ->addColumn('name', function($datas) {
        return '<td>'. $datas->i_name .'</td>';
      })
      ->addColumn('status', function($datas) {
          return '<td><button class="btn btn-primary status-approve" style="pointer-events: none">Sudah Digunakan</button></td>';
      })
      ->rawColumns(['date', 'name', 'status'])
      ->make(true);
    }
}
