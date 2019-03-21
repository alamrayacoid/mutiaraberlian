<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_opnameauth;
use Yajra\DataTables\DataTables;

use DB;

use Carbon\Carbon;

class HistoryOpnameController extends Controller
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
      $datas = DB::table('d_opname')->orderBy('o_date', 'asc')
        ->join('m_item', 'i_id', '=', 'o_item')
        ->whereBetween('o_date', [$from, $to])
        ->get();

      return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('date', function($datas) {
        return '<td>'. Carbon::parse($datas->o_date)->format('d/m/Y') .'</td>';
      })
      ->addColumn('name', function($datas) {
        return '<td>'. $datas->i_name .'</td>';
      })
      ->addColumn('status', function($datas) {
        if ($datas->o_status == 'P') {
            return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">Pending</button></td>';
        } elseif ($datas->o_status == 'D') {
          return '<td><button class="btn btn-primary status-approve" style="pointer-events: none">Sudah Digunakan</button></td>';
        }
      })
      ->rawColumns(['date', 'name', 'status'])
      ->make(true);
    }
}
