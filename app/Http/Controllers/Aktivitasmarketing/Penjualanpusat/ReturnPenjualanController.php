<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_return;
use App\d_salescomp;
use App\d_salescompcode;
use App\m_company;
use App\m_wil_provinsi;
use App\m_wil_kota;
use Carbon\Carbon;
use CodeGenerator;
use DB;
use Mutasi;
use Validator;

class ReturnPenjualanController extends Controller
{
    public function index()
    {
        $data = DB::table("d_return")
                    ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->r_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detail" type="button" onclick="detail(' . $data->r_id . ')" title="Detail"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning btn-process" type="button" onclick="edit(' . $data->r_id . ')" title="Edit"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btn-process" type="button" onclick="hapus(' . $data->r_id . ')" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>';
                // <button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href=\''. route('orderpenjualan.proses') .'?id='.encrypt($data->po_id).'\'"><i class="fa fa-arrow-right"></i></button>
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

        // $branch = m_company::where('c_type', '!=', 'PUSAT')
        // ->where('c_area', '=', $cityId)
        // ->get();

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
        $salesComp = d_salescomp::where('sc_member', $agentCode)
        ->where('sc_type', 'C') // chek it first
        ->select('sc_id')
        ->get();
        $listSalesCompId = array();
        foreach ($salesComp as $key => $val) {
            array_push($listSalesCompId, $val->sc_id);
        }

        $prodCode = d_salescompcode::whereIn('ssc_salescomp', $listSalesCompId)
        ->groupBy('ssc_code')
        ->get();
        
        // if (count($prodCode) == 0) {
        //     $results[] = [
        //         'id' => 0,
        //         'label' => 'Kode produksi tidak ditemukan !'
        //     ];
        // }
        // else {
        //     foreach ($prodCode as $key => $val) {
        //         $results[] = [
        //             'id' => $val->ssc_code,
        //             'label' => $val->ssc_code
        //         ];
        //     }
        // }

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

        return response()->json([
            'data' => $data
        ]);
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
            $qtyReturn = (int)$request->qtyReturn;
            $prodCode = $request->kodeproduksi;
            $type = $request->type;

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
                    'r_type' => $type
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

            $mutationOut = mutasi::salesOut(
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
            $stockParentId = $mutationOut->original['stockParentId'];
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
                $stockParentId, // stock-parent id
                'ON DESTINATION', // item status
                'BROKEN' // item condition
            );
            if ($mutationIn->original['status'] !== 'success') {
                return $mutationIn;
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

    public function delete(Request $request)
    {
        $data = DB::table('d_return')
        ->where('r_id', $request->id)
        ->first();

        DB::table('d_return')
        ->where('r_id', $request->id)
        ->delete();

        mutasi::rollbackStockMutDist($data->r_nota, $data->r_item, 3);

        return response()->json(['status' => 'berhasil']);
    }

}
