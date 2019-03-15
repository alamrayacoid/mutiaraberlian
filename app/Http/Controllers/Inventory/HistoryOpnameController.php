<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_opnameauth;
use Yajra\DataTables\DataTables;

use DB;

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
        return '<td>'. date('d-m-Y', strtotime($datas->oa_date)) .'</td>';
      })
      ->addColumn('name', function($datas) {
        return '<td>'. $datas->i_name .'</td>';
      })
      ->addColumn('status', function($datas) {
<<<<<<< HEAD
        return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">-</button></td>';
=======
        if ($datas->o_status == 'P') {
            return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">Pending</button></td>';
        } elseif ($datas->o_status == 'D') {
          return '<td><button class="btn btn-primary status-approve" style="pointer-events: none">Sudah Digunakan</button></td>';
        }
>>>>>>> 4e7e672de29fe6cb1e82c5f64af0081af3804a50
      })
      ->rawColumns(['date', 'name', 'status'])
      ->make(true);
    }
}
