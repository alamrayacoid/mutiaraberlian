<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_itemout;
use App\d_stock;
use App\d_stock_mutation;
use App\m_item;
use Mutasi;
use Auth;
use carbon\Carbon;
use CodeGenerator;
use DB;
use test\Mockery\MockClassWithUnknownTypeHintTest;
use Validator;
use Yajra\DataTables\DataTables;

class BarangKeluarController extends Controller
{
    /**
     * Return list of items from 'm_item'.
     *
     * @return \Illuminate\Http\Response
     */
    public function getItems(Request $request)
    {
        $term = $request->term;
        $items = m_item::where('i_name', 'like', '%' . $term . '%')
            ->orWhere('i_code', 'like', '%' . $term . '%')
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->get();
        if (sizeof($items) > 0) {
            foreach ($items as $item) {
                $results[] = [
                    'id' => $item->i_id,
                    'label' => $item->i_name,
                    'unit1_id' => $items[0]->getUnit1['u_id'],
                    'unit1_name' => $items[0]->getUnit1['u_name'],
                    'unit2_id' => $items[0]->getUnit2['u_id'],
                    'unit2_name' => $items[0]->getUnit2['u_name'],
                    'unit3_id' => $items[0]->getUnit3['u_id'],
                    'unit3_name' => $items[0]->getUnit3['u_name']
                ];
            }
        } else {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        }
        return response()->json($results);
    }

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
            'itemId' => 'required',
            'qty' => 'required',
            'unit' => 'required',
            'position' => 'required',
            'owner' => 'required',
            'mutcat' => 'required'
        ],
            [
                'itemId.required' => 'Item masih kosong !',
                'qty.required' => 'Jumlah barang masih kosong !',
                'unit.required' => 'Satuan masih kosong !',
                'position.required' => 'Lokasi barang masih kosong !',
                'owner.required' => 'Pemilik barang masih kosong !',
                'mutcat.required' => 'Keterangan masih kosong !'
            ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    /**
     * Return a new 'nota' for creating new 'item out'.
     *
     * @return varchar $nota
     */
    public function getNewNota()
    {
        $nota = CodeGenerator::codeWithSeparator('d_itemout', 'io_nota', 9, 10, 3, 'OUT', '-');
        return $nota;
    }

    /**
     * Return a converted value by unit.
     *
     * @return int outQty
     */
    public function convertOutQtyToSmallestUnit($itemId, $unit, $outQty)
    {
        $outQty = (int)$outQty;
        $item = m_item::where('i_id', $itemId)->first();
        if ($unit == $item->i_unit1) {
            $outQty = $outQty * $item->i_unitcompare1;
        } elseif ($unit == $item->i_unit2) {
            $outQty = $outQty * $item->i_unitcompare2;
        } elseif ($unit == $item->i_unit3) {
            $outQty = $outQty * $item->i_unitcompare3;
        }
        return $outQty;
    }

    /**
     * Inser new 'item out' row.
     *
     * @param string
     * @param int
     */
    public function storeNewItemOut($req)
    {
        try {
            $newId = d_itemout::max('io_id') + 1;
            DB::beginTransaction();
            $itemOut = new d_itemout;
            $itemOut->io_id = $newId;
            $itemOut->io_date = Carbon::now();
            $itemOut->io_nota = $this->getNewNota();
            $itemOut->io_item = $req->itemId;
            $itemOut->io_qty = $req->qty;
            $itemOut->io_unit = $req->unit;
            $itemOut->io_mutcat = $req->mutcat;
            $itemOut->io_user = Auth::user()->employee->e_id;
            $itemOut->save();
            DB::commit();
            return $itemOut;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $pemilik = $request->pemilik;
        $posisi = $request->posisi;
        $produk = $request->produk;
        $mutcat = $request->mutcat;

        $datas = DB::table('d_stock_mutation')
            ->join('d_stock', 'sm_stock', 's_id')
            ->join('m_company as pemilik', 'd_stock.s_comp', 'pemilik.c_id')
            ->join('m_company as posisi', 'd_stock.s_position', 'posisi.c_id')
            ->join('m_mutcat', 'sm_mutcat', '=', 'm_id')
            ->join('m_item', 'i_id', '=', 's_item')
            ->join('m_unit', 'u_id', '=', 'i_unit1')
            ->select('sm_stock','sm_detailid',DB::raw('date_format(sm_date, "%d/%m/%Y") as sm_date'), DB::raw('concat(sm_qty, " ", u_name) as sm_qty'), 'pemilik.c_name as pemilik', 'posisi.c_name as posisi', 's_condition', 'm_name', 'i_name')
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('m_status', '=', 'K');

        if ($from != null || $from != ''){
            $datas->where('sm_date', '>=', $from);
        }
        if ($to != null || $to != ''){
            $datas->where('sm_date', '<=', $to);
        }
        if ($pemilik != 'semua'){
            $datas->where('s_comp', '=', $pemilik);
        }
        if ($posisi != 'semua'){
            $datas->where('s_position', '=', $posisi);
        }
        if ($produk != 'semua'){
            $datas->where('s_item', '=', $produk);
        }
        if ($mutcat != 'semua'){
            $datas->where('sm_mutcat', '=', $mutcat);
        }

        $datas->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('kondisi', function($datas) {
                if($datas->s_condition == 'FINE'){
                    return 'BAIK';
                } else {
                    return 'RUSAK';
                }
            })
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.$datas->sm_stock.'\',\''.$datas->sm_detailid.'\')"><i class="fa fa-folder"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['kondisi','action'])
            ->make(true);

//        $datas = d_itemout::whereBetween('io_date', [$from, $to])
//            ->orderBy('io_id', 'asc')
//            ->with('getUnit')
//            ->with('getMutcat')
//            ->with('getItem')
//            ->get();
//        return Datatables::of($datas)
//            ->addIndexColumn()
//            ->addColumn('code', function ($datas) {
//                return $datas->getItem['i_code'];
//            })
//            ->addColumn('name', function ($datas) {
//                return $datas->getItem['i_name'];
//            })
//            ->addColumn('qty', function ($datas) {
//                return '<span class="pull-right">' . number_format($datas->io_qty, 0, ',', '.') . '</span>';
//            })
//            ->addColumn('unit', function ($datas) {
//                return $datas->getUnit['u_name'];
//            })
//            ->addColumn('mutcat', function ($datas) {
//                return $datas->getMutcat['m_name'];
//            })
//            ->addColumn('action', function ($datas) {
//                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
//            <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="Detail(' . $datas->io_id . ', \'' . $datas->io_nota . '\')"><i class="fa fa-folder"></i>
//            </button>
//            </div>';
//            })
//            ->rawColumns(['code', 'name', 'qty', 'unit', 'mutcat', 'action'])
//            ->make(true);
    }

    /**
     * Return detail of an 'item-out'.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetail($id, $dt)
    {
        // dd($request);
        $data = DB::table('d_stock_mutation')
            ->join('d_stock', 'sm_stock', 's_id')
            ->join('m_company as pemilik', 's_comp', 'pemilik.c_id')
            ->join('m_company as posisi', 's_position', 'posisi.c_id')
            ->join('m_item', 's_item', 'i_id')
            ->join('m_unit', 'i_unit1', 'u_id')
            ->where('sm_stock', '=', $id)
            ->where('sm_detailid', '=', $dt)
            ->select('m_item.i_code as code', 'm_item.i_name as i_name', 'pemilik.c_name as pemilik', 'posisi.c_name as posisi', DB::raw('format(sm_qty, 0) as jumlah'), 'm_unit.u_name as u_name', 'sm_nota as nota', 'sm_hpp')
            ->first();

        // dd($data);
        $detail = DB::table('d_stockmutationdt')
            ->where('smd_stock', '=', $id)
            ->where('smd_stockmutation', '=', $dt)
            ->get();
        return response()->json([
            "data" => $data,
            "detail" => $detail,
            "hpp" => 'Rp. '.number_format($data->sm_hpp, 0,',','.')
        ]);
        // $data = d_itemout::where('io_id', $id)
        //     ->with('getItem')
        //     ->with('getItem.getUnit1')
        //     ->with('getMutationDetail')
        //     ->firstOrFail();
        // return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!AksesUser::checkAkses(15, 'read')) {
            abort(401);
        }

        $pemilik = DB::table('d_stock')
            ->join('m_company as pemilik', 'd_stock.s_comp', 'pemilik.c_id')
            ->where('s_status', '=', 'ON DESTINATION')
            ->groupBy('s_comp')
            ->get();

        $posisi = DB::table('d_stock')
            ->join('m_company as posisi', 'd_stock.s_position', 'posisi.c_id')
            ->where('s_status', '=', 'ON DESTINATION')
            ->groupBy('s_position')
            ->get();

        $produk = DB::table('d_stock')
            ->join('m_item', 'i_id', '=', 's_item')
            ->where('s_status', '=', 'ON DESTINATION')
            ->groupBy('s_item')
            ->get();

        $mutcat = DB::table('d_stock')
            ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
            ->join('m_mutcat', 'm_id', '=', 'sm_mutcat')
            ->select('m_name', 'm_id')
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('m_status', '=', 'K')
            ->whereMonth('sm_date', Carbon::now('Asia/Jakarta'))
            ->groupBy('m_id')
            ->get();

        return view('inventory/barangkeluar/index', compact('pemilik', 'posisi', 'produk', 'mutcat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['company'] = DB::table('m_company')->select('c_id', 'c_name')->get();
        $data['unit'] = DB::table('m_unit')->get();
        $data['mutcat'] = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Keluar%')->get();

        return view('inventory/barangkeluar/create', compact('data'));
    }

    // -------------------- start: function below is unused --------------------
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // // validate request
        // $isValidRequest = $this->validate_req($request);
        // if ($isValidRequest != '1') {
        //     $errors = $isValidRequest;
        //     return response()->json([
        //         'status' => 'invalid',
        //         'message' => $errors
        //     ]);
        // }
        //
        // DB::beginTransaction();
        // try {
        //     // insert new 'item out (d_itemout)'
        //     $storeItemOut = $this->storeNewItemOut($request);
        //     if ($storeItemOut === false) {
        //         return response()->json([
        //             'status' => 'gagal',
        //             'message' => 'Gagal, Barang keluar gagal ditambahkan !'
        //         ]);
        //     }
        //     $itemQtyUnitBase = $this->convertOutQtyToSmallestUnit(
        //         $request->itemId,
        //         $request->unit,
        //         $request->qty
        //     );
        //     // insert new mutasi-keluar
        //     $mutasi = Mutasi::mutasikeluar(
        //         $request->mutcat, // mutcat
        //         $request->owner, // item-owner
        //         $request->position, // item-position
        //         $request->itemId, // item-id
        //         $itemQtyUnitBase, // item-qty in smallest unit
        //         $storeItemOut->io_nota // nota
        //     );
        //
        //     DB::commit();
        //     return response()->json([
        //         'status' => 'berhasil'
        //     ]);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 'gagal',
        //         'message' => $e->getMessage()
        //     ]);
        // }
    }
    // -------------------- end: function is unused --------------------
}
