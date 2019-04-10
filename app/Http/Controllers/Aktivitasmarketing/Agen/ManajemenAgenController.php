<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_sales;
use App\d_salesdt;
use App\m_item;
use App\m_member;
use App\m_priceclass;
use DataTables;
use DB;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use Response;
use Validator;

class ManajemenAgenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinsi = DB::table('m_wil_provinsi')
      		->select('m_wil_provinsi.*')
      		->get();

        return view('marketing/agen/index', compact('provinsi'));
    }

    // Start: Kelola Data Inventory Agen ----------------
    public function getAgen($city)
    {
        $agen = DB::table('m_agen')
        ->join('m_company', 'a_code', 'c_user')
        ->select('a_code', 'a_name', 'c_id')
        ->where('a_kabupaten', '=', $city)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $agen
        ]);
    }
    public function filterData($id)
    {
        $data = DB::table('d_stock')
        ->leftJoin('m_company as comp', 's_position', 'comp.c_id')
        ->leftJoin('m_company as agen', 's_comp', 'agen.c_id')
        ->leftJoin('m_item', 's_item', 'i_id')
        ->where('s_comp', '=', $id)
        ->select('agen.c_name as agen', 'comp.c_name as comp', 'i_name', 's_condition', 's_qty')
        ->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kondisi', function ($data) {
            if ($data->s_condition == "FINE") {
                return "Normal";
            } else {
                return "Rusak";
            }
        })
        ->addColumn('qty', function ($data) {
            return "<div class='text-center'>$data->s_qty</div>";
        })
        ->rawColumns(['kondisi', 'qty'])
        ->make(true);
    }
    // End: Kelola Data Inventory Agen ----------------


    // Start: Kelola Penjualan Langsung -----------------
    /**
    * Validate request before execute command.
    *
    * @param  \Illuminate\Http\Request $request
    * @return 'error message' or '1'
    */
    public function validate_req(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'member' => 'required'
        ],
        [
            'member.required' => 'Pilih member terlebih dahulu !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }
    public function createKPL()
    {
        $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->get();

        // dd($data['member']);
        return view('marketing/agen/kelolapenjualan/create', compact('data'));
    }
    // get items using autocomple.js
    public function findItem(Request $request)
    {
        $term = $request->term;
        $items = m_item::where(function ($q) use ($term){
                $q->orWhere('i_name', 'like', '%'.$term.'%');
                $q->orWhere('i_code', 'like', '%'.$term.'%');
            })
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->get();

        if (count($items) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($items as $item) {
                $results[] = ['id' => $item->i_id, 'label' => $item->i_code . ' - ' .strtoupper($item->i_name), 'data' => $item];
            }
        }
        return response()->json($results);
    }
    // get price
    public function getPrice(Request $request)
    {
        // dd($request->all());
        $itemId = $request->itemId;
        $unitId = $request->unitId;
        $price = m_priceclass::with(['getPriceClassDt' => function($query) use ($itemId, $unitId) {
            $query
                ->where('pcd_item', $itemId)
                ->where('pcd_unit', $unitId)
                ->where('pcd_type', 'R')
                ->where('pcd_payment', 'C')
                ->first();
            }])
            ->first();
        return response()->json($price);
    }
    // store new KPL
    public function storeKPL(Request $request)
    {
        // dd($request->all());
        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            // start insert data
            $salesId = d_sales::max('s_id') + 1;
            $salesNota = CodeGenerator::codeWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PC', '-');
            $sales = new d_sales();
            $sales->s_id = $salesId;
            // $sales->s_comp = ;
            // $sales->s_member = ;
            $sales->s_type = 'C';
            $sales->s_date = Carbon::now('Asia/Jakarta');
            $sales->s_nota = $salesNota;
            $sales->s_total = (int)$request->total;
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            for ($i=0; $i < sizeof($request->itemListId); $i++) {
                if ($request->itemListId[$i] === null) {
                    continue;
                }
                $salesDtId = d_salesdt::where('sd_sales', $salesId)->max('sd_detailid') + 1;
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $salesId;
                $salesDt->sd_detailid = $salesDtId;
                // $salesDt->sd_comp = ;
                $salesDt->sd_item = $request->itemListId[$i];
                $salesDt->sd_qty = (int)$request->itemQty[$i];
                $salesDt->sd_unit = $request->itemUnit[$i];
                $salesDt->sd_value = (int)$request->itemSubTotal[$i];
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = 0;
                $salesDt->sd_totalnet = (int)$request->itemSubTotal[$i];
                $salesDt->save();
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // End: Kelola Penjualan Langsung -----------------

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
