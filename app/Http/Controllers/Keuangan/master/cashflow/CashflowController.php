<?php

namespace App\Http\Controllers\Keuangan\master\cashflow;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Response;
use Crypt;
use App\Http\Controllers\Controller;

class CashflowController extends Controller
{
    //
    public function index()
    {
        $akun = DB::table('dk_akun')
            ->where('ak_comp', '=', 'MB0000001')
            ->get();
        return view('masterdatautama.cashflow.index', compact('akun'));
    }

    public function get_data_cashflow()
    {
        $datas = DB::table('dk_akun_cashflow')->get();

        return DataTables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function($datas) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-warning hint--top" aria-label="Edit" onclick="edit(\''.Crypt::encrypt($datas->ac_id).'\')"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top" aria-label="Hapus"><i class="fa fa-trash" onclick="hapus(\''.Crypt::encrypt($datas->ac_id).'\')"></i></button>
                        </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        DB::beginTransaction();
        try {
            $ac_id = DB::table('dk_akun_cashflow')->max('ac_id') + 1;
            DB::table('dk_akun_cashflow')->insert([
                'ac_id'   => $ac_id,
                'ac_nama' => $request->ac_nama,
                'ac_type' => $request->ac_type
            ]);
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        $data = DB::table('dk_akun_cashflow')->where('ac_id', $id)->first();

        return response()->json([
            'data' => $data,
            'id'   => Crypt::encrypt($data->ac_id)
        ]);
    }

    public function update(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->ac_id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
        
            DB::table('dk_akun_cashflow')->where('ac_id', $id)->update([
               'ac_nama' => $request->ac_nama,
               'ac_type' => $request->ac_type 
            ]);            
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
        
    }

    public function delete($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
        
            DB::table('dk_akun_cashflow')->where('ac_id', $id)->delete();
        
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data'   => ''
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

    public function enable(Request $request)
    {
        
    }

    public function disable(Request $request)
    {
        
    }

}
