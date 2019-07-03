<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_canvassing;
use App\d_productorder;
use App\d_productorderdt;
use App\d_productordercode;
use App\d_sales;
use App\d_salescomp;
use App\d_salescompdt;
use App\d_salescompcode;
use App\d_stock;
use App\d_stock_mutation;
use App\d_username;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_wil_provinsi;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use DataTables;
use DB;
use Mutasi;
use Response;
use Validator;

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
        return view('marketing/marketingarea/orderproduk/nota', compact('order', 'nota'));
    }

    // Order Produk Ke Cabang ==============================================================================
    public function orderList()
    {
        $order = [];
        if (Auth::user()->getCompany->c_type == "PUSAT") {
            $order = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', 'pod_productorder')
                ->join('m_company as comp', 'po_comp', 'comp.c_id')
                ->join('m_company as agen', 'po_agen', 'agen.c_id')
                ->join('m_item', 'pod_item', 'i_id')
                ->join('m_unit', 'pod_unit', 'u_id')
                ->select('d_productorder.*', DB::raw('date_format(po_date, "%m/%Y") as po_date'), 'i_name', 'u_name', 'comp.c_name as comp', 'agen.c_name as agen', DB::raw('SUM(pod_totalprice) as totalprice'))
                ->where('comp.c_type', '=', "PUSAT")
                ->groupBy('po_id')
                ->get();
        } else {
            $order = DB::table('d_productorder')
                ->join('d_productorderdt', 'po_id', 'pod_productorder')
                ->join('m_company as comp', 'po_comp', 'comp.c_id')
                ->join('m_company as agen', 'po_agen', 'agen.c_id')
                ->join('m_item', 'pod_item', 'i_id')
                ->join('m_unit', 'pod_unit', 'u_id')
                ->select('d_productorder.*', DB::raw('date_format(po_date, "%m/%Y") as po_date'), 'i_name', 'u_name', 'comp.c_name as comp', 'agen.c_name as agen', DB::raw('SUM(pod_totalprice) as totalprice'))
                ->where('comp.c_type', '=', "PUSAT")
                ->where('agen.c_id', '=', Auth::user()->u_company)
                ->groupBy('po_id')
                ->get();
        }
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
            ->rawColumns(['totalprice', 'action'])
            ->make(true);
    }

    public function createOrderProduk(Request $req)
    {
        try {
            $u_id = Crypt::decrypt($req->user);
        } catch (\Exception $e) {
            return view('errors.404');
        }
        $user = DB::table('d_username')
            ->leftJoin('m_company', 'u_company', 'c_id')
            ->leftJoin('m_wil_kota', 'c_area', 'wc_id')
            ->leftJoin('m_wil_provinsi', 'wc_provinsi', 'wp_id')
            ->where('u_id', '=', $u_id)->first();
        $provinsi = DB::table('m_wil_provinsi')->select('m_wil_provinsi.*')->get();
        $city = DB::table('m_wil_kota')->select('m_wil_kota.*')->get();
        $company = DB::table('m_company')->select('m_company.*')
            ->where('c_type', '=', 'PUSAT')
            ->get();
        return view('marketing/marketingarea/orderproduk/create', compact('provinsi', 'city', 'company', 'user'));
    }

    public function getCity(Request $request)
    {
        $provId = $request->provId;
        $city = DB::table('m_wil_kota')->select('wc_id', 'wc_name')
            ->where('wc_provinsi', '=', $provId)
            ->orderBy('wc_name', 'asc')
            ->get();
        return Response::json(array(
            'success' => true,
            'data' => $city
        ));
    }

    public function getComp()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        return Response::json(array(
            'success' => true,
            'data' => $company
        ));
    }

    public function getAgen(Request $request)
    {
        $id = $request->cityId;
        $agen = DB::table('m_company')
            ->where('c_type', '!=', 'PUSAT')
            ->where('c_area', '=', $id)
            ->get();
        return Response::json(array(
            'success' => true,
            'data' => $agen
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
        $qty = $request->qty;

        if ($qty != null || $qty != "") {
            if ($qty == 1) {
                $price = DB::table('m_priceclassdt')
                    ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                    ->select('m_priceclassdt.*', 'm_priceclass.*')
                    ->where('pc_name', '=', "Agen")
                    ->where('pcd_item', '=', $idItem)
                    ->where('pcd_unit', '=', $idUnit)
                    ->where('pcd_type', '=', "U")
                    ->where('pcd_payment', '=', 'C')
                    ->where('pcd_rangeqtystart', '=', $qty)
                    ->where('pcd_rangeqtyend', '=', $qty)
                    ->first();
            } else if ($qty > 1) {
                $infinity = DB::table('m_priceclassdt')
                    ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                    ->select('m_priceclassdt.*', 'm_priceclass.*')
                    ->where('pc_name', '=', "Agen")
                    ->where('pcd_item', '=', $idItem)
                    ->where('pcd_unit', '=', $idUnit)
                    ->where('pcd_type', '=', "R")
                    ->where('pcd_payment', '=', 'C')
                    ->where('pcd_rangeqtystart', '<=', $qty)
                    ->where('pcd_rangeqtyend', '=', 0)->first();
                if ($infinity) {
                    return Response::json(array(
                        'success' => true,
                        'data' => number_format($infinity->pcd_price, 0, ',', '')
                    ));
                } else {
                    $price = DB::table('m_priceclassdt')
                        ->join('m_priceclass', 'pcd_classprice', 'pc_id')
                        ->select('m_priceclassdt.*', 'm_priceclass.*')
                        ->where('pc_name', '=', "Agen")
                        ->where('pcd_item', '=', $idItem)
                        ->where('pcd_unit', '=', $idUnit)
                        ->where('pcd_type', '=', "R")
                        ->where('pcd_payment', '=', 'C')
                        ->where('pcd_rangeqtystart', '<=', $qty)
                        ->where('pcd_rangeqtyend', '>=', $qty)
                        ->first();
                }

            } else {
                return Response::json(array(
                    'success' => true,
                    'data' => 0
                ));
            }
        } else {
            return Response::json(array(
                'success' => true,
                'data' => 0
            ));
        }

        if ($price) {
            return Response::json(array(
                'success' => true,
                'data' => number_format($price->pcd_price, 0, ',', '')
            ));
        } else {
            return Response::json(array(
                'success' => true,
                'data' => 0
            ));
        }
    }

    public function orderProdukStore(Request $request)
    {
        // dd($request);
        $data = $request->all();
        $now = Carbon::now('Asia/Jakarta');
        $time = date('Y-m-d', strtotime($now));
        DB::beginTransaction();
        try {
            $detailId = 0;
            for ($i = 0; $i < count($data['idItem']); $i++) {

                $query1 = DB::table('d_productorder')
                    ->where('po_date', '=', $time)
                    ->where('po_comp', '=', $data['po_comp'])
                    ->where('po_agen', '=', $data['po_agen'])
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
                                'pod_qty' => $qtyAkhir,
                                'pod_totalprice' => $priceAkhir
                            ]);
                    } else {

                        $detailId = DB::table('d_productorderdt')
                            ->where('pod_productorder', '=', $query1->po_id)
                            ->max('pod_detailid');

                        DB::table('d_productorderdt')->insert([
                            'pod_productorder' => $query1->po_id,
                            'pod_detailid'     => $detailId + 1,
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
                        'po_comp'   => $data['po_comp'],
                        'po_agen'   => $data['po_agen'],
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
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function editOrderProduk($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (Exception $e) {
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
            for ($i = 0; $i < count($data['idItem']); $i++) {
                DB::table('d_productorderdt')->insert([
                    'pod_productorder' => $id,
                    'pod_detailid' => ++$detailId,
                    'pod_item' => $data['idItem'][$i],
                    'pod_unit' => $data['po_unit'][$i],
                    'pod_qty' => $data['po_qty'][$i],
                    'pod_price' => $data['po_hrg'][$i],
                    'pod_totalprice' => $data['sbtotal'][$i]
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
                'barang' => $dt->i_name,
                'unit' => $dt->u_name,
                'qty' => $dt->pod_qty,
                'price' => Currency::addRupiah($dt->pod_price),
                'totalprice' => Currency::addRupiah($dt->pod_totalprice)
            ];
        }


        return Response::json(array(
            'success' => true,
            'data1' => $order,
            'data2' => $produk
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
                }
                else if ($data_agen->po_status == "N") {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i>
                                </button>
                                <button class="btn btn-disabled" onclick="rejectAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-times"></i>
                                </button>
                                <button class="btn btn-success hint--top-left hint--success" aria-label="Aktifkan" onclick="activateAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-check-circle-o"></i>
                                </button>
                            </div>';
                }
                else {
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
            ->rawColumns(['totalprice', 'action_agen'])
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
                            <button class="btn btn-primary hint--top-left hint--primary"  aria-label="Pilih Agen Ini" onclick="chooseAgen(\'' . $agen->c_id . '\',\'' . $agen->a_name . '\',\'' . $agen->c_user . '\')"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
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
    // list data order-agent
    public function filterDataAgen(Request $request)
    {
        $status = $request->state;
        $id = $request->agen;

        $data_agen = DB::table('d_productorder')
            ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
            ->join('m_company as agen', 'po_agen', '=', 'agen.c_id')
            ->join('m_company as cabang', 'po_comp', '=', 'cabang.c_id')
            ->select('d_productorder.*', 'd_productorderdt.*',
                DB::raw('date_format(po_date, "%d/%m/%Y") as date'),
                DB::raw('SUM(pod_totalprice) as total_price'), 'agen.c_id as c_id', 'agen.c_name as c_name','cabang.c_name as cabang','cabang.c_id as id_cabang')
            ->where('po_status', '=', $status);
        //filter start_date, end_date, id_agen
        if ($request->start_date != null){
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $data_agen->where('po_date', '>=', $start_date);
        }
        if ($request->end_date != null){
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $data_agen->where('po_date', '<=', $end_date);
        }
        if ($id != null){
            $data_agen->where('po_agen', '=', $id);
        }
        if (Auth::user()->getCompany->c_type != "PUSAT"){
            $data_agen->where('po_comp', '=', Auth::user()->u_company);
        }

        $data_agen = $data_agen->groupBy('po_id')->get();

        return DataTables::of($data_agen)
            ->addIndexColumn()
            ->addColumn('total_price', function ($data_agen) {
                return Currency::addRupiah($data_agen->total_price);
            })
            ->addColumn('action_agen', function ($data_agen) {
                if ($data_agen->po_status == "Y") {
                    $btns = '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                                <button class="btn btn-primary hint--top-left hint--info" aria-label="Detail Order" onclick="detailAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-folder"></i></button>';
                    if ($data_agen->po_send == "Y") {
                        $btns = $btns .'<button class="btn btn-disabled hint--top-left hint--error" aria-label="Reject Approve" onclick="rejectApproveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-times"></i></button>
                            <button class="btn btn-disabled hint--top-left hint--info" aria-label="Receive" onclick="receiveItemOrder(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-get-pocket"></i></button>
                            <button class="btn btn-disabled" Order" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-check"></i></button>
                            </div>';
                    } else {
                        $btns = $btns .'<button class="btn btn-danger hint--top-left hint--error" aria-label="Reject Approve" onclick="rejectApproveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-times"></i></button>
                            <button class="btn btn-warning hint--top-left hint--info" aria-label="Receive" onclick="receiveItemOrder(\'' . Crypt::encrypt($data_agen->po_id) . '\')"><i class="fa fa-fw fa-get-pocket"></i></button>
                            <button class="btn btn-disabled" Order" onclick="approveAgen(\'' . Crypt::encrypt($data_agen->po_id) . '\')" disabled><i class="fa fa-fw fa-check"></i></button>
                            </div>';
                    }
                    return $btns;
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
            ->rawColumns(['total_price', 'action_agen'])
            ->make(true);
    }

    public function getDetailOrder(Request $request)
    {
        $po_id = Crypt::decrypt($request->id);
        $data = DB::table('d_productorder')
            ->join('d_productorderdt', 'pod_productorder', '=', 'po_id')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit', 'u_id', '=', 'i_unit1')
            ->select('i_name', DB::raw('concat(pod_qty, " ", u_name) as kuantitas'), DB::raw('floor(pod_price) as pod_price'),
                DB::raw('floor(pod_totalprice) as pod_totalprice'), 'po_id', 'pod_item', 'u_name', 'pod_qty', 'pod_unit',
                'i_unit1', 'i_unit2', 'i_unit3', 'i_unitcompare2', 'i_unitcompare3')
            ->where('po_id', '=', $po_id)
            ->get();

        for ($i = 0; $i < count($data); $i++){
            if ($data[$i]->pod_unit == $data[$i]->i_unit2){
                $data[$i]->pod_qty = $data[$i]->pod_qty * $data[$i]->i_unitcompare2;
            }elseif ($data[$i]->pod_unit == $data[$i]->i_unit3){
                $data[$i]->pod_qty = $data[$i]->pod_qty * $data[$i]->i_unitcompare3;
            }
        }

        return DataTables::of($data)
            ->editColumn('pod_price', function ($data){
                return "<span class='modaldtharga-".$data->pod_item."'>Rp. " . number_format($data->pod_price, "0", ",", ".") . "</span><input type='hidden' value='".$data->pod_price."' class='input-modaldtharga".$data->pod_item."'>";
            })
            ->editColumn('pod_totalprice', function ($data){
                return "<span class='modaldtsubharga-".$data->pod_item."'>Rp. " . number_format($data->pod_totalprice, "0", ",", ".") . "</span><input type='hidden' value='".$data->pod_totalprice."' name='subtotalmodaldt[]' class='subtotalmodaldt input-modaldtsubharga".$data->pod_item."'>";
            })
            ->addColumn('kode', function ($data){
                return "<div class='text-center' style='width: 100%'><button type='button' onclick='addCodeProd(".$data->po_id.", ".$data->pod_item.",\"".$data->i_name."\")' class='btn btn-info btn-xs btnAddProdCode'><i class='fa fa-plus'></i> Kode Produksi</button></div>";
            })
            ->addColumn('input', function ($data){
                return "<div class='text-center'>
                <input type='number' onkeyup='getHargaGolongan(".$data->pod_item.")' onchange='getHargaGolongan(".$data->pod_item.")' style='text-align: right; width: 100%;' class='input-qty-proses qty-modaldt-".$data->pod_item."' name='qty_proses[]' value='".$data->pod_qty."'>
                <input type='hidden' name='itemsId[]' class='itemsId' value='". $data->pod_item ."'>
                <input type='hidden' name='units[]' class='units' value='". $data->i_unit1 ."'>
                </div>";
            })
            ->rawColumns(['kode', 'input', 'pod_price', 'pod_totalprice'])
            ->make(true);

    }

    public function getDetailOrderAgen(Request $request)
    {
        $po_id = Crypt::decrypt($request->id);
        $data = DB::table('d_productorder')
            ->join('d_productorderdt', 'pod_productorder', '=', 'po_id')
            ->join('m_company', 'c_id', '=', 'po_agen')
            ->select(DB::raw('date_format(po_date, "%d-%m-%Y") as po_date'), 'c_name', 'po_nota', 'po_agen', DB::raw('floor(sum(pod_totalprice)) as pod_totalprice'))
            ->where('po_id', '=', $po_id)
            ->groupBy('po_id')
            ->first();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getCodeOrder(Request $request)
    {
        $po_id = $request->id;
        $item = $request->item;

        $data = DB::table('d_productorder')
            ->join('d_productorderdt', 'po_id', '=', 'pod_productorder')
            ->join('d_productordercode', function ($q){
                $q->on('poc_productorder', '=', 'po_id');
                $q->on('poc_item', '=', 'pod_item');
            })
            ->select('poc_code', 'poc_qty', 'po_id', 'pod_item')
            ->where('po_id', '=', $po_id)
            ->where('pod_item', '=', $item)
            ->get();

        return DataTables::of($data)
            ->editColumn('poc_qty', function ($data) {
                return '<div class="qty-prod-code">'. $data->poc_qty .'</div>';
            })
            ->addColumn('aksi', function ($data){
                return "<div class='text-center' style='width: 100%'><button type='button' onclick='removeCodeOrder(".$data->po_id.", ".$data->pod_item.", \"".$data->poc_code."\")' class='btn btn-danger btn-xs'><i class='fa fa-close'></i></button></div>";
            })
            ->rawColumns(['aksi', 'poc_qty'])
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
    // approve order agent and create mutation
    public function approveAgen(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get product-order
            $productOrder = d_productorder::where('po_id', $id)
            ->with('getPODt')
            ->first();

            // mutation
            foreach ($productOrder->getPODt as $key => $PO) {
                $idxQty = array_search($PO->pod_item, $request->itemsId);
                $PO->pod_qty = $request->qty_proses[$idxQty];
                $PO->pod_unit = $request->units[$idxQty];
                $PO->save();
                // get sellprice
                $sellPrice = $PO->pod_price;

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

                // insert stock mutation sales 'out'
                $mutationOut = Mutasi::salesOut(
                    $productOrder->po_comp, // from
                    $productOrder->po_agen, // to
                    $PO->pod_item, // item-id
                    $request->qty_proses[$idxQty], // qty of smallest-unit
                    $productOrder->po_nota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sellPrice, // sellprice
                    5 // mutcat
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
                    $stockParentId // stock-parent id
                );
                if ($mutationIn->original['status'] !== 'success') {
                    return $mutationIn;
                }
            }
            // dd('x', $mutationIn);

            // update qty and status in d_productorder
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "Y"
                ]);

            // get total-price based on d_productorderdt
            $totalPrice = (int)d_productorderdt::where('pod_productorder', $productOrder->po_id)
            ->sum('pod_totalprice');

            // clone data from d_productorder to d_salescomp
            $salescompId= (DB::table('d_salescomp')->max('sc_id')) ? DB::table('d_salescomp')->max('sc_id') + 1 : 1;
            $val_sales = [
                'sc_id'      => $salescompId,
                'sc_comp'    => $productOrder->po_comp,
                'sc_member'  => $productOrder->po_agen,
                'sc_type'    => 'C',
                'sc_date'    => $productOrder->po_date,
                'sc_nota'    => $productOrder->po_nota,
                'sc_total'   => $totalPrice,
                'sc_user'    => Auth::user()->u_id,
                'sc_insert'  => Carbon::now(),
                'sc_update'  => Carbon::now()
            ];
            DB::table('d_salescomp')->insert($val_sales);
            // clone data from  d_productorderdt to d_salescompdt
            $salescompdtid = (DB::table('d_salescompdt')->where('scd_sales', '=', $salescompId)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $salescompId)->max('sd_detailid')) + 1 : 1;
            $val_salesdt = array();
            foreach ($productOrder->getPODt as $key => $po) {
                $val_salesdt[] = [
                    'scd_sales' => $salescompId,
                    'scd_detailid' => $salescompdtid,
                    'scd_comp' => $productOrder->po_comp,
                    'scd_item' => $po->pod_item,
                    'scd_qty' => $po->pod_qty,
                    'scd_unit' => $po->pod_unit,
                    'scd_value' => $po->pod_price,
                    'scd_discpersen' => 0,
                    'scd_discvalue' => 0,
                    'scd_totalnet' => $po->pod_totalprice
                ];

                // clone data from productordercode to salescompcode
                $prodCode = d_productordercode::where('poc_productorder', $productOrder->po_id)
                ->where('poc_item', $po->pod_item)
                ->get();
                $salescompcodeid = (d_salescompcode::where('ssc_salescomp', $salescompId)->where('ssc_item', $po->pod_item)->max('ssc_detailid')) ? d_salescompcode::where('ssc_salescomp', $salescompId)->where('ssc_item', $po->pod_item)->max('ssc_detailid') + 1 : 1;
                $val_salescode = array();
                foreach ($prodCode as $key => $poc) {
                    $val_salescode[] = [
                        'ssc_salescomp' => $salescompId,
                        'ssc_item' => $po->pod_item,
                        'ssc_detailid' => $salescompcodeid,
                        'ssc_code' => $poc->poc_code,
                        'ssc_qty' => $poc->poc_qty
                    ];
                    $salescompcodeid++;
                }
                DB::table('d_salescompcode')->insert($val_salescode);

                $salescompdtid++;
            }
            DB::table('d_salescompdt')->insert($val_salesdt);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // reject approved order and roll-it-back
    public function rejectApproveAgen($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get product-order
            $productOrder = d_productorder::where('po_id', $id)
            ->with('getPODt')
            ->first();

            foreach ($productOrder->getPODt as $key => $po) {
                // rollBack qty in stock-mutation and stock-item
                $rollbackPO = Mutasi::rollbackStockMutDist(
                    $productOrder->po_nota, // productorder nota
                    $po->pod_item, // item-id
                    5
                );
                if ($rollbackPO !== 'success') {
                    DB::rollback();
                    return $rollbackPO;
                }
            }
            // update status of productorder
            DB::table('d_productorder')
                ->where('po_id', $id)
                ->update([
                    'po_status' => "P"
                ]);

            // get salescomp by nota
            $salescomp = d_salescomp::where('sc_nota', $productOrder->po_nota)
            ->with('getSalesCompDt')
            ->first();
            // delete linked production code
            foreach ($salescomp->getSalesCompDt as $key => $salescompdt) {
                DB::table('d_salescompcode')->where('ssc_salescomp', $salescomp->sc_id)
                ->where('ssc_item', $salescompdt->scd_item)
                ->delete();
                // delete linked salescompdt
                $salescompdt->delete();
            }
            // delete linked salescomp
            $salescomp->delete();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // receive order and make it disabeld for editing
    public function receiveItemOrder($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            // get product-order
            $productOrder = d_productorder::where('po_id', $id)
            ->with('getPODt')
            ->first();

            // update stock using mutation distrtibution
            // acutually its public function, just add mutcat as condition to deal it
            foreach ($productOrder->getPODt as $key => $po) {
                $mutConfirm = Mutasi::confirmSales(
                    $productOrder->po_agen, // destination
                    $po->pod_item, // itemId
                    $productOrder->po_nota, // nota
                    20, // mutcat in
                    5 // mutcat out
                );
                if ($mutConfirm->original['status'] !== 'success') {
                    return $mutConfirm;
                }
            }

            // update product-order
            $productOrder->po_send = 'Y';
            $productOrder->save();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
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
                'barang' => $dt->i_name,
                'unit' => $dt->u_name,
                'qty' => $dt->pod_qty,
                'price' => Currency::addRupiah($dt->pod_price),
                'totalprice' => Currency::addRupiah($dt->pod_totalprice)
            ];
        }


        return Response::json(array(
            'success' => true,
            'agen1' => $order,
            'agen2' => $produk
        ));
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

    function inRange($value, $array)
    {
       // in_array($request->rangestartedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend));
        $idx = null;
        foreach ($array as $key =>  $val) {
            $x = in_array($value, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend));
            if ($x == true) {
                $idx = $key;
                break;
            }
        }
        return $idx;
    }

    public function cekHarga(Request $request)
    {
        $agen = $request->agen;
        $item = $request->item;

        // start: get stock
        try {
            $id = Crypt::decrypt($request->id);
        } catch (\Exception $e) {
            return Response::json([
                "status" => "gagal",
                "message" => 'gagal mendapatkan stock item !'
            ]);
        }
        $stock = $this->getStock($id, $item);
        // end: get stock

        $barang = DB::table('m_item')
            ->where('i_id', '=', $item)
            ->first();
        $unit = $barang->i_unit1;
        $qty = $request->qty;

        $type = DB::table('m_company')
            ->join('m_agen', 'c_user', '=', 'a_code')
            ->where('c_id', '=', $agen)
            ->first();

        $get_price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pc_id', '=', $type->a_class)
            ->where('pcd_payment', '=', 'C')
            ->where('pcd_item', '=', $item)
            ->where('pcd_unit', '=', $unit)
            ->get();

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
                    $z = $this->inRange($qty, $get_price);
                    if ($z !== null) {
                        $harga = $get_price[$z]->pcd_price;
                    }
                }

            }
        }

        return Response::json([
            "price" => number_format($harga, 0, '', ''),
            "stock" => $stock
        ]);

        // return Response::json(number_format($harga, 0, '', ''));
    }

    public function getStock($id, $item)
    {
        // get productorder
        $productOrder = d_productorder::where('po_id', $id)->first();
        // get stock
        $stock = d_stock::where('s_position', $productOrder->po_comp)
        ->where('s_item', $item)
        ->where('s_status', 'ON DESTINATION')
        ->where('s_condition', 'FINE')
        ->first();

        return $stock->s_qty;
    }

    public function setKode(Request $request)
    {
        $nota = $request->nota;
        $qty = $request->qty;
        $kode = strtoupper($request->kode);
        $item = $request->item;

        // set variable to validate production
        $listItemsId = array();
        $listProdCode = array();
        $listProdCodeLength = array();
        array_push($listItemsId, $item);
        array_push($listProdCode, $kode);
        array_push($listProdCodeLength, 1);

        // validate production-code is exist in stock-item
        $validateProdCode = Mutasi::validateProductionCode(
            Auth::user()->u_company, // from
            $listItemsId, // list item-id
            $listProdCode, // list production-code
            $listProdCodeLength // list production-code length each item
        );
        if ($validateProdCode !== 'validated') {
            return $validateProdCode;
        }

        $productorder = DB::table('d_productorder')
            ->where('po_nota', '=', $nota)
            ->first();
        $po_id = $productorder->po_id;
        DB::beginTransaction();
        try {
            $cek = DB::table('d_productordercode')
                ->where('poc_productorder', '=', $po_id)
                ->where('poc_item', '=', $item)
                ->where('poc_code', '=', $kode)
                ->get();

            if (count($cek) > 0){
                //update qty
                $qtyawal = $cek[0]->poc_qty;
                $qtyakhir = $qtyawal + $qty;
                DB::table('d_productordercode')
                    ->where('poc_productorder', '=', $po_id)
                    ->where('poc_item', '=', $item)
                    ->where('poc_code', '=', $kode)
                    ->update([
                        "poc_qty" => $qtyakhir
                    ]);
                DB::commit();
                return Response::json([
                    "status" => "success"
                ]);
            }
            else {
                //create baru
                $detail = DB::table('d_productordercode')
                    ->where('poc_productorder', '=', $po_id)
                    ->where('poc_item', '=', $item)
                    ->max('poc_detailid');

                ++$detail;
                DB::table('d_productordercode')
                    ->insert([
                        "poc_productorder" => $po_id,
                        "poc_item" => $item,
                        "poc_detailid" => $detail,
                        "poc_code" => $kode,
                        "poc_qty" => $qty
                    ]);
                DB::commit();
                return Response::json([
                    "status" => "success"
                ]);
            }
        } catch (\Exception $e){
            DB::rollback();
            return Response::json([
                "status" => "gagal",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function removeKode(Request $request)
    {
        $po_id = $request->id;
        $item = $request->item;
        $kode = $request->kode;

        DB::table('d_productordercode')
            ->where('poc_productorder', '=', $po_id)
            ->where('poc_item', '=', $item)
            ->where('poc_code', '=', $kode)
            ->delete();

        return Response::json([
            "status" => "success"
        ]);
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
            if ($user == null) {
                $datas = Collection::make();
            } else {
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
        } else {
            if ($userType === 'E') {
                $datas = d_canvassing::whereBetween('c_date', [$from, $to])
                    ->orderBy('c_name', 'asc')
                    ->get();
            } else {
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
            ->addColumn('action', function ($datas) {
                if (Auth::user()->u_id != $datas->c_user) {
                    return
                        '<div class="btn-group btn-group-sm">
                (Owned by others)
                </div>';
                } else {
                    return
                        '<div class="btn-group btn-group-sm">
                <button class="btn btn-warning btn-edit-canv" type="button" title="Edit" onclick="editDataCanvassing(' . $datas->c_id . ')"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btn-disable-canv" type="button" title="Delete" onclick="deleteDataCanvassing(' . $datas->c_id . ')"><i class="fa fa-times-circle"></i></button>
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
        $agents = m_agen::where('a_name', 'like', '%' . $term . '%')
            ->get();

        if (count($agents) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($agents as $agent) {
                $results[] = ['id' => $agent->a_id, 'label' => $agent->a_code . ' - ' . strtoupper($agent->a_name), 'agent_code' => $agent->a_code];
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
        if ($agentCode !== null) {
            // get user based on agent-code
            $user = d_username::where('u_code', $agentCode)->first();
            // if $user is null, then return empty list
            if ($user == null) {
                $datas = Collection::make();
            } else {
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
        } else {
            if ($userType === 'E') {
                // get query to show sales-list
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                    ->with('getUser.employee')
                    ->with('getUser.agen')
                    ->orderBy('s_date', 'desc')
                    ->orderBy('s_nota', 'desc')
                    ->get();
            } else {
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
            ->addColumn('name', function ($datas) {
                if ($datas->getUser->employee !== null) {
                    return $datas->getUser->employee->e_name;
                } elseif ($datas->getUser->agen !== null) {
                    return $datas->getUser->agen->a_name;
                } else {
                    return '( Nama user tidak ditemukan )';
                }
                return;
            })
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->s_date)->format('d M Y');
            })
            ->addColumn('total', function ($datas) {
                return '<div><span class="pull-right">Rp ' . number_format((float)$datas->s_total, 2, ',', '.') . '</span></div>';
            })
            ->addColumn('action', function ($datas) {
                return
                    '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-detail-canv" type="button" title="Detail" onclick="detailMPA(' . $datas->s_id . ')"><i class="fa fa-folder"></i></button>
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
    // Monitoring Penjualan Agen End ===========================================

    // Start: konsinyasi =======================================================
    // index -> read data and display to table
    public function getListDK(Request $request)
    {
        $branchCode = $request->branchCode;

        $datas = d_salescomp::where('sc_type', '=', 'K');
        // get comoany 'pusat'
        $pusat = m_company::where('c_type', 'PUSAT')->first();
        // if pusat is logged in
        if (Auth::user()->u_company == $pusat->c_id) {
            // add filter which branch wil be shown
            $datas = $datas->where('sc_comp', $branchCode);
        }
        // if branch is logged in
        else {
            // show konsinyasi that is made by him
            $datas = $datas->where('sc_comp', Auth::user()->u_company)
            ->where('sc_comp', '!=', 'MB0000001');
        }
        $datas = $datas
        ->where('sc_paidoff', 'N')
        ->with('getSalesCompDt')
        ->with('getAgent')
        ->orderBy('sc_date', 'desc')
        ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->sc_date)->format('d M Y');
            })
            ->addColumn('agent', function ($datas) {
                return $datas->getAgent->c_name;
            })
            ->addColumn('total', function ($datas) {
                return '<div class="text-right">Rp '. number_format($datas->sc_total, 0, 0, '.') .'</div>';
            })
            ->addColumn('action', function ($datas) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-warning btn-edit-kons" type="button" title="Edit" onclick="editDK('. $datas->sc_id .')"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger btn-delete-kons" type="button" title="Delete" onclick="deleteDK('. $datas->sc_id .')"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date', 'action', 'agent', 'total'])
            ->make(true);
    }
    // create
    public function create_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/create');
    }
    // get branch
    public function getBranchDK(Request $request)
    {
        $prov = $request->prov;
        $kota = $request->city;

        $nama = m_company::where('c_area', $kota)
        ->where('c_type', 'CABANG')
        ->get();

        return Response::json($nama);
    }
    // get agents
    public function getAgentsDK(Request $request)
    {
        $branch = $request->branch;

        $nama = m_agen::where('a_mma', $branch)
        ->with('getCompany')
        ->get();

        return Response::json($nama);
    }
    // get items
    public function getItemsDK(Request $request)
    {
        // set list items that is already exist
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        $comp = $request->branch;
        // dd($comp);
        // start: query to get items
        $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                });
        if(count($is_item) != 0){
            $nama = $nama->whereNotIn('i_id', $is_item);
        }
        $nama = $nama->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->groupBy('d_stock.s_id')
                ->get();

        // end: query to get items
        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->i_id,
                    'label' => $query->i_code . ' - ' .strtoupper($query->i_name),
                    'data' => $query,
                    'stock' => $query->s_id
                ];
            }
        }
        return Response::json($results);
    }
    // get item-listUnits
    public function getSatuanDK($id)
    {
        $data = m_item::where('i_id', $id)
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->first();
        return Response::json($data);
    }
    // check item stock by unit
    public function checkItemStockDK(Request $request)
    {
        $stock = $request->idStock;
        $item = $request->itemId;
        $satuan = $request->unit;
        $qty = $request->qty;

        $data_check = DB::table('m_item')
        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
        'm_item.i_unit3 as unit3')
        ->where('i_id', '=', $item)
        ->first();

        $data = DB::table('d_stock')
        ->join('d_stock_mutation', function($sm){
            $sm->on('sm_stock', '=', 's_id');
        })
        ->where('s_id', '=', $stock)
        // ->where('s_item', '=', $item)
        // ->where('s_status', '=', 'ON DESTINATION')
        // ->where('s_condition', '=', 'FINE')
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
                $qty_compare = (int)$data->sisa/(int)$data_check->compare2;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa/(int)$data_check->compare3;
            } else {
                $qty_compare = $qty;
            }
        }

        // dd(floor($qty_compare));
        return Response::json(floor($qty_compare));
    }
    // check item stock by unit
    public function checkItemStockDKOld(Request $request)
    {
        $stock = $request->idStock;
        $item = $request->itemId;
        $oldSatuan = $request->unitOld;
        $satuan = $request->unit;
        $qtyOld = $request->qtyOld;
        $qty = $request->qty;

        $data_check = DB::table('m_item')
        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
        'm_item.i_unit3 as unit3')
        ->where('i_id', '=', $item)
        ->first();

        $data = DB::table('d_stock')
        ->join('d_stock_mutation', function($sm){
            $sm->on('sm_stock', '=', 's_id');
        })
        ->where('s_id', '=', $stock)
        // ->where('s_item', '=', $item)
        // ->where('s_status', '=', 'ON DESTINATION')
        // ->where('s_condition', '=', 'FINE')
        ->select('sm_residue as sisa')
        ->first();

        $qty_compare_old = 0;
        if ($oldSatuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa) {
                $qty_compare_old = $data->sisa + $qtyOld;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit2) {
            $compare = (int)$qty * (int)$data_check->compare2;
            if ((int)$compare > (int)($data->sisa + $qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare2;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)($data->sisa+$qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare3;
            } else {
                $qty_compare_old = $qty;
            }
        }
        // dd(floor($qty_compare_old));
        return Response::json(floor($qty_compare_old));
    }
    // get item price
    public function checkHargaDK(Request $request)
    {
        $agent = $request->agentCode;
        $item = $request->itemId;
        $unit = $request->unit;
        $qty = $request->qty;

        $type = m_agen::whereHas('getCompany', function ($q) use ($agent) {
            $q->where('c_id', '=', $agent);
        })
        ->first();
        // dd($request->all(), $type);

        $get_price = DB::table('m_priceclassdt')
        ->join('m_priceclass', 'pcd_classprice', 'pc_id')
        ->select('m_priceclassdt.*', 'm_priceclass.*')
        ->where('pc_id', '=', $type->a_class)
        ->where('pcd_payment', '=', 'K')
        ->where('pcd_item', '=', $item)
        ->where('pcd_unit', '=', $unit)
        ->get();

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
            }
            else if ($qty > 1) {
                if ($price->pcd_rangeqtyend == 0){
                    if ($qty >= $price->pcd_rangeqtystart) {
                        $harga = $price->pcd_price;
                    }
                } else {
                    $z = $this->inRange($qty, $get_price);
                    if ($z !== null) {
                        $harga = $get_price[$z]->pcd_price;
                    }
                }
            }
        }

        // dd($get_price, $type, $qty, $harga);
        return Response::json(number_format($harga, 0, '', ''));
    }
    // store
    public function storeDK(Request $request)
    {
        $data   = $request->all();
        $comp   = $data['branchCode']; // pelaku konsinyasi
        $member = $data['agentCode']; // penerima item
        $compItem = $data['idStock']; // pemilik item
        $user   = Auth::user()->u_id;
        $type   = 'K';
        $date   = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $total  = $data['tot_hrg'];
        $insert = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota   = CodeGenerator::codeWithSeparator('d_salescomp', 'sc_nota', 8, 10, 3, 'SK', '-');
        $idSales= (DB::table('d_salescomp')->max('sc_id')) ? DB::table('d_salescomp')->max('sc_id') + 1 : 1;

        DB::beginTransaction();
        try {
            // get item owner
            foreach ($compItem as $key => $val) {
                $owner = d_stock::where('s_id', $val)->first();
                $compItem[$key] = $owner->s_comp;
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from / position
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength // list production-code length each item
            );
            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }
            $val_sales = [
                'sc_id'      => $idSales,
                'sc_comp'    => $comp,
                'sc_member'  => $member,
                'sc_type'    => $type,
                'sc_date'    => $date,
                'sc_nota'    => $nota,
                'sc_total'   => $total,
                'sc_user'    => $user,
                'sc_insert'  => $insert,
                'sc_update'  => $update
            ];

            $sddetail = (DB::table('d_salescompdt')->where('scd_sales', '=', $idSales)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $idSales)->max('sd_detailid')) + 1 : 1;
            $detailsd = $sddetail;
            $val_salesdt = [];
            for ($i = 0; $i < count($data['idItem']); $i++) {
                // values for insert to salescomp-dt
                $val_salesdt[] = [
                    'scd_sales' => $idSales,
                    'scd_detailid' => $detailsd,
                    'scd_comp' => $compItem[$i], // pemilik item
                    'scd_item' => $data['idItem'][$i],
                    'scd_qty' => $data['jumlah'][$i],
                    'scd_unit' => $data['satuan'][$i],
                    'scd_value' => Currency::removeRupiah($data['harga'][$i]),
                    'scd_discpersen' => 0,
                    'scd_discvalue' => 0,
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
                ];

                // values for insert to salescomp-code
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
                    $detailidcode = d_salescompcode::where('ssc_salescomp', $idSales)
                    ->where('ssc_item', $data['idItem'][$i])
                    ->max('ssc_detailid') + 1;

                    $val_salescode = [
                        'ssc_salescomp' => $idSales,
                        'ssc_item' => $data['idItem'][$i],
                        'ssc_detailid' => $detailidcode,
                        'ssc_code' => strtoupper($request->prodCode[$j]),
                        'ssc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescompcode')->insert($val_salescode);
                }

                // mutasi
                $data_check = DB::table('m_item')
                ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
                ->where('i_id', '=', $data['idItem'][$i])
                ->first();

                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]);
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare3;
                }

                $stock = DB::table('d_stock')
                ->where('s_id', '=', $data['idStock'][$i])
                ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                ->where('sm_stock', '=', $stock->s_id)
                ->first();

                $posisi = DB::table('m_company')
                ->where('c_id', '=', $member)
                ->first();

                // declaare list of production-code
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                $statusKons = 'cabang';
                // set mutation (mutation-out is called inside mutation-in)
                $mutKons = Mutasi::mutasimasuk(
                    12, // mutcat
                    $compItem[$i], // comp / item position from
                    $member, // position / destination
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty item with smallest unit
                    'ON DESTINATION', // status
                    'FINE', // condition
                    $stock_mutasi->sm_hpp, // hpp
                    $sellPrice, // sell value
                    $nota, // nota
                    $stock_mutasi->sm_nota, // nota refference
                    $listPC, // list production-code
                    $listQtyPC, // list qty roduction code
                    $statusKons, // status konsinyasi ('pusat' / 'branch')
                    $stock->s_comp // item owner
                );
                if (!is_bool($mutKons)) {
                    return $mutKons;
                }
                // dd('stored');
                $startProdCodeIdx += $prodCodeLength;
                $detailsd++;
            }
            // insert into db
            DB::table('d_salescomp')->insert($val_sales);
            DB::table('d_salescompdt')->insert($val_salesdt);

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil disimpan"
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }
    // edit
    public function edit_datakonsinyasi($id)
    {
        // $detail = d_salescomp::where('sc_id', $id)
        // ->with(['getComp' => function ($q) {
        //     $q->with('getCity');
        // }])
        // ->with(['getAgent' => function ($q) {
        // }])
        // ->first();

        $data_item = d_salescomp::where('sc_id', $id)
        ->with(['getSalesCompDt' => function ($query) {
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
        ->with(['getComp' => function ($q) {
            $q->with('getCity');
        }])
        ->with('getAgent')
        ->first();
        // set nota
        $nota = $data_item->sc_nota;
        // get stock item
        foreach ($data_item->getSalesCompDt as $key => $val)
        {
            $item = $val->scd_item;
            // get item stock
            $mainStock = d_stock::where('s_comp', $val->scd_comp)
            ->where('s_position', $data_item->sc_comp)
            ->where('s_item', $item)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->with('getItem')
            ->first();
            // dd($mainStock);
            // add stock id to data
            $val->stockId = $mainStock->s_id;

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
            // add stockunit to data
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

        // $ids = Crypt::encrypt($id);
        $ids = $id;

        return view('marketing/marketingarea/datakonsinyasi/edit', compact('data_item', 'ids'));
    }
    // update
    public function updateDK(Request $request, $id)
    {
        // dd($request->all());
        $data   = $request->all();
        $comp   = $data['branchCode']; // pelaku konsinyasi
        $member = $data['agentCode']; // penerima item
        $compItem = $data['idStock']; // pemilik item
        $user   = Auth::user()->u_id;
        $total  = $data['tot_hrg'];
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota   = $data['nota'];

        DB::beginTransaction();
        try{
            // get item owner
            foreach ($compItem as $key => $val) {
                $owner = d_stock::where('s_id', $val)->first();
                    $compItem[$key] = $owner->s_comp;
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength // list production-code length each item
            );
            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }
            // get konsinyasi by id
            $konsinyasi = d_salescomp::where('sc_id', $id)
            ->with('getSalesCompDt.getProdCode')
            ->first();
            // rollBack konsinyasi-detail
            foreach ($konsinyasi->getSalesCompDt as $key => $konsDt) {
                // set index item by array_search
                if (in_array($konsDt->scd_item, $data['idItem'])) {
                    $localIdx = array_search($konsDt->scd_item, $data['idItem']);
                }
                else {
                    $localIdx = 0;
                }
                // check used item is-modified
                if (in_array($konsDt->scd_item, $data['idItem']) && $data['status'][$localIdx] == 'used') {
                    // get salescompdt from db
                    $recordFromDb = [
                        'scd_item' => $konsDt->scd_item,
                        'scd_qty' => $konsDt->scd_qty,
                        'scd_unit' => $konsDt->scd_unit,
                        'scd_value' => (int)$konsDt->scd_value,
                        'scd_totalnet' => (int)$konsDt->scd_totalnet
                    ];
                    // set salescompdt from input/request
                    $newRecord = [
                        'scd_item' => (int)$data['idItem'][$localIdx],
                        'scd_qty' => (int)$data['jumlah'][$localIdx],
                        'scd_unit' => (int)$data['satuan'][$localIdx],
                        'scd_value' => (int)Currency::removeRupiah($data['harga'][$localIdx]),
                        'scd_totalnet' => (int)Currency::removeRupiah($data['subtotal'][$localIdx])
                    ];
                    // compare the result, return failed if different
                    if (sizeof(array_diff($recordFromDb, $newRecord)) != 0) {
                        DB::rollBack();
                        return Response::json([
                            'status' => "Failed",
                            'message'=> $data['barang'][$localIdx]. " sudah digunakan, tidak dapat dilakukan modifikasi data !"
                        ]);
                    }
                    else {
                        // delete production-code of selected stockdistribution
                        foreach ($konsDt->getProdCode as $idx => $prodCode) {
                            $prodCode->delete();
                        }
                        // skip item (not rollBack)
                        continue;
                    }
                }
                // rollBack mutation
                $rollbackKons = Mutasi::rollback(
                    $konsinyasi->sc_nota, // nota
                    $konsDt->scd_item, // itemId
                    12 // mutcat
                );
                if (!is_bool($rollbackKons)) {
                    DB::rollBack();
                    return $rollbackKons;
                }
                // delete production-code of selected stockdistribution
                foreach ($konsDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                // delete konsinyasi-detail
                $konsDt->delete();
            }

            // update salescomp
            $val_sales = [
                'sc_comp'    => $comp,
                'sc_member'  => $member,
                'sc_total'   => $total,
                'sc_user'    => $user,
                'sc_update'  => $update
            ];
            // Update konsinyasi
            $updateSalesComp = DB::table('d_salescomp')
            ->where('sc_id', '=', $id)
            ->update($val_sales);

            // re-insert konsinyasi-detail
            $sddetail = (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) + 1 : 1;
            $detailsd = $sddetail;
            $val_salesdt = [];
            // values for insert to salescomp-code
            $startProdCodeIdx = 0;

            foreach ($data['idItem'] as $key => $itemId) {
                if ($data['status'][$key] === 'used') {
                    // get konsinyasi-detail
                    $salescompdt = d_salescompdt::where('scd_sales', $id)
                    ->where('scd_item', $itemId)
                    ->first();

                    // update salescompdt
                    $salescompdt->scd_qty = $data['jumlah'][$key];
                    $salescompdt->scd_unit = $data['satuan'][$key];
                    $salescompdt->scd_value = Currency::removeRupiah($data['harga'][$key]);
                    $salescompdt->scd_totalnet = Currency::removeRupiah($data['subtotal'][$key]);
                    $salescompdt->save();

                    // insert new production-code
                    $prodCodeLength = (int)$request->prodCodeLength[$key];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        }
                        $detailidcode = d_salescompcode::where('ssc_salescomp', $id)
                        ->where('ssc_item', $data['idItem'][$key])
                        ->max('ssc_detailid') + 1;

                        $val_salescode = [
                            'ssc_salescomp' => $id,
                            'ssc_item' => $data['idItem'][$key],
                            'ssc_detailid' => $detailidcode,
                            'ssc_code' => strtoupper($request->prodCode[$j]),
                            'ssc_qty' => $request->qtyProdCode[$j]
                        ];
                        DB::table('d_salescompcode')->insert($val_salescode);
                    }
                    // increments production-code index
                    $startProdCodeIdx += $prodCodeLength;
                    continue;
                }

                // set new value for re-insert konsinyasi-detail /salescompdt
                $val_salesdt[] = [
                    'scd_sales' => $id,
                    'scd_detailid' => $detailsd,
                    'scd_comp' => $compItem[$key],
                    'scd_item' => $data['idItem'][$key],
                    'scd_qty' => $data['jumlah'][$key],
                    'scd_unit' => $data['satuan'][$key],
                    'scd_value' => Currency::removeRupiah($data['harga'][$key]),
                    'scd_discpersen' => 0,
                    'scd_discvalue' => 0,
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$key])
                ];

                $prodCodeLength = (int)$request->prodCodeLength[$key];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    $detailidcode = d_salescompcode::where('ssc_salescomp', $id)
                    ->where('ssc_item', $data['idItem'][$key])
                    ->max('ssc_detailid') + 1;

                    $val_salescode = [
                        'ssc_salescomp' => $id,
                        'ssc_item' => $data['idItem'][$key],
                        'ssc_detailid' => $detailidcode,
                        'ssc_code' => strtoupper($request->prodCode[$j]),
                        'ssc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescompcode')->insert($val_salescode);
                }

                // mutasi
                $data_check = DB::table('m_item')
                ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
                ->where('i_id', '=', $data['idItem'][$key])
                ->first();
                // get qty with smallest unit
                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$key] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$key];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]);
                } else if ($data['satuan'][$key] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$key] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]) / $data_check->compare2;
                } else if ($data['satuan'][$key] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$key] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]) / $data_check->compare3;
                }

                // get item stock
                $stock = DB::table('d_stock')
                ->where('s_id', '=', $data['idStock'][$key])
                ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                ->where('sm_stock', '=', $stock->s_id)
                ->first();

                $posisi = DB::table('m_company')
                ->where('c_id', '=', $member)
                ->first();

                // declaare list of production-code
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                $statusKons = 'cabang';
                // set mutation (mutation-out is called inside mutation-in)
                $mutKons = Mutasi::mutasimasuk(
                    12, // mutcat
                    $compItem[$key], // comp / item-owner
                    $member, // position / destination
                    $data['idItem'][$key], // item-id
                    $qty_compare, // qty item with smallest unit
                    'ON DESTINATION', // status
                    'FINE', // condition
                    $stock_mutasi->sm_hpp, // hpp
                    $sellPrice, // sell value
                    $nota, // nota
                    $stock_mutasi->sm_nota, // nota refference
                    $listPC, // list production-code
                    $listQtyPC, // list qty roduction code
                    $statusKons, // status konsinyasi ('pusat' / 'branch')
                    $stock->s_comp // item owner
                );
                if (!is_bool($mutKons)) {
                    return $mutKons;
                }

                // increments production-code index
                $startProdCodeIdx += $prodCodeLength;
                // increments detailid
                $detailsd++;
            }

            // re-insert data in konsinyasi-detail
            DB::table('d_salescompdt')->insert($val_salesdt);

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil diperbarui"
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }
    // delete
    public function deleteDK(Request $request)
    {
        // if (!AksesUser::checkAkses(21, 'delete')){
        //     abort(401);
        // }

        $id = $request->id;

        DB::beginTransaction();
        try{
            $konsinyasi = d_salescomp::where('sc_id', $id)
            ->with('getSalesCompDt.getProdCode')
            ->first();

            foreach ($konsinyasi->getSalesCompDt as $key => $konsDt) {
                $rollbackKons = Mutasi::rollback(
                    $konsinyasi->sc_nota, // nota
                    $konsDt->scd_item, // itemId
                    12 // mutcat
                );
                if (!is_bool($rollbackKons)) {
                    DB::rollBack();
                    return $rollbackKons;
                }
                // delete production-code of selected stockdistribution
                foreach ($konsDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                // delete konsinyasi-detail
                $konsDt->delete();
            }
            // delete konsinyasi
            $konsinyasi->delete();

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> 'Data berhasil dihapus'
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }

    // Start: orderprodukagent =================================================
    public function create_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/create');
    }

    public function edit_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/edit');
    }

}
