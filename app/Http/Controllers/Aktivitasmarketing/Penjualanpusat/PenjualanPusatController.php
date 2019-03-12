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

	// Target Realisasi
	public function getTargetView()
	{
		$target = DB::table('d_salestargetdt')
			->join('d_salestarget', 'std_salestarget', 'st_id')
			->join('m_item', 'std_item', 'i_id')
			->join('m_unit', 'std_unit', 'u_id')
			->join('m_company', 'st_comp', 'c_id')
			->select('')->get();
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
                        'label' => $query->i_code.' - '.$query->i_name
                    ];
                }
            }
        }
        return Response::json($hasilItem);
    }
	public function createTargetReal()
	{
		return view('marketing.penjualanpusat.targetrealisasi.create');
	}
}