<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

use App\Http\Controllers\AksesUser;
use App\Http\Controllers\MarketingController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use Auth;
use Response;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;
use App\d_productorder;
use App\d_productorderdt;
use App\d_stock;

class PenjualanPusatController extends Controller
{
    public function index()
    {
        if (!AksesUser::checkAkses(20, 'read')){
            abort(401);
        }
        return view('marketing/penjualanpusat/index');
    }

    // Terima Order Penjualan
    public function getTableTOP()
    {
        $data = DB::table('d_productorder')
            ->leftjoin('m_company', 'c_id', '=', 'po_agen')
            ->where('po_status', 'P')
            ->where('po_comp', '=', Auth::user()->u_company)
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->po_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detail" type="button" onclick="getDetailTOP(' . $data->po_id . ')" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
                <button class="btn btn-success btn-process" type="button" onclick="processTOP(' . $data->po_id . ')" title="Proses" data-toggle="modal" data-target="#modalProcessTOP"><i class="fa fa-arrow-right"></i></button>
                </div>';
                // <button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href=\''. route('orderpenjualan.proses') .'?id='.encrypt($data->po_id).'\'"><i class="fa fa-arrow-right"></i></button>
            })
            ->addColumn('total', function ($data) {
                $tmp = DB::table('d_productorderdt')->where('pod_productorder', $data->po_id)->sum('pod_totalprice');

                return "Rp " . number_format($tmp, 2, ',', '.');;
            })
            ->rawColumns(['tanggal', 'action', 'total'])
            ->make(true);
    }

    public function getDetailTOP(Request $request)
    {
        $data = d_productorder::where('po_id', $request->id)
            ->with('getAgent')
            ->with(['getPODt' => function ($query) {
                $query
                    ->with(['getItem' => function ($query) {
                        $query
                            ->with('getUnit1')
                            ->with('getUnit2')
                            ->with('getUnit3')
                            ->get();
                    }])
                    ->with('getUnit')
                    ->get();
            }])
            ->first();

        // check again how to get stock, is it true ?
//        $stockItem = array();
//        foreach ($data->getPODt as $key => $val) {
//            $getStock = d_stock::where('s_item', $val->pod_item)
//                ->where('s_position', $data->po_comp)
//                ->where('s_status', 'ON DESTINATION')
//                ->where('s_condition', 'FINE')
//                ->first();
//
//            array_push($stockItem, $getStock->s_qty);
//        }

        //$data->stockItem = $stockItem;
        $data->total = d_productorderdt::where('pod_productorder', $request->id)->sum('pod_totalprice');
        $data->dateFormated = Carbon::parse($data->po_date)->format('d M Y');

        return response()->json($data);
    }

    public function getProsesTOP(Request $request)
    {
        $data = d_productorder::where('po_id', $request->id)
            ->with('getAgent')
            ->with(['getPODt' => function ($query) {
                $query
                    ->with(['getItem' => function ($query) {
                        $query
                            ->with('getUnit1')
                            ->with('getUnit2')
                            ->with('getUnit3')
                            ->get();
                    }])
                    ->with('getUnit')
                    ->get();
            }])
            ->first();

         //check again how to get stock, is it true ?
        $stockItem = array();
        $satuanItem = array();

        foreach ($data->getPODt as $key => $val) {
            $getStock = d_stock::where('s_item', $val->pod_item)
                ->where('s_position', $data->po_comp)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->first();

            $item = DB::table('m_item')
                ->join('m_unit', 'i_unit1', '=', 'u_id')
                ->where('i_id', '=', $val->pod_item)
                ->first();

            if ($getStock != null){
                array_push($satuanItem, $getStock->s_qty . ' ' . $item->u_name);
                array_push($stockItem, $getStock->s_qty);
            }
            else {
                array_push($satuanItem, 0 . ' ' . $item->u_name);
                array_push($stockItem, 0);
            }
        }

        $data->stockItem = $stockItem;
        $data->stockTable = $satuanItem;
        $data->total = d_productorderdt::where('pod_productorder', $request->id)->sum('pod_totalprice');
        $data->dateFormated = Carbon::parse($data->po_date)->format('d M Y');

        return response()->json($data);
    }

    public function confirmProcessTOP(Request $request, $id)
    {
        $id_po = $request->idPO;
        $qty = $request->qty;
        $item = $request->itemId;
        $unit = $request->unit;
        $harga = $request->hargasatuan;

        DB::beginTransaction();
        try {

            DB::table('d_productorder')
                ->where('po_id', '=', $id_po)
                ->update([
                    'po_status' => 'Y'
                ]);

            for($i = 0; $i < count($item); $i++){
                DB::table('d_productorderdt')
                    ->where('pod_productorder', '=', $id_po)
                    ->where('pod_item', '=', $item[$i])
                    ->update([
                        'pod_unit' => $unit[$i],
                        'pod_price' => $harga[$i],
                        'pod_qty' => $qty[$i],
                        'pod_totalprice' => $qty[$i] * $harga[$i],
                        'pod_isapproved' => 'Y'
                    ]);
            }

            DB::table('d_productorderdt')
                ->whereNotIn('pod_item', $item)
                ->update([
                    'pod_isapproved' => 'N',
                    'pod_qty' => 0,
                    'pod_price' => 0,
                    'pod_totalprice' => 0,
                ]);

            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function orderpenjualan_proses(Request $request)
    {
        try {
            $tmp = decrypt($request->id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $data = DB::table('d_productorder')->where('po_id', $tmp)->first();

        $dt = DB::table('d_productorderdt')->join('m_item', 'pod_item', '=', 'i_id')->where('pod_productorder', $tmp)->get();

        $unit1 = [];
        $unit2 = [];
        $unit3 = [];
        for ($i = 0; $i < count($dt); $i++) {
            $unit1[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit1)->first();
            $unit2[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit2)->first();
            $unit3[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit3)->first();
        }

        return view('marketing.penjualanpusat.terimaorder.proses', compact('data', 'dt', 'unit1', 'unit2', 'unit3'));
    }

    public function getTarget(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $dt = Crypt::decrypt($request->dt_id);
        $data = DB::table('d_salestarget')
            ->join('d_salestargetdt', 'st_id', '=', 'std_salestarget')
            ->join('m_item', 'i_id', '=', 'std_item')
            ->leftJoin('m_unit as satuan1', 'satuan1.u_id', 'm_item.i_unit1')
            ->leftJoin('m_unit as satuan2', 'satuan2.u_id', 'm_item.i_unit2')
            ->leftJoin('m_unit as satuan3', 'satuan3.u_id', 'm_item.i_unit3')
            ->join('m_company', 'c_id', '=', 'st_comp')
            ->select('d_salestarget.*', 'd_salestargetdt.*', 'c_name', DB::raw('concat(date_format(st_periode, "%m/%Y")) as periode'), 'i_unit1', 'i_unit2', 'i_unit3', 'i_name', 'satuan1.u_name as satuan1', 'satuan2.u_name as satuan2', 'satuan3.u_name as satuan3')
            ->where('st_id', '=', $id)
            ->where('std_detailid', '=', $dt)
            ->first();

        $satuan = [];
        array_push($satuan, array('id' => $data->i_unit1, 'text' => $data->satuan1));
        array_push($satuan, array('id' => $data->i_unit2, 'text' => $data->satuan2));
        array_push($satuan, array('id' => $data->i_unit3, 'text' => $data->satuan3));

        return Response::json(array(
            'data' => $data,
            'satuan' => $satuan
        ));
    }

    // Target Realisasi
    public function targetList()
    {
        $target = DB::table('d_salestargetdt')
            ->join('d_salestarget', 'std_salestarget', 'st_id')
            ->join('m_item', 'std_item', 'i_id')
            ->join('m_unit', 'std_unit', 'u_id')
            ->join('m_company', 'st_comp', 'c_id')
            ->select('d_salestargetdt.*', DB::raw('concat(std_qty, " ", u_name) as target'), 'st_id', 'c_name', DB::raw("concat(i_code, '-', i_name) as i_name"), 'st_periode', DB::raw('date_format(st_periode, "%m/%Y") as st_periode'))
            ->get();
        return Datatables::of($target)
            ->addIndexColumn()
            ->addColumn('status', function ($target) {
                return '<label class="bg-danger status-reject px-3 py-1" disabled>Gagal</label>';
            })
            ->addColumn('action', function ($target) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Target" onclick="editTarget(\'' . Crypt::encrypt($target->std_salestarget) . '\', \'' . Crypt::encrypt($target->std_detailid) . '\')"><i class="fa fa-pencil"></i>
                        </button>
                    </div>';
            })
            ->addColumn('realisasi', function () {
                return '0';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function createTargetReal()
    {
        $company = DB::table('m_company')
            ->select('m_company.*')
            ->where('c_isactive', '=', 'Y')
            ->where('c_type', '!=', 'AGEN')
            ->get();
        return view('marketing.penjualanpusat.targetrealisasi.create', compact('company'));
    }

    public function getComp()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        return Response::json(array(
            'success' => true,
            'data' => $company
        ));
    }

    public function cariBarang(Request $request)
    {
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        $nama = DB::table('m_item')
            ->select('m_item.*')
            ->whereNotIn('i_id', $is_item)
            ->where(function ($q) use ($cari) {
                $q->whereRaw("i_name like '%" . $cari . "%'");
                $q->orWhereRaw("i_code like '%" . $cari . "%'");
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x) {
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y) {
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z) {
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function targetRealStore(Request $request)
    {
        $data = $request->all();

        $periode = Carbon::createFromFormat('d/m/Y', '01/' . $data['t_periode']);
        DB::beginTransaction();
        try {
            $stDetail = 0;
            /*for ($i=0; $i < count($data['idItem']); $i++) {

            }*/
            $query1 = DB::table('d_salestarget')
                ->where('st_comp', '=', $data['t_comp'][0])
                ->whereMonth('st_periode', '=', $periode->month)
                ->first();

            if ($query1 != null) {
                //update data item di tabel detail periode
                $check = DB::table('d_salestargetdt')
                    ->join('m_item', 'std_item', 'i_id')
                    ->select('d_salestargetdt.*', 'i_id', 'i_name')
                    ->where('std_salestarget', '=', $query1->st_id);

                $query2 = $check->get();
                $item = [];

                for ($i = 0; $i < count($query2); $i++) {
                    array_push($item, strval($query2[$i]->i_id));
                }
                if (count(array_diff($data['idItem'], $item)) > 0) {
                    for ($i = 0; $i < count($data['idItem']); $i++) {
                        $detail = DB::table('d_salestargetdt')
                            ->where('std_salestarget', '=', $query1->st_id)
                            ->max('std_detailid');

                        $stDetail = $detail + 1;

                        DB::table('d_salestargetdt')->insert([
                            'std_salestarget' => $query1->st_id,
                            'std_detailid' => $stDetail,
                            'std_item' => $data['idItem'][$i],
                            'std_qty' => $data['t_qty'][$i],
                            'std_unit' => $data['t_unit'][$i]
                        ]);
                        DB::commit();
                        return response()->json([
                            'status' => 'sukses'
                        ]);
                    }
                } else {
                    $query2 = $check->whereIn('std_item', $data['idItem'])->first();
                    DB::rollBack();
                    return response()->json([
                        'status' => 'peringatan',
                        'data' => $query2
                    ]);
                }

            } else {
                // create baru
                $getIdMax = DB::table('d_salestarget')->max('st_id');
                $stId = $getIdMax + 1;
                DB::table('d_salestarget')->insert([
                    'st_id' => $stId,
                    'st_comp' => $data['t_comp'][0],
                    'st_periode' => Carbon::createFromFormat('d/m/Y', '01/' . $data['t_periode'])->format('Y-m-d')
                ]);

                for ($i = 0; $i < count($data['idItem']); $i++) {
                    DB::table('d_salestargetdt')->insert([
                        'std_salestarget' => $stId,
                        'std_detailid' => ++$stDetail,
                        'std_item' => $data['idItem'][$i],
                        'std_qty' => $data['t_qty'][$i],
                        'std_unit' => $data['t_unit'][$i]
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => 'sukses'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e
            ]);
        }

    }

    public function editTarget($st_id, $dt_id)
    {
        try {
            $st_id = Crypt::decrypt($st_id);
            $dt_id = Crypt::decrypt($dt_id);
        } catch (\Exception $e) {
            return view('errors.404');
        }
        $target = DB::table('d_salestargetdt')
            ->join('d_salestarget', 'std_salestarget', 'st_id')
            ->join('m_item', 'std_item', 'i_id')
            ->join('m_unit', 'std_unit', 'u_id')
            ->join('m_company', 'st_comp', 'c_id')
            ->select('d_salestargetdt.*', 'st_id', 'st_comp', 'st_periode', 'i_name', 'i_code', 'c_name', 'u_id', 'u_name')
            ->where('std_salestarget', '=', $st_id)
            ->where('std_detailid', '=', $dt_id)->first();
        $company = DB::table('m_company')->select('m_company.*')->get();
        return view('marketing.penjualanpusat.targetrealisasi.edit', compact('target', 'company'));
    }

    public function updateTarget($st_id, $dt_id, Request $request)
    {
        try {
            $st_id = Crypt::decrypt($st_id);
            $dt_id = Crypt::decrypt($dt_id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $data = $request->all();

        DB::beginTransaction();
        try {
            DB::table('d_salestargetdt')
                ->where('std_salestarget', '=', $st_id)
                ->where('std_detailid', '=', $dt_id)
                ->update([
                    'std_qty' => $data['targetbaru'],
                    'std_unit' => $data['satuantarget']
                ]);
            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function getPeriode(Request $request)
    {
        $idItem = $request->barang;
        $periode = $request->periode;

        $data = DB::table('d_salestarget')
            ->join('d_salestargetdt', 'st_id', '=', 'std_salestarget')
            ->join('m_item', 'std_item', 'i_id')
            ->join('m_unit', 'std_unit', 'u_id')
            ->join('m_company', 'st_comp', 'c_id')
            ->select('d_salestargetdt.*', DB::raw('concat(std_qty, " ", u_name) as target'), 'st_id', 'c_name', DB::raw("concat(i_code, '-', i_name) as i_name"), 'st_periode', DB::raw('date_format(st_periode, "%m/%Y") as st_periode'));

        if ($periode != null || $periode != '') {
            $periode = Carbon::createFromFormat('d/m/Y', '01/' . $periode);
            $data = $data->whereMonth('st_periode', '=', $periode->month)
                ->whereYear('st_periode', '=', $periode->year);
        }
        if ($idItem != null || $idItem != '') {
            $data = $data->where('std_item', '=', $idItem);
        }

        $target = $data->get();

        return Datatables::of($target)
            ->addIndexColumn()
            ->addColumn('status', function () {
                return '<label class="bg-danger status-reject px-3 py-1" disabled>Gagal</label>';
            })
            ->addColumn('action', function ($target) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Target" onclick="editTarget(\'' . Crypt::encrypt($target->std_salestarget) . '\', \'' . Crypt::encrypt($target->std_detailid) . '\')"><i class="fa fa-pencil"></i>
                        </button>
                    </div>';
            })
            ->addColumn('realisasi', function () {
                return '0';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $detailid = $request->detailid;
        $status = $request->status;

        DB::table('d_productorderdt')
            ->where('pod_productorder', '=', $id)
            ->where('pod_detailid', '=', $detailid)
            ->update([
                'pod_isapproved' => $status
            ]);

        return response()->json([
            'status' => 'sukses'
        ]);
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

    public function getPrice(Request $request)
    {
        $agen = DB::table('m_company')
            ->where('c_id', '=', $request->agen)
            ->first();
        $unit = $request->satuan;
        $item = $request->item;
        $qty = $request->kuantitas;

        if ($agen->c_user == null || $agen->c_user == ''){
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'agen tidak ditemukan'
            ]);
        }

        $type = DB::table('m_agen')
            ->where('a_code', '=', $agen->c_user)
            ->first();

        $get_price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pcd_payment', '=', 'C')
            ->where('pc_id', '=', $type->a_class)
            ->where('pcd_item', '=', $item)
            ->where('pcd_unit', '=', $unit)
            ->get();

        if (count($get_price) == 0){
            return response()->json([
                'status' => 'gagal',
                'pesan' => 'harga tidak ditemukan'
            ]);
        }

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
                    $marketing = new MarketingController();
                    $z = $marketing->inRange($qty, $get_price);
                    if ($z !== null) {
                        $harga = $get_price[$z]->pcd_price;
                    }
                }

            }
        }

        return Response::json(number_format($harga, 0, '', ''));
    }

    public function getTableDistribusi(Request $request)
    {
        $status = $request->status;
        $data = DB::table('d_productorder')
            ->leftjoin('m_company', 'c_id', '=', 'po_agen')
            ->where('po_status', 'Y')
            ->where('po_comp', '=', Auth::user()->u_company);

        if ($status == 'P'){
            $data->where('po_send', '=', 'P');
        } elseif ($status == 'Y'){
            $data->where('po_send', '=', 'Y');
        } else {
            $data->whereNull('po_send');
        }
        $data = $data->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->po_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('action', function ($data) {

                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detail" type="button" onclick="getDetailTOP(' . $data->po_id . ')" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning btn-process" type="button" onclick="distribusiPenjualan(' . $data->po_id . ')" title="Kirim" data-toggle="modal" data-target="#modal_distribusi"><i class="fa fa-send"></i></button>
                </div>';
                // <button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href=\''. route('orderpenjualan.proses') .'?id='.encrypt($data->po_id).'\'"><i class="fa fa-arrow-right"></i></button>
            })
            ->addColumn('total', function ($data) {
                $tmp = DB::table('d_productorderdt')->where('pod_productorder', $data->po_id)->sum('pod_totalprice');

                return "Rp " . number_format($tmp, 2, ',', '.');;
            })
            ->rawColumns(['tanggal', 'action', 'total'])
            ->make(true);
    }
}
