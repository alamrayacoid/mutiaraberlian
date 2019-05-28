<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use CodeGenerator;
use Carbon\Carbon;
use DB;
use App\d_stock;
use App\d_stockdistribution;
use App\d_stockdistributiondt;
use App\d_stockdistributioncode;
use App\d_stock_mutation;
use App\m_company;
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
        $provinces = DB::table('m_wil_provinsi')->get();
        return view('inventory/distribusibarang/distribusi/create', compact('provinces'));
    }
    // get list-cities based on province-id
    public function getAreas(Request $request)
    {
        $cities = m_wil_provinsi::where('wp_id', $request->provId)
        ->with('getCities')
        ->firstOrFail();
        return response()->json($cities);
    }
    // get list-branches based on area-id
    public function getBranch(Request $request)
    {
        $branches = m_company::where('c_type', 'CABANG')
        ->where('c_area', $request->areaId)
        ->get();
        return response()->json($branches);
    }

    public function printNota(Request $request)
    {
        $data = DB::table('d_stockdistribution')->where('sd_id', $request->id)->first();

        $tujuan = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

        $cabang = DB::table('m_company')->where('c_id', $data->sd_from)->first();

        $dt = DB::table('d_stockdistributiondt')->join('m_item', 'i_id', '=', 'sdd_item')->join('m_unit', 'u_id', '=', 'sdd_unit')->where('sdd_stockdistribution', $request->id)->get();

        return view('inventory/distribusibarang/distribusi/nota', compact('data', 'tujuan', 'cabang', 'dt'));
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
                    'unit3' => $query->getUnit3
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

        $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
        $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
        $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);

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
        // if (!AksesUser::checkAkses(7, 'create')){
        //     abort(401);
        // }

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

            $startProdCodeIdx = 0;
            // insert new stockdist-detail
            foreach ($request->itemsId as $i => $itemId) {
                if ($request->qty[$i] != 0) {
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
                        $detailidcode = d_stockdistributioncode::where('sdc_stockdistribution', $id)
                        ->where('sdc_stockdistributiondt', $detailid)
                        ->max('sdc_detailid') + 1;
                        $distcode = new d_stockdistributioncode;
                        $distcode->sdc_stockdistribution = $id;
                        $distcode->sdc_stockdistributiondt = $detailid;
                        $distcode->sdc_detailid = $detailidcode;
                        $distcode->sdc_code = $request->prodCode[$j];
                        $distcode->sdc_qty = $request->qtyProdCode[$j];
                        $distcode->save();
                    }
                    // insert stock-mutation
                    $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];
                    $item = m_item::where('i_id', $itemId)->first();
                    if ($item->i_unit1 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare1;
                    }
                    elseif ($item->i_unit2 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare2;
                    }
                    elseif ($item->i_unit3 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare3;
                    }
                    // waiit, check the name of $reff
                    $reff = 'DISTRIBUSI-MASUK';
                    Mutasi::distribusicabangkeluar(
                        Auth::user()->u_company,
                        $request->selectBranch,
                        $itemId,
                        $convert,
                        $nota,
                        $reff,
                        $listPC,
                        $listQtyPC,
                        $listUnitPC
                    );

                    $startProdCodeIdx += $prodCodeLength;
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
    // validate request
    public function validateDist(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'selectBranch' => 'required',
            'itemsId.*' => 'required',
            'qty.*' => 'required'
        ],
            [
                'selectBranch.required' => 'Silahkan pilih \'Cabang\' terlebih dahulu !',
                'itemsId.*.required' => 'Masih terdapat baris item yang kosong !',
                'qty.*.required' => 'Masih terdapat \'Jumlah Item\' yang kosong !'
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
        // if (!AksesUser::checkAkses(7, 'edit')){
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
                    ->with('getCodeProd');
            }])
            ->first();
        // set variabel to store nota number
        $nota = $data['stockdist']->sd_nota;
        // get data item-stock
        foreach ($data['stockdist']->getDistributionDt as $key => $val)
        {
            $item = $val->sdd_item;

            // get item-stock in pusat/werehouse
            $mainStock = d_stock::where('s_position', $val->sdd_comp)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            // calculate item-stock based on unit-compare each item
            $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
            $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
            $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
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
                }
                else {
                    $val->qtyUsed += 0;
                }
            }
            // set status of the distributed item (used or unused)
            if ($val->qtyUsed > 0) {
                $val->status = 'used';
            }
            else {
                $val->status = 'unused';
            }
        }

        // dd($data['stockdist']);

        return view('inventory/distribusibarang/distribusi/edit', compact('data'));
    }
    // update selected item
    public function update(Request $request, $id)
    {
        dd($request->all());
        // if (!AksesUser::checkAkses(7, 'edit')){
        //     abort(401);
        // }

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
                ->with('getDistributionDt')
                ->first();

            $startProdCodeIdx = 0;
            // start : loop each item
            foreach ($request->itemsId as $key => $val) {
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

                // if : qty in stock-mutation still 'unused'
                if ($request->status[$key] == "unused") {
                    // rollBack qty in stock-mutation and stock-item
                    $rollbackDist = Mutasi::rollbackDistribusi($stockdist->sd_nota, $val);
                    if ($rollbackDist != true) {
                        DB::rollback();
                        return response()->json([
                            'status' => 'gagal',
                            'message' => $rollbackDist->getMessage()
                        ]);
                    }
                    // --- start: create new stock-mutation for distribution ---
                    $prodCodeLength = (int)$request->prodCodeLength[$key];
                    $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];
                    // waiit, check the name of $reff
                    $reff = 'DISTRIBUSI-MASUK';
                    Mutasi::distribusicabangkeluar(
                        Auth::user()->u_company, // from
                        $request->sd_destination, // to
                        $val, // item-id
                        $convert, // qty of smallest-unit
                        $stockdist->sd_nota, // nota
                        $reff, // nota-reff
                        $listPC,
                        $listQtyPC,
                        $listUnitPC
                    );
                    // update starting-index of production-code-list
                    $startProdCodeIdx += $prodCodeLength;
                    // --- end: create new stock-mutation for distribution ---
                } // else : stock already 'used'
                elseif ($request->status[$key] == "used") {
                    // update qty in stock-mutation and in stock-item
                    $qty = $convert;
                    $updateDist = Mutasi::updateDistribusi(
                        $stockdist->sd_nota,
                        $val, // item-id
                        $qty
                    );
                    if ($updateDist != true) {
                        DB::rollback();
                        return response()->json([
                            'status' => 'gagal',
                            'message' => $updateDist->getMessage()
                        ]);
                    }
                }
            }
            // end: loop

            // delete all stockdist-detail
            foreach ($stockdist->getDistributionDt as $key => $val) {
                $val->delete();
            }
            // insert new stockdist-detail
            foreach ($request->itemsId as $key => $val) {
                $detailid = d_stockdistributiondt::where('sdd_stockdistribution', $stockdist->sd_id)->max('sdd_detailid') + 1;
                $distdt = new d_stockdistributiondt;
                $distdt->sdd_stockdistribution = $stockdist->sd_id;
                $distdt->sdd_detailid = $detailid;
                $distdt->sdd_comp = Auth::user()->u_company;
                $distdt->sdd_item = $val;
                $distdt->sdd_qty = $request->qty[$key];
                $distdt->sdd_unit = $request->units[$key];
                $distdt->save();
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
    // start: unused (maybe) -> deleted soon ===========================
    // public function simpancabang(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //
    //         $nota = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 8, 10, 3, 'DK', '-');
    //
    //         for ($x=0; $x < count($request->namabarang); $x++) {
    //             if ($request->qty[$x] != 0) {
    //                 $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();
    //
    //                 if ($barang->i_unit1 == $request->satuan[$i]) {
    //                     $convert  = $request->qty[$i] * $barang->i_unitcompare1;
    //                 } elseif ($barang->i_unit2 == $request->satuan[$i]) {
    //                     $convert  = $request->qty[$i] * $barang->i_unitcompare2;
    //                 } elseif ($barang->i_unit3 == $request->satuan[$i]) {
    //                     $convert  = $request->qty[$i] * $barang->i_unitcompare3;
    //                 }
    //
    //                 Mutasi::distribusicabangkeluar(Auth::user()->u_company, $request->cabang, $request->idbarang[$x], $convert, $nota, $nota);
    //             }
    //         }
    //
    //         $id = DB::table('d_stockdistribution')->max('sd_id')+1;
    //         DB::table('d_stockdistribution')
    //         ->insert([
    //         'sd_id' => $id,
    //         'sd_from' => Auth::user()->u_company,
    //         'sd_destination' => $request->cabang,
    //         'sd_date' => Carbon::now('Asia/Jakarta'),
    //         'sd_nota' => CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 8, 10, 3, 'DK', '-'),
    //         'sd_type' => 'K',
    //         'sd_user' => Auth::user()->u_id
    //         ]);
    //
    //         for ($i=0; $i < count($request->namabarang); $i++) {
    //             if ($request->qty[$i] != 0) {
    //
    //                 $dt = DB::table('d_stockdistributiondt')->max('sdd_detailid')+1;
    //                 DB::table('d_stockdistributiondt')
    //                 ->insert([
    //                 'sdd_stockdistribution' => $id,
    //                 'sdd_detailid' => $dt,
    //                 'sdd_comp' => Auth::user()->u_company,
    //                 'sdd_item' => $request->idbarang[$i],
    //                 'sdd_qty' => $request->qty[$i],
    //                 'sdd_unit' => $request->satuan[$i]
    //                 ]);
    //             }
    //         }
    //
    //         DB::commit();
    //         return response()->json([
    //         'status' => 'berhasil'
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         return response()->json([
    //         'status' => 'gagal'
    //         ]);
    //     }
    //
    // }
    // end: unused (maybe) -> deleted soon ===========================
    // retrive DataTable for distribusibarang

    public function table(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
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
                <button class="btn btn-danger btn-disable-distribusi" onclick="hapus(' . $data->sd_id . ')" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary" onclick="showDetailHt(' . $data->sd_id . ')"><i class="fa fa-folder"></i></button>
            </div>';
            })
            ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
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

    // retrieve DataTable for acceptance distribusibarang
    public function tableAcceptance(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        // if logged in user is 'pusat'
        if (Auth::user()->u_user == 'E' && Auth::user()->getCompany->c_type == 'PUSAT') {
            $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_status', 'P') // status == 'pending'
            ->orderBy('sd_date', 'asc')
            ->orderBy('sd_nota', 'asc')
            ->get();
        }
        // if logged in user is 'cabang'
        elseif (Auth::user()->u_user == 'E' && Auth::user()->getCompany->c_type == 'CABANG') {
            $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_status', 'P') // status == 'pending'
            ->where('sd_destination', '=', Auth::user()->u_company)
            ->orderBy('sd_date', 'asc')
            ->orderBy('sd_nota', 'asc')
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
            <button class="btn btn-primary" onclick="showDetailAc(' . $data->sd_id . ')"><i class="fa fa-folder"></i></button>
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

    // confirm acceptance
    public function setAcceptance(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $stockdist = d_stockdistribution::where('sd_id', $id)
                ->with('getDistributionDt')
                ->first();

            // update stockdist-status to 'Y'
            $stockdist->sd_status = 'Y';
            $stockdist->save();

            foreach ($stockdist->getDistributionDt as $key => $val) {
                $mutasi = Mutasi::confirmDistribusiCabang(
                    $stockdist->sd_from,
                    $stockdist->sd_destination,
                    $val->sdd_item,
                    $stockdist->sd_nota
                );
                if ($mutasi !== true) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => $mutasi
                    ]);
                }
            }

            DB::table('d_stock')
                ->where('s_qty', '=', 0)
                ->where('s_status', '=', 'ON GOING')
                ->delete();
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

    public function hapus(Request $request)
    {
        DB::beginTransaction();
        try {

            $parrent = DB::table('d_stockdistribution')
                ->where('sd_id', $request->id)
                ->first();

            $tmp = DB::table('d_stock_mutation')
                ->where('sm_nota', $parrent->sd_nota)
                ->get();

            $mutcatmasuk = DB::table('m_mutcat')->where('m_name', 'Distribusi Cabang Masuk')->first();
            $mutcatkeluar = DB::table('m_mutcat')->where('m_name', 'Distribusi Cabang Keluar')->first();

            $reff = [];
            $status = 'no';
            for ($i = 0; $i < count($tmp); $i++) {
                if ($tmp[$i]->sm_mutcat == $mutcatkeluar->m_id) {
                    $reff[] = $tmp[$i]->sm_reff;
                }
                if ($tmp[$i]->sm_mutcat == $mutcatmasuk->m_id) {
                    if ($tmp[$i]->sm_use > 0) {
                        $status = 'yes';
                    }
                }

            }

            if ($status == 'no') {
                DB::table('d_stockdistribution')
                    ->where('sd_id', $request->id)
                    ->delete();

                $dt = DB::table('d_stockdistributiondt')
                    ->where('sdd_stockdistribution', $request->id)
                    ->get();

                DB::table('d_stockdistributiondt')
                    ->where('sdd_stockdistribution', $request->id)
                    ->delete();

                DB::table('d_stock_mutation')
                    ->where('sm_nota', $parrent->sd_nota)
                    ->delete();

                for ($i = 0; $i < count($dt); $i++) {
                    DB::table('d_stock')
                        ->where('s_comp', $dt[$i]->sdd_comp)
                        ->where('s_position', $parrent->sd_destination)
                        ->where('s_item', $dt[$i]->sdd_item)
                        ->where('s_status', 'ON DESTINATION')
                        ->where('s_condition', 'FINE')
                        ->update([
                            's_qty' => DB::raw('s_qty - ' . $dt[$i]->sdd_qty)
                        ]);

                    DB::table('d_stock_mutation')
                        ->where('sm_nota', $reff[$i])
                        ->update([
                            'sm_use' => DB::raw('sm_use - ' . $dt[$i]->sdd_qty),
                            'sm_residue' => DB::raw('sm_residue + ' . $dt[$i]->sdd_qty)
                        ]);
                }
            } elseif ($status == 'yes') {
                return response()->json([
                    'status' => 'failed',
                    'ex' => 'Stock yang ada digudang tujuan sudah digunakan'
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal'
            ]);
        }
    }


}
