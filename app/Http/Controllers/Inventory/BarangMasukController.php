<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Controllers\Controller;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('inventory/barangmasuk/index');
    }

    public function create()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        $unit = DB::table('m_unit')->get();
        return view('inventory/barangmasuk/create')->with(compact('unit', 'company'));
    }

    public function edit()
    {
        return view('inventory/barangmasuk/edit');
    }

    public function auto_item(Request $request)
    {
        $cari = $request->term;
        $item = DB::table('m_item')
            ->select('i_id', 'i_name')
            ->whereRaw("c_name like '%" . $cari . "%'")
            ->where('c_id', '!=', $company)->get();

        if ($comp == null) {
            $hasilcomp[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($comp as $query) {
                $hasilcomp[] = [
                    'id' => $query->c_id,
                    'label' => $query->c_name
                ];
            }
        }
        return Response::json($hasilcomp);
    }
}
