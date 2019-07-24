<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

use App\d_stockdt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_return;
use App\d_salescomp;
use App\d_salescompcode;
use App\d_stock;
use App\m_company;
use App\m_wil_provinsi;
use App\m_wil_kota;
use Carbon\Carbon;
use CodeGenerator;
use DB;
use DataTables;
use Mutasi;
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
        $provinsi = m_wil_provinsi::get();
        return view('marketing/penjualanpusat/returnpenjualan/create', compact('provinsi'));
    }
    // get list city
    public function getCity(Request $request)
    {
        $provId = $request->provId;
        $city = m_wil_kota::select('wc_id', 'wc_name')
        ->where('wc_provinsi', '=', $provId)
        ->orderBy('wc_name', 'asc')
        ->get();

        return response()->json(array(
            'success' => true,
            'data' => $city
        ));
    }
    // get branch
    public function getAgent(Request $request)
    {
        $cityId = $request->cityId;
        $agent = m_company::where('c_type', '!=', 'PUSAT')
        ->whereHas('getAgent', function ($q) use ($cityId) {
            $q->where('a_area', '=', $cityId);
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

        // get list salescomp-id by agent
        $kode = d_stock::with('getStockDt')
            ->where('s_position', '=', $agentCode)
            ->get();

        $listSalesCompId = array();
        foreach ($kode as $key => $val) {
            array_push($listSalesCompId, $val->sd_code);
        }

        $prodCode = d_salescompcode::whereIn('ssc_code', $listSalesCompId)
            ->groupBy('ssc_salescomp')
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
        ->with(['getSalesCompById' => function($q) {
        }])
        ->whereHas('getSalesCompDt', function($q) use ($agentCode) {
            $q->whereHas('getStock', function ($query) use ($agentCode) {
                $query
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE')
                    ->where('s_position', $agentCode)
                    ->where('s_comp', $agentCode);
            });
        })
        ->get();

        return response()->json($listNota);
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

        // $comp = DB::table('m_company')
        //             ->where('c_id', $data->sc_comp)
        //             ->first();
        // $agen = DB::table('m_company')
        //             ->where('c_id', $data->sc_member)
        //             ->first();
        // $item = DB::table('d_salescompdt')
        //             ->join('m_item', 'i_id', '=', 'scd_item')
        //             ->where('scd_sales', $data->sc_id)
        //             ->where('scd_item', $request->itemid)
        //             ->first();

        $data->sc_date = Carbon::parse($data->sc_date)->format('d M Y');

        $data->sc_total = number_format($data->sc_total, 2, ",", ".");
        // dd($data);
        return response()->json([
            'data' => $data
        ]);
    }
    // get production-code substitute
    public function getProdCodeSubstitute(Request $request)
    {
        $sellerCode = $request->sellerCode;
        $itemId = $request->itemId;

        // get production-code in seller position
        $stocks = d_stock::where('s_position', $sellerCode)
        ->where('s_status', 'ON DESTINATION')
        ->where('s_condition', 'FINE')
        ->where('s_item', $itemId)
        ->where('s_qty', '>', 0)
        ->with(['getStockDt' => function($q) {
            $q->where('sd_qty', '>', 0);
        }])
        ->get();

        $listProdCode = array();
        foreach ($stocks as $key => $stock) {
            foreach ($stock->getStockDt as $key => $stockDt) {
                if (!in_array($stockDt->sd_code, $listProdCode)) {
                    array_push($listProdCode, $stockDt->sd_code);
                }
            }
        }

        return response()->json($listProdCode);
    }
    // validate request
    public function validateData(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'agent' => 'required',
            'kodeproduksi' => 'required',
            'nota' => 'required',
            'qty' => 'required'
        ],
        [
            'agent.required' => 'Agen masih kosong !',
            'kodeproduksi.required' => 'Kode Produksi masih kosong !',
            'nota.required' => 'Nota masih kosong !',
            'qty.required' => 'Jumlah barang masih kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }
    // store data to Database
    public function store(Request $request)
    {
        // validate request
        $isValidRequest = $this->validateData($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $notaPenjualan = $request->nota;
            $member = $request->agent;
            $itemId = $request->itemId;
            $qty = (int)$request->qty;
            $prodCode = $request->kodeproduksi;
            $qtyReturn = (int)$request->qtyReturn;
            $type = $request->type;
            $prodCodeGB = $request->kodeproduksiGB;
            $qtyGB = (int)$request->qtyGB;
            $note = $request->keterangan;

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
                $dataSales->sc_comp, // to
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
                $listStockParentId, // stock-parent id
                'ON DESTINATION', // item status
                'BROKEN' // item condition
            );
            if ($mutationIn->original['status'] !== 'success') {
                return $mutationIn;
            }

            // insert new mutation for 'ganti barang'
            if ($type == 'GB') {
                // set list of production-code and qty each production-code
                $listPCGB = array($prodCodeGB);
                $listQtyPCGB = array($qtyGB);
                $listUnitPCGB = array();

                $mutationOut = mutasi::salesOut(
                    $dataSales->sc_comp, // from
                    $member, // to
                    $itemId, // item id
                    $qtyGB, // qty item GB
                    $nota, // nota return
                    $listPCGB, // list production-code
                    $listQtyPCGB, // list qty of production-code
                    $listUnitPCGB, // list unit pf production-code
                    null,// sellPrice
                    5 // mutcat sales to agent
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
                    $member, // to
                    $itemId, // item-id
                    $nota, // nota return
                    $listPCReturn, // list of list production-code
                    $listQtyPCReturn, // list of list production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $listSellPrice, // list of sellprice
                    $listHPP,
                    $listSmQty,
                    20, // mutcat masuk return barang rusak
                    $listStockParentId // stock-parent id
                );
                if ($mutationIn->original['status'] !== 'success') {
                    return $mutationIn;
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $th->getMessage()
            ]);
        }
    }
    // delete data 'return' from database
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $data = d_return::where('r_id', $id)
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
                // rollback mutation 'out'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $data->r_nota,
                    $data->r_item,
                    5
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }
                // rollback mutation 'in'
                $mutRollbackIn = Mutasi::rollbackSalesIn(
                    $data->r_nota,
                    $data->r_item,
                    20
                );
                if ($mutRollbackIn->original['status'] !== 'success') {
                    return $mutRollbackIn;
                }
            }


            d_return::where('r_id', $id)
            ->delete();
            // dd('x');

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
