<?php

namespace App\Http\Controllers\Inventory\Distribusi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_productdelivery;
use App\d_stock;
use App\d_stockdistribution;
use App\d_stockdistributioncode;
use App\d_stockdistributiondt;
use App\m_expedition;
use App\m_item;
use Auth;
use Carbon\Carbon;
use DataTables;
use DB;
use Mutasi;
use Validator;

class ProsesOrderController extends Controller
{
    // retrive dataTable: list order
    public function getListOrder(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_from', Auth::user()->u_company)
            ->where('sd_status', 'N')
            ->orderBy('sd_date', 'desc')
            ->orderBy('sd_nota', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->sd_date)->format('d M Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-warning btn-approve-order" onclick="approveOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Proses Order"><i class="fa fa-get-pocket"></i></button>
                <button class="btn btn-danger btn-reject-order" onclick="rejectOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Tolak Order"><i class="fa fa-ban"></i></button>
            </div>';
            // <button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Print Nota" title="Nota" type="button" onclick="printNota(' . $data->sd_id . ')"><i class="fa fa-print"></i></button>
            })
            ->addColumn('tujuan', function ($data) {
                $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

                return $tmp->c_name;
            })
            ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
            ->make(true);
    }
    // process and approve order
    public function approveOrder($id)
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
            // ->with('getProductDelivery')
            ->first();
        // set variabel to store nota number
        $nota = $data['stockdist']->sd_nota;
        // change number format to int before send it to view
        // $data['stockdist']->getProductDelivery->pd_price = (int)$data['stockdist']->getProductDelivery->pd_price;
        // dd($data);
        // get data item-stock
        foreach ($data['stockdist']->getDistributionDt as $key => $val)
        {
            $item = $val->sdd_item;
            // get item-stock in pusat/werehouse
            $mainStock = d_stock::where('s_position',  Auth::user()->u_company)
                ->where('s_item', $val->sdd_item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            // dd($mainStock);

            if (is_null($mainStock)) {
                $val->stockUnit1 = 0;
                $val->stockUnit2 = 0;
                $val->stockUnit3 = 0;
            }
            else {
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
            }

        }

        $data['expeditions'] = m_expedition::get();

        return view('inventory/distribusibarang/prosesorder/approve', compact('data'));
    }
    // store approved order
    public function storeApproval(Request $request)
    {
        $id = $request->sd_id;
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
            $nota = $request->sd_nota;
            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from
                $request->itemsId, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength // list production-code length each item
            );
            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }

            // get stockdist
            $stockdist = d_stockdistribution::where('sd_id', $id)
            ->with('getDistributionDt.getProdCode')
            ->first();

            // update stockdist
            // $stockdist->sd_from = Auth::user()->u_company;
            $stockdist->sd_date = Carbon::now();
            $stockdist->sd_status = 'P';
            $stockdist->sd_user = Auth::user()->u_id;
            $stockdist->save();
            // insert new stockdist
            // $id = d_stockdistribution::max('sd_id') + 1;
            // $dist = new d_stockdistribution;
            // $dist->sd_id = $id;
            // $dist->sd_from = Auth::user()->u_company;
            // $dist->sd_destination = $request->selectBranch;
            // $dist->sd_date = Carbon::now();
            // $dist->sd_nota = $nota;
            // $dist->sd_user = Auth::user()->u_id;
            // $dist->save();

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

            // delete current stockdist-detail
            foreach ($stockdist->getDistributionDt as $key => $value) {
                $value->delete();
            }

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
                    }
                    elseif ($item->i_unit2 == $request->units[$i]) {
                        $convert = (int)$request->qty[$i] * $item->i_unitcompare2;
                    }
                    elseif ($item->i_unit3 == $request->units[$i]) {
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
                        $request->sd_destination,
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
                }
            }
            // dd('x', $mutDist);
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
    // reject order
    public function rejectOrder($id)
    {
        // if (!AksesUser::checkAkses(7, 'delete')){
        //     abort(401);
        // }

        DB::beginTransaction();
        try {
            $id = decrypt($id);
            // get stockdist
            $stockdist = d_stockdistribution::where('sd_id', $id)
            ->with('getDistributionDt.getProdCode')
            ->with('getProductDelivery')
            ->first();

            // foreach ($stockdist->getDistributionDt as $key => $stockdistDt) {
            //     // rollBack qty in stock-mutation and stock-item
            //     $rollbackDist = Mutasi::rollbackStockMutDist(
            //         $stockdist->sd_nota, // distribution nota
            //         $stockdistDt->sdd_item // item-id
            //     );
            //     if ($rollbackDist !== 'success') {
            //         DB::rollback();
            //         return $rollbackDist;
            //     }
            //     // delete production-code of selected stockdistribution
            //     if (count($stockdistDt->getProdCode) != 0) {
            //         foreach ($stockdistDt->getProdCode as $idx => $prodCode) {
            //             $prodCode->delete();
            //         }
            //     }
            // }
            
            // delete selected stockdistribution-detail
            foreach ($stockdist->getDistributionDt as $key => $stockdistDt) {
                $stockdistDt->delete();
            }
            // // delete selected productDelivery
            // if (!is_null($stockdist->getProductDelivery)) {
            //     $stockdist->getProductDelivery->delete();
            // }
            // delete selected stockdistribution
            $stockdist->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (Exception $e) {
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
}
