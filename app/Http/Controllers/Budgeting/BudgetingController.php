<?php

namespace App\Http\Controllers\Budgeting;

use App\Helper\keuangan\laporan\laporan as laporan;
use App\Model\Keuangan\dk_hierarki_satu as level_1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;

use App\d_budgeting;
use App\Model\keuangan\dk_hierarki_satu as level_1;
use DB;
use Carbon\Carbon;

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
        // get first day of selected month
        $month = Carbon::createFromFormat('m-Y', $request->periode)->firstOfMonth()->format('Y-m-d');
        $d1 = $month;

        $budgeting = d_budgeting::where('b_date', $d1)->get();
        $data = level_1::where('hs_id', '>', '3')
        ->with([
            'subclass' => function($query) {
                $query->select('hs_id', 'hs_nama', 'hs_level_1')
                ->orderBy('hs_flag')
                ->with([
                    'level2' => function($query) {
                        $query->select('hd_id', 'hd_nama', 'hd_subclass', 'hd_nomor')
                        ->with([
                            'akun' => function($query) {
                                $query->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                                ->groupBy('ak_id')
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

        return response()->json([
            'data' => $data,
            'budgeting' => $budgeting
        ]);
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
        DB::beginTransaction();
        try {
            $periode = Carbon::createFromFormat('m-Y', $request->periode)->firstOfMonth()->format('Y-m-d');
            $budget = d_budgeting::where('b_date', $periode)->get();
            $listPendAkun = $request->pendAkun;
            $listPendValue = $request->pendValue;

            $dataPendapatan = [];
            if (count($budget) > 0) {
                $budgetId = $budget[0]->b_id;
                // delete current budgeting
                foreach ($budget as $key => $bdg) {
                    $bdg->delete();
                }
            }
            else {
                $budgetId = d_budgeting::max('b_id') + 1;
            }

            // prepare data for new budgeting
            foreach ($listPendAkun as $key => $akun) {
                $temp = array(
                    'b_id' => $budgetId,
                    'b_detailid' => $key + 1,
                    'b_date' => $periode,
                    'b_akun' => $akun,
                    'b_value' => $listPendValue[$key],
                    'b_insert_at' => Carbon::now(),
                    'b_updated_at' => Carbon::now()
                );
                array_push($dataPendapatan, $temp);
            }

            // insert new budgeting
            $x = DB::table('d_budgeting')->insert($dataPendapatan);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }

    public function data_lr(Request $request)
    {
        $d1 = date('Y-m').'-01';
        $periode = date('F Y');

//        return \GuzzleHttp\json_encode($d1);

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

        if($cabang = DB::table('m_company')->where('c_id', Auth::user()->u_company)->first()) {
            $namaCabang = $cabang->c_name;
        }

        return json_encode([
            "data"	        => $data,
            "namaCabang" => $namaCabang,
            "periode"	=> $periode,
        ]);

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
