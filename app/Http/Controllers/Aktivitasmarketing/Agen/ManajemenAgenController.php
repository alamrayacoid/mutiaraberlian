<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_sales;
use App\d_salesdt;
use App\d_stock;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_member;
use App\m_priceclass;
use App\m_wil_provinsi;
use DataTables;
use DB;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use Mutasi;
use Response;
use Validator;

class ManajemenAgenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function cariPembeli(Request $request, $kode)
    {
        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('a_parent', '=', $kode)
            ->where(function ($q) use ($cari){
                $q->orWhere('a_name', 'like', '%'.$cari.'%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->a_id,
                    'label' => strtoupper($query->a_name),
                    'data' => $query,
                    'kode' => $query->a_code,
                    'comp' => $query->c_id
                ];
            }
        }
        return Response::json($results);
    }

    public function cariPenjual(Request $request, $prov = null, $kota = null)
    {
        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('m_agen.a_provinsi', '=', $prov)
            ->where('m_agen.a_kabupaten', '=', $kota)
//            ->where('m_company.c_type', '=', 'AGEN')
            ->where(function ($q) use ($cari){
                $q->orWhere('a_name', 'like', '%'.$cari.'%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->a_id,
                    'label' => strtoupper($query->a_name),
                    'data' => $query,
                    'kode' => $query->a_code,
                    'comp' => $query->c_id
                ];
            }
        }
        return Response::json($results);
    }

    public function getProv()
    {
        $prov = DB::table('m_wil_provinsi')->get();
        return Response::json($prov);
    }

    public function getKota($idprov = null)
    {
        $kota = DB::table('m_wil_kota')->where('wc_provinsi', $idprov)->get();
        return Response::json($kota);
    }

    public function create_orderprodukagencabang()
    {
        return view('marketing/agen/orderproduk/create');
    }

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
            'member' => 'required',
            'itemListId.*' => 'required'
        ],
        [
            'member.required' => 'Pilih member terlebih dahulu !',
            'itemListId.*.required' => 'List item ada yang kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }
    /**
    * Return DataTable list for view.
    *
    * @return Yajra/DataTables
    */
    public function getListKPL(Request $request)
    {
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        // dd($agentCode);

        if ($agentCode !== null) {
            $company = m_company::where('c_user', $agentCode)
            ->first();
            $datas = d_sales::whereBetween('s_date', [$from, $to])
            ->where('s_comp', $company->c_id)
            ->with('getMember')
            ->orderBy('s_nota', 'desc')
            ->get();
        } else {
            $datas = d_sales::whereBetween('s_date', [$from, $to])
            ->with('getMember')
            ->orderBy('s_nota', 'desc')
            ->get();
        }

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('date', function($datas) {
            return Carbon::parse($datas->s_date)->format('d M Y');
        })
        ->addColumn('member', function($datas) {
            return $datas->getMember['m_name'];
        })
        ->addColumn('total', function($datas) {
            return 'Rp '. number_format($datas->s_total, '2', ',', '.');
        })
        ->addColumn('action', function($datas) {
            return
            '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-detailKPL" type="button" title="Detail" onclick="showDetailPenjualan('. $datas->s_id .')"><i class="fa fa-folder"></i></button>
            <button class="btn btn-warning btn-editKPL" type="button" title="Edit" onclick="editDetailPenjualan('. $datas->s_id .')"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-danger btn-delete" type="button" title="Delete" onclick="deleteDetailPenjualan('. $datas->s_id .')"><i class="fa fa-trash"></i></button>
            </div>';
        })
        ->rawColumns(['customer', 'staff', 'action'])
        ->make(true);
    }
    // get list-cities based on province-id
    public function getCitiesKPL(Request $request)
    {
        $cities = m_wil_provinsi::where('wp_id', $request->provId)
        ->with('getCities')
        ->firstOrFail();
        return response()->json($cities);
    }
    // get list-cities based on province-id
    public function getAgentsKPL(Request $request)
    {
        $agents = m_agen::where('a_area', $request->cityId)
        ->where('a_type', 'AGEN')
        ->with('getProvince')
        ->with('getCity')
        ->orderBy('a_code', 'desc')
        ->get();
        // dd($agents);
        // var_dump($agents);
        return response()->json($agents);
    }
    // get detail-kpl
    public function getDetailPenjualan(Request $request)
    {
        $detail = d_sales::where('s_id', $request->id)
        ->with('getSalesDt.getItem')
        ->with('getSalesDt.getUnit')
        ->first();
        return response()->json($detail);
    }
    // delete detail-kpl
    public function deleteDetailPenjualan(Request $request)
    {
        $id = $request->id;
        DB::beginTransaction();
        try {
            $penjualan = d_sales::where('s_id', $id)
            ->firstOrFail();

            $mutasi = Mutasi::rollback($penjualan->s_nota);
            $penjualan->getSalesDt()->delete();
            $penjualan->delete();

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
    // show page to create new KPL
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
        $term = $request->termToFind;
        // set list of item where already exist in shopping-list
        $itemList = array();
        if ($request->itemListId !== null) {
            foreach ($request->itemListId as $itemId) {
                if ($itemId === null) {
                    $itemId = 0;
                }
                array_push($itemList, $itemId);
            }
        }
        // startu query to find specific item
        $items = m_item::whereNotIn('i_id', $itemList)
            ->where(function ($q) use ($term){
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
    // get stock
    public function getItemStock(Request $request)
    {
        $stock = d_stock::where('s_item', $request->itemId)
            ->where('s_position', Auth::user()->u_company)
            ->first();
        if ($stock !== null) {
            return response()->json($stock);
        }
        return response()->json();
    }
    // ---------------------------------------------------------
    // get price (need to be repaired, changed table and system)
    public function getPrice(Request $request)
    {
        $itemId = $request->itemId;
        $unitId = $request->unitId;
        $price = m_priceclass::where('pc_id', 1)
            ->with(['getPriceClassDt' => function($query) use ($itemId, $unitId) {
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
            $salesNota = CodeGenerator::codeSalesWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PC', '-');
            $sales = new d_sales();
            $sales->s_id = $salesId;
            $sales->s_comp = Auth::user()->u_company;
            $sales->s_member = $request->member;
            $sales->s_type = 'C';
            $sales->s_date = Carbon::now('Asia/Jakarta');
            $sales->s_nota = $salesNota;
            $sales->s_total = (int)$request->total;
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            for ($i=0; $i < sizeof($request->itemListId); $i++) {
                $salesDtId = d_salesdt::where('sd_sales', $salesId)->max('sd_detailid') + 1;
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $salesId;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $request->itemOwner[$i];
                $salesDt->sd_item = $request->itemListId[$i];
                $salesDt->sd_qty = (int)$request->itemQty[$i];
                $salesDt->sd_unit = $request->itemUnit[$i];
                $salesDt->sd_value = (int)$request->itemPrice[$i];
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = 0;
                $salesDt->sd_totalnet = (int)$request->itemSubTotal[$i];
                $salesDt->save();

                // get total qty with base-unit item
                $itemQtyUnitBase = 0;
                $itemQtyUnitBase = (int)$request->itemQty[$i] * (int)$request->itemUnitCmp[$i];

                // mutasi keluar
                $mutasi = Mutasi::mutasikeluarcustomsell(
                    14,
                    $request->itemOwner[$i],
                    Auth::user()->u_company,
                    $request->itemListId[$i],
                    $itemQtyUnitBase,
                    $salesNota,
                    $request->itemPrice[$i]
                );
                if ($mutasi !== true) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'Mutasi gagal',
                        'message' => $mutasi->error->getMessage()
                    ]);
                }
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
    // edit selected kpl
    public function editKPL($id)
    {
        $data['kpl'] = d_sales::where('s_id', $id)
        ->with(['getSalesDt.getItem' => function($query) {
            $query
                ->with('getUnit1')
                ->with('getUnit2')
                ->with('getUnit3');
        }])
        ->firstOrFail();
        $data['member'] = m_member::orWhere('m_id', 1)
        ->orWhere('m_agen', Auth::user()->u_code)
        ->get();
        // dd($data['kpl']);
        return view('marketing/agen/kelolapenjualan/edit', compact('data'));
    }
    // update selected kpl
    public function updateKPL(Request $request, $id)
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
            $sales = d_sales::where('s_id', $id)->first();
            // dd($sales);
            $sales->s_comp = Auth::user()->u_company;
            $sales->s_member = $request->member;
            $sales->s_type = 'C';
            $sales->s_date = Carbon::now('Asia/Jakarta');
            $sales->s_total = (int)$request->total;
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            // rollback mutasi-sales which is updated
            $mutasi = Mutasi::rollback($sales->s_nota);
            // delete all item from this sales in sales-dt
            $sales->getSalesDt()->delete();

            for ($i=0; $i < sizeof($request->itemListId); $i++) {
                $salesDtId = d_salesdt::where('sd_sales', $sales->s_id)->max('sd_detailid') + 1;
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $sales->s_id;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $request->itemOwner[$i];
                $salesDt->sd_item = $request->itemListId[$i];
                $salesDt->sd_qty = (int)$request->itemQty[$i];
                $salesDt->sd_unit = $request->itemUnit[$i];
                $salesDt->sd_value = (int)$request->itemPrice[$i];
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = 0;
                $salesDt->sd_totalnet = (int)$request->itemSubTotal[$i];
                $salesDt->save();

                // get total qty with base-unit item
                $itemQtyUnitBase = 0;
                $itemQtyUnitBase = (int)$request->itemQty[$i] * (int)$request->itemUnitCmp[$i];

                // mutasi keluar
                $mutasi = Mutasi::mutasikeluarcustomsell(
                    14,
                    $request->itemOwner[$i],
                    Auth::user()->u_company,
                    $request->itemListId[$i],
                    $itemQtyUnitBase,
                    $sales->s_nota,
                    $request->itemPrice[$i]
                );
                if ($mutasi !== true) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'Mutasi gagal',
                        'message' => $mutasi->error->getMessage()
                    ]);
                }
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
