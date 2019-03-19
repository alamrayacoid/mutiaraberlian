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

    // Order Produk Ke Cabang
    public function targetList()
    {
        // $target = DB::table('d_salestargetdt')
        //     ->join('d_salestarget', 'std_salestarget', 'st_id')
        //     ->join('m_item', 'std_item', 'i_id')
        //     ->join('m_unit', 'std_unit', 'u_id')
        //     ->join('m_company', 'st_comp', 'c_id')
        //     ->select('d_salestargetdt.*', 'st_id', 'c_name', DB::raw("concat(i_code, '-', i_name) as i_name"), 'st_periode', DB::raw('date_format(st_periode, "%m/%Y") as st_periode'))
        //     ->get();
        // return Datatables::of($target)
        //     ->addIndexColumn()
        //     ->addColumn('status', function ($target) {
        //         return '<label class="bg-danger status-reject px-3 py-1" disabled>Gagal</label>';
        //     })
        //     ->addColumn('action', function ($target) {
        //         return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
        //                 <button class="btn btn-warning hint--top-left hint--warning" aria-label="Edit Target" onclick="editTarget(\'' . Crypt::encrypt($target->std_salestarget) . '\', \'' . Crypt::encrypt($target->std_detailid) . '\')"><i class="fa fa-pencil"></i>
        //                 </button>
        //             </div>';
        //     })
        //     ->addColumn('realisasi', function (){
        //         return '0';
        //     })
        //     ->rawColumns(['status', 'action'])
        //     ->make(true);
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
        $salesTarget = [];
        $salesTargetDt = [];

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
                    'std_item' => $data['idItem'][0],
                    'std_unit' => $data['t_unit'][0],
                    'std_qty' => $data['t_qty'][0]
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
}
