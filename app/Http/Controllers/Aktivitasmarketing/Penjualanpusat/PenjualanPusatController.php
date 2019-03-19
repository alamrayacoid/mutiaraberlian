<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

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

class PenjualanPusatController extends Controller
{
    public function index()
    {
        return view('marketing/penjualanpusat/index');
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
            ->addColumn('realisasi', function (){
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
}
