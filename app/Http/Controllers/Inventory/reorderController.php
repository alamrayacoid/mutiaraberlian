<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Yajra\DataTables\DataTables;
use DB;

class reorderController extends Controller
{
    public function index()
    {
        return view('inventory.reorderpoin.index');
    }

    public function getDataReorderPoin(Request $request)
    {
        $user = Auth::user();
        $data = DB::table('d_stock')
            ->join('m_item', 'i_id', '=', 's_item')
            ->join('m_unit', 'u_id', '=', 'i_unit1')
            ->select('i_name', 's_qty', 'u_name', 's_reorderpoin', 's_id', 'i_code', 's_qtymin', 's_qtymax', 's_qtysafetyend',
                's_qtysafetystart', DB::raw('concat(i_code, " - ", i_name) as nama'))
            ->where('s_comp', '=', $user->u_company)
            ->where('s_position', '=', $user->u_company)
            ->where('i_isactive', '=', 'Y')
            ->where('s_condition', '=', 'FINE');

        if (isset($request->id_item)){
            $data = $data->where('i_id', '=', $request->id_item);
        }

        $data = $data->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('s_reorderpoin', function ($data){
                if ($data->s_reorderpoin === null){
                    return "<center><button class='btn btn-sm btn-primary' onclick='setReorderPoin(\"" .$data->s_id. "\")'>Set Data</button></center>";
                } else {
                    return $data->s_reorderpoin . " " . $data->u_name;
                }
            })
            ->addColumn('status', function ($data){
                if ($data->s_qty >= $data->s_qtymax && $data->s_qtymax !== null){
                    return 'Jangan Order';
                } elseif ($data->s_qty <= $data->s_qtysafetyend && $data->s_qty >= $data->s_qtysafetystart && $data->s_qtysafetyend !== null && $data->s_qtysafetystart !== null){
                    return 'Stok Aman, Boleh order';
                } elseif ($data->s_qty < $data->s_qtysafetystart && $data->s_qtysafetystart !== null && $data->s_qty > $data->s_qtymin && $data->s_qtymin !== null && $data->s_qty >= $data->s_reorderpoin){
                    return 'Rekomendasi untuk order';
                } elseif ($data->s_qty < $data->s_qtysafetystart && $data->s_qtysafetystart !== null && $data->s_qty > $data->s_qtymin && $data->s_qtymin !== null && $data->s_qty < $data->s_reorderpoin){
                    return 'Harus Order';
                } elseif ($data->s_qty <= $data->s_qtymin && $data->s_qtymin !== null){
                    return 'Stock Tipis, Harus Order!!!';
                } elseif ($data->s_qty <= $data->s_reorderpoin && $data->s_reorderpoin !== null){
                    return 'Harus Order';
                } elseif ($data->s_qty > $data->s_reorderpoin && $data->s_reorderpoin !== null){
                  return 'Boleh Order';
                } elseif ($data->s_reorderpoin === null){
                    return 'Data belum dimasukkan';
                } else {
                    return '-';
                }
            })
            ->editColumn('s_qty', function ($data){
                return $data->s_qty . " " . $data->u_name;
            })
            ->addColumn('aksi', function ($data){
                return "<center><button class='btn btn-sm btn-warning' onclick='editReorderPoin(\"" .$data->s_id. "\", \"" .$data->s_reorderpoin. "\")'>Edit Data</button></center>";
            })
            ->rawColumns(['status', 's_reorderpoin', 'aksi'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $idStock = $request->idStock;
        $qty = $request->qty;

        DB::beginTransaction();
        try {
            DB::table('d_stock')
                ->where('s_id', '=', $idStock)
                ->update([
                    's_reorderpoin' => $qty
                ]);

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        $idStock = $request->idStock;
        $qty = $request->qty;

        DB::beginTransaction();
        try {
            DB::table('d_stock')
                ->where('s_id', '=', $idStock)
                ->update([
                    's_reorderpoin' => $qty
                ]);

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}
