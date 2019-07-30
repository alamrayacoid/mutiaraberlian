<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\d_return;
use App\d_returndt;
use App\d_returncode;
use App\d_stock;
use App\d_salescomp;
use App\d_salescompcode;
// use App\d_sales;
// use App\d_salescode;
use App\m_company;
use App\m_item;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use DB;
use DataTables;
use Mutasi;
use Mockery\Exception;
use Validator;

class ReturnPenjualanController extends Controller
{
    public function index()
    {
        $data = d_return::get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->r_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('type', function($data){
                if ($data->r_type == 'GB') {
                    return '<span class="badge badge-primary">Ganti Barang</span>';
                } elseif ($data->r_type == 'GU') {
                    return '<span class="badge badge-success">Ganti Uang</span>';
                } else {
                    return '<span class="badge badge-info">Potong Nota</span>';
                }
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-primary btn-detail" type="button" onclick="detailReturn(' . $data->r_id . ')" title="Detail"><i class="fa fa-folder"></i></button>
                    <button class="btn btn-danger btn-process" type="button" onclick="deleteReturn(' . $data->r_id . ')" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>';
                // <button class="btn btn-warning btn-process" type="button" onclick="editReturn(' . $data->r_id . ')" title="Edit"><i class="fa fa-pencil"></i></button>
            })
            ->addColumn('agen', function($data){
                $member = DB::table('m_company')
                            ->where('c_id', $data->r_member)
                            ->first();

                return $member->c_name;
            })
            ->rawColumns(['tanggal', 'action', 'type'])
            ->make(true);

        return response()->json($data);
    }

    public function create()
    {
        return view('marketing/marketingarea/returnpenjualan/create');
    }
    // get branch
    public function getAgent(Request $request)
    {
        $mma = Auth::user()->getCompany->c_id;
        $agent = m_company::where('c_type', 'AGEN')
        ->whereHas('getAgent', function ($q) use ($mma) {
            $q->where('a_mma', $mma);
        })
        ->get();

        return response()->json(array(
            'success' => true,
            'data' => $agent
        ));
    }
    // get production-code
    public function getProdCode(Request $request)
    {
        $agentCode = $request->agentCode;
        if (is_null($agentCode)) {
            $agentCode = Auth::user()->getCompany->c_id;
        }
        // dd($agentCode);
        // get list salescomp-id by agent
        $kode = d_stock::with('getStockDt')
            ->where('s_position', '=', $agentCode)
            ->get();

        $listSalesCompId = array();
        foreach ($kode as $key => $val) {
            array_push($listSalesCompId, $val->getStockDt[0]->sd_code);
        }

        $prodCode = d_salescompcode::whereIn('ssc_code', $listSalesCompId)
            ->whereHas('getSalesCompById', function($q) use ($agentCode) {
                $q->where('sc_member', $agentCode);
            })
            ->groupBy('ssc_code')
            ->get();

        return response()->json($prodCode);
    }
    // get list nota based on production-code
    public function getNota(Request $request)
    {
        $prodCode = $request->prodCode;
        $agentCode = $request->agentCode;

        $listNota = d_salescompcode::where('ssc_code', 'like', '%'. $prodCode .'%')
        ->whereHas('getSalesCompById', function($q) use ($agentCode) {
            $q->where('sc_member', $agentCode);
        })
        ->with(['getSalesCompById' => function($q) {}])
        ->get();

        return response()->json($listNota);
    }
    // get items using autocomple.js
    public function findItem(Request $request)
    {
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        if (Auth::user()->getCompany->c_type == "PUSAT") {
            if ($request->agent == '' || is_null($request->agent)) {
                $results[] = ['id' => null, 'label' => 'Silahkan isi agen terlebih dahulu !'];
                return Response::json($results);
            }
            $comp = m_company::where('c_user', $request->agent)->first();
        } else {
            $comp = Auth::user()->getCompany;
        }

        // return if $comp is-null
        if (is_null($comp)) {
            $results[] = ['id' => null, 'label' => 'Agen tidak memiliki item apapun'];
            return Response::json($results);
        }

        $comp = $comp->c_id;
        $cari = $request->term;

        if (count($is_item) == 0) {
            $nama = d_stock::where('s_position', $comp)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->where('s_qty', '>', 0)
            ->whereHas('getItem', function ($q) use ($cari) {
                $q->where('i_name', 'like', '%' . $cari . '%');
            })
            ->with('getItem')
            ->get();
        }
        else {
            $nama = d_stock::where('s_position', $comp)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->where('s_qty', '>', 0)
            ->whereHas('getItem', function ($q) use ($cari) {
                $q->where('i_name', 'like', '%' . $cari . '%');
            })
            ->with('getItem')
            ->whereNotIn('s_item', $is_item)
            ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->getItem->i_id, 'label' => $query->getItem->i_code . ' - ' . strtoupper($query->getItem->i_name), 'data' => $query, 'stock' => $query->s_id];
            }
        }
        return response()->json($results);
    }
    // get satuan of an item
    public function getUnit($id)
    {
        $data = m_item::where('i_id', $id)
        ->with('getUnit1')
        ->with('getUnit2')
        ->with('getUnit3')
        ->first();

        return response()->json($data);
    }
    // check item stock
    public function checkStock($stock = null, $item = null, $satuan = null, $qty = null)
    {
        $data_check = DB::table('m_item')
        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
        'm_item.i_unit3 as unit3')
        ->where('i_id', '=', $item)
        ->first();

        $data = DB::table('d_stock')
        ->join('d_stock_mutation', function ($sm) {
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
                $qty_compare = (int)$data->sisa / (int)$data_check->compare2;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa / (int)$data_check->compare3;
            } else {
                $qty_compare = $qty;
            }
        }

        return response()->json(floor($qty_compare));
    }
    // get sales-comp data
    public function getData(Request $request)
    {
        $nota = $request->nota;
        $itemId = $request->itemId;
        $prodCode = $request->prodCode;

        $data = d_salescomp::where('sc_nota', $request->nota)
            ->with('getComp')
            ->with('getAgent')
            ->with(['getSalesCompDt' => function($q) use ($itemId, $prodCode) {
                $q->where('scd_item', $itemId)
                    ->with('getItem')
                    ->with(['getProdCode' => function($q) use ($prodCode) {
                        $q->where('ssc_code', $prodCode);
                    }]);
            }])
            ->first();

        $data->sc_date = Carbon::parse($data->sc_date)->format('d M Y');

        $data->sc_total = number_format($data->sc_total, 2, ",", ".");

        return response()->json([
            'data' => $data
        ]);
    }
    // store data to Database
    public function store(Request $request)
    {
        // // validate request
        // $isValidRequest = $this->validateData($request);
        // if ($isValidRequest != '1') {
        //     $errors = $isValidRequest;
        //     return response()->json([
        //         'status' => 'invalid',
        //         'message' => $errors
        //     ]);
        // }
        DB::beginTransaction();
        try {
            $notaPenjualan = $request->nota;
            $member = $request->agent;
            $itemId = $request->itemId;
            $prodCode = $request->kodeproduksi;
            $qtyReturn = (int)$request->qtyReturn;
            $type = $request->type;
            $note = $request->keterangan;

            // get comp using agent-code
            if (Auth::user()->getCompany->c_type == "PUSAT") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            }
            else {
                $agent = Auth::user()->getCompany;
            }

            // dd($request->all());
            // get data salescomp
            $dataSales = d_salescomp::where('sc_nota', $notaPenjualan)->first();

            $nota = CodeGenerator::codeWithSeparator('d_return', 'r_nota', 8, 10, 3, 'RT', '-');
            $id = d_return::max('r_id') + 1;

            // insert data to table d_return
            DB::table('d_return')
                ->insert([
                    'r_id' => $id,
                    'r_nota' => $nota,
                    'r_reff' => $notaPenjualan,
                    'r_date' => Carbon::now('Asia/Jakarta'),
                    'r_comp' => $agent->c_id,
                    'r_member' => $member,
                    'r_item' => $itemId,
                    'r_qty' => $qtyReturn,
                    'r_code' => $prodCode,
                    'r_type' => $type,
                    'r_note' => $note
                ]);

            if ($type == 'GB') {
                $mutcat = 16;
            }
            elseif ($type == 'GU') {
                $mutcat = 15;
            }
            else {
                $mutcat = 17;
            }

            // set list of production-code and qty each production-code
            $listPC = array($prodCode);
            $listQtyPC = array($qtyReturn);
            $listUnitPC = array();

            // insert stock mutation using sales 'out'
            $mutationOut = Mutasi::salesOut(
                $member, // from
                null, // to
                $itemId, // item id
                $qtyReturn, // qty item
                $nota, // nota return
                $listPC, // list production-code
                $listQtyPC, // list qty of production-code
                $listUnitPC, // list unit pf production-code
                null,// sellPrice
                $mutcat // mutcat
            );
            if ($mutationOut->original['status'] !== 'success') {
                return $mutationOut;
            }
            // set stock-parent-id
            $listStockParentId = $mutationOut->original['listStockParentId'];
            // get list
            $listSellPrice = $mutationOut->original['listSellPrice'];
            $listHPP = $mutationOut->original['listHPP'];
            $listSmQty = $mutationOut->original['listSmQty'];
            $listPCReturn = $mutationOut->original['listPCReturn'];
            $listQtyPCReturn = $mutationOut->original['listQtyPCReturn'];

            // insert stock mutation using sales 'in'
            $mutationIn = Mutasi::salesIn(
                // $member, // from
                $dataSales->sc_comp, // to
                $itemId, // item-id
                $nota, // nota return
                $listPCReturn, // list of list production-code
                $listQtyPCReturn, // list of list production-code-qty
                $listUnitPC, // list of production-code-unit
                $listSellPrice, // list of sellprice
                $listHPP,
                $listSmQty,
                3, // mutcat masuk return barang rusak
                null, // stock-parent id
                'ON DESTINATION', // item status
                'BROKEN' // item condition
            );
            if ($mutationIn->original['status'] !== 'success') {
                return $mutationIn;
            }

            // insert new mutation for 'ganti barang'
            if ($type == 'GB') {
                // validate 'ganti barang'
                if ((int)$request->subsValue > (int)$request->returnValue) {
                    throw new Exception("Total Nilai Pengganti tidak boleh melebihi Total Nilai Return", 1);
                }
                dd($request->all());

                // validate production-code is exist in stock-item
                $validateProdCode = Mutasi::validateProductionCode(
                    $agent->c_id, // from
                    $request->idItem, // list item-id
                    $request->prodCode, // list production-code
                    $request->prodCodeLength ,// list production-code length each item
                    $request->qtyProdCode // list of qty each production-code
                );
                if ($validateProdCode !== 'validated') {
                    DB::rollback();
                    return $validateProdCode;
                }

                $startProdCodeIdx = 0;
                $rdDetailid = d_returndt::where('rd_return', $id)->max('rd_detailid') + 1;
                foreach ($request->idItem as $key => $itemIdX) {
                    // insert detail exchange-items in return-detail
                    $retDetail = new d_returndt();
                    $retDetail->rd_return = $id;
                    $retDetail->rd_detailid = $rdDetailid;
                    $retDetail->rd_item = $itemIdX;
                    $retDetail->rd_qty = $request->jumlah[$key];
                    $retDetail->rd_unit = $request->satuan[$key];
                    $retDetail->rd_value = (int)Currency::removeRupiah($request->harga[$key]);
                    $retDetail->rd_delivered = 'N';
                    $retDetail->save();

                    $prodCodeLength = (int)$request->prodCodeLength[$key];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    $sumQtyPC = 0;
                    $listPC = array();
                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        }
                        array_push($listPC, strtoupper($request->prodCode[$j]));

                        // insert code exchange-items in return-code
                        $rcDetailid = d_returncode::where('d_return', $retDetail->rd_return)
                        ->where('d_returndt', $retDetail->rd_detailid)
                        ->max('d_detailid') + 1;

                        $retCode = new d_returncode();
                        $retCode->d_return = $retDetail->rd_return;
                        $retCode->d_returndt = $retDetail->rd_detailid;
                        $retCode->d_detailid = $rcDetailid;
                        $retCode->d_code = strtoupper($request->prodCode[$j]);
                        $retCode->d_qty = (int)$request->qtyProdCode[$j];
                        $retCode->save();
                    }

                    // get qty in smallest unit
                    $data_check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                    'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                    'm_item.i_unit3 as unit3')
                    ->where('i_id', $itemIdX)
                    ->first();

                    $qty_compare = 0;
                    $sellPrice = 0;
                    if ($request->satuan[$key] == $data_check->unit1) {
                        $qty_compare = $request->jumlah[$key];
                        $sellPrice = (int)Currency::removeRupiah($request->harga[$key]);
                    } else if ($request->satuan[$key] == $data_check->unit2) {
                        $qty_compare = $request->jumlah[$key] * $data_check->compare2;
                        $sellPrice = (int)Currency::removeRupiah($request->harga[$key]) / $data_check->compare2;
                    } else if ($request->satuan[$key] == $data_check->unit3) {
                        $qty_compare = $request->jumlah[$key] * $data_check->compare3;
                        $sellPrice = (int)Currency::removeRupiah($request->harga[$key]) / $data_check->compare3;
                    }

                    // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];

                    $mutationOut = mutasi::salesOut(
                        $dataSales->sc_comp, // from
                        null, // to
                        $itemIdX, // item id
                        $qty_compare, // qty item GB
                        $nota, // nota return
                        $listPC, // list production-code
                        $listQtyPC, // list qty of production-code
                        $listUnitPC, // list unit pf production-code
                        $sellPrice,// sellPrice
                        5 // mutcat sales to agent
                    );
                    if ($mutationOut->original['status'] !== 'success') {
                        return $mutationOut;
                    }

                    // get list
                    $listSellPrice = $mutationOut->original['listSellPrice'];
                    $listHPP = $mutationOut->original['listHPP'];
                    $listSmQty = $mutationOut->original['listSmQty'];
                    $listPCReturn = $mutationOut->original['listPCReturn'];
                    $listQtyPCReturn = $mutationOut->original['listQtyPCReturn'];

                    // insert stock mutation using sales 'in'
                    $mutationIn = Mutasi::salesIn(
                        $member, // to
                        $itemIdX, // item-id
                        $nota, // nota return
                        $listPCReturn, // list of list production-code
                        $listQtyPCReturn, // list of list production-code-qty
                        $listUnitPC, // list of production-code-unit
                        $listSellPrice, // list of sellprice
                        $listHPP,
                        $listSmQty,
                        20, // mutcat masuk return barang rusak
                        null // stock-parent id
                    );
                    if ($mutationIn->original['status'] !== 'success') {
                        return $mutationIn;
                    }

                    $startProdCodeIdx += $prodCodeLength;
                    $rdDetailid++;
                    // // set list of production-code and qty each production-code
                    // $listPCGB = array($prodCodeGB);
                    // $listQtyPCGB = array($qtyGB);
                    // $listUnitPCGB = array();
                    //
                    // $mutationOut = mutasi::salesOut(
                    //     $dataSales->sc_comp, // from
                    //     $member, // to
                    //     $itemId, // item id
                    //     $qtyGB, // qty item GB
                    //     $nota, // nota return
                    //     $listPCGB, // list production-code
                    //     $listQtyPCGB, // list qty of production-code
                    //     $listUnitPCGB, // list unit pf production-code
                    //     null,// sellPrice
                    //     5 // mutcat sales to agent
                    // );
                    // if ($mutationOut->original['status'] !== 'success') {
                    //     return $mutationOut;
                    // }
                    // // set stock-parent-id
                    // $listStockParentId = $mutationOut->original['listStockParentId'];
                    // // get list
                    // $listSellPrice = $mutationOut->original['listSellPrice'];
                    // $listHPP = $mutationOut->original['listHPP'];
                    // $listSmQty = $mutationOut->original['listSmQty'];
                    // $listPCReturn = $mutationOut->original['listPCReturn'];
                    // $listQtyPCReturn = $mutationOut->original['listQtyPCReturn'];
                    //
                    // // insert stock mutation using sales 'in'
                    // $mutationIn = Mutasi::salesIn(
                    //     $member, // to
                    //     $itemId, // item-id
                    //     $nota, // nota return
                    //     $listPCReturn, // list of list production-code
                    //     $listQtyPCReturn, // list of list production-code-qty
                    //     $listUnitPC, // list of production-code-unit
                    //     $listSellPrice, // list of sellprice
                    //     $listHPP,
                    //     $listSmQty,
                    //     20, // mutcat masuk return barang rusak
                    //     null // stock-parent id
                    // );
                    // if ($mutationIn->original['status'] !== 'success') {
                    //     return $mutationIn;
                    // }
                }
            }

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
    // delete data 'return' from database
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = d_return::where('r_id', $id)
            ->with('getReturnDt.getProdCode')
            ->first();

            // get mutcat 'out' based on return-type
            switch ($data->r_type) {
                case 'GB':
                    $mutcatOut = 16;
                    break;
                case 'GU':
                    $mutcatOut = 15;
                    break;
                case 'PN':
                    $mutcatOut = 17;
                    break;
                default:
                    break;
            }

            // rollback mutation 'out'
            $mutRollbackOut = Mutasi::rollbackSalesOut(
                $data->r_nota,
                $data->r_item,
                $mutcatOut
            );
            if ($mutRollbackOut->original['status'] !== 'success') {
                return $mutRollbackOut;
            }
            // rollback mutation 'in'
            $mutRollbackIn = Mutasi::rollbackSalesIn(
                $data->r_nota,
                $data->r_item,
                3
            );
            if ($mutRollbackIn->original['status'] !== 'success') {
                return $mutRollbackIn;
            }

            // extra rollback for 'ganti barang'
            if ($data->r_type == 'GB') {
                foreach ($data->getReturnDt as $key => $val) {
                    // rollback mutation 'out'
                    $mutRollbackOut = Mutasi::rollbackSalesOut(
                        $data->r_nota,
                        $val->rd_item,
                        5
                    );
                    if ($mutRollbackOut->original['status'] !== 'success') {
                        return $mutRollbackOut;
                    }
                    // rollback mutation 'in'
                    $mutRollbackIn = Mutasi::rollbackSalesIn(
                        $data->r_nota,
                        $val->rd_item,
                        20
                    );
                    if ($mutRollbackIn->original['status'] !== 'success') {
                        return $mutRollbackIn;
                    }

                    foreach ($val->getProdCode as $idx => $pc) {
                        $pc->delete();
                    }
                    $val->delete();
                }
            }

            $ret = d_return::where('r_id', $id)->delete();

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
}
