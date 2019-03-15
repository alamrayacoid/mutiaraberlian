<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_opnameauth;
use Yajra\DataTables\DataTables;

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
      $datas = d_opnameauth::orderBy('oa_date', 'asc')
        ->with('getItem')
        ->whereBetween('oa_date', [$from, $to])
        ->get();

      return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('date', function($datas) {
        return '<td>'. date('d-m-Y', strtotime($datas->oa_date)) .'</td>';
      })
      ->addColumn('name', function($datas) {
        return '<td>'. $datas->getItem['i_name'] .'</td>';
      })
      ->addColumn('status', function($datas) {
        return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">-</button></td>';
      })
      ->rawColumns(['date', 'name', 'status'])
      ->make(true);
    }
}
