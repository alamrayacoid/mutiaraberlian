<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Auth;
use Response;
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

        $mutcat = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Masuk%')->get();

        
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
}
