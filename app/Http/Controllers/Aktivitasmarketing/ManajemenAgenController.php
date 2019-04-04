<?php

namespace App\Http\Controllers\Aktivitasmarketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


use DB;
use Auth;
use Response;
use Currency;
use Validator;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;

class ManajemenAgenController extends Controller
{
  public function index()
  {
  	$provinsi = DB::table('m_wil_provinsi')
  		->select('m_wil_provinsi.*')
  		->get();

    return view('marketing/agen/index', compact('provinsi'));
  }
	// Kelola Data Inventory Agen ----------------
  public function getAgen($city)
  {
  	$agen = DB::table('m_agen')
  		->join('m_company', 'a_code', 'c_user')
  		->select('a_code', 'a_name', 'c_id')
  		->where('a_kabupaten', '=', $city)
    	->get();

    return response()->json([
        'success' => true,
        'data' => $agen
    ]);
  }

  public function filterData($id)
  {
  	$data = DB::table('d_stock')
  		->leftJoin('m_company as comp', 's_position', 'comp.c_id')
  		->leftJoin('m_company as agen', 's_comp', 'agen.c_id')
  		->leftJoin('m_item', 's_item', 'i_id')
  		->where('s_comp', '=', $id)
  		->select('agen.c_name as agen', 'comp.c_name as comp', 'i_name', 's_condition', 's_qty')
  		->get();

    return Datatables::of($data)
      ->addIndexColumn()
      ->addColumn('kondisi', function ($data) {
      	if ($data->s_condition == "FINE") {
      		return "Normal";
      	} else {
      		return "Rusak";
      	}
      })
      ->addColumn('qty', function ($data) {
      	return "<div class='text-center'>$data->s_qty</div>";
      })
      ->rawColumns(['kondisi', 'qty'])
      ->make(true);
  }
	// End Code ----------------------------------
}