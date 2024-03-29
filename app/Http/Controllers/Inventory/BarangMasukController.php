<?php

namespace App\Http\Controllers\Inventory;
use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use App\d_stockdt;
use App\d_stockmutationdt;
use Auth;
use Response;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;

class BarangMasukController extends Controller
{
    public function index()
    {
        if (!AksesUser::checkAkses(14, 'read')){
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

        // $kodeproduksi = d_stockdt::select('sd_code')
        //     ->groupBy('sd_code')
        //     ->get();

        $mutcat = DB::table('d_stock')
            ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
            ->join('m_mutcat', 'm_id', '=', 'sm_mutcat')
            ->select('m_name', 'm_id')
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('m_status', '=', 'M')
            ->whereMonth('sm_date', Carbon::now('Asia/Jakarta'))
            ->groupBy('m_id')
            ->get();

        return view('inventory/barangmasuk/index', compact('produk','posisi', 'pemilik', 'mutcat'));
    }
    // get list production-code
    public function getProductionCode(Request $request)
    {
        // dd($request->all());
        $term = $request->term;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $pemilik = $request->pemilik;
        $posisi = $request->posisi;
        $produk = $request->produk;
        $mutcat = $request->mutcat;

        $datas = d_stockmutationdt::whereHas('getStockMutation', function ($q) use ($mutcat) {
                $q->whereHas('getMutcat', function ($query) use ($mutcat) {
                    $query->where('m_status', 'M');
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

    public function getData(Request $request)
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
            ->select('sm_stock','sm_detailid',DB::raw('date_format(sm_date, "%d/%m/%Y") as sm_date'),'sm_qty as qty', DB::raw('concat(sm_qty, " ", u_name) as sm_qty'), 'pemilik.c_name as pemilik', 'posisi.c_name as posisi', 's_condition', 'm_name', 'i_name')
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('m_status', '=', 'M');

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
            $datas->leftjoin('d_stockdt', 'd_stock.s_id', 'sd_stock')
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

    public function getQty($datas)
    {
      $arrays=array();
      foreach ($datas as $key => $value) {
        array_push($arrays,$value->qty);
      }
      return array_sum($arrays);
    }

    public function create()
    {
        $company = DB::table('m_company')
            ->where('c_type', 'CABANG')
            ->orWhere('c_type', 'PUSAT')
            ->select('c_id', 'c_name')
            ->get();
        $unit    = DB::table('m_item')
            ->join('m_unit as unit1', 'unit1.u_id', 'i_unit1')
            ->join('m_unit as unit2', 'unit2.u_id', 'i_unit2')
            ->join('m_unit as unit3', 'unit3.u_id', 'i_unit3')
            ->select('m_item.*','unit1.u_id as id1', 'unit1.u_name as name1', 'unit2.u_id as id2', 'unit2.u_name as name2', 'unit3.u_id as id3', 'unit3.u_name as name3')
            ->get();
        $mutcat  = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Masuk%')->get();

        return view('inventory/barangmasuk/create')->with(compact('unit', 'company', 'mutcat'));
    }

    public function getUnit(Request $request)
    {
        $idUnit = $request->id;
        $getUnit = DB::table('m_item')
            ->join('m_unit as unit1', 'unit1.u_id', 'i_unit1')
            ->join('m_unit as unit2', 'unit2.u_id', 'i_unit2')
            ->join('m_unit as unit3', 'unit3.u_id', 'i_unit3')
            ->select('m_item.*','unit1.u_id as id1', 'unit1.u_name as name1', 'unit2.u_id as id2', 'unit2.u_name as name2', 'unit3.u_id as id3', 'unit3.u_name as name3')
            ->where('i_id', '=', $idUnit)
            ->first();
        return Response::json(array(
            'success' => true,
            'data'    => $getUnit
        ));
    }

    public function edit()
    {
        return view('inventory/barangmasuk/edit');
    }

    public function auto_item(Request $request)
    {
        $cari = $request->term;
        $item = DB::table('m_item')
            ->select('i_id', 'i_name', 'i_code', 'i_unit1', 'i_unit2', 'i_unit3')
            ->whereRaw("i_name like '%" . $cari . "%'")
            ->orWhereRaw("i_code like '%" . $cari . "%'")
            ->get();

        if ($item == null) {
            $hasilItem[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($item as $query) {
                if($query->i_code == null){
                    $hasilItem[] = [
                        'id'    => $query->i_id,
                        'label' => $query->i_name
                    ];
                }else{
                    $hasilItem[] = [
                        'id'    => $query->i_id,
                        'label' => $query->i_code.' - '.$query->i_name,
                        'unit1' => $query->i_unit1,
                        'unit2' => $query->i_unit2,
                        'unit3' => $query->i_unit3
                    ];
                }
            }
        }
        return Response::json($hasilItem);
    }

    public function store(Request $request)
    {
        $user        = Auth::user()->u_id;
        $s_item      = $request->idItem;
        $m_mutcat    = $request->m_mutcat;
        $m_unit      = $request->m_unit;
        $s_position  = $request->s_position;
        $s_comp      = $request->s_comp;
        $sm_hpp      = $request->sm_hpp;
        $s_condition = $request->s_condition;
        $s_qty       = $request->s_qty;
        $timeNow     = Carbon::now('Asia/Jakarta');

        $countStock = DB::table('d_stock')->count();
        $query1 = DB::table('d_stock')
            ->where('s_comp', '=', $s_comp)
            ->where('s_position', '=', $s_position)
            ->where('s_item', '=', $s_item)
            ->where('s_condition', '=', $s_condition)
            ->first();

        $getId = 1;
        if ($countStock > 0) {
            $getIdMax = DB::table('d_stock')->max('s_id');
            $getId    = $getIdMax + 1;
        }

        $countEntry = DB::table('d_itementry')->count();
        $entryId = 1;
        if ($countEntry > 0) {
            $getIdMax = DB::table('d_itementry')->max('ie_id');
            $entryId  = $getIdMax + 1;
        }

        DB::beginTransaction();
        try {
            if ($query1) {
                $detail = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $query1->s_id)
                    ->max('sm_detailid');
                $qtyAkhir = $query1->s_qty + $s_qty;
                DB::table('d_stock')->where('s_id', $query1->s_id)->update([
                    's_qty' => $qtyAkhir
                ]);

                DB::table('d_stock_mutation')->insert([
                    'sm_stock'    => $query1->s_id,
                    'sm_detailid' => ++$detail,
                    'sm_mutcat'   => $m_mutcat,
                    'sm_qty'      => $s_qty,
                    'sm_hpp'      => str_replace('.', '', $sm_hpp),
                    'sm_nota'     => CodeGenerator::codeWithSeparator('d_stock_mutation', 'sm_nota', 8, 10, 3, 'IN', '-')
                ]);

                DB::table('d_itementry')->insert([
                    'ie_id'     => $entryId,
                    'ie_date'   => date('Y-m-d', strtotime($timeNow)),
                    'ie_nota'   => CodeGenerator::codeWithSeparator('d_itementry', 'ie_nota', 8, 10, 3, 'IN', '-'),
                    'ie_item'   => $s_item,
                    'ie_qty'    => $s_qty,
                    'ie_unit'   => $m_unit,
                    'ie_mutcat' => $m_mutcat,
                    'ie_hpp'    => str_replace('.', '', $sm_hpp),
                    'ie_user'   => $user
                ]);
            } else {
                $dtId = 0;
                DB::table('d_stock')->insert([
                    's_id'         => $getId,
                    's_comp'       => $s_comp,
                    's_position'   => $s_position,
                    's_item'       => $s_item,
                    's_qty'        => $s_qty,
                    's_condition'  => $s_condition
                ]);

                DB::table('d_stock_mutation')->insert([
                    'sm_stock'    => $getId,
                    'sm_detailid' => $dtId+1,
                    'sm_mutcat'   => $m_mutcat,
                    'sm_qty'      => $s_qty,
                    'sm_hpp'      => str_replace('.', '', $sm_hpp),
                    'sm_nota'     => CodeGenerator::codeWithSeparator('d_stock_mutation', 'sm_nota', 8, 10, 3, 'IN', '-')
                ]);

                DB::table('d_itementry')->insert([
                    'ie_id'     => $entryId,
                    'ie_date'   => date('Y-m-d', strtotime($timeNow)),
                    'ie_nota'   => CodeGenerator::codeWithSeparator('d_itementry', 'ie_nota', 8, 10, 3, 'IN', '-'),
                    'ie_item'   => $s_item,
                    'ie_qty'    => $s_qty,
                    'ie_unit'   => $m_unit,
                    'ie_mutcat' => $m_mutcat,
                    'ie_hpp'    => str_replace('.', '', $sm_hpp),
                    'ie_user'   => $user
                ]);
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

    public function getDetail(Request $request)
    {
        $id = $request->stock;
        $dt = $request->detail;


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
}
