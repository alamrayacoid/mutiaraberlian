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

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('inventory/barangmasuk/index');
    }

    public function getData()
    {
        $datas = DB::table('d_stock')
            ->join('m_item', 'i_id', 's_item')
            ->select('d_stock.*', 'i_code')
            ->get();
        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
            return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                    <button class="btn btn-warning hint--bottom-left hint--warning" onclick="EditCabang(\''.Crypt::encrypt($datas->s_id).'\')" data-toggle="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-disabled disabled" onclick="nonActive(\''.Crypt::encrypt($datas->s_id).'\')" data-toggle="tooltip" data-placement="top" disabled><i class="fa fa-times"></i></button>
                    </div>
                  </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function create()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        $unit    = DB::table('m_unit')->get();
        $mutcat  = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Masuk%')->get();

        return view('inventory/barangmasuk/create')->with(compact('unit', 'company', 'mutcat'));
    }

    public function edit()
    {
        return view('inventory/barangmasuk/edit');
    }

    public function auto_item(Request $request)
    {
        $cari = $request->term;
        $item = DB::table('m_item')
            ->select('i_id', 'i_name', 'i_code')
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
                        'label' => $query->i_code.' - '.$query->i_name
                    ];
                }
            }
        }
        return Response::json($hasilItem);
    }

    public function store(Request $request)
    {
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

        DB::beginTransaction();
        try {
            if ($query1) {
                $qtyAkhir = $query1->s_qty + $s_qty;
                DB::table('d_stock')->where('s_id', $query1->s_id)->update([
                    's_qty' => $qtyAkhir
                ]);
                DB::commit();
                return response()->json([
                  'status' => 'berhasil'
                ]);
            } else {
                DB::table('d_stock')->insert([
                  's_id'         => $getId,
                  's_comp'       => $s_comp,
                  's_position'   => $s_position,
                  's_item'       => $s_item,
                  's_qty'        => $s_qty,
                  's_condition'  => $s_condition,
                  's_created_at' => $timeNow,
                  's_updated_at' => $timeNow
                ]);
                DB::commit();
                return response()->json([
                  'status' => 'sukses'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'status'  => 'Gagal',
              'message' => $e
            ]);
        }
    }
}
