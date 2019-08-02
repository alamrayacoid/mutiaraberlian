<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\keuangan\jurnal\jurnal;

use AksesUser;
use Auth;
use CodeGenerator;
use Carbon\Carbon;
use DB;
use App\d_productdelivery;
use App\d_stock;
use App\d_stockdistribution;
use App\d_stockdistributiondt;
use App\d_stockdistributioncode;
use App\d_stock_mutation;
use App\m_company;
use App\m_expedition;
use App\m_expeditiondt;
use App\m_item;
use App\m_mutcat;
use App\m_unit;
use App\m_wil_provinsi;
use Mutasi;
use Validator;
use Yajra\DataTables\DataTables;

class DistribusiController extends Controller
{
    public function index()
    {
        return view('inventory/distribusibarang/index');
    }

    public function create()
    {
        $provinces = DB::table('m_wil_provinsi')->orderBy('wp_name', 'asc')->get();
        $expeditions = m_expedition::get();
        // dd($expeditions);
        return view('inventory/distribusibarang/distribusi/create', compact('provinces', 'expeditions'));
    }

    // get list-cities based on province-id
    public function getAreas(Request $request)
    {
        $cities = m_wil_provinsi::where('wp_id', $request->provId)
            ->with(['getCities' => function ($q) {
                $q->orderBy('wc_name');
            }])
            ->first();
        return response()->json($cities);
    }

    // get list-branches based on area-id
    public function getBranch(Request $request)
    {
        $branches = m_company::where('c_type', 'CABANG')
            ->where('c_area', $request->areaId)
            ->orderBy('c_name')
            ->get();
        return response()->json($branches);
    }

    // get list-expeditionType based on expedition
    public function getExpeditionType(Request $request)
    {
        $expdtId = $request->id;
        $expeditionType = m_expeditiondt::where('ed_expedition', $expdtId)
            ->orderBy('ed_product', 'asc')
            ->get();
        return response()->json($expeditionType);
    }

    public function printNota(Request $request)
    {
        $data = DB::table('d_stockdistribution')->where('sd_id', $request->id)->first();
        $tujuan = DB::table('m_company')->where('c_id', $data->sd_destination)->first();
        $cabang = DB::table('m_company')->where('c_id', $data->sd_from)->first();
        $ekspedisi = d_productdelivery::where('pd_nota', $data->sd_nota)
            ->with('getExpedition')
            ->with('getExpeditionType')
            ->first();

        $dt = DB::table('d_stockdistributiondt')
            ->join('m_item', 'i_id', '=', 'sdd_item')
            ->join('m_unit', 'u_id', '=', 'sdd_unit')
            ->where('sdd_stockdistribution', $request->id)
            ->get();

        return view('inventory/distribusibarang/distribusi/nota', compact('data', 'tujuan', 'cabang', 'dt', 'ekspedisi'));
    }

    // get list items for AutoComplete
    public function getItem(Request $request)
    {
        // get list of existed-items (id)
        if ($request->existedItems != null) {
            $existedItems = array();
            for ($i = 0; $i < count($request->existedItems); $i++) {
                if ($request->existedItems[$i] != null) {
                    array_push($existedItems, $request->existedItems[$i]);
                }
            }
        } else {
            $existedItems = array();
        }

        $cari = $request->term;
        $data = m_item::where('i_isactive', 'Y')
            ->whereNotIn('i_id', $existedItems)
            ->where(function ($query) use ($cari) {
                $query
                    ->where('i_name', 'like', '%' . $cari . '%')
                    ->orWhere('i_code', 'like', '%' . $cari . '%');
            })
            ->whereHas('getStock', function ($query) {
                $query
                    ->where('s_position', Auth::user()->u_company)
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE');
            })
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->get();

        if (count($data) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($data as $query) {
                $results[] = [
                    'id' => $query->i_id,
                    'label' => $query->i_code . ' - ' . $query->i_name,
                    'unit1' => $query->getUnit1,
                    'unit2' => $query->getUnit2,
                    'unit3' => $query->getUnit3,
                    'unitcmp1' => $query->i_unitcompare1,
                    'unitcmp2' => $query->i_unitcompare2,
                    'unitcmp3' => $query->i_unitcompare3
                ];
            }
        }
        return response()->json($results);
    }

    // get item stock
    public function getStock($id)
    {
        $mainStock = d_stock::where('s_item', $id)
            ->where('s_position', Auth::user()->u_company)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->with('getItem')
            ->first();

        if ($mainStock->getItem->i_unitcompare1 != null) {
            $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
        } else {
            $stock['unit1'] = 0;
        }
        if ($mainStock->getItem->i_unitcompare2 != null) {
            $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
        } else {
            $stock['unit2'] = 0;
        }
        if ($mainStock->getItem->i_unitcompare3) {
            $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
        } else {
            $stock['unit3'] = 0;
        }

        return response()->json($stock);
    }

    // get list unit for selct-option
    public function getListUnit(Request $request)
    {
        $units = m_item::where('i_id', $request->itemId)
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->first();
        return $units;
    }

    // store new-distribusibarang to db
    public function store(Request $request)
    {
        // return json_encode($request->all());
        if (!AksesUser::checkAkses(16, 'create')) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Anda tidak memiliki akses ini'
            ]);
        }

        // validate request
        $isValidRequest = $this->validateDist($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $nota = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 16, 10, 3, 'DISTRIBUSI', '-');

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from
                $request->itemsId, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength, // list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }

            // insert new stockdist
            $id = d_stockdistribution::max('sd_id') + 1;
            $dist = new d_stockdistribution;
            $dist->sd_id = $id;
            $dist->sd_from = Auth::user()->u_company;
            $dist->sd_destination = $request->selectBranch;
            $dist->sd_date = Carbon::now();
            $dist->sd_nota = $nota;
            $dist->sd_user = Auth::user()->u_id;
            $dist->save();

            // insert new product-delivery
            $idDeliv = d_productdelivery::max('pd_id') + 1;
            $prodDeliv = new d_productdelivery;
            $prodDeliv->pd_id = $idDeliv;
            $prodDeliv->pd_date = Carbon::now();
            $prodDeliv->pd_nota = $nota;
            $prodDeliv->pd_expedition = $request->expedition;
            $prodDeliv->pd_product = $request->expeditionType;
            $prodDeliv->pd_resi = strtoupper($request->resi);
            $prodDeliv->pd_couriername = $request->courierName;
            $prodDeliv->pd_couriertelp = $request->courierTelp;
            $prodDeliv->pd_price = $request->shippingCost;
            $prodDeliv->save();

            $startProdCodeIdx = 0;
            // insert new stockdist-detail
            foreach ($request->itemsId as $i => $itemId) {
                $jumlahkode = 0;
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }

                if ($request->prodCode[$i] === null || $request->qtyProdCode[$i] === null){
                    $barang = m_item::where('i_id', $itemId)->first();
                    DB::rollback();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Kode produksi ' . strtoupper($barang->i_name) . ' tidak boleh kosong!!!'
                    ]);
                } else {
                    //menghitung jumlah kode produksi per-item
                    $prodCodeLength = (int)$request->prodCodeLength[$i];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] === null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        } else {
                            $jumlahkode = $jumlahkode + $request->qtyProdCode[$j];
                        }
                    }
                }

                if ($request->qty[$i] != 0 && $request->qty[$i] == $jumlahkode) {
                    //insert stock distribusi dt
                    $detailid = d_stockdistributiondt::where('sdd_stockdistribution', $id)->max('sdd_detailid') + 1;
                    $distdt = new d_stockdistributiondt;
                    $distdt->sdd_stockdistribution = $id;
                    $distdt->sdd_detailid = $detailid;
                    $distdt->sdd_comp = Auth::user()->u_company;
                    $distdt->sdd_item = $itemId;
                    $distdt->sdd_qty = $request->qty[$i];
                    $distdt->sdd_unit = $request->units[$i];
                    $distdt->save();

                    // insert new d_stockdistributioncode
                    if ($i == 0) {
                        $startProdCodeIdx = 0;
                    }
                    $prodCodeLength = (int)$request->prodCodeLength[$i];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        }
                        $detailidcode = d_stockdistributioncode::where('sdc_stockdistribution', $id)
                                ->where('sdc_stockdistributiondt', $detailid)
                                ->max('sdc_detailid') + 1;

                        $distcode = new d_stockdistributioncode;
                        $distcode->sdc_stockdistribution = $id;
                        $distcode->sdc_stockdistributiondt = $detailid;
                        $distcode->sdc_detailid = $detailidcode;
                        $distcode->sdc_code = strtoupper($request->prodCode[$j]);
                        $distcode->sdc_qty = $request->qtyProdCode[$j];
                        $distcode->save();
                    }

                    // get qty of smallest unit
                    $item = m_item::where('i_id', $itemId)->first();
                    if ($item->i_unit1 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare1;
                    } elseif ($item->i_unit2 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare2;
                    } elseif ($item->i_unit3 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare3;
                    }

                    // declaare list of production-code
                    $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];
                    // insert stock-mutation
                    // waiit, check the name of $reff
                    // $reff = 'DISTRIBUSI-MASUK';
                    $mutDist = Mutasi::distribusicabangkeluar(
                        Auth::user()->u_company,
                        $request->selectBranch,
                        $itemId, // item id
                        $convert, // qty with smallest unit
                        $nota, // nota
                        $nota, // nota reff
                        $listPC,
                        $listQtyPC,
                        $listUnitPC
                    );

                    if ($mutDist !== 'success') {
                        return $mutDist;
                    }
                    $startProdCodeIdx += $prodCodeLength;
                } else {
                    if ($request->qty[$i] != 0){
                        $barang = m_item::where('i_id', $itemId)->first();
                        DB::rollback();
                        return response()->json([
                            'status' => 'gagal',
                            'message' => 'Kode produksi ' . strtoupper($barang->i_name) . ' tidak tidak sesuai!!!'
                        ]);
                    }
                }
            }

            $acc_ongkir_kas = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Ongkos Kirim Distribusi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Kas/Setara Kas')
                                        ->first();

            $acc_ongkir_beban = DB::table('dk_pembukuan_detail')
                                    ->where('pd_pembukuan', function($query){
                                        $query->select('pe_id')->from('dk_pembukuan')
                                                    ->where('pe_nama', 'Ongkos Kirim Distribusi')
                                                    ->where('pe_comp', Auth::user()->u_company)->first();
                                    })->where('pd_nama', 'COA beban ongkos kirim')
                                    ->first();

            $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Ongkos Kirim Distribusi')
                            ->where('pe_comp', Auth::user()->u_company)->first();
            $details = [];

            // return json_encode($parrent);

            if(!$parrent || !$acc_ongkir_kas || !$acc_ongkir_beban){
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                ]);
            }

            array_push($details, [
                "jrdt_nomor"        => 1,
                "jrdt_akun"         => $acc_ongkir_kas->pd_acc,
                "jrdt_value"        => $request->shippingCost,
                "jrdt_dk"           => "K",
                "jrdt_keterangan"   => $acc_ongkir_kas->pd_keterangan,
                "jrdt_cashflow"     => $acc_ongkir_kas->pd_cashflow
            ]);

            array_push($details, [
                "jrdt_nomor"        => 2,
                "jrdt_akun"         => $acc_ongkir_beban->pd_acc,
                "jrdt_value"        => $request->shippingCost,
                "jrdt_dk"           => "D",
                "jrdt_keterangan"   => $acc_ongkir_beban->pd_keterangan,
                "jrdt_cashflow"     => $acc_ongkir_beban->pd_cashflow
            ]);

            $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $nota, $parrent->pe_nama, 'TK', Auth::user()->u_company);

            if($jurnal['status'] == 'error'){
                return json_encode($jurnal);
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

    // validate request
    public function validateDist(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'selectBranch' => 'required',
            'itemsId.*' => 'required',
            'qty.*' => 'required',
            'expedition' => 'required',
            'expeditionType' => 'required',
            'resi' => 'required'
        ],
            [
                'selectBranch.required' => 'Silahkan pilih \'Cabang\' terlebih dahulu !',
                'itemsId.*.required' => 'Masih terdapat baris item yang kosong !',
                'qty.*.required' => 'Masih terdapat \'Jumlah Item\' yang kosong !',
                'expedition.required' => 'Silahkan pilih \'Jasa Ekspedisi\' yang akan digunakan !',
                'expeditionType.required' => 'Silahkan pilih \'Jenis Ekspedisi\' yang akan digunakan !',
                'resi.required' => 'Silahkan isi \'Nomor Resi\' terlebih dahulu !'
            ]);

        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    // edit selected item
    public function edit($id)
    {
        // if (!AksesUser::checkAkses(7, 'update')){
        //     abort(401);
        // }

        // get stockdistribution by id
        $data['stockdist'] = d_stockdistribution::where('sd_id', decrypt($id))
            ->with('getOrigin')
            ->with('getDestination')
            ->with(['getDistributionDt' => function ($query) {
                $query
                    ->with(['getItem' => function ($query) {
                        $query
                            ->with('getUnit1')
                            ->with('getUnit2')
                            ->with('getUnit3');
                    }])
                    ->with('getUnit')
                    ->with('getProdCode');
            }])
            ->with('getProductDelivery')
            ->first();
        // set variabel to store nota number
        $nota = $data['stockdist']->sd_nota;
        // change number format to int before send it to view
        $data['stockdist']->getProductDelivery->pd_price = (int)$data['stockdist']->getProductDelivery->pd_price;
        // dd($data);
        // get data item-stock
        foreach ($data['stockdist']->getDistributionDt as $key => $val) {
            $item = $val->sdd_item;
            // get item-stock in pusat/werehouse
            $mainStock = d_stock::where('s_position', $val->sdd_comp)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            // calculate item-stock based on unit-compare each item
            if ($mainStock->getItem->i_unitcompare1 != null) {
                $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
            } else {
                $stock['unit1'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare2 != null) {
                $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
            } else {
                $stock['unit2'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare3) {
                $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
            } else {
                $stock['unit3'] = 0;
            }
            $val->stockUnit1 = $stock['unit1'];
            $val->stockUnit2 = $stock['unit2'];
            $val->stockUnit3 = $stock['unit3'];

            // get item-stock in destination
            $st_mutation = d_stock_mutation::where('sm_nota', '=', $nota)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->get();

            foreach ($st_mutation as $keysm => $valsm) {
                if ($valsm->sm_use > 0) {
                    $val->qtyUsed += $valsm->sm_use;
                } else {
                    $val->qtyUsed += 0;
                }
            }
            // set status of the distributed item (used or unused)
            if ($val->qtyUsed > 0) {
                $val->status = 'used';
            } else {
                $val->status = 'unused';
            }
        }

        $data['expeditions'] = m_expedition::get();

        return view('inventory/distribusibarang/distribusi/edit', compact('data'));
    }

    // update selected item
    public function update(Request $request, $id)
    {
        //dd($request->all());
        if (!AksesUser::checkAkses(16, 'update')) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Anda tidak memiliki akses ini'
            ]);
        }

        // validate request
        $isValidRequest = $this->validateDist($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            // get stockdist
            $stockdist = d_stockdistribution::where('sd_id', $id)
                ->with('getDistributionDt.getProdCode')
                ->first();

            // update stockdist
            $stockdist->sd_from = Auth::user()->u_company;
            $stockdist->sd_date = Carbon::now();
            $stockdist->sd_user = Auth::user()->u_id;
            $stockdist->save();

            // update product-delivery
            $prodDeliv = d_productdelivery::where('pd_nota', $stockdist->sd_nota)->first();
            $prodDeliv->pd_date = Carbon::now();
            $prodDeliv->pd_expedition = $request->expedition;
            $prodDeliv->pd_product = $request->expeditionType;
            $prodDeliv->pd_resi = strtoupper($request->resi);
            $prodDeliv->pd_couriername = $request->courierName;
            $prodDeliv->pd_couriertelp = $request->courierTelp;
            $prodDeliv->pd_price = $request->shippingCost;
            $prodDeliv->save();

            $startProdCodeIdx = 0;
            // count skipped index based on 'isDeleted' row
            $skippedIndex = 0;
            // set list-items-id, used for validate production-code
            $listItemsId = [];
            // start : loop each item (rollback stock-mutation distribution)
            foreach ($request->itemsId as $key => $val) {
                // if the item is being deleted -> rollback-distribution
                if ($request->isDeleted[$key] == 'true') {
                    // rollBack qty in stock-mutation and stock-item
                    $rollbackDist = Mutasi::rollbackStockMutDist(
                        $stockdist->sd_nota, // dist-nota
                        $val // itemId
                    );
                    if ($rollbackDist !== 'success') {
                        DB::rollback();
                        return $rollbackDist;
                    }
                    $skippedIndex += 1;
                    // continue to next counter loop
                    continue;
                }
                // fill $listItemsId with non-deleted items-id
                array_push($listItemsId, $val);
                // validate production-code is exist in stock-item
                $validateProdCode = Mutasi::validateProductionCode(
                    Auth::user()->u_company, // from
                    $listItemsId, // item-id
                    $request->prodCode, // production-code
                    $request->prodCodeLength, // production-code length each item
                    $request->qtyProdCode // list of qty each production-code
                );
                if ($validateProdCode !== 'validated') {
                    return $validateProdCode;
                }

                // set '$key' minus by $skippedIndex
                $key -= $skippedIndex;
                // get item-detail
                $item = m_item::where('i_id', $val)->first();
                // convert qty-item to smallest units
                if ($item->i_unit1 == $request->units[$key]) {
                    $convert = (int)$request->qty[$key] * $item->i_unitcompare1;
                } elseif ($item->i_unit2 == $request->units[$key]) {
                    $convert = (int)$request->qty[$key] * $item->i_unitcompare2;
                } elseif ($item->i_unit3 == $request->units[$key]) {
                    $convert = (int)$request->qty[$key] * $item->i_unitcompare3;
                }
                // get list of production-code
                $prodCodeLength = (int)$request->prodCodeLength[$key];
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                //validasi jumlah kuantitas kode produksi
                $jumlahkode = 0;

                for ($g = 0; $g < count($listPC); $g++) {
                    if ($listQtyPC[$g] == '' || $listQtyPC[$g] === null || $listQtyPC[$g] == 0
                    || $listPC[$g] === null || $listPC == '') {
                        continue;
                    } else {
                        $jumlahkode = $jumlahkode + (int)$listQtyPC[$g];
                    }
                }

                if ($jumlahkode != $request->qty[$key]){
                    $barang = m_item::where('i_id', $request->itemsId[$key])->first();
                    DB::rollback();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Jumlah kode produksi ' . strtoupper($barang->i_name) . ' tidak tidak sesuai dengan jumlah permintaan'
                    ]);
                }

                // if : qty in stock-mutation still 'unused'
                if ($request->status[$key] == "unused") {
                    // rollBack qty in stock-mutation and stock-item
                    $rollbackDist = Mutasi::rollbackStockMutDist(
                        $stockdist->sd_nota, // dist-nota
                        $val // itemId
                    );
                    if ($rollbackDist !== 'success') {
                        DB::rollback();
                        return $rollbackDist;
                    }
                    // waiit, check the name of $reff
                    // $reff = 'DISTRIBUSI-MASUK';
                    $mutDist = Mutasi::distribusicabangkeluar(
                        Auth::user()->u_company, // from
                        $request->sd_destination, // to
                        $val, // item-id
                        $convert, // qty of smallest-unit
                        $stockdist->sd_nota, // nota
                        $stockdist->sd_nota, // nota-reff
                        $listPC, // list of production-code
                        $listQtyPC, // list of production-code-qty
                        $listUnitPC // list of production-code-unit
                    );
                    if ($mutDist !== 'success') {
                        return $mutDist;
                    }
                } // else : stock already 'used'
                elseif ($request->status[$key] == "used") {
                    // update qty in stock-mutation and in stock-item
                    $qty = $convert;
                    $updateDist = Mutasi::updateDistribusi(
                        $stockdist->sd_nota,
                        $val, // item-id
                        $qty, // new qty with smallest unit
                        $listPC, // list of production-code
                        $listQtyPC, // list of production-code-qty
                        $listUnitPC // list of production-code-unit
                    );
                    if ($updateDist != true) {
                        DB::rollback();
                        return response()->json([
                            'status' => 'gagal',
                            'message' => $updateDist->getMessage()
                        ]);
                    }
                }
                // update starting-index of production-code-list
                $startProdCodeIdx += $prodCodeLength;
            }
            // end: loop

            // delete all stockdist-detail
            foreach ($stockdist->getDistributionDt as $key => $distdt) {
                // delete all stockdist-code
                foreach ($distdt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                $distdt->delete();
            }

            // re-declare $skippedIndex
            // count skipped index based on 'isDeleted' row
            $skippedIndex = 0;
            foreach ($request->itemsId as $key => $val) {
                if ($request->isDeleted[$key] == 'true') {
                    $skippedIndex += 1;
                    continue;
                }
                // set '$key' minus by $skippedIndex
                $key -= $skippedIndex;
                // insert new stockdist-detail
                $detailid = d_stockdistributiondt::where('sdd_stockdistribution', $stockdist->sd_id)->max('sdd_detailid') + 1;
                $distdt = new d_stockdistributiondt;
                $distdt->sdd_stockdistribution = $stockdist->sd_id;
                $distdt->sdd_detailid = $detailid;
                $distdt->sdd_comp = Auth::user()->u_company;
                $distdt->sdd_item = $val;
                $distdt->sdd_qty = $request->qty[$key];
                $distdt->sdd_unit = $request->units[$key];
                $distdt->save();

                // insert new d_stockdistributioncode
                if ($key == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$key];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    $detailidcode = d_stockdistributioncode::where('sdc_stockdistribution', $id)
                            ->where('sdc_stockdistributiondt', $detailid)
                            ->max('sdc_detailid') + 1;

                    $distcode = new d_stockdistributioncode;
                    $distcode->sdc_stockdistribution = $id;
                    $distcode->sdc_stockdistributiondt = $detailid;
                    $distcode->sdc_detailid = $detailidcode;
                    $distcode->sdc_code = strtoupper($request->prodCode[$j]);
                    $distcode->sdc_qty = $request->qtyProdCode[$j];
                    $distcode->save();
                }
                // update starting-index of production-code-list
                $startProdCodeIdx += $prodCodeLength;
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }

    // what if the items has been used by others ??
    // still get to delete it ??
    public function hapus(Request $request)
    {
        // if (!AksesUser::checkAkses(7, 'delete')){
        //     abort(401);
        // }
        DB::beginTransaction();
        try {
            // get stockdist
            $stockdist = d_stockdistribution::where('sd_id', $request->id)
                ->with('getDistributionDt.getProdCode')
                ->with('getProductDelivery')
                ->first();

            foreach ($stockdist->getDistributionDt as $key => $stockdistDt) {
                // rollBack qty in stock-mutation and stock-item
                $rollbackDist = Mutasi::rollbackStockMutDist(
                    $stockdist->sd_nota, // distribution nota
                    $stockdistDt->sdd_item // item-id
                );
                if ($rollbackDist !== 'success') {
                    DB::rollback();
                    return $rollbackDist;
                }
                // delete production-code of selected stockdistribution
                foreach ($stockdistDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
            }
            // delete selected stockdistribution-detail
            foreach ($stockdist->getDistributionDt as $key => $stockdistDt) {
                $stockdistDt->delete();
            }
            // delete selected productDelivery
            $stockdist->getProductDelivery->delete();
            // delete selected stockdistribution
            $stockdist->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // confirm distribution
    public function setAcceptance(Request $request, $id)
    {
        // if (!AksesUser::checkAkses(7, 'update')){
        //     abort(401);
        // }

        DB::beginTransaction();
        try {
            $stockdist = d_stockdistribution::where('sd_id', $id)
                ->with('getDistributionDt')
                ->first();

            // confirm each item
            foreach ($stockdist->getDistributionDt as $key => $val) {
                $mutConfirm = Mutasi::confirmDistribution(
                    $val->sdd_comp, // item-owner
                    $stockdist->sd_destination, // destination
                    $val->sdd_item, // item id
                    $stockdist->sd_nota, // nota distribution
                    18, // mutcat distribution 'in'
                    19 // mutcat distribution 'out'
                );
                if ($mutConfirm !== 'success') {
                    return $mutConfirm;
                }
            }

            // update stockdist-status to 'Y'
            $stockdist->sd_status = 'Y';
            $stockdist->save();

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

    public function table(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_status', '!=', 'Y')
            ->where('sd_status', '!=', 'N')
            ->orderBy('sd_date', 'desc')
            ->orderBy('sd_nota', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->sd_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Print Nota" title="Nota" type="button" onclick="printNota(' . $data->sd_id . ')"><i class="fa fa-print"></i></button>
                <button class="btn btn-warning btn-edit-distribusi" onclick="edit(\'' . encrypt($data->sd_id) . '\')" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btn-disable-distribusi" onclick="hapus(' . $data->sd_id . ')" type="button" title="Hapus"><i class="fa fa-times-circle"></i></button>
            </div>';
            })
            ->addColumn('tujuan', function ($data) {
                $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

                return $tmp->c_name;
            })
            ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
            ->make(true);
    }

    // retrieve DataTable for history distribusibarang
    public function tableHistory(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_from', Auth::user()->u_company)
            ->where('sd_status', 'Y')
            ->orderBy('sd_date', 'desc')
            ->orderBy('sd_nota', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->sd_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('tujuan', function ($data) {
                $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();
                return $tmp->c_name;
            })
            // ->addColumn('status', function($data) {
            //     if ($data->sd_status == 'Y') {
            //         return '<span class="badge badge-pill badge-primary text-center">Telah diterima</span>';
            //     }
            //     else {
            //         return '<span class="badge badge-pill badge-warning text-center">Belum diterima</span>';
            //     }
            // })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary" onclick="showDetailHt(' . $data->sd_id . ')" title="Detail distribusi"><i class="fa fa-folder"></i></button>
            </div>';
            })
            ->rawColumns(['tanggal', 'status', 'action', 'tujuan', 'type'])
            ->make(true);
    }

    // retrieve detail for history distribusibarang
    public function showDetailHt($id)
    {
        $detail = d_stockdistribution::where('sd_id', $id)
            ->with('getOrigin')
            ->with('getDestination')
            ->with(['getDistributionDt' => function ($query) {
                $query
                    ->with('getItem')
                    ->with('getUnit');
            }])
            ->first();
        $detail->dateFormated = Carbon::parse($detail->sd_date)->format('d M Y');

        return response()->json($detail);
    }

    // retrieve list of production code from distribution
    public function showPC($idDist, $detailId)
    {
        $listPC = d_stockdistributioncode::where('sdc_stockdistribution', $idDist)
            ->where('sdc_stockdistributiondt', $detailId)
            ->get();

        return response()->json($listPC);
    }

    // retrieve DataTable for acceptance distribusibarang
    public function tableAcceptance(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_status', 'P')// status == 'pending'
            ->orderBy('sd_date', 'asc')
            ->orderBy('sd_nota', 'asc');

        // if logged in user is 'pusat'
        if (Auth::user()->u_user == 'E' && Auth::user()->getCompany->c_type == 'PUSAT') {
            $data = $data->get();
        } // if logged in user is 'cabang'
        elseif (Auth::user()->u_user == 'E' && Auth::user()->getCompany->c_type == 'CABANG') {
            $data = $data->where('sd_destination', '=', Auth::user()->u_company)
                ->get();
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->sd_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('tujuan', function ($data) {
                $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();
                return $tmp->c_name;
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary" title="Terima Barang" onclick="showDetailAc(' . $data->sd_id . ')"><i class="fa fa-get-pocket"></i></button>
            </div>';
            })
            ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
            ->make(true);
    }

    // retrieve detail for acceptance distribusibarang
    public function showDetailAc($id)
    {
        $detail = d_stockdistribution::where('sd_id', $id)
            ->with('getOrigin')
            ->with('getDestination')
            ->with(['getDistributionDt' => function ($query) {
                $query
                    ->with('getItem')
                    ->with('getUnit');
            }])
            ->first();
        $detail->dateFormated = Carbon::parse($detail->sd_date)->format('d M Y');

        return response()->json($detail);
    }

}
