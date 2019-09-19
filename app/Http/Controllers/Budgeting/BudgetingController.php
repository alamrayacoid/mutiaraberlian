<?php

namespace App\Http\Controllers\Budgeting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
