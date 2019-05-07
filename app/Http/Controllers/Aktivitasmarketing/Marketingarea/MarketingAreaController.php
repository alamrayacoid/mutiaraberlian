<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use Auth;
use Currency;
use Response;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;
use Validator;
use App\d_canvassing;
use App\d_username;
use App\d_sales;
use App\m_agen;
use App\m_wil_provinsi;

class MarketingAreaController extends Controller
{
    public function index()
    {
        $provinsi = DB::table('m_wil_provinsi')->select('m_wil_provinsi.*')->get();
        $city = DB::table('m_wil_kota')->select('m_wil_kota.*')->get();
        $user = Auth::user();

        return view('marketing/marketingarea/index', compact('provinsi', 'city', 'user'));
    }

    public function printNota($id, $dt)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }
        $order = DB::table('d_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->select('d_productorder.*', 'comp.c_name as comp', 'agen.c_name as agen')
            ->where('po_id', $id)
            ->first();
        $nota = DB::table('d_productorder')
            ->join('d_productorderdt', 'po_id', 'pod_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorderdt.*', 'd_productorder.*', 'm_item.*', 'm_unit.*', 'comp.c_name as comp', 'agen.c_name as agen')
            ->where('pod_productorder', $id)
            ->get();
        return view('marketing/marketingarea/orderproduk/nota', compact('order','nota'));
    }

    // Order Produk Ke Cabang ==============================================================================
    public function orderList()
    {
        $order = DB::table('d_productorder')
            ->join('d_productorderdt', 'po_id', 'pod_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorder.*', DB::raw('date_format(po_date, "%m/%Y") as po_date'), 'i_name', 'u_name', 'comp.c_name as comp', 'agen.c_name as agen', DB::raw('SUM(pod_totalprice) as totalprice'))
            ->groupBy('po_id')
            ->get();
        return Datatables::of($order)
            ->addIndexColumn()
            ->addColumn('totalprice', function ($order) {
                return Currency::addRupiah($order->totalprice);
            })
            ->addColumn('action', function ($order) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailOrder(\'' . Crypt::encrypt($order->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                            </button>
                            <button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Print Nota" title="Nota" type="button" onclick="printNota(\'' . Crypt::encrypt($order->po_id) . '\')"><i class="fa fa-fw fa-print"></i>
                            </button>
                            <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Order" onclick="editOrder(\'' . Crypt::encrypt($order->po_id) . '\')"><i class="fa fa-fw fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger hint--top-left hint--error" aria-label="Hapus Order" onclick="deleteOrder(\'' . Crypt::encrypt($order->po_id) . '\')"><i class="fa fa-fw fa-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['totalprice','action'])
            ->make(true);
    }

    public function createOrderProduk()
    {
        $provinsi = DB::table('m_wil_provinsi')->select('m_wil_provinsi.*')->get();
        $city = DB::table('m_wil_kota')->select('m_wil_kota.*')->get();
        $company = DB::table('m_company')->select('m_company.*')
            ->where('c_type', '=', 'PUSAT')
            ->orWhere('c_type', '=', 'CABANG')
            ->get();
        return view('marketing/marketingarea/orderproduk/create', compact('provinsi', 'city', 'company'));
    }

    public function getCity(Request $request)
    {
        $provId = $request->provId;
        $city = DB::table('m_wil_kota')->select('wc_id', 'wc_name')
            ->where('wc_provinsi', '=', $provId)
            ->get();
        return Response::json(array(
            'success' => true,
            'data'    => $city
        ));
    }

    public function getComp()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        return Response::json(array(
            'success' => true,
            'data'    => $company
        ));
    }

    public function getAgen(Request $request)
    {
        $id = $request->cityId;
        $agen = DB::table('m_company')
            ->join('m_agen', 'c_user', 'a_code')
            ->select('m_company.*', 'm_agen.*')
            ->where('c_type', '=', 'AGEN')
            ->where('a_area', '=', $id)
            ->get();
        return Response::json(array(
            'success' => true,
            'data'    => $agen
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

    public function getPrice(Request $request)
    {
        $idItem = $request->item;
        $idUnit = $request->unit;
        $qty    = $request->qty;

        if ($qty != null || $qty != "") {
            if ($qty == 1) {
                $price = DB::table('m_priceclassdt')
                    ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                    ->select('m_priceclassdt.*', 'm_priceclass.*')
                    ->where('pc_name', '=', "Agen")
                    ->where('pcd_item', '=', $idItem)
                    ->where('pcd_unit', '=', $idUnit)
                    ->where('pcd_type', '=', "U")
                    ->where('pcd_rangeqtystart', '=', $qty)
                    ->where('pcd_rangeqtyend', '=', $qty)
                    ->first();
            } else if($qty > 1) {
                $infinity = DB::table('m_priceclassdt')
                    ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                    ->select('m_priceclassdt.*', 'm_priceclass.*')
                    ->where('pc_name', '=', "Agen")
                    ->where('pcd_item', '=', $idItem)
                    ->where('pcd_unit', '=', $idUnit)
                    ->where('pcd_type', '=', "R")
                    ->where('pcd_rangeqtystart', '<=', $qty)
                    ->where('pcd_rangeqtyend', '=', 0)->first();
                if ($infinity) {
                    return Response::json(array(
                        'success' => true,
                        'data'    => number_format($infinity->pcd_price,0, ',', '')
                    ));
                } else {
                    $price = DB::table('m_priceclassdt')
                        ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                        ->select('m_priceclassdt.*', 'm_priceclass.*')
                        ->where('pc_name', '=', "Agen")
                        ->where('pcd_item', '=', $idItem)
                        ->where('pcd_unit', '=', $idUnit)
                        ->where('pcd_type', '=', "R")
                        ->where('pcd_rangeqtystart', '<=', $qty)
                        ->where('pcd_rangeqtyend', '>=', $qty)
                        ->first();
                }

            } else {
                return Response::json(array(
                    'success' => true,
                    'data'    => 0
                ));
            }
        } else {
            return Response::json(array(
                'success' => true,
                'data'    => 0
            ));
        }

        if($price){
            return Response::json(array(
                'success' => true,
                'data'    => number_format($price->pcd_price,0, ',', '')
            ));
        } else {
            return Response::json(array(
                'success' => true,
                'data'    => 0
            ));
        }
    }

    public function orderProdukStore(Request $request)
    {
        $data = $request->all();
        $now  = Carbon::now('Asia/Jakarta');
        $time = date('Y-m-d', strtotime($now));
        DB::beginTransaction();
        try {
            $detailId = 0;
            for ($i=0; $i < count($data['idItem']); $i++) {

                $query1 = DB::table('d_productorder')
                    ->where('po_date', '=', $time)
                    ->where('po_comp', '=', $data['po_comp'][0])
                    ->where('po_agen', '=', $data['po_agen'][0])
                    ->first();

                if ($query1) {

                    $query2 = DB::table('d_productorderdt')
                            ->where('pod_productorder', '=', $query1->po_id)
                            ->where('pod_item', '=', $data['idItem'][$i])
                            ->where('pod_unit', '=', $data['po_unit'][$i])
                            ->first();

                    if ($query2) {

                        $qtyAkhir = $query2->pod_qty + $data['po_qty'][$i];
                        $priceAkhir = $query2->pod_totalprice + $data['sbtotal'][$i];

                        DB::table('d_productorderdt')
                            ->where('pod_productorder', '=', $query1->po_id)
                            ->where('pod_item', '=', $data['idItem'][$i])
                            ->where('pod_unit', '=', $data['po_unit'][$i])
                            ->update([
                            'pod_qty'          => $qtyAkhir,
                            'pod_totalprice'   => $priceAkhir
                        ]);
                    } else {

                        $detailId = DB::table('d_productorderdt')
                                  ->where('pod_productorder', '=', $query1->po_id)
                                  ->max('pod_detailid');

                        DB::table('d_productorderdt')->insert([
                            'pod_productorder' => $query1->po_id,
                            'pod_detailid'     => $detailId+1,
                            'pod_item'         => $data['idItem'][$i],
                            'pod_unit'         => $data['po_unit'][$i],
                            'pod_qty'          => $data['po_qty'][$i],
                            'pod_price'        => $data['po_hrg'][$i],
                            'pod_totalprice'   => $data['sbtotal'][$i]
                        ]);
                    }
                } else {

                    $getIdMax = DB::table('d_productorder')->max('po_id');
                    $poId = $getIdMax + 1;

                    DB::table('d_productorder')->insert([
                        'po_id'     => $poId,
                        'po_comp'   => $data['po_comp'][0],
                        'po_agen'   => $data['po_agen'][0],
                        'po_date'   => $time,
                        'po_nota'   => CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 9, 10, 3, 'PRO', '-'),
                        'po_status' => "P"
                    ]);

                    DB::table('d_productorderdt')->insert([
                        'pod_productorder' => $poId,
                        'pod_detailid'     => ++$detailId,
                        'pod_item'         => $data['idItem'][$i],
                        'pod_unit'         => $data['po_unit'][$i],
                        'pod_qty'          => $data['po_qty'][$i],
                        'pod_price'        => $data['po_hrg'][$i],
                        'pod_totalprice'   => $data['sbtotal'][$i]
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function editOrderProduk($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $produk = DB::table('d_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->select('d_productorder.*', DB::raw('date_format(po_date, "%d/%m/%Y") as po_date'), 'comp.c_name as comp', 'agen.c_name as agen')
            ->where('po_id', $id)
            ->first();

        $detail = DB::table('d_productorderdt')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->join('m_unit as unit1', 'm_item.i_unit1', 'unit1.u_id')
            ->join('m_unit as unit2', 'm_item.i_unit2', 'unit2.u_id')
            ->join('m_unit as unit3', 'm_item.i_unit3', 'unit3.u_id')
            ->select('d_productorderdt.*', 'm_item.*', 'm_unit.*', 'unit1.u_id as uid_1', 'unit2.u_id as uid_2', 'unit3.u_id as uid_3', 'unit1.u_name as uname_1', 'unit2.u_name as uname_2', 'unit3.u_name as uname_3')
            ->where('pod_productorder', $id)
            ->get();
        return view('marketing/marketingarea/orderproduk/edit', compact('produk', 'detail'));
    }

    public function updateOrderProduk($id, Request $request)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $data = $request->all();
        DB::beginTransaction();
        try {
            DB::table('d_productorderdt')
                ->where('pod_productorder', $id)
                ->delete();

            $detailId = 0;
            for ($i=0; $i < count($data['idItem']) ; $i++) {
                DB::table('d_productorderdt')->insert([
                    'pod_productorder' => $id,
                    'pod_detailid'     => ++$detailId,
                    'pod_item'         => $data['idItem'][$i],
                    'pod_unit'         => $data['po_unit'][$i],
                    'pod_qty'          => $data['po_qty'][$i],
                    'pod_price'        => $data['po_hrg'][$i],
                    'pod_totalprice'   => $data['sbtotal'][$i]
                ]);
            }

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

    public function deleteOrder($id, $dt)
    {
        try {
            $id = Crypt::decrypt($id);
            $dt = Crypt::decrypt($dt);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            $query1 = DB::table('d_productorderdt')
                ->join('d_productorder', 'pod_productorder', 'po_id')
                ->select('d_productorderdt.*', 'd_productorder.*')
                ->where('pod_productorder', $id)
                ->where('pod_detailid', $dt)
                ->first();
            if ($query1->po_status == "P" || $query1->po_status == "N") {
                DB::table('d_productorderdt')
                    ->where('pod_productorder', $id)
                    ->where('pod_detailid', $dt)
                    ->delete();
            } else {
                DB::commit();
                return response()->json([
                    'status' => 'warning'
                ]);
            }

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

    public function detailOrder($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $produk = DB::table('d_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->select('d_productorder.*', DB::raw('date_format(po_date, "%d/%m/%Y") as po_date'), 'comp.c_name as comp', 'agen.c_name as agen')
            ->where('po_id', $id)
            ->first();

        $detail = DB::table('d_productorderdt')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorderdt.*', 'm_item.*', 'm_unit.*')
            ->where('pod_productorder', $id)
            ->get();

        foreach ($detail as $key => $dt) {
            $order[] = [
                'barang'     => $dt->i_name,
                'unit'       => $dt->u_name,
                'qty'        => $dt->pod_qty,
                'price'      => Currency::addRupiah($dt->pod_price),
                'totalprice' => Currency::addRupiah($dt->pod_totalprice)
            ];
        }


        return Response::json(array(
            'success' => true,
            'data1'   => $order,
            'data2'   => $produk
        ));
    }
    // Order Produk Ke Cabang End ==========================================================================

    // Kelola Data Order Agen ==============================================================================
    public function listAgen($status)
    {
        $data_agen = DB::table('d_productorder')
            ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
            ->join('m_company', 'po_agen', '=', 'c_id')
            ->select('d_productorder.*', DB::raw('date_format(po_date, "%d/%m/%Y") as po_date'), DB::raw('SUM(pod_totalprice) as total_price'), 'c_name as agen')
            ->where('po_status', '=', $status)
            ->groupBy('po_id')
            ->get();

        return Datatables::of($data_agen)
            ->addIndexColumn()
            ->addColumn('totalprice', function ($data_agen) {
                return Currency::addRupiah((int)$data_agen->total_price);
            })
            ->addColumn('action_agen', function ($data_agen) {
                if ($data_agen->po_status == "Y") {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-danger hint--top-left hint--error" aria-label="Reject Approve" onclick="rejectApproveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-disabled" Order" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-check"></i>
                                </button>
                            </div>';
                } else if ($data_agen->po_status == "N") {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-disabled" onclick="rejectAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-success hint--top-left hint--success" aria-label="Aktifkan" onclick="activateAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-check-circle-o"></i>
                                </button>
                            </div>';
                } else {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-danger hint--top-left hint--error" aria-label="Reject" onclick="rejectAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-success hint--top-left hint--success" aria-label="Approve" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-check"></i>
                                </button>
                            </div>';
                }

            })
            ->rawColumns(['totalprice','action_agen'])
            ->make(true);
    }

    public function getDataAgen(Request $request)
    {
        $id = $request->id;
        $agen = DB::table('m_company')
            ->join('m_agen', 'c_user', 'a_code')
            ->join('m_wil_provinsi', 'a_provinsi', 'wp_id')
            ->join('m_wil_kota', 'a_area', 'wc_id')
            ->select('m_company.*', 'm_agen.*', 'm_wil_provinsi.*', 'm_wil_kota.*')
            ->where('c_type', '=', 'AGEN')
            ->where('a_area', '=', $id)
            ->get();
        return Datatables::of($agen)
            ->addIndexColumn()
            ->addColumn('action_agen', function ($agen) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-primary hint--top-left hint--primary"  aria-label="Pilih Agen Ini" onclick="chooseAgen(\''.$agen->c_id.'\',\''.$agen->a_name.'\',\''.$agen->c_user.'\')"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                        </div>';

            })
            ->rawColumns(['action_agen'])
            ->make(true);
    }

    public function cariDataAgen(Request $request)
    {
        $is_agen = array();
        for ($i = 0; $i < count($request->idAgen); $i++) {
            if ($request->idAgen[$i] != null) {
                array_push($is_agen, $request->idAgen[$i]);
            }
        }

        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', 'c_user')
            ->select('m_agen.*', 'm_company.*')
            ->whereNotIn('a_id', $is_agen)
            ->where(function ($q) use ($cari) {
                $q->whereRaw("a_name like '%" . $cari . "%'");
                $q->orWhereRaw("a_code like '%" . $cari . "%'");
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->c_id, 'label' => strtoupper($query->a_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function filterDataAgen(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date   = Carbon::parse($request->end_date)->format('Y-m-d');
        $status     = $request->state;
        $id         = $request->agen;

        if ($start_date != null && $end_date != null && $id != null) {
            $data_agen = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
                ->join('m_company', 'po_agen', '=', 'c_id')
                ->select('d_productorder.*', 'd_productorderdt.*', DB::raw('date_format(po_date, "%d/%m/%Y") as date'), DB::raw('SUM(pod_totalprice) as total_price'), 'm_company.*')
                ->whereBetween('po_date', [$start_date, $end_date])
                ->where('po_status', '=', $status)
                ->where('po_agen', '=', $id)
                ->groupBy('po_id')
                ->get();
        } else if ($start_date != null && $end_date != null && $id == null) {
            $data_agen = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
                ->join('m_company', 'po_agen', '=', 'c_id')
                ->select('d_productorder.*', 'd_productorderdt.*', DB::raw('date_format(po_date, "%d/%m/%Y") as date'), DB::raw('SUM(pod_totalprice) as total_price'), 'm_company.*')
                ->whereBetween('po_date', [$start_date, $end_date])
                ->where('po_status', '=', $status)
                ->groupBy('po_id')
                ->get();
        } else if ($start_date == null && $end_date == null && $id != null) {
            $data_agen = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
                ->join('m_company', 'po_agen', '=', 'c_id')
                ->select('d_productorder.*', 'd_productorderdt.*', DB::raw('date_format(po_date, "%d/%m/%Y") as date'), DB::raw('SUM(pod_totalprice) as total_price'), 'm_company.*')
                ->where('po_status', '=', $status)
                ->where('po_agen', '=', $id)
                ->groupBy('po_id')
                ->get();
            dd($data_agen);
        } else {
            $data_agen = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
                ->join('m_company', 'po_agen', '=', 'c_id')
                ->select('d_productorder.*', 'd_productorderdt.*', DB::raw('date_format(po_date, "%d/%m/%Y") as date'), DB::raw('SUM(pod_totalprice) as total_price'), 'm_company.*')
                ->where('po_status', '=', $status)
                ->groupBy('po_id')
                ->get();
        }

        return DataTables::of($data_agen)
            ->addIndexColumn()
            ->addColumn('total_price', function ($data_agen) {
                return Currency::addRupiah($data_agen->total_price);
            })
            ->addColumn('action_agen', function ($data_agen) {
                if ($data_agen->po_status == "Y") {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-danger hint--top-left hint--error" aria-label="Reject Approve" onclick="rejectApproveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-disabled" Order" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-check"></i>
                                </button>
                            </div>';
                } else if ($data_agen->po_status == "N") {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-disabled" onclick="rejectAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-success hint--top-left hint--success" aria-label="Aktifkan" onclick="activateAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-check-circle-o"></i>
                                </button>
                            </div>';
                } else {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-danger hint--top-left hint--error" aria-label="Reject" onclick="rejectAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-success hint--top-left hint--success" aria-label="Approve" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-check"></i>
                                </button>
                            </div>';
                }

            })
            ->rawColumns(['total_price','action_agen'])
            ->make(true);
    }

    public function rejectAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "N"
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

    public function activateAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "P"
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

    public function approveAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "Y"
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

    public function rejectApproveAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "P"
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

    public function detailAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $produk = DB::table('d_productorder')
            ->join('m_company as comp', 'po_comp', 'comp.c_id')
            ->join('m_company as agen', 'po_agen', 'agen.c_id')
            ->select('d_productorder.*', DB::raw('date_format(po_date, "%d/%m/%Y") as po_date'), 'comp.c_name as comp', 'agen.c_name as agen')
            ->where('po_id', $id)
            ->first();

        $detail = DB::table('d_productorderdt')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorderdt.*', 'm_item.*', 'm_unit.*')
            ->where('pod_productorder', $id)
            ->get();

        foreach ($detail as $key => $dt) {
            $order[] = [
                'barang'     => $dt->i_name,
                'unit'       => $dt->u_name,
                'qty'        => $dt->pod_qty,
                'price'      => Currency::addRupiah($dt->pod_price),
                'totalprice' => Currency::addRupiah($dt->pod_totalprice)
            ];
        }


        return Response::json(array(
            'success' => true,
            'agen1'   => $order,
            'agen2'   => $produk
        ));
    }
    // Kelola Data Order Agen End ==========================================================================

    // Kelola Data Canvassing Start ==============================================================================
    public function getListDC(Request $request)
    {
        $userType = Auth::user()->u_user;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        if ($agentCode !== null) {
            // get user based on agent-code
            $user = d_username::where('u_code', $agentCode)->first();
            // if $user is null, then return empty list
            if ($user == null)
            {
                $datas = Collection::make();
            }
            else
            {
                // get sub-agent's code  from selected-user/agent
                $subAgents = m_agen::where('a_parent', $user->u_code)
                ->get();
                $listAgentCode = array();
                foreach ($subAgents as $subAgent) {
                    array_push($listAgentCode, $subAgent->a_code);
                }
                // add selected-user's code
                array_push($listAgentCode, $user->u_code);
                // get user from created list of agent/sub-agent's code
                $users = d_username::whereIn('u_code', $listAgentCode)->get();
                $listUserId = array();
                foreach ($users as $user) {
                    array_push($listUserId, $user->u_id);
                }
                $datas = d_canvassing::whereBetween('c_date', [$from, $to])
                ->whereIn('c_user', $listUserId)
                ->orderBy('c_name', 'asc')
                ->get();
            }
        }
        else
        {
            if ($userType === 'E')
            {
                $datas = d_canvassing::whereBetween('c_date', [$from, $to])
                ->orderBy('c_name', 'asc')
                ->get();
            }
            else
            {
                // get sub-agent's code  from currently logged in user
                $subAgents = m_agen::where('a_parent', Auth::user()->u_code)
                ->get();
                $listAgentCode = array();
                foreach ($subAgents as $subAgent) {
                    array_push($listAgentCode, $subAgent->a_code);
                }
                // add logged-in user's code
                array_push($listAgentCode, Auth::user()->u_code);
                // get user from created list of agent/sub-agent's code
                $users = d_username::whereIn('u_code', $listAgentCode)->get();
                $listUserId = array();
                foreach ($users as $user) {
                    array_push($listUserId, $user->u_id);
                }

                $datas = d_canvassing::whereBetween('c_date', [$from, $to])
                ->whereIn('c_user', $listUserId)
                ->orderBy('c_name', 'asc')
                ->get();
            }
        }
        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
            if (Auth::user()->u_id != $datas->c_user) {
                return
                '<div class="btn-group btn-group-sm">
                (Owned by others)
                </div>';
            } else {
                return
                '<div class="btn-group btn-group-sm">
                <button class="btn btn-warning btn-edit-canv" type="button" title="Edit" onclick="editDataCanvassing('. $datas->c_id .')"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btn-disable-canv" type="button" title="Delete" onclick="deleteDataCanvassing('. $datas->c_id .')"><i class="fa fa-times-circle"></i></button>
                </div>';
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    // this following function is also used by 'Manajemen Penjualan Agen'
    // get list-cities based on province-id
    public function getCitiesDC(Request $request)
    {
        $cities = m_wil_provinsi::where('wp_id', $request->provId)
        ->with('getCities')
        ->firstOrFail();
        return response()->json($cities);
    }
    // this following function is also used by 'Manajemen Penjualan Agen'
    // get list-agents based on citiy-id
    public function getAgentsDC(Request $request)
    {
        $agents = m_agen::where('a_area', $request->cityId)
        ->where('a_type', 'AGEN')
        ->with('getProvince')
        ->with('getCity')
        ->orderBy('a_code', 'desc')
        ->get();

        return response()->json($agents);
    }
    // this following function is also used by 'Manajemen Penjualan Agen'
    // find agents and retrieve it by autocomple.js
    public function findAgentsByAu(Request $request)
    {
        $term = $request->termToFind;

        // startu query to find specific item
        $agents = m_agen::where('a_name', 'like', '%'.$term.'%')
            ->get();

        if (count($agents) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($agents as $agent) {
                $results[] = ['id' => $agent->a_id, 'label' => $agent->a_code . ' - ' .strtoupper($agent->a_name), 'agent_code' => $agent->a_code];
            }
        }
        return response()->json($results);
    }
    // validate request
    public function validateDC(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'telp' => 'required'
        ],
        [
            'name.required' => 'Nama masih kosong !',
            'telp.required' => 'No telp masih kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }
    // store item to db
    public function storeDC(Request $request)
    {
        // validate request
        $isValidRequest = $this->validateDC($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        // start insert data
        DB::beginTransaction();
        try {
            $canvassingId = d_canvassing::max('c_id') + 1;
            $canvassing = new d_canvassing();
            $canvassing->c_id = $canvassingId;
            $canvassing->c_user = Auth::user()->u_id;
            $canvassing->c_date = Carbon::now();
            $canvassing->c_name = $request->name;
            $canvassing->c_tlp = $request->telp;
            $canvassing->c_email = $request->email;
            $canvassing->c_address = $request->address;
            $canvassing->c_note = $request->note;
            $canvassing->save();

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
    // display edit page
    public function editDC($id)
    {
        $data = d_canvassing::where('c_id', $id)->firstOrFail();
        return response()->json($data);
    }
    // update specific item in db
    public function updateDC(Request $request, $id)
    {
        // validate request
        $isValidRequest = $this->validateDC($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        // start insert data
        DB::beginTransaction();
        try {
            $canvassing = d_canvassing::where('c_id', $id)->first();
            $canvassing->c_user = Auth::user()->u_id;
            $canvassing->c_date = Carbon::now();
            $canvassing->c_name = $request->name;
            $canvassing->c_tlp = $request->telp;
            $canvassing->c_email = $request->email;
            $canvassing->c_address = $request->address;
            $canvassing->c_note = $request->note;
            $canvassing->save();

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
    // delete specific item from db
    public function deleteDC($id)
    {
        // start insert data
        DB::beginTransaction();
        try {
            $canvassing = d_canvassing::where('c_id', $id)->first();
            $canvassing->delete();

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
    // Kelola Data Canvassing End ==============================================================================

    // Monitoring Penjualan Agen Start ==============================================================================
    // display list of manajemen penjualan agen
    public function getListMPA(Request $request)
    {
        $userType = Auth::user()->u_user;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        // filter sales-list based on existence of agents
        // aim : show sales-list from selected agent and it's sub-agents
        if ($agentCode !== null)
        {
            // get user based on agent-code
            $user = d_username::where('u_code', $agentCode)->first();
            // if $user is null, then return empty list
            if ($user == null)
            {
                $datas = Collection::make();
            }
            else
            {
                // get sub-agent's code from selected-user/agent
                $subAgents = m_agen::where('a_parent', $user->u_code)
                ->get();
                $listAgentCode = array();
                foreach ($subAgents as $subAgent) {
                    array_push($listAgentCode, $subAgent->a_code);
                }
                // add selected-user's code
                array_push($listAgentCode, $user->u_code);
                // get user from created list of agent/sub-agent's code
                $users = d_username::whereIn('u_code', $listAgentCode)->get();
                $listUserCompany = array();
                foreach ($users as $user) {
                    array_push($listUserCompany, $user->u_company);
                }
                // get query to show sales-list
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->whereIn('s_comp', $listUserCompany)
                ->with('getUser.agen')
                ->orderBy('s_date', 'desc')
                ->orderBy('s_nota', 'desc')
                ->get();
            }
        }
        else
        {
            if ($userType === 'E')
            {
                // get query to show sales-list
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->with('getUser.employee')
                ->with('getUser.agen')
                ->orderBy('s_date', 'desc')
                ->orderBy('s_nota', 'desc')
                ->get();
            }
            else
            {
                // get sub-agent's code  from currently logged in user
                $subAgents = m_agen::where('a_parent', Auth::user()->u_code)
                ->get();
                $listAgentCode = array();
                foreach ($subAgents as $subAgent) {
                    array_push($listAgentCode, $subAgent->a_code);
                }
                // add logged-in user's code
                array_push($listAgentCode, Auth::user()->u_code);
                // get user from created list of agent/sub-agent's code
                $users = d_username::whereIn('u_code', $listAgentCode)->get();
                $listUserCompany = array();
                foreach ($users as $user) {
                    array_push($listUserCompany, $user->u_company);
                }

                // get query to show sales-list
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->whereIn('s_comp', $listUserCompany)
                ->with('getUser.agen')
                ->orderBy('s_date', 'desc')
                ->orderBy('s_nota', 'desc')
                ->get();
            }
        }
        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('name', function($datas) {
            if ($datas->getUser->employee !== null) {
                return $datas->getUser->employee->e_name;
            } elseif ($datas->getUser->agen !== null) {
                return $datas->getUser->agen->a_name;
            } else {
                return '( Nama user tidak ditemukan )';
            }
            return ;
        })
        ->addColumn('date', function($datas) {
            return Carbon::parse($datas->s_date)->format('d M Y');
        })
        ->addColumn('total', function($datas) {
            return '<div><span class="pull-right">Rp '. number_format((float)$datas->s_total, 2, ',', '.') .'</span></div>';
        })
        ->addColumn('action', function($datas) {
            return
            '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-detail-canv" type="button" title="Detail" onclick="detailMPA('. $datas->s_id .')"><i class="fa fa-folder"></i></button>
            </div>';
        })
        ->rawColumns(['name', 'date', 'total', 'action'])
        ->make(true);
    }
    public function getDetailMPA($id)
    {
        $detail = d_sales::where('s_id', $id)
        ->with('getUser.employee')
        ->with('getUser.agen')
        ->with('getSalesDt.getItem.getUnit1')
        ->firstOrFail();
        // get list qty with smallest unit
        $listQty = array();
        foreach ($detail->getSalesDt as $salesDt) {
            if ($salesDt->sd_unit == $salesDt->getItem->i_unit1) {
                array_push($listQty, $salesDt->sd_qty);
            } elseif ($salesDt->sd_unit == $salesDt->getItem->i_unit2) {
                array_push($listQty, (int)$salesDt->sd_qty * (int)$salesDt->getItem->i_unitcompare2);
            } elseif ($salesDt->sd_unit == $salesDt->getItem->i_unit3) {
                array_push($listQty, (int)$salesDt->sd_qty * (int)$salesDt->getItem->i_unitcompare3);
            }
        }

        return response()->json(array(
            'detail' => $detail,
            'listQty' => $listQty
        ));
    }
    // Monitoring Penjualan Agen End ==============================================================================

    public function create_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/create');
    }

    public function edit_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/edit');
    }

    public function create_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/create');
    }

    public function edit_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/edit');
    }
}
