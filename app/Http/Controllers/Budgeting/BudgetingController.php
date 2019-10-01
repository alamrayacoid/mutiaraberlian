<?php

namespace App\Http\Controllers\Budgeting;

use App\Helper\keuangan\laporan\laporan as laporan;
use App\Model\keuangan\dk_hierarki_satu as level_1;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\pushnotifikasiController as pushNotif;

use DB;
use Auth;

use App\d_budgeting;
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
        if(!$request->periode){
            $month = Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');
        }
        else {
            $month = Carbon::createFromFormat('m-Y', $request->periode)->firstOfMonth()->format('Y-m-d');
        }

        $d1 = $month;

        $data = level_1::where('hs_id', '>', '3')
        ->with([
            'subclass' => function($query) use ($month) {
                $query->select('hs_id', 'hs_nama', 'hs_level_1')
                ->orderBy('hs_flag')
                ->with([
                    'level2' => function($query) use ($month) {
                        $query->select('hd_id', 'hd_nama', 'hd_subclass', 'hd_nomor')
                        ->with([
                            'akun' => function($query) use ($month) {
                                $query->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                                ->leftJoin('d_budgeting', function ($join) use ($month){
                                    $join->on('d_budgeting.b_akun', 'dk_akun.ak_nomor')
                                    ->where('d_budgeting.b_date', $month);
                                })
                                ->groupBy('ak_id')
                                ->select(
                                    'ak_id',
                                    'ak_nomor',
                                    'ak_kelompok',
                                    'ak_nama',
                                    'ak_posisi',
                                    'd_budgeting.b_value AS budgeting_value',
                                    DB::RAW('coalesce(as_saldo_akhir - as_saldo_awal) as saldo_akhir'),
                                    DB::RAW('coalesce(as_saldo_akhir - as_saldo_awal) - d_budgeting.b_value AS diff_value')
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
            'data' => $data
        ]);
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

            $listAkun = array();
            $listValue = array();
            foreach ($request->pendAkun as $key => $value) {
                array_push($listAkun, $value);
                array_push($listValue, $request->pendValue[$key]);
            }
            foreach ($request->bebanAkun as $key => $value) {
                array_push($listAkun, $value);
                array_push($listValue, $request->bebanValue[$key]);
            }

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
            foreach ($listAkun as $key => $akun) {
                $temp = array(
                    'b_id' => $budgetId,
                    'b_detailid' => $key + 1,
                    'b_date' => $periode,
                    'b_akun' => $akun,
                    'b_value' => $listValue[$key],
                    'b_insert_at' => Carbon::now(),
                    'b_updated_at' => Carbon::now()
                );
                array_push($dataPendapatan, $temp);
            }

            // insert new budgeting
            $x = DB::table('d_budgeting')->insert($dataPendapatan);

            // pusher -> push notification
            pushNotif::notifikasiup('Notifikasi Pembuatan Perencanaan Budgeting');

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
        $month = Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');
        $d1 = $month;

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

        if($cabang = DB::table('m_company')->where('c_id', Auth::user()->u_company)->first()) {
            $namaCabang = $cabang->c_name;
        }

        return response()->json([
            'data' => $data
        ]);

    }

    public function data_budget(Request $request)
    {
        // get first day of selected month
        if(!$request->periode){
            $month = Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');
        }
        else {
            $month = Carbon::createFromFormat('m-Y', $request->periode)->firstOfMonth()->format('Y-m-d');
        }

        $coun = [];
        $d1 = $month;

        $data2 = level_1::where('hs_id', '>', '3')
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

        $data = DB::table('d_budgeting')
            ->join('dk_akun','ak_nomor','b_akun')
            ->where('b_date',Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'))
            ->orderBy('b_akun','asc')->get();

        foreach ($data2 as $row){
            foreach ($row->subclass as $roww){
                foreach ($roww->level2 as $rowww ){
                    foreach ($rowww->akun as $rowwww){
                        $dataa = DB::table('d_budgeting')
                            ->join('dk_akun','ak_nomor','b_akun')
                            ->where('b_date',Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'))
                            ->orderBy('b_akun','asc')->where('b_akun',$rowwww->ak_nomor)->get();
                        foreach ($dataa as $row2){
                            $cou =[
                                'ak_posisi' => $row2->ak_posisi,
                                'ak_nama' => $row2->ak_nama,
                                'ak_nomor' => $row2->ak_nomor,
                                'count' => (int)$rowwww->saldo_akhir - (int)$row2->b_value
                            ];
                            array_push($coun , $cou);
                        }
                    }
                }
            }
        }
        return response()->json([
            'data' => $data,
            'count' => $coun,
        ]);
    }

}
