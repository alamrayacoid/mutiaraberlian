<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\m_item;
use App\d_opname;
use CodeGenerator;
use carbon\Carbon;

class OpnameController extends Controller
{
    /**
    * Return list of items from 'm_item'.
    *
    * @return \Illuminate\Http\Response
    */
    public function getItems(Request $request)
    {
      $term = $request->term;
      $items = m_item::where('i_name', 'like', '%'.$term.'%')
      ->orWhere('i_code', 'like', '%'.$term.'%')
      ->with('getUnit1')
      ->with('getUnit2')
      ->with('getUnit3')
      ->get();
      if (sizeof($items) > 0) {
        foreach ($items as $item) {
          $results[] = [
            'id' => $item->i_id,
            'label' => $item->i_name,
            'unit1_id' => $items[0]->getUnit1['u_id'],
            'unit1_name' => $items[0]->getUnit1['u_name'],
            'unit2_id' => $items[0]->getUnit2['u_id'],
            'unit2_name' => $items[0]->getUnit2['u_name'],
            'unit3_id' => $items[0]->getUnit3['u_id'],
            'unit3_name' => $items[0]->getUnit3['u_name']
          ];
        }
      } else {
        $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
      }
      return response()->json($results);
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
        return view('inventory/manajemenstok/opname/opnamestock/create');
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
        $newId = d_opname::max('o_id') + 1;
        DB::beginTransaction();
          $opname = new d_opname;
          $opname->o_id = $newId;
          $opname->date = Carbon::now();
          $opname->nota = $nota;
          $opname->item = $request->itemId;
          $opname->qty_real =
      } catch (\Exception $e) {

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
