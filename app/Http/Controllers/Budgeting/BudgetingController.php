<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_budgeting;
use App\Model\keuangan\dk_hierarki_satu as level_1;
use DB;

class BudgetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('budgeting/index');
    }

    public function getAkunPendapatan(Request $request)
    {
        $month = Carbon::now();

        $d1 = $periode = 0;

        $d1 = explode('/', $request->lap_tanggal_awal)[1].'-'.explode('/', $request->lap_tanggal_awal)[0].'-01';
        $periode = date('F Y', strtotime($d1));

        $data = level_1::where('hs_id', '>', '3')
                    ->with([
                        'subclass' => function($query) use ($d1){
                            $query->select('hs_id', 'hs_nama', 'hs_level_1')
                                    ->orderBy('hs_flag')
                                    ->with([
                                        'level2' => function($query) use ($d1){
                                            $query->select('hd_id', 'hd_nama', 'hd_subclass', 'hd_nomor')
                                                ->with([
                                                    'akun' => function($query) use ($d1){
                                                        $query->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                                                                ->where('as_periode', $d1)
                                                                ->select(
                                                                    'ak_id',
                                                                    'ak_nomor',
                                                                    'ak_kelompok',
                                                                    'ak_nama',
                                                                    'ak_posisi',
                                                                    DB::raw('coalesce((as_saldo_akhir - as_saldo_awal), 2) as saldo_akhir')
                                                                );
                                                    }
                                                ]);
                                        }
                                    ]);
                        }
                    ])
                    ->select('hs_id', 'hs_nama')
                    ->get();
        dd('x', $data);
    }

    public function getAkunBeban(Request $request)
    {
        // code...
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('budgeting/create');
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
