<?php

namespace App\Http\Controllers\Inventory;
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

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('inventory/barangmasuk/index');
    }

    public function getData()
    {
        $datas = DB::table('d_stock_mutation')
            ->join('d_stock', 'sm_stock', 's_id')
            ->join('m_company as pemilik', 'd_stock.s_comp', 'pemilik.c_id')
            ->join('m_company as posisi', 'd_stock.s_position', 'posisi.c_id')
            ->select('sm_stock','sm_detailid',DB::raw('date_format(sm_date, "%d/%m/%Y") as sm_date'), 'sm_qty', 'pemilik.c_name as pemilik', 'posisi.c_name as posisi', 's_condition')
            ->where('sm_mutcat', '=', '1')
            ->orWhere('sm_mutcat', '=', '2')
            ->orWhere('sm_mutcat', '=', '3')
            ->get();
        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
            return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.$datas->sm_stock.'\',\''.$datas->sm_detailid.'\')"><i class="fa fa-folder"></i>
                        </button>
                    </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
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
            'data' => $getUnit
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
                        'id' => $query->i_id,
                        'label' => $query->i_name
                    ];
                }else{
                    $hasilItem[] = [
                        'id' => $query->i_id,
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
            $getId = $getIdMax + 1;
        }

        $countEntry = DB::table('d_itementry')->count();
        $entryId = 1;
        if ($countEntry > 0) {
            $getIdMax = DB::table('d_itementry')->max('ie_id');
            $entryId = $getIdMax + 1;
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
        $st = $request->stock;
        $dt = $request->detail;

        $detail = DB::table('d_stock_mutation')
            ->join('d_stock', 'sm_stock', 's_id')
            ->join('m_item', 's_item', 'i_id')
            ->join('m_company as pemilik', 's_comp', 'pemilik.c_id')
            ->join('m_company as posisi', 's_position', 'posisi.c_id')
            ->select('pemilik.c_name as pemilik', 'posisi.c_name as posisi', 'i_code', 'i_name')
            ->where('sm_stock', '=', $st)
            ->where('sm_detailid', '=', $dt)->first();
        return Response::json(array(
            'success' => true,
            'data' => $detail
        ));

    }
}
