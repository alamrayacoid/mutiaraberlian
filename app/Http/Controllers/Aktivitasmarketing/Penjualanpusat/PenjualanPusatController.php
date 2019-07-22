<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

use App\Http\Controllers\AksesUser;
use App\Http\Controllers\Aktivitasmarketing\Marketingarea\MarketingAreaController;
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
use App\d_productordercode;
use App\d_stock;
use App\d_salescomppayment;
use App\m_item;
use App\m_paymentmethod;
use Currency;
use Mutasi;
use Mockery\Exception;
use App\Helper\keuangan\jurnal\jurnal;


class PenjualanPusatController extends Controller
{
    public function index()
    {
        if (!AksesUser::checkAkses(20, 'read')) {
            abort(401);
        }
        return view('marketing/penjualanpusat/index');
    }


    // Terima Order Penjualan
    public function createTOP()
    {
        if (!AksesUser::checkAkses(20, 'create')) {
            abort('401');
        }
        $data = 'employee';
        if (Auth::user()->u_user == 'A') {
            $data = DB::table('m_agen')
                ->where('a_code', '=', Auth::user()->u_code)
                ->first();
        }
        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        return view('marketing/penjualanpusat/terimaorder/create', compact('data', 'pusat'));
    }

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
                <button class="btn btn-danger btn-delete" type="button" onclick="deleteTOP(' . $data->po_id . ')" title="Hapus"><i class="fa fa-close"></i></button>
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

    public function deleteTOP(Request $request)
    {
        $id = $request->po_id;

        if (!AksesUser::checkAkses(20, 'delete')) {
            return Response::json([
                'status' => 'gagal',
                'message' => 'Anda tidak memiliki akses'
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', '=', $id)
                ->delete();

            DB::table('d_productordercode')
                ->where('poc_productorder', '=', $id)
                ->delete();

            DB::table('d_productorderdt')
                ->where('pod_productorder', '=', $id)
                ->delete();

            DB::commit();
            return Response::json([
                'status' => 'sukses'
            ]);
        } catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
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
        // $stockItem = array();
        // foreach ($data->getPODt as $key => $val) {
        //     $getStock = d_stock::where('s_item', $val->pod_item)
        //         ->where('s_position', $data->po_comp)
        //         ->where('s_status', 'ON DESTINATION')
        //         ->where('s_condition', 'FINE')
        //         ->first();
        //
        //     array_push($stockItem, $getStock->s_qty);
        // }

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

            if ($getStock != null) {
                array_push($satuanItem, $getStock->s_qty . ' ' . $item->u_name);
                array_push($stockItem, $getStock->s_qty);
            } else {
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
        $diskon = $request->diskon;

        DB::beginTransaction();
        try {

            DB::table('d_productorder')
                ->where('po_id', '=', $id_po)
                ->update([
                    'po_status' => 'Y'
                ]);

            for ($i = 0; $i < count($item); $i++) {
                DB::table('d_productorderdt')
                    ->where('pod_productorder', '=', $id_po)
                    ->where('pod_item', '=', $item[$i])
                    ->update([
                        'pod_unit' => $unit[$i],
                        'pod_price' => $harga[$i],
                        'pod_qty' => $qty[$i],
                        'pod_discvalue' => $diskon[$i],
                        'pod_totalprice' => $qty[$i] * ($harga[$i] - $diskon[$i]),
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
        } catch (DecryptException $e) {
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
        $sekarang = Carbon::now('Asia/Jakarta');
        $target = DB::table('d_salestargetdt')
            ->join('d_salestarget', 'std_salestarget', 'st_id')
            ->join('m_item', 'std_item', 'i_id')
            ->join('m_unit', 'std_unit', 'u_id')
            ->join('m_company', 'st_comp', 'c_id')
            ->select('d_salestargetdt.*', DB::raw('concat(std_qty, " ", u_name) as target'), 'st_id', 'c_name', DB::raw("concat(i_code, '-', i_name) as i_name"), 'st_periode', DB::raw('date_format(st_periode, "%m/%Y") as st_periode'))
            ->whereMonth('st_periode', '=', $sekarang->format('m'))
            ->whereYear('st_periode', '=', $sekarang->format('Y'))
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

        if ($agen->c_user == null || $agen->c_user == '') {
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

        if (count($get_price) == 0) {
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
                if ($price->pcd_rangeqtyend == 0) {
                    if ($qty >= $price->pcd_rangeqtystart) {
                        $harga = $price->pcd_price;
                    }
                } else {
                    $marketing = new MarketingAreaController();
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

        if ($status == 'P') {
            $data->where('po_send', '=', 'P');
        } elseif ($status == 'Y') {
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
                if ($data->po_send == null){
                    return '<div class="btn-group btn-group-sm">
                            <button class="btn btn-primary btn-detail" type="button" onclick="getDetailTOP(' . $data->po_id . ')" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-danger btn-delete" type="button" onclick="deleteTOP(' . $data->po_id . ')" title="Hapus"><i class="fa fa-close"></i></button>
                            <button class="btn btn-warning btn-process" type="button" onclick="distribusiPenjualan(' . $data->po_id . ')" title="Kirim Barang" data-toggle="modal" data-target="#modal_distribusi"><i class="fa fa-send"></i></button>
                        </div>';
                } else {
                    return '<div class="btn-group btn-group-sm">
                            <button class="btn btn-primary btn-detail" type="button" onclick="getDetailTOP(' . $data->po_id . ')" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
                        </div>';
                }

                // <button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href=\''. route('orderpenjualan.proses') .'?id='.encrypt($data->po_id).'\'"><i class="fa fa-arrow-right"></i></button>
            })
            ->addColumn('total', function ($data) {
                $tmp = DB::table('d_productorderdt')->where('pod_productorder', $data->po_id)->sum('pod_totalprice');

                return "Rp " . number_format($tmp, 2, ',', '.');;
            })
            ->rawColumns(['tanggal', 'action', 'total'])
            ->make(true);
    }
    // get detail item that will send
    public function getDetailSend(Request $request)
    {
        $data = d_productorder::where('po_id', $request->id)
        ->with('getAgent')
        ->with(['getPODt' => function ($query) {
            $query
            ->where('pod_isapproved', 'Y')
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

        $ekspedisi = DB::table('m_expedition')
        ->where('e_isactive', '=', 'Y')
        ->get();

        //$data->stockItem = $stockItem;
        $data->total = d_productorderdt::where('pod_productorder', $request->id)->sum('pod_totalprice');
        $data->dateFormated = Carbon::parse($data->po_date)->format('d M Y');
        $data->ekspedisi = $ekspedisi;
        return response()->json($data);
    }
    // get list of paymentMethod
    public function getPaymentMethod()
    {
        $data = m_paymentmethod::where('pm_isactive', 'Y')
            ->with('getAkun')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getProdukEkspedisi(Request $request)
    {
        $id = $request->id;

        $data = DB::table('m_expeditiondt')
            ->where('ed_expedition', '=', $id)
            ->where('ed_isactive', '=', 'Y')
            ->get();

        return Response::json($data);
    }

    public function sendOrder(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {

            // return json_encode($request->all());

            $nota = $request->nota;
            $ekspedisi = $request->ekspedisi;
            $produk = $request->produk;
            $nama = $request->nama;
            $tlp = $request->tlp;
            $resi = $request->resi;
            $harga = $request->harga;
            // $paymentType = $request->paymentType;
            // $paymentMethod = $request->paymentMethod;
            // $payCash = $request->payCash;
            // $dateTop = $request->dateTop;

            $cek = DB::table('d_productorderdt')
                ->join('d_productorder', 'po_id', '=', 'pod_productorder')
                ->where('po_nota', '=', $nota)
                ->where('pod_isapproved', 'Y')
                ->get();

            // validate qty of production-code
            for ($i = 0; $i < count($cek); $i++){
                $kode = DB::table('d_productordercode')
                    ->join('d_productorderdt', function ($q) use ($cek){
                        $q->on('pod_productorder', '=', 'poc_productorder');
                        $q->on('pod_item', '=', 'poc_item');
                    })
                    ->select(DB::raw('sum(poc_qty) as poc_qty'))
                    ->where('poc_item', '=', $cek[$i]->pod_item)
                    ->where('poc_productorder', '=', $cek[$i]->pod_productorder)
                    ->groupBy('poc_item')
                    ->get();

                // if the items is not-found
                if (count($kode) == 0) {
                    $item = m_item::where('i_id', $cek[$i]->pod_item)->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }
                // if the prod-code is not same
                if (count($kode) > 0 && $kode[0]->poc_qty != $cek[$i]->pod_qty){
                    $item = m_item::where('i_id', $cek[$i]->pod_item)->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }
            }

            DB::table('d_productorder')
                ->where('po_nota', '=', $nota)
                ->update([
                    'po_send' => 'P'
                ]);
            $pd_id = DB::table('d_productdelivery')
                ->max('pd_id');

            ++$pd_id;

            DB::table('d_productdelivery')
                ->insert([
                    'pd_id' => $pd_id,
                    'pd_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'pd_nota' => $nota,
                    'pd_expedition' => $ekspedisi,
                    'pd_product' => $produk,
                    'pd_resi' => $resi,
                    'pd_couriername' => $nama,
                    'pd_couriertelp' => $tlp,
                    'pd_price' => $harga
                ]);

            $productOrder = d_productorder::where('po_nota', '=', $nota)
                ->with(['getPODt' => function ($q) {
                    $q
                        ->where('pod_isapproved', 'Y')
                        ->with('getProdCode');
                }])
                ->first();

            // $dataItem = DB::table('d_productorder')
            //     ->join('d_productorderdt', 'pod_productorder', '=', 'po_id')
            //     ->where('po_nota', '=', $nota)
            //     ->get();

            $idItems = array();
            // dd($idItems, $productOrder->getPODt[0]->pod_item);
            for ($i = 0; $i < count($productOrder->getPODt); $i++) {
                array_push($idItems, $productOrder->getPODt[$i]->pod_item);
            }
            // dd('list id-items: ', $idItems);
            // mutation

            // return json_encode($productOrder);

            // tambahan dirga
                $totalHpp = 0;

            foreach ($productOrder->getPODt as $key => $PO) {
                // get list production-code
                $prodCode = d_productordercode::where('poc_productorder', $productOrder->po_id)
                    ->where('poc_item', $PO->pod_item)
                    ->select('poc_code', 'poc_qty')
                    ->get();

                $listPC = array();
                $listQtyPC = array();
                $listUnitPC = array();
                foreach ($prodCode as $key => $val) {
                    array_push($listPC, $val->poc_code);
                    array_push($listQtyPC, $val->poc_qty);
                }

                //convert unit to smallest
                $barang = DB::table('m_item')
                    ->where('i_id', '=', $PO->pod_item)
                    ->first();

                $kuantitas = 0;
                $sellprice = 0;

                if ($PO->pod_unit == $barang->i_unit1) {
                    $kuantitas = $PO->pod_qty;
                    $sellprice = $PO->pod_price;
                } elseif ($PO->pod_unit == $barang->i_unit2) {
                    $kuantitas = ($PO->pod_qty * $barang->i_unitcompare2);
                    $sellprice = $PO->pod_price / $barang->i_unitcompare2;
                } elseif ($PO->pod_unit == $barang->i_unit3) {
                    $kuantitas = ($PO->pod_qty * $barang->i_unitcompare3);
                    $sellprice = $PO->pod_price / $barang->i_unitcompare3;
                }

                // insert stock mutation using distribusicabangkeluar
                // actually its public function, not specific
                // waiit, check the name of $reff
                $reff = $nota;
                //sm_sell $sellprice
                $sell = $PO->pod_price - $PO->pod_discvalue;
                $mutDist = Mutasi::salesOut(
                    $productOrder->po_comp, // from
                    $productOrder->po_agen, // to
                    $PO->pod_item, // item-id
                    $kuantitas, // qty of smallest-unit
                    $productOrder->po_nota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sell,
                    5
                );
                // return json_encode($mutDist->original);
                if ($mutDist->original['status'] !== 'success') {
                    return $mutDist;
                }

                // set stock-parent-id
                $listStockParentId = $mutDist->original['listStockParentId'];
                // get list
                $listSellPrice = $mutDist->original['listSellPrice'];
                $listHPP = $mutDist->original['listHPP'];
                $listSmQty = $mutDist->original['listSmQty'];
                $listPCReturn = $mutDist->original['listPCReturn'];
                $listQtyPCReturn = $mutDist->original['listQtyPCReturn'];

                // insert stock mutation using sales 'in'
                $mutationIn = Mutasi::salesIn(
                // $productOrder->po_comp, // from
                    $productOrder->po_agen, // to
                    $PO->pod_item, // item-id
                    $productOrder->po_nota, // nota
                    $listPCReturn, // list of list production-code
                    $listQtyPCReturn, // list of list production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $listSellPrice, // sellprice
                    $listHPP,
                    $listSmQty,
                    20, // mutcat masuk pembelian
                    $listStockParentId // stock-parent id
                );
                if ($mutationIn->original['status'] !== 'success') {
                    return $mutationIn;
                }

                // Tambahan Dirga
                    foreach ($mutDist->original['listSmQty'] as $key => $value) {
                        $totalHpp += (float) $value * (float) $mutDist->original['listHPP'][$key];
                    }
                // end Dirga

            }


            // d_salescomp
            $s_id = DB::table('d_salescomp')
                ->max('sc_id');
            ++$s_id;

            // $data = DB::table('d_productorder')
            //     ->join('d_productorderdt', 'pod_productorder', '=', 'po_id')
            //     ->where('po_nota', '=', $nota)
            //     ->get();

            // $kode = DB::table('d_productordercode')
            //     ->where('poc_productorder', '=', $productOrder->po_id)
            //     ->get();

            $notasales = CodeGenerator::codeWithSeparator('d_salescomp', 'sc_nota', '8', '10', '3', 'SC', '-');

            $total = 0;
            $insert = [];
            for ($i = 0; $i < count($productOrder->getPODt); $i++) {
                $temp = [
                    'scd_sales' => $s_id,
                    'scd_detailid' => $i + 1,
                    'scd_comp' => $productOrder->po_comp,
                    'scd_item' => $productOrder->getPODt[$i]->pod_item,
                    'scd_qty' => $productOrder->getPODt[$i]->pod_qty,
                    'scd_unit' => $productOrder->getPODt[$i]->pod_unit,
                    'scd_value' => $productOrder->getPODt[$i]->pod_price,
                    'scd_discpersen' => 0,
                    'scd_discvalue' => $productOrder->getPODt[$i]->pod_discvalue,
                    'scd_totalnet' => $productOrder->getPODt[$i]->pod_qty * ($productOrder->getPODt[$i]->pod_price - $productOrder->getPODt[$i]->pod_discvalue)
                ];
                $total = $total + ($productOrder->getPODt[$i]->pod_qty * ($productOrder->getPODt[$i]->pod_price - $productOrder->getPODt[$i]->pod_discvalue));
                array_push($insert, $temp);

                // insert data production-code
                $code = [];
                foreach ($productOrder->getPODt[$i]->getProdCode as $j => $val) {
                    $temp = [
                        'ssc_salescomp' => $s_id,
                        'ssc_item' => $val->poc_item,
                        'ssc_detailid' => $j + 1,
                        'ssc_code' => $val->poc_code,
                        'ssc_qty' => $val->poc_qty
                    ];
                    array_push($code, $temp);
                }
                // for ($j = 0; $j < count(); $j++) {
                // }
                DB::table('d_salescompcode')
                ->insert($code);
            }
            DB::table('d_salescompdt')
                ->insert($insert);

            // set paid of for 'cash' payment
            if ($request->paymentType == 'C') {
                $paidOff = 'Y';
                $payCash = $total;
            }
            else {
                $paidOff = 'N';
                $payCash = $request->payCash;
            }

            DB::table('d_salescomp')
                ->insert([
                    'sc_id' => $s_id,
                    'sc_comp' => $productOrder->po_comp,
                    // 'sc_member' => $data[0]->po_agen,
                    'sc_member' => $productOrder->po_agen,
                    'sc_type' => 'C',
                    'sc_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                    'sc_nota' => $notasales,
                    'sc_total' => $total,
                    'sc_paidoff' => $paidOff,
                    'sc_datetop'   => Carbon::parse($request->dateTop),
                    'sc_paymenttype' => $request->paymentType,
                    'sc_paymentmethod' => $request->paymentMethod,
                    'sc_user' => Auth::user()->u_id,
                    'sc_insert' => Carbon::now('Asia/Jakarta'),
                    'sc_update' => Carbon::now('Asia/Jakarta')
                ]);

            // set value for salespayment
            $val_salespayment = [
                'scp_salescomp' => $s_id,
                'scp_detailid' => d_salescomppayment::where('scp_salescomp', $s_id)->max('scp_detailid') + 1,
                'scp_date' => Carbon::now(),
                'scp_pay' => $payCash,
                'scp_payment' => $request->paymentMethod
            ];
            DB::table('d_salescomppayment')->insert($val_salespayment);


            // tambahan dirga
                $jurnalSendOrder = $this->jurnalSendOrder($totalHpp, (float) $request->harga, $notasales, $request);

                // return json_encode($jurnalSendOrder);

                if($jurnalSendOrder['status'] != 'berhasil')
                    return json_encode($jurnalSendOrder);

            // end dirga

            // return json_encode($request->all());

            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
        catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

     private function jurnalSendOrder(float $totHpp, float $ongkir, String $notasales, Request $request){
        
        $details = [];

            // Acc persediaan keluar
                $acc_persediaan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Pengiriman Distribusi Penjualan')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan Item')
                                        ->first();

                $acc_persediaan_jalan = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Pengiriman Distribusi Penjualan')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA Persediaan dalam perjalanan')
                                            ->first();

            if(!is_null($request->harga) && $request->harga != 0){
                // Acc Ongkir
                    $acc_beban_ongkir = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Pengiriman Distribusi Penjualan')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA beban ongkos kirim')
                                            ->first();

                    $acc_kas = DB::table('dk_pembukuan_detail')
                                    ->where('pd_pembukuan', function($query){
                                        $query->select('pe_id')->from('dk_pembukuan')
                                                    ->where('pe_nama', 'Pengiriman Distribusi Penjualan')
                                                    ->where('pe_comp', Auth::user()->u_company)->first();
                                    })->where('pd_nama', 'COA Kas/Setara Kas')
                                    ->first();
            }

            $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Pengiriman Distribusi Penjualan')
                        ->where('pe_comp', Auth::user()->u_company)->first();

            if(!$parrent || !$acc_persediaan || !$acc_persediaan_jalan){
                return [
                    'status' => 'gagal',
                    'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                ];
            }

            array_push($details, [
                "jrdt_nomor"        => 1,
                "jrdt_akun"         => $acc_persediaan_jalan->pd_acc,
                "jrdt_value"        => $totHpp,
                "jrdt_dk"           => "D",
                "jrdt_keterangan"   => $acc_persediaan_jalan->pd_keterangan,
                "jrdt_cashflow"     => $acc_persediaan_jalan->pd_cashflow
            ]);

            if(!is_null($request->harga) && $request->harga != 0){
                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $acc_beban_ongkir->pd_acc,
                    "jrdt_value"        => $request->harga,
                    "jrdt_dk"           => "D",
                    "jrdt_keterangan"   => $acc_beban_ongkir->pd_keterangan,
                    "jrdt_cashflow"     => $acc_beban_ongkir->pd_cashflow
                ]);
            }

            array_push($details, [
                "jrdt_nomor"        => 3,
                "jrdt_akun"         => $acc_persediaan->pd_acc,
                "jrdt_value"        => $totHpp,
                "jrdt_dk"           => "K",
                "jrdt_keterangan"   => $acc_persediaan->pd_keterangan,
                "jrdt_cashflow"     => $acc_persediaan->pd_cashflow,
            ]);

            if(!is_null($request->harga) && $request->harga != 0){
                array_push($details, [
                    "jrdt_nomor"        => 4,
                    "jrdt_akun"         => $acc_kas->pd_acc,
                    "jrdt_value"        => $request->harga,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $acc_kas->pd_keterangan,
                    "jrdt_cashflow"     => $acc_kas->pd_cashflow,
                ]);
            }

            // return $details;

            $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $notasales, $parrent->pe_nama, 'TK', Auth::user()->u_company);

            if($jurnal['status'] == 'error'){
                return $jurnal;
            }

            if($request->paymentType == 'T'){
                $details = [];

                $acc_kas = m_paymentmethod::where('pm_id', $request->paymentMethod)
                                        ->first();

                $acc_kas_ket = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Penjualan Pusat Dengan DP')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA Kas/Setara Kas')
                                            ->first();

                $acc_DP = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Penjualan Pusat Dengan DP')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA Pendapatan Dibayar Dimuka')
                                            ->first();

                $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Penjualan Pusat Dengan DP')
                        ->where('pe_comp', Auth::user()->u_company)->first();

                if(!$parrent || !$acc_DP || !$acc_kas){
                    return [
                        'status' => 'gagal',
                        'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                    ];
                }

                array_push($details, [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $acc_kas->pm_akun,
                    "jrdt_value"        => $request->payCash,
                    "jrdt_dk"           => "D",
                    "jrdt_keterangan"   => $acc_kas_ket->pd_keterangan,
                    "jrdt_cashflow"     => $acc_kas_ket->pd_cashflow
                ]);

                array_push($details, [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $acc_DP->pd_acc,
                    "jrdt_value"        => $request->payCash,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $acc_DP->pd_keterangan,
                    "jrdt_cashflow"     => $acc_DP->pd_cashflow
                ]);

                $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $notasales, $parrent->pe_nama, 'TK', Auth::user()->u_company);

                if($jurnal['status'] == 'error'){
                    return $jurnal;
                }
            }

            // return $details;

            return [
                'status' => 'berhasil',
                'message' => ''
            ];
     }

    // Penerimaan Piutang -------------------------->
    public function cariNota(Request $request)
    {
        $cari = $request->term;
        $nota = DB::table('d_salescomp')
            ->select('d_salescomp.*')
            ->where(function ($q) use ($cari) {
                $q->whereRaw("sc_nota like '%" . $cari . "%'");
            })
            ->where('sc_paidoff', '=', 'N')
            ->where('sc_type', '=', 'C')
            ->get();

        if (count($nota) == 0) {
            $results[] = ['label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nota as $query) {
                $results[] = ['label' => strtoupper($query->sc_nota), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getProvinsi()
    {
        $data = DB::table('m_wil_provinsi')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getCity($id)
    {
        $data = DB::table('m_wil_kota')->where('wc_provinsi', '=', $id)->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getAgen($id)
    {
        $data = DB::table('m_company')
            ->join('m_agen', 'c_user', 'a_code')
            ->where('a_area', '=', $id)
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    public function getNotaAgen($code)
    {
        // dd($code);
        $data = DB::table('d_salescomp')
            ->join('m_company', 'c_id', '=', 'sc_member')
            ->join('m_agen', 'a_code', '=', 'c_user')
            ->leftJoin('d_salescomppayment', 'scp_salescomp', '=', 'sc_id')
            ->select('sc_total', 'sc_datetop', 'sc_nota', DB::raw('COALESCE(SUM(scp_pay), 0) as payment'))
            ->where('c_user', '=', $code)
            ->where('sc_paidoff', '=', 'N')
            ->where('sc_type', '=', 'C')
            ->groupBy('sc_id');
        // dd($data);
        return Datatables::of($data)
            ->addColumn('sisa', function ($data) {
                $sisa = $data->sc_total - $data->payment;
                $sisa = Currency::addRupiah($sisa);
                return $sisa;
            })
            ->addColumn('action', function ($data) {
                // return '<button class="btn btn-sm btn-success" onclick="get_list(\''.Crypt::encrypt($data->sc_nota).'\')"><i class="fa fa-download"></i> Gunakan</button>';
                return '<button class="btn btn-sm btn-success" onclick="get_list(\'' . $data->sc_nota . '\')"><i class="fa fa-download"></i> Gunakan</button>';
            })
            ->rawColumns(['sisa', 'action'])
            ->make(true);
    }

    public function listPiutang(Request $request)
    {
        $nota = $request->nota;
        $datas = DB::table('d_salescomp')
            ->leftJoin('d_salescomppayment', 'scp_salescomp', 'sc_id')
            ->select('sc_total', DB::raw('date_format(sc_datetop, "%d/%m/%Y") as deadline'), 'sc_nota', DB::raw('COALESCE(SUM(scp_pay), 0) as payment'))
            ->where('sc_nota', '=', $nota)
            ->groupBy('sc_id');

        return Datatables::of($datas)
            // ->addIndexColumn()
            ->addColumn('sisa', function($datas){
                $sisa = (int)$datas->sc_total - (int)$datas->payment;
                $sisa = Currency::addRupiah($sisa);
                return $sisa;
            })
            ->addColumn('bayar', function($datas){
                $sisa = (int)$datas->sc_total - (int)$datas->payment;
                if ($sisa == 0) {
                    return '<button class="btn btn-sm btn-success" disabled><i class="fa fa-money"></i> Bayar</button>';
                } else {
                    return '<button class="btn btn-sm btn-success" onclick="toPayment(\''.$datas->sc_nota.'\')"><i class="fa fa-money"></i> Bayar</button>';
                }
            })
            ->addColumn('sisa', function ($datas) {
                $sisa = $datas->sc_total - $datas->payment;
                $sisa = Currency::addRupiah($sisa);
                return $sisa;
            })
            ->addColumn('bayar', function ($datas) {
                return '<button class="btn btn-sm btn-success" onclick="toPayment(\'' . $datas->sc_nota . '\')"><i class="fa fa-money"></i> Bayar</button>';
            })
            ->rawColumns(['sisa', 'bayar'])
            ->make(true);
    }

    public function savePayment(Request $request)
    {
        $nota    = $request->nota;
        $nominal = $request->nominal;

        DB::beginTransaction();
        try {
            $sales = DB::table('d_salescomp')->where('sc_nota', '=', $nota)->first();
            $dtId  = DB::table('d_salescomppayment')->where('scp_salescomp', '=', $sales->sc_id)->max('scp_detailid') + 1;
            DB::table('d_salescomppayment')->insert([
                'scp_salescomp' => $sales->sc_id,
                'scp_detailid'  => $dtId,
                'scp_date'      => Carbon::now(),
                'scp_pay'       => $nominal
            ]);

            $checkSCP = DB::table('d_salescomppayment')->where('scp_salescomp', '=', $sales->sc_id)->get();

            $jumlah = 0;
            for ($i=0; $i < count($checkSCP) ; $i++) {
                $jumlah += (int)$checkSCP[$i]->scp_pay;
            }

            if ((int)$jumlah == (int)$sales->sc_total) {
                DB::table('d_salescomp')->where('sc_nota', '=', $nota)->update([
                    'sc_paidoff' => 'Y'
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}
