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
      ->rawColumns(['name', 'status'])
      ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
