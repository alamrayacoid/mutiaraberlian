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
            ->join('m_company', 'c_id', 's_comp')
            ->select('d_stock.*', DB::raw('date_format(s_created_at, "%d/%m/%Y") as tgl_masuk'), 'i_code', 'c_name')
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
        $noteTime    = Carbon::now('Asia/Jakarta')->format('d/m/Y');

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

        $cekNota = DB::table('d_stock_mutation')
            ->whereRaw('sm_nota like "%' . $noteTime . '%"')
            ->select(DB::raw('CAST(MID(sm_nota, 4, 3) AS UNSIGNED) as sm_nota'))
            ->first();

        $temp = 1;
        if ($cekNota) {
            $temp = ($cekNota->sm_nota+1);
        }

        $count = ++$temp;
        $kode = sprintf("%03s", $count);
        $nota = 'IN-' . $kode . '/' . $noteTime;

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
                    'sm_hpp'      => $sm_hpp,
                    'sm_nota'     => $nota
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
                    'sm_hpp'      => $sm_hpp,
                    'sm_nota'     => $nota
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
}
