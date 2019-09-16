<?php

namespace App\Http\Controllers\Inventory\Distribusi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AksesUser;
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
                <button class="btn btn-info btn-info-order hint--top-left hint--info" aria-label="Info Order" onclick="detailOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Info Order"><i class="fa fa-folder"></i></button>
                <button class="btn btn-success btn-approve-order hint--top-left hint--success" aria-label="Proses Order" onclick="approveOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Proses Order"><i class="fa fa-check"></i></button>
                <button class="btn btn-danger btn-reject-order hint--top-left hint--danger" aria-label="Tolak Order" onclick="rejectOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Tolak Order"><i class="fa fa-ban"></i></button>
            </div>';
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
        if (!AksesUser::checkAkses(16, 'update')) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Anda tidak memiliki akses ini'
            ]);
        }

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
            ->first();
        // set variabel to store nota number
        $nota = $data['stockdist']->sd_nota;
        // get data item-stock
        foreach ($data['stockdist']->getDistributionDt as $key => $val) {
            $kondisistock = 0;
            $item = $val->sdd_item;
            // get item-stock in pusat/werehouse
            $mainStock = d_stock::where('s_position', Auth::user()->u_company)
                ->where('s_item', $val->sdd_item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            if (is_null($mainStock)) {
                $val->stockUnit1 = 0;
                $val->stockUnit2 = 0;
                $val->stockUnit3 = 0;
            }
            else {
                // calculate item-stock based on unit-compare each item
                if ($mainStock->getItem->i_unitcompare1 != null) {
                    $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
                    $kondisistock = $stock['unit1'] . ' ' . $mainStock->getItem->getUnit1->u_name;
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
            $val->kondisistock = $kondisistock;
        }

        $data['expeditions'] = m_expedition::get();

        return view('inventory/distribusibarang/prosesorder/approve', compact('data'));
    }

    // store approved order
    public function storeApproval(Request $request)
    {
        // return json_encode($request->all());
        if (!AksesUser::checkAkses(16, 'create')) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Anda tidak memiliki akses ini'
            ]);
        }

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
            (is_null($request->dateSend)) ? $dateSend = Carbon::now() : $dateSend = Carbon::createFromFormat('d-m-Y', $request->dateSend);

            $nota = $request->sd_nota;
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

            // get stockdist
            $stockdist = d_stockdistribution::where('sd_id', $id)
                ->with('getDistributionDt.getProdCode')
                ->first();

            // update stockdist
            $stockdist->sd_date = $dateSend;
            $stockdist->sd_status = 'P';
            $stockdist->sd_user = Auth::user()->u_id;
            $stockdist->save();

            // insert new product-delivery
            $idDeliv = d_productdelivery::max('pd_id') + 1;
            $prodDeliv = new d_productdelivery;
            $prodDeliv->pd_id = $idDeliv;
            $prodDeliv->pd_date = $dateSend;
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
                $jumlahkode = 0;

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
                    $prodCodeLength = (int)$request->prodCodeLength[$i];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    $sumQtyPC = 0;
                    $listPC = array();

                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] === null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        }
                        array_push($listPC, strtoupper($request->prodCode[$j]));
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
                        $sumQtyPC += (int)$request->qtyProdCode[$j];
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

                    // validate qty production-code
                    if ($sumQtyPC != $convert) {
                        $item = m_item::where('i_id', $itemId)->first();
                        throw new Exception("Jumlah kode produksi " . strtoupper($item->i_name) . " tidak sama dengan jumlah item yang dipesan !");
                    }
                    // declaare list of production-code
                    // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];

                    // insert stock mutation sales 'out'
                    $mutDistributionOut = Mutasi::distributionOut(
                        Auth::user()->u_company, // from (company-id)
                        Auth::user()->u_company, // item-owner (company-id)
                        $itemId, // item id
                        $convert, // qty item
                        $nota, // nota distribution
                        null, // nota refference
                        $listPC, // list production-code
                        $listQtyPC, // list qty of production-code
                        $listUnitPC, // list unit of production-code
                        $sellPrice = null, // sellprice
                        19, // mutation category
                        $dateSend // date
                    );
                    if ($mutDistributionOut->original['status'] !== 'success') {
                        return $mutDistributionOut;
                    }
                    // set stock-parent-id
                    $listStockParentId = $mutDistributionOut->original['listStockParentId'];
                    // get list
                    $listSellPrice = $mutDistributionOut->original['listSellPrice'];
                    $listHPP = $mutDistributionOut->original['listHPP'];
                    $listSmQty = $mutDistributionOut->original['listSmQty'];
                    $listPCReturn = $mutDistributionOut->original['listPCReturn'];
                    $listQtyPCReturn = $mutDistributionOut->original['listQtyPCReturn'];
                    // dd($listSmQty, $listPCReturn, $listQtyPCReturn);
                    // insert stock mutation using sales 'in'
                    $mutDistributionIn = Mutasi::distributionIn(
                        Auth::user()->u_company, // item-owner (company-id)
                        $request->sd_destination, // destination (company-id)
                        $itemId, // item id
                        $nota, // nota sales
                        $listPCReturn, // list of list production-code (based on how many smQty used / each smQty has a list of prod-code)
                        $listQtyPCReturn, // list of list qty of production-code
                        $listUnitPC, // list  unit of production-code (unused)
                        $listSellPrice, // list of sellprice
                        $listHPP, // list of hpp
                        $listSmQty, // lsit of sm-qty (it got from salesOut, each qty used from different stock-mutation)
                        18, // mutation category
                        null, // stock parent id
                        $status = 'ON GOING', // items status in stock
                        $condition = 'FINE', // item condition in stock
                        $dateSend // date
                    );
                    if ($mutDistributionIn->original['status'] !== 'success') {
                        return $mutDistributionIn;
                    }

                    $startProdCodeIdx += $prodCodeLength;
                }
                else {
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

// start: pembukuan jurnal
// end: pembukuan jurnal

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

            // delete selected stockdistribution-detail
            foreach ($stockdist->getDistributionDt as $key => $stockdistDt) {
                $stockdistDt->delete();
            }

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
