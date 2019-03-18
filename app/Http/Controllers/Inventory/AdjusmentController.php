<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

use Carbon\Carbon;

class AdjusmentController extends Controller
{
  public function index()
  {
      return view('inventory/manajemenstok/adjustment/index');
  }
  public function create()
  {
      $nota = DB::table('d_opname')->where('o_status', 'P')->get();

      return view('inventory/manajemenstok/adjustment/adjustmentstock/create', compact('nota'));
  }
  public function nota()
  {
      return view('inventory/manajemenstok/adjustment/nota/index');
  }
  public function getopname(Request $request){
    $data = DB::table('d_opname')->where('o_nota', $request->nota)->first();

    $stock = DB::table('d_stock')->where('s_comp', $data->o_comp)->where('s_position', $data->o_position)->where('s_item', $data->o_item)->first();

    $item = DB::table('m_item')->where('i_id', $data->o_item)->first();

    $unitsystem = DB::table('m_unit')->where('u_id', $data->o_unitsystem)->first();

    $unitreal = DB::table('m_unit')->where('u_id', $data->o_unitreal)->first();

    return response()->json([
      'item' => $item,
      'data' => $data,
      'unitsystem' => $unitsystem,
      'unitreal' => $unitreal,
      'stock' => $stock
    ]);
  }
}
