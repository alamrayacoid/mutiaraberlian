<?php

namespace App\Http\Controllers\Produksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_productionorder;
use App\d_productionorderpayment;
use Yajra\DataTables\DataTables;

class PembayaranController extends Controller
{
    /**
    * Return DataTable list for view.
    *
    * @return Yajra/DataTables
    */
    public function getList(Request $request)
    {
      // dd($request->all());
      $datas = d_productionorderpayment::where('pop_productionorder', $request->po_id)
      ->orderBy('pop_productionorder', 'asc')
      ->orderBy('pop_termin', 'asc')
      ->get();
      return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('estimasi', function($datas) {
        return date('d-m-Y', strtotime($datas->pop_datetop));
      })
      ->addColumn('nominal', function($datas) {
        return '<td><span class="float-left">Rp </span>
          <span class="float-right">' . number_format($datas->pop_value, 2, ',', '.') . '</span>
          </td>';
      })
      ->addColumn('date', function($datas) {
        return date('d-m-Y', strtotime($datas->pop_date));
      })
      ->addColumn('minus', function($datas) {
        return 'xxxXxxx';
      })
      ->addColumn('status', function($datas) {
        if ($datas->pop_status == 'Y') {
          return '<div class="status-termin-lunas"><p>Lunas</p></div>';
        } elseif ($datas->pop_status == 'N') {
          return '<div class="status-termin-belum"><p>Belum</p></div>';
        }
      })
      ->addColumn('action', function($datas) {
        return '<td width="15%">
          <button class="btn btn-primary btn-modal" data-toggle="modal" type="button" onclick="Detail('. $datas->pop_productionorder .','. $datas->pop_termin .')">Detail</button>
          </td>';
      })
      ->rawColumns(['estimasi', 'nominal', 'date', 'status', 'action'])
      ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data['po'] = d_productionorder::with('getSupplier')
        ->with('getPOPayment')
        ->orderBy('po_nota', 'asc')
        ->get();
      return view('produksi/pembayaran/index', compact('data'));
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
    public function show($id, $termin)
    {
      // var_dump($termin);
      // dd($termin);
      $data = d_productionorder::where('po_id', $id)
        ->with('getPODt')
        ->with('getPODt.getItem')
        ->with('getPODt.getUnit')
        ->with(['getPOPayment' => function($query) use($termin) {
          $query->where('pop_termin',$termin);
        }])
        ->first();
      return $data;
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
