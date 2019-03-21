<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

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

class MarketingAreaController extends Controller
{
    public function index()
    {
        return view('marketing/marketingarea/index');
    }

    // Order Produk Ke Cabang ==============================================================================
    public function orderList()
    {
        
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
        $unit = DB::table('m_unit')->select('m_unit.*')
            ->where('u_id', '=', $request->idUnit)->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }

            $results2[] = ['unit' => $unit];

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

        $price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pc_name', '=', "Agen")
            ->where('pcd_item', '=', $idItem)
            ->whereIn('pcd_unit', $idUnit)
            ->where('pcd_type', '=', "R")
            ->first();
        if($price){
            if ($price->pcd_rangeqtystart <= $qty && $price->pcd_rangeqtyend >= $qty) {
                return Response::json(array(
                    'success' => true,
                    'data'    => $price->pcd_price
                ));
            } else {
                return Response::json(array(
                    'success' => true,
                    'data'    => "0"
                ));
            }
        } else {
            echo "Failed";
        }

        
    }

    public function orderProdukStore(Request $request)
    {
        //dd($request);
        $data = $request->all();
        $now = Carbon::now('Asia/Jakarta');
        $time = date('Y-m-d', strtotime($now));
        // DB::beginTransaction();
        // try {
            $detailId = 0;
            $query1 = DB::table('d_productorder')
                ->where('po_date', '=', $time)
                ->where('po_comp', '=', $data['po_comp'])
                ->where('po_agen', '=', $data['po_agen'])
                ->first();
            if ($query1) {
                for ($i=0; $i < count($data['idItem']); $i++) {

                    $detailId = DB::table('d_productorderdt')
                        ->where('pod_productorder', '=', $query1->po_id)
                        ->max('pod_detailid');

                    DB::table('d_productorderdt')->insert([
                        'pod_productorder' => $query1->po_id,
                        'pod_detailid' => $detailId+1,
                        'pod_item' => $data['idItem'][$i],
                        'pod_unit' => $data['po_unit'][$i],
                        'pod_qty' => $data['po_qty'][$i],
                        'pod_price' => number_format($data['po_hrg'][$i]),
                        'pod_totalprice' => number_format($data['tot_hrg'][0])
                    ]);
                }
            } else {
                $getIdMax = DB::table('d_productorder')->max('po_id');
                $poId = $getIdMax + 1;
                DB::table('d_productorder')->insert([
                    'po_id' => $poId,
                    'po_comp' => $data['po_comp'][0],
                    'po_agen' => $data['po_agen'][0],
                    'po_date' => $time,
                    'po_nota' => CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 8, 10, 4, 'PRO', '-'),
                    'po_status' => "P"
                ]);

                for ($i=0; $i < count($data['idItem']) ; $i++) { 
                    DB::table('d_productorderdt')->insert([
                        'pod_productorder' => $poId,
                        'pod_detailid' => ++$detailId,
                        'pod_item' => $data['idItem'][$i],
                        'pod_unit' => $data['po_unit'][$i],
                        'pod_qty' => $data['po_qty'][$i],
                        'pod_price' => number_format($data['po_hrg'][$i]),
                        'pod_totalprice' => number_format($data['tot_hrg'][0])
                    ]);
                }
            }
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

    public function editOrderProduk()
    {
        // try {
        //     $st_id = Crypt::decrypt($st_id);
        //     $dt_id = Crypt::decrypt($dt_id);
        // } catch (\Exception $e) {
        //     return view('errors.404');
        // }
        return view('marketing/marketingarea/orderproduk/edit');
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
