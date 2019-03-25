<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use Illuminate\Http\Request;
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

class MarketingAreaController extends Controller
{
    public function index()
    {
        return view('marketing/marketingarea/index');
    }

    // Order Produk Ke Cabang ==============================================================================
    public function orderList()
    {
        $order = DB::table('d_productorderdt')
            ->join('d_productorder', 'pod_productorder', 'po_id')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorderdt.*', 'd_productorder.*', DB::raw('date_format(po_date, "%m/%Y") as po_date'), 'i_name', 'u_name')
            ->get();
        return Datatables::of($order)
            ->addIndexColumn()
            ->addColumn('price', function ($order) {
                return Currency::addRupiah($order->pod_totalprice);
            })
            ->addColumn('action', function ($order) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                            <button class="btn btn-info hint--top-left hint--info" aria-label="Detail Order" onclick="detailOrder(\'' . Crypt::encrypt($order->pod_productorder) . '\', \'' . Crypt::encrypt($order->pod_detailid) . '\')"><i class="fa fa-folder"></i>
                            </button>
                            <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Order" onclick="editOrder(\'' . Crypt::encrypt($order->pod_productorder) . '\', \'' . Crypt::encrypt($order->pod_detailid) . '\', \'' . Crypt::encrypt($order->pod_item) . '\')"><i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger hint--top-left hint--error" aria-label="Hapus Order" onclick="deleteOrder(\'' . Crypt::encrypt($order->pod_productorder) . '\', \'' . Crypt::encrypt($order->pod_detailid) . '\')"><i class="fa fa-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['price','action'])
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

        $price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pc_name', '=', "Agen")
            ->where('pcd_item', '=', $idItem)
            ->where('pcd_unit', '=', $idUnit)
            ->where('pcd_type', '=', "R")
            ->first();
        if($price){
            if ($price->pcd_rangeqtystart <= $qty[0] && $price->pcd_rangeqtyend >= $qty[0]) {
                return Response::json(array(
                    'success' => true,
                    'data'    => number_format($price->pcd_price,0, ',', '')
                ));
            } else {
                return Response::json(array(
                    'success' => true,
                    'data'    => "Rp. 0"
                ));
            }
        } else {
            return Response::json(array(
                'success' => true,
                'data'    => "Rp. 0"
            ));
        }
    }

    public function orderProdukStore(Request $request)
    {
        //dd($request);
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
                        'po_nota'   => CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 8, 10, 4, 'PRO', '-'),
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

    public function editOrderProduk($id, $dt, $item)
    {
        try {
            $id = Crypt::decrypt($id);
            $dt = Crypt::decrypt($dt);
            $item = Crypt::decrypt($item);
        } catch (\Exception $e) {
            return view('errors.404');
        }
        $produk = DB::table('d_productorderdt')
            ->join('m_item', 'pod_item', 'i_id')
            ->join('m_unit', 'pod_unit', 'u_id')
            ->select('d_productorderdt.*', 'm_item.*', 'm_unit.*')
            ->where('pod_productorder', $id)
            ->where('pod_detailid', $dt)
            ->first();
        $item = DB::table('m_item')
            ->select('m_item.*')
            ->where('i_id', '=', $item)
            ->first();
        $unit = DB::table('m_unit')
            ->select('m_unit.*')
            ->where('u_id', '=', $item->i_unit1)
            ->orWhere('u_id', '=', $item->i_unit2)
            ->orWhere('u_id', '=', $item->i_unit3)
            ->get();
        return view('marketing/marketingarea/orderproduk/edit', compact('produk', 'unit'));
    }

    public function updateTarget($st_id, $dt_id, Request $request)
    {
        // try {
        //     $st_id = Crypt::decrypt($st_id);
        //     $dt_id = Crypt::decrypt($dt_id);
        // } catch (\Exception $e) {
        //     return view('errors.404');
        // }

        // $data = $request->all();
        // DB::beginTransaction();
        // try {
        //     DB::commit();
        //     return response()->json([
        //         'status' => 'sukses'
        //     ]);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 'Gagal',
        //         'message' => $e
        //     ]);
        // }
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
    // Order Produk Ke Cabang End ==========================================================================
    public function create_keloladataorder()
    {
        return view('marketing/marketingarea/keloladataorder/create');
    }

    public function edit_keloladataorder()
    {
        return view('marketing/marketingarea/keloladataorder/edit');
    }

    public function create_datacanvassing()
    {
        return view('marketing/marketingarea/datacanvassing/create');
    }

    public function edit_datacanvassing()
    {
        return view('marketing/marketingarea/datacanvassing/edit');
    }

    public function create_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/create');
    }

    public function edit_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/edit');
    }
    
    public function agen()
    {
        return view('marketing/agen/index');
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
