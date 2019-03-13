<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\m_item;
use App\d_opnameauth;
use App\d_stock;
use Validator;
use CodeGenerator;
use carbon\Carbon;

class OpnameController extends Controller
{

    /**
    * Return list of items from 'm_item'.
    *
    * @return \Illuminate\Http\Response
    */
    public function getItemAutocomplete(Request $request)
    {
      $term = $request->term;
      $items = m_item::where('i_name', 'like', '%'.$term.'%')
        ->orWhere('i_code', 'like', '%'.$term.'%')
        ->get();
      if (sizeof($items) > 0) {
        foreach ($items as $item) {
          $results[] = [
            'id' => $item->i_id,
            'label' => $item->i_code .' - '. $item->i_name,
          ];
        }
      } else {
        $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
      }
      return response()->json($results);
    }

    /**
    * Return list of items from 'm_item'.
    *
    * @return \Illuminate\Http\Response
    */
    public function getItem(Request $request)
    {
      $stock = d_stock::where('s_item', $request->itemId)
        ->where('s_comp', $request->owner)
        ->where('s_position', $request->position)
        ->with('getItem')
        ->with('getItem.getUnit1')
        ->with('getItem.getUnit2')
        ->with('getItem.getUnit3')
        ->first();
      if ($stock != null) {
        $results = [
          'unit1_id' => $stock->getItem['getUnit1']['u_id'],
          'unit1_name' => $stock->getItem['getUnit1']['u_name'],
          'unit2_id' => $stock->getItem['getUnit2']['u_id'],
          'unit2_name' => $stock->getItem['getUnit2']['u_name'],
          'unit3_id' => $stock->getItem['getUnit3']['u_id'],
          'unit3_name' => $stock->getItem['getUnit3']['u_name']
        ];
      } else {
        $results = 'empty';
      }
      return response()->json($results);
    }

    /**
    * return total qty of select item in specific unit.
    *
    */
    public function getQty(Request $request)
    {
      if ($request->itemId == null || $request->owner == null || $request->position == null) {
        return response()->json([
          'qty' => 0
        ]);
      }
      $item = m_item::where('i_id', $request->itemId)
        ->first();
      $itemStock = d_stock::where('s_comp', $request->owner)
        ->where('s_position', $request->position)
        ->where('s_item', $request->itemId)
        ->first();
      if ($request->unit_sys == $item->i_unit1) {
        $qty = $itemStock->s_qty / $item->i_unitcompare1;
      } elseif ($request->unit_sys == $item->i_unit2) {
        $qty = $itemStock->s_qty / $item->i_unitcompare2;
      } elseif ($request->unit_sys == $item->i_unit3) {
        $qty = $itemStock->s_qty / $item->i_unitcompare3;
      }
      return response()->json([
        'qty' => $qty
      ]);
    }


    /**
    * Validate request before execute command.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return 'error message' or '1'
    */
    public function validate_req(Request $request)
    {
      // start: validate data before execute
      $validator = Validator::make($request->all(), [
        'itemId' => 'required',
        'unit_sys' => 'required',
        'qty_sys' => 'required',
        'unit_real' => 'required',
        'qty_real' => 'required'
      ],
      [
        'itemId.required' => 'Item masih kosong !',
        'qty_sys.required' => 'Jumlah barang sistem masih kosong !',
        'unit_sys.required' => 'Satuan barang sistem masih kosong !',
        'qty_real.required' => 'Jumlah barang real masih kosong !',
        'unit_real.required' => 'Satuan barang real masih kosong !'
      ]);
      if($validator->fails())
      {
        return $validator->errors()->first();
      }
      else
      {
        return '1';
      }
    }

    /**
    * Return a new 'nota' for creating new 'item out'.
    *
    * @return varchar $nota
    */
    public function getNewNota()
    {
      $nota = CodeGenerator::codeWithSeparator('d_itemout', 'io_nota', 12, 10, 3, 'OPNAME', '-');
      return $nota;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventory/manajemenstok/opname/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['company'] = DB::table('m_company')->select('c_id', 'c_name')->get();
        return view('inventory/manajemenstok/opname/opnamestock/create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // validate request
      $isValidRequest = $this->validate_req($request);
      if ($isValidRequest != '1') {
        $errors = $isValidRequest;
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // insert data to db
      try {
        $nota = $this->getNewNota();
        $newId = d_opnameauth::max('oa_id') + 1;
        DB::beginTransaction();
          $opname = new d_opnameauth;
          $opname->oa_id = $newId;
          $opname->oa_date = Carbon::now();
          $opname->oa_nota = $nota;
          $opname->oa_item = $request->itemId;
          $opname->oa_qtyreal = $request->qty_real;
          $opname->oa_unitreal = $request->unit_real;
          $opname->oa_qtysystem = $request->qty_sys;
          $opname->oa_unitsystem = $request->unit_sys;
          $opname->oa_status = 'P';
          $opname->oa_insert = Carbon::now();
          $opname->save();
        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
          'status' => 'gagal',
          'message' => 'Gagal, hubungi pengembang !'
        ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
