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

    public function cariAgen(Request $request)
    {
        $cari = $request->term;
        $agen = DB::table('m_company')
            ->where('c_type', '=', 'AGEN')
            ->select('c_id', 'c_name', 'c_type')
            ->whereRaw("c_name like '%" . $cari . "%'")
            ->orWhereRaw("c_id like '%" . $cari . "%'")
            ->get();

        if (count($agen) == 0) {
            $result[] = [
                'id'    => null,
                'label' => 'Tidak ditemukan data terkait'
            ];
        } else {
            foreach ($agen as $query) {
                $result[] = [
                    'id'    => $query->c_id,
                    'label' => $query->c_name
                ];
            }
        }
        return Response::json($result);
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
