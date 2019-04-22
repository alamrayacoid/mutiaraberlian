<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_sales;
use App\d_salesdt;
use App\d_salesprice;
use App\d_stock;
use App\d_username;
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

    public function cariBarang(Request $request)
    {
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        $comp = Auth::user()->u_company;
        if(count($is_item) == 0){
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_comp', '=', $comp);
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }else{
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_comp', '=', $comp);
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->whereNotIn('i_id', $is_item)
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query, 'stock' => $query->s_id];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function checkStock($stock = null, $item = null, $satuan = null, $qty = null)
    {
        $data_check = DB::table('m_item')
            ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
            ->where('i_id', '=', $item)
            ->first();

        $data = DB::table('d_stock')
            ->join('d_stock_mutation', function($sm){
                $sm->on('sm_stock', '=', 's_id');
            })
            ->where('s_id', '=', $stock)
            ->where('s_item', '=', $item)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->select('sm_residue as sisa')
            ->first();

        $qty_compare = 0;
        if ($satuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa) {
                $qty_compare = $data->sisa;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit2) {
            $compare = (int)$qty * (int)$data_check->compare2;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa/(int)$data_check->compare2;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa/(int)$data_check->compare3;
            } else {
                $qty_compare = $qty;
            }
        }
        return Response::json(floor($qty_compare));
    }

    public function checkStockOld($stock = null, $item = null, $oldSatuan = null, $satuan = null, $qtyOld = null, $qty = null)
    {
        $data_check = DB::table('m_item')
            ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
            ->where('i_id', '=', $item)
            ->first();

        $data = DB::table('d_stock')
            ->join('d_stock_mutation', function($sm){
                $sm->on('sm_stock', '=', 's_id');
            })
            ->where('s_id', '=', $stock)
            ->where('s_item', '=', $item)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->select('sm_residue as sisa')
            ->first();

        $qty_compare_old = 0;
        if ($oldSatuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa) {
                $qty_compare_old = $data->sisa + $qtyOld;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit2) {
            $compare = (int)$qty * (int)$data_check->compare2;
            if ((int)$compare > (int)($data->sisa + $qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare2;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)($data->sisa+$qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare3;
            } else {
                $qty_compare_old = $qty;
            }
        }


        return Response::json(floor($qty_compare_old));
    }

    function existsInArray($entry, $array)
    {
        $x = false;
        foreach ($array as $compare) {
            if ($compare->pcd_type == $entry) {
                $x = true;
            }
        }
        return $x;
    }

    function inRange($value, $array)
    {
//        in_array($request->rangestartedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend));
        $idx = null;
        foreach ($array as $key =>  $val) {
            $x = in_array($value, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend));
            if ($x == true) {
                $idx = $key;
            }
        }
        return $idx;
    }

    public function checkHarga($konsigner, $item, $unit, $qty)
    {

        $type = DB::table('m_agen')
            ->where('a_code', '=', $konsigner)
            ->first();

        $get_price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pc_name', '=', $type->a_type)
            ->where('pcd_item', '=', $item)
            ->where('pcd_unit', '=', $unit)
            ->get();

        $harga = 0;
        $z = false;
        foreach ($get_price as $key => $price) {
            if ($qty == 1) {
                if ($this->existsInArray("U", $get_price) == true) {
                    if ($get_price[$key]->pcd_type == "U") {
                        $harga = $get_price[$key]->pcd_price;
                    }
                } else {
                    if ($price->pcd_rangeqtystart == 1) {
                        $harga = $get_price[$key]->pcd_price;
                    }
                }
            } else if ($qty > 1) {
                if ($price->pcd_rangeqtyend == 0){
                    if ($qty >= $price->pcd_rangeqtystart) {
                        $harga = $price->pcd_price;
                    }
                } else {
                    $z = $this->inRange($qty, $get_price);
                    if ($z != null) {
                        $harga = $get_price[$z]->pcd_price;
                    }
                }

            }
        }

        return Response::json(number_format($harga, 0, '', ''));
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
        // get current user
        $user = Auth::user();
        return view('marketing/agen/index', compact('provinsi', 'user'));
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
        $userType = Auth::user()->u_user;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        if ($agentCode !== null) {
            $company = m_company::where('c_user', $agentCode)
            ->first();
            $datas = d_sales::whereBetween('s_date', [$from, $to])
            ->where('s_comp', $company->c_id)
            ->with('getMember')
            ->orderBy('s_date', 'desc')
            ->get();
        } else {
            if ($userType === 'E') {
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->with('getMember')
                ->orderBy('s_date', 'desc')
                ->get();
            } else {
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->where('s_comp', Auth::user()->u_company)
                ->with('getMember')
                ->orderBy('s_date', 'desc')
                ->get();
            }
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
        ->with('getMember')
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
        $data['user'] = Auth::user()->u_user;
        $data['agents'] = m_agen::get();
        $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->get();
        return view('marketing/agen/kelolapenjualan/create', compact('data'));
    }
    // get member for KPL if the user is Employee, not Agent
    public function getMemberKPL(Request $request)
    {
        $members = m_member::where('m_id', 1)
        ->orWhere('m_agen', $request->agentCode)
        ->get();
        return response()->json($members);
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
        if ($request->agentCode !== null) {
            $position = d_username::where('u_code', $request->agentCode)
            ->first();
            $stock = d_stock::where('s_item', $request->itemId)
            ->where('s_position', $position->u_company)
            ->first();
        } else {
            $stock = d_stock::where('s_item', $request->itemId)
            ->where('s_position', Auth::user()->u_company)
            ->first();
        }
        if ($stock !== null) {
            return response()->json($stock);
        }
        return response()->json();
    }
    // get price
    public function getPrice(Request $request)
    {
        if (Auth::user()->u_user === 'E') {
            $agent = m_agen::where('a_code', $request->agentCode)
            ->first();
        } else {
            $agent = m_agen::where('a_code', Auth::user()->u_code)
            ->first();
        }

        $itemId = $request->itemId;
        $unitId = $request->unitId;
        $price = d_salesprice::where('sp_id', $agent->a_salesprice)
            ->with(['getSalesPriceDt' => function($query) use ($itemId, $unitId) {
                $query
                    ->where('spd_item', $itemId)
                    ->where('spd_unit', $unitId)
                    ->where('spd_type', 'U')
                    ->where('spd_payment', 'C')
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
            $salesNota = CodeGenerator::codeWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PC', '-');
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
        $data['user'] = Auth::user();
        $data['agents'] = m_agen::get();

        $data['kpl'] = d_sales::where('s_id', $id)
        ->with(['getSalesDt.getItem' => function($query) {
            $query
                ->with('getUnit1')
                ->with('getUnit2')
                ->with('getUnit3');
        }])
        ->with('getMember.getAgent')
        ->firstOrFail();
        // prevent null value for getAgent
        if ($data['kpl']->getMember->getAgent === null) {
            $data['kpl-agent'] = 0;
        } else {
            $data['kpl-agent'] = $data['kpl']->getMember->getAgent->a_code;
        }
        // get item stock
        $data['item-stock'] = array();
        foreach ($data['kpl']->getSalesDt as $item) {
            $request = new Request;
            $request->replace([
                'agentCode' => $data['kpl-agent'],
                'itemId' => $item->sd_item
            ]);
            $stock = $this->getItemStock($request);
            array_push($data['item-stock'], $stock->original->s_qty);
        }

        if (Auth::user()->u_user === 'E') {
            $data['member'] = m_member::get();
        } else {
            $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->get();
        }

        return view('marketing/agen/kelolapenjualan/edit', compact('data'));
    }
    // update selected kpl
    public function updateKPL(Request $request, $id)
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
            // start update data
            $sales = d_sales::where('s_id', $id)->first();
            $sales->s_comp = Auth::user()->u_company;
            $sales->s_member = $request->member;
            $sales->s_type = 'C';
            // $sales->s_date = Carbon::now('Asia/Jakarta');
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
