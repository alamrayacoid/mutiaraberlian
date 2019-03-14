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

	public function createTargetReal()
	{
		$company = DB::table('m_company')->select('m_company.*')->get();
		return view('marketing.penjualanpusat.targetrealisasi.create', compact('company'));
	}

	public function getComp()
	{
		$company = DB::table('m_company')->select('c_id', 'c_name')->get();
        return Response::json(array(
			'success' => true,
			'data'    => $company
        )); 
	}

	public function cariBarang(Request $request)
    {
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }
        $cari = $request->term;
	    $nama = DB::table('m_item')
	        ->select('m_item.*')
            ->whereRaw("i_name like '%" . $cari . "%'")
            ->orWhereRaw("i_code like '%" . $cari . "%'")
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }
    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function targetRealStore(Request $request)
    {
    	
		$data          = $request->all();
		$salesTarget   = [];
		$salesTargetDt = [];


        $countST = DB::table('d_salestarget')->count();
		$stId = 1;
        if ($countST > 0) {
            $getIdMax = DB::table('d_salestarget')->max('st_id');
            $stId    = $getIdMax + 1;
        }
        $periode = Carbon::createFromFormat('d/m/Y', $data['t_periode']);
        DB::beginTransaction();
        try{
			
			$stDetail = 0;
			for ($i=0; $i < count($data['idItem']); $i++) {
				$query1 = DB::table('d_salestarget')
				    ->where('st_comp', '=', $data['t_comp'][0])
				    ->whereMonth('st_periode', '=', $periode->month)
				    ->first();
				    
				if ($query1 != null) {
		            $detail = DB::table('d_salestargetdt')
		                    ->where('std_salestarget', '=', $query1->st_id)
		                    ->max('std_detailid');

					$stDetail = $detail + 1;
					DB::table('d_salestargetdt')->insert([
						'std_salestarget' => $query1->st_id,
						'std_detailid'    => $stDetail,
						'std_item'        => $data['idItem'][$i],
						'std_qty'         => $data['t_qty'][$i],
						'std_unit'        => $data['t_unit'][$i]
					]);
				} else {

					DB::table('d_salestarget')->insert([
						'st_id'      => $stId,
						'st_comp'    => $data['t_comp'][0],
						'st_periode' => Carbon::createFromFormat('d/m/Y', $data['t_periode'])->format('Y-m-d')
					]);
					DB::table('d_salestargetdt')->insert([
						'std_salestarget' => $stId,
						'std_detailid'    => $stDetail+1,
						'std_item'        => $data['idItem'][$i],
						'std_qty'         => $data['t_qty'][$i],
						'std_unit'        => $data['t_unit'][$i]
					]);
				}
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