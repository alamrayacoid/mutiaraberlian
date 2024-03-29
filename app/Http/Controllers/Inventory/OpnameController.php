<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;

use App\d_opnameauth;
use App\d_stock;
use App\m_item;
use App\m_company;
use carbon\Carbon;
use CodeGenerator;
use DB;
use Validator;
use Yajra\DataTables\DataTables;

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
        // dd($request);
        $stock = d_stock::where('s_item', $request->itemId)
        ->where('s_comp', $request->owner)
        ->where('s_position', $request->position)
        // ->where('s_condition', $request->condition)
        ->with('getItem')
        ->with('getItem.getUnit1')
        ->with('getItem.getUnit2')
        ->with('getItem.getUnit3')
        ->first();
        // dd($stock);
        if ($stock != null) {
            $results = [
            'unit1_id'   => $stock->getItem['getUnit1']['u_id'],
            'unit1_name' => $stock->getItem['getUnit1']['u_name'],
            'unit2_id'   => $stock->getItem['getUnit2']['u_id'],
            'unit2_name' => $stock->getItem['getUnit2']['u_name'],
            'unit3_id'   => $stock->getItem['getUnit3']['u_id'],
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
        $qtySystem = $itemStock->s_qty;
        $qty = 0;
        if ($request->unit_sys == $item->i_unit1) {
            $qty = $itemStock->s_qty / $item->i_unitcompare1;
        } elseif ($request->unit_sys == $item->i_unit2) {
            $qty = $itemStock->s_qty / $item->i_unitcompare2;
        } elseif ($request->unit_sys == $item->i_unit3) {
            $qty = $itemStock->s_qty / $item->i_unitcompare3;
        }

        // dd($qty);
        return response()->json([
        'qty' => $qty,
        'qtySystem' => $qtySystem
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
    * Return DataTable list for view.
    *
    * @return Yajra/DataTables
    */
    public function getList()
    {
        $datas = d_opnameauth::orderBy('oa_id', 'asc')
        ->with('getItem')
        ->get();

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('name', function($datas) {
            return '<td>'. $datas->getItem['i_name'] .'</td>';
        })
        ->addColumn('status', function($datas) {
            return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">Pending</button></td>';
        })
        ->addColumn('date', function($datas) {
            return Carbon::parse($datas->oa_date)->format('d/m/Y');
        })
        ->addColumn('action', function($datas) {
            return '<td><div class="btn-group btn-group-sm">
                <button class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Detail data" onclick="Detail('. $datas->oa_id .')"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="Edit data" onclick="Edit('. $datas->oa_id .')"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus data" onclick="Delete('. $datas->oa_id .')"><i class="fa fa-times-circle"></i></button></div></td>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
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

    public function list_codeProduksi(Request $req)
    {
        // $stock = DB::table('d_stock')->where('s_item', '=', $req->item)
        //     ->where('s_comp', '=', $req->owner)
        //     ->where('s_position', '=', $req->position)
        //     ->where('s_status', '=', 'ON DESTINATION')
        //     ->where('s_condition', '=', 'FINE')
        //     ->get();
        //
        // if (count($stock) > 0) {
        //     $stockId = $stock[0]->s_id;
        // } else {
        //     $stockId = 0;
        // }
        //
        // $codes = DB::table('d_stockdt')
        // ->where('sd_stock', '=', $stockId)
        // ->get();

        $stock = DB::table('d_stockdt')
            ->join('d_stock', 'sd_stock', 's_id')
            ->where('s_item', '=', $req->item)
            ->where('s_comp', '=', $req->owner)
            ->where('s_position', '=', $req->position)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->get();

        return Datatables::of($stock)
        ->make(true);
    }

    public function list_codeOpname(Request $req)
    {
        $stock = DB::table('d_stockdt')
            ->join('d_stock', 'sd_stock', 's_id')
            ->where('s_item', '=', $req->item)
            ->where('s_comp', '=', $req->owner)
            ->where('s_position', '=', $req->position)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->get();

        return Datatables::of($stock)
        ->make(true);
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
            DB::beginTransaction();
            $notaOpnameAuth = CodeGenerator::codeWithSeparator('d_opnameauth', 'oa_nota', 12, 10, 3, 'OPNAME', '-');
            $notaOpname = CodeGenerator::codeWithSeparator('d_opname', 'o_nota', 12, 10, 3, 'OPNAME', '-');
            if (strcmp($notaOpname, $notaOpnameAuth) > 0) {
                $nota = $notaOpname;
            }
            else {
                $nota = $notaOpnameAuth;
            };

            $newId = d_opnameauth::max('oa_id') + 1;

            $opname                = new d_opnameauth;
            $opname->oa_id         = $newId;
            $opname->oa_date       = Carbon::now();
            $opname->oa_nota       = $nota;
            $opname->oa_comp       = $request->owner;
            $opname->oa_position   = $request->position;
            $opname->oa_item       = $request->itemId;
            $opname->oa_qtyreal    = $request->qty_real;
            $opname->oa_unitreal   = $request->unit_real;
            $opname->oa_qtysystem  = $request->qty_sys_hidden;
            $opname->oa_unitsystem = $request->unit_sys;
            $opname->oa_insert     = Carbon::now();
            $opname->save();

            for ($i=0; $i < count($request->code_r); $i++) {
                $dt_Id = DB::table('d_opnameauthdt')->where('oad_opname', '=', $newId)->max('oad_detailid') + 1;
                DB::table('d_opnameauthdt')->insert([
                    'oad_opname'   => $newId,
                    'oad_detailid' => $dt_Id,
                    'oad_code'     => $request->code_r[$i],
                    'oad_qty'      => $request->qty_r[$i]
                ]);
            }

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Opname');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }

    // show specific resource
    public function show($id)
    {
        $data = d_opnameauth::where('oa_id', $id)
            ->with('getItem')
            ->with('getUnitReal')
            ->with('getUnitSystem')
            ->with('getPosition')
            ->with('getOwner')
            ->first();
        return $data;
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $data['opname'] = d_opnameauth::where('oa_id', $id)
            ->join('m_item', 'oa_item', 'i_id')
            ->with('getItem')
            ->first();
        $code_real = DB::table('d_opnameauthdt')->where('oad_opname', '=', $data['opname']->oa_id)->get();
        $data['company'] = DB::table('m_company')->select('c_id', 'c_name')
            ->get();
        return view('inventory/manajemenstok/opname/opnamestock/edit', compact('data', 'code_real'));
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
            DB::beginTransaction();
            $opname = d_opnameauth::where('oa_id', $id)
                ->first();
            $opname->oa_comp       = $request->owner;
            $opname->oa_position   = $request->position;
            $opname->oa_item       = $request->itemId;
            $opname->oa_qtyreal    = $request->qty_real;
            $opname->oa_unitreal   = $request->unit_real;
            $opname->oa_qtysystem  = $request->qty_sys_hidden;
            $opname->oa_unitsystem = $request->unit_sys;
            $opname->oa_insert     = Carbon::now();
            $opname->save();

            DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->delete();

            for ($i=0; $i < count($request->code_r) ; $i++) {
                $detailId = DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->max('oad_detailid') + 1;
                DB::table('d_opnameauthdt')->insert([
                    'oad_opname'   => $id,
                    'oad_detailid' => $detailId,
                    'oad_code'     => $request->code_r[$i],
                    'oad_qty'      => $request->qty_r[$i]
                ]);
            }

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Opname');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        // start: execute delete data
        DB::beginTransaction();
        try {
            DB::table('d_opnameauth')
                ->where('oa_id', $id)
                ->delete();

            DB::table('d_opnameauthdt')
                ->where('oad_opname', '=', $id)
                ->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Opname');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}
