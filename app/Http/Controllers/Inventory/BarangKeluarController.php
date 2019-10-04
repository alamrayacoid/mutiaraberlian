<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_stock;
use App\d_stockdt;
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

    // get list production-code
    public function getProductionCode(Request $request)
    {
        $term = $request->term;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $pemilik = $request->pemilik;
        $posisi = $request->posisi;
        $produk = $request->produk;
        $mutcat = $request->mutcat;

        $datas = d_stockmutationdt::whereHas('getStockMutation', function ($q) use ($mutcat) {
                $q->whereHas('getMutcat', function ($query) use ($mutcat) {
                    $query->where('m_status', 'K');
                });
            })
            ->where('smd_productioncode', 'LIKE', '%'. $term .'%');

        if ($from != null || $from != ''){
            $datas = $datas->whereHas('getStockMutation', function ($qFrom) use ($from) {
                    $qFrom->where('sm_date', '>=', $from);
                });
        }
        if ($to != null || $to != ''){
            $datas = $datas->whereHas('getStockMutation', function ($qTo) use ($to) {
                    $qTo->where('sm_date', '<=', $to);
                });
        }
        if ($pemilik != 'semua'){
            $datas = $datas->whereHas('getStock', function ($qStock) use ($pemilik) {
                    $qStock->where('s_comp', $pemilik);
                });
        }
        if ($posisi != 'semua'){
            $datas = $datas->whereHas('getStock', function ($qOwner) use ($posisi) {
                    $qOwner->where('s_position', $posisi);
                });
        }
        if ($produk != 'semua'){
            $datas = $datas->whereHas('getStock', function ($qProduct) use ($produk) {
                    $qProduct->where('s_item', $produk);
                });
        }
        if ($mutcat != 'semua'){
            $datas = $datas->whereHas('getStockMutation', function ($qMutcat) use ($mutcat) {
                    $qMutcat->where('sm_mutcat', $mutcat);
                });
        }
        $datas = $datas->groupBy('smd_productioncode')->get();
        // dd($request->all(), $datas);

        if (count($datas) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan kode produksi terkait'];
        } else {
            foreach ($datas as $query) {
                $results[] = [
                    'id' => $query->smd_productioncode,
                    'label' => $query->smd_productioncode,
                ];
            }
        }
        return response()->json($results);
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
        $kodeproduksi = $request->kodeproduksi;

        $datas = DB::table('d_stock_mutation')
            ->join('d_stock', 'sm_stock', 's_id')
            ->join('m_company as pemilik', 'd_stock.s_comp', 'pemilik.c_id')
            ->join('m_company as posisi', 'd_stock.s_position', 'posisi.c_id')
            ->join('m_mutcat', 'sm_mutcat', '=', 'm_id')
            ->join('m_item', 'i_id', '=', 's_item')
            ->join('m_unit', 'u_id', '=', 'i_unit1')
            ->select('sm_stock','sm_detailid',DB::raw('date_format(sm_date, "%d/%m/%Y") as sm_date'), DB::raw('concat(sm_qty, " ", u_name) as sm_qty'),'sm_qty as qty', 'pemilik.c_name as pemilik', 'posisi.c_name as posisi', 's_condition', 'm_name', 'i_name')
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
        if ($kodeproduksi != ''){
            $datas->join('d_stockdt', 'd_stock.s_id', 'sd_stock')
                ->where('sd_code', '=', $kodeproduksi);
        }
        if ($mutcat != 'semua'){
            $datas->where('sm_mutcat', '=', $mutcat);
        }

        $datas = $datas->get();
        foreach ($datas as $key => $value) {
          $value->action  = '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                  <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.$value->sm_stock.'\',\''.$value->sm_detailid.'\')"><i class="fa fa-folder"></i>
                  </button>
              </div>';
        }
        $qty = $this->getQty($datas);

        return response()->json([
              'datatable' => [
                  'msg' => '201',
                  'data' => $datas,
                  'qty' => $qty,
              ]
          ], '201')->header('Content-Type', 'application/x-www-form-urlencoded');

    }

    /**
     * Return detail of an 'item-out'.
     *
     * @return \Illuminate\Http\Response
     */

     public function getQty($datas)
     {
       $arrays=array();
       foreach ($datas as $key => $value) {
         array_push($arrays,$value->qty);
       }
       return array_sum($arrays);
     }

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

        $kodeproduksi = d_stockdt::select('sd_code')
            ->groupBy('sd_code')
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

        return view('inventory/barangkeluar/index', compact('pemilik', 'posisi', 'produk', 'mutcat', 'kodeproduksi'));
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

}
