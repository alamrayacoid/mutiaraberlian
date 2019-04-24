<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_itemout;
use App\d_stock;
use App\d_stock_mutation;
use App\m_item;
use Mutasi;
use Auth;
use carbon\Carbon;
use CodeGenerator;
use DB;
use Validator;
use Yajra\DataTables\DataTables;

class BarangKeluarController extends Controller
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
        'qty' => 'required',
        'unit' => 'required',
        'position' => 'required',
        'owner' => 'required',
        'mutcat' => 'required'
      ],
      [
        'itemId.required' => 'Item masih kosong !',
        'qty.required' => 'Jumlah barang masih kosong !',
        'unit.required' => 'Satuan masih kosong !',
        'position.required' => 'Lokasi barang masih kosong !',
        'owner.required' => 'Pemilik barang masih kosong !',
        'mutcat.required' => 'Keterangan masih kosong !'
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
      $nota = CodeGenerator::codeWithSeparator('d_itemout', 'io_nota', 9, 10, 3, 'OUT', '-');
      return $nota;
    }

    /**
    * Return a converted value by unit.
    *
    * @return int outQty
    */
    public function convertOutQtyToSmallestUnit($itemId, $unit, $outQty)
    {
        $outQty = (int)$outQty;
        $item = m_item::where('i_id', $itemId)->first();
        if ($unit == $item->i_unit1) {
            $outQty = $outQty * $item->i_unitcompare1;
        } elseif ($unit == $item->i_unit2) {
            $outQty = $outQty * $item->i_unitcompare2;
        } elseif ($unit == $item->i_unit3) {
            $outQty = $outQty * $item->i_unitcompare3;
        }
        return $outQty;
    }

    // /**
    //  * Update stock in 'd_stock'.
    //  *
    //  * @param string $positionId
    //  * @param string $condition
    //  * @param string $itemId
    //  * @param int $outQty -> the number of 'jumlah barang keluar'
    //  */
    // public function updateMainStock(
    //   $positionId, $condition, $itemId, $outQty, $unit
    // )
    // {
    //   try {
    //     DB::beginTransaction();
    //       $stock = d_stock::where('s_comp', Auth::user()->employee->e_id)
    //         ->where('s_position', $positionId)
    //         ->where('s_condition', $condition)
    //         ->where('s_item', $itemId)
    //         ->firstOrFail();
    //       // convert outQty to smallest unit
    //       if ($unit != 1) {
    //         $outQty = $this->convertOutQtyToSmallestUnit($itemId, $unit, $outQty);
    //       }
    //       if ($outQty > $stock->s_qty) {
    //         return response()->json([
    //           'status' => 'invalid',
    //           'message' => 'Stock tidak cukup !'
    //         ]);
    //       }
    //       $stock->s_qty = $stock->s_qty - $outQty;
    //       $stock->save();
    //     DB::commit();
    //     return response()->json([
    //       'status' => 'berhasil'
    //     ]);
    //   } catch (\Exception $e) {
    //     DB::rollBack();
    //     var_dump('update-main-stock error : ' . $e->getMessage());
    //     return response()->json([
    //       'status' => 'gagal',
    //       'message' => $e->getMessage()
    //     ]);
    //   }
    // }

    /**
    * Inser new 'item out' row.
    *
    * @param string
    * @param int
    */
    public function storeNewItemOut($req)
    {
      try {
        $newId = d_itemout::max('io_id') + 1;
        DB::beginTransaction();
          $itemOut = new d_itemout;
          $itemOut->io_id = $newId;
          $itemOut->io_date = Carbon::now();
          $itemOut->io_nota = $this->getNewNota();
          $itemOut->io_item = $req->itemId;
          $itemOut->io_qty = $req->qty;
          $itemOut->io_unit = $req->unit;
          $itemOut->io_mutcat = $req->mutcat;
          $itemOut->io_user = Auth::user()->employee->e_id;
          $itemOut->save();
        DB::commit();
        return $itemOut;
      } catch (\Exception $e) {
        DB::rollBack();
        return false;
      }
    }

    // /**
    //  * Return a row (selected 'stock mutation')
    //  * where 'sm_stock'='$stockId' and 'sm_residue' > 0
    //  *
    //  * @param int $stockId
    //  */
    // public function getStockMutation($stockId)
    // {
    //   $stockMutation = d_stock_mutation::orderBy('sm_detailid', 'asc')
    //     ->where('sm_stock', $stockId)
    //     ->where('sm_residue', '>', 0)
    //     ->firstOrFail();
    //   return $stockMutation;
    // }

    // /**
    //  * Update mutationResidue and mutationUse
    //  *
    //  */
    // public function updateMutationResidue(
    //   $stockMutation, $mutationUse, $mutationResidue, $stockId
    // )
    // {
    //   try {
    //     DB::beginTransaction();
    //     $stockMutation->sm_use = $mutationUse;
    //     $stockMutation->sm_residue = $mutationResidue;
    //     $stockMutation->save();
    //     DB::commit();
    //     return true;
    //   } catch (\Exception $e) {
    //     DB::rollBack();
    //     var_dump('update-mutation-residue error : ' . $e->getMessage());
    //     return false;
    //   }
    // }

    // /**
    //  * Return a new 'detail id' for creating new mutation.
    //  *
    //  * @param int $stockId
    //  * @return int $detailId
    //  */
    // public function getNewDetailId($stockId)
    // {
    //   $maxDetailId = d_stock_mutation::where('sm_stock', $stockId)
    //     ->max('sm_detailid');
    //   $detailId = $maxDetailId + 1;
    //   return $detailId;
    // }

    // /**
    // * Return current 'nota' for creating new mutation.
    // *
    // * @return varchar $nota
    // */
    // public function getCurrentNota()
    // {
    //   $nota = d_itemout::orderBy('io_id', 'desc')->select('io_nota')->first();
    //   $nota = $nota->io_nota;
    //   return $nota;
    // }

    // /**
    //  * Insert a new 'stock mutation' row
    //  *
    //  * @param int $stockId -> stock id
    //  * @param int $detailId -> new detail id
    //  * @param int $mutcat -> mutation category (reff: 'd_mutcat' table)
    //  * @param int $outQty -> amount of 'out items'
    //  * @param int $HPP ->
    //  * @param varchar $nota
    //  * @param varchar $reff
    //  * @return varchar $nota
    //  */
    // public function storeNewStockMutation(
    //   $stockId, $detailId, $mutcat,
    //   $outQty, $HPP, $nota, $reff
    // )
    // {
    //   try {
    //     DB::beginTransaction();
    //       $newStockMutation = new d_stock_mutation;
    //       $newStockMutation->sm_stock = $stockId;
    //       $newStockMutation->sm_detailid = $detailId;
    //       $newStockMutation->sm_date = Carbon::now();
    //       $newStockMutation->sm_mutcat = $mutcat;
    //       $newStockMutation->sm_qty = $outQty;
    //       $newStockMutation->sm_use = 0;
    //       $newStockMutation->sm_residue = 0;
    //       $newStockMutation->sm_hpp = $HPP;
    //       $newStockMutation->sm_sell = null;
    //       $newStockMutation->sm_nota = $nota;
    //       $newStockMutation->sm_reff = $reff;
    //       $newStockMutation->sm_user = Auth::user()->employee->e_id;
    //       $newStockMutation->save();
    //     DB::commit();
    //     return true;
    //   } catch (\Exception $e) {
    //     DB::rollBack();
    //     var_dump('Store-new-mutation error : ' . $e->getMessage());
    //     return false;
    //   }
    //
    // }

    // /**
    //  * prepare and insert a new 'stock mutation'
    //  *
    //  * @param int $stockId -> id stock
    //  * @param int $outQty -> the number of 'jumlah barang keluar'
    //  * @param int $mutcat -> mutation category, reff: 'd_mutcat' table
    //  */
    // public function createNewStockMutation($stockId, $unit, $outQty, $mutcat)
    // {
    //   $outQty = $this->convertOutQtyToSmallestUnit($stockId, $unit, $outQty);
    //   while ($outQty > 0) {
    //     // start: step 1
    //     // order 'stock mutation' by 'detail id' and
    //     // get 'row' where 'id=$stockId' and 'sm_residue>0'
    //     $stockMutation = $this->getStockMutation($stockId);
    //     // calculate 'mutationResidue', 'mutationUse',
    //     // 'outQtyResidue', and 'newOutQty'
    //     if ($stockMutation->sm_residue >= $outQty) {
    //       $mutationResidue = $stockMutation->sm_residue - $outQty;
    //       $mutationUse = $stockMutation->sm_use + $outQty;
    //       $outQtyResidue = 0;
    //       $newOutQty = $outQty;
    //     } else {
    //       $outQtyResidue = $outQty - $stockMutation->sm_residue;
    //       $newOutQty = $stockMutation->sm_residue;
    //       $mutationResidue = 0;
    //       $mutationUse = $stockMutation->sm_use + $stockMutation->sm_residue;
    //     }
    //     // update 'sm_residue' and 'sm_use' in 'd_stock_mutation'
    //     $isMutationUpdated = $this->updateMutationResidue($stockMutation, $mutationUse, $mutationResidue, $stockId);
    //     // get 'new detail id'
    //     $newDetailId = $this->getNewDetailId($stockId);
    //     // get 'new nota'
    //     $newNota = $this->getCurrentNota();
    //     // set 'new sm_hpp' value same with 'sm_hpp' from used 'row'
    //     $newHPP = $stockMutation->sm_hpp;
    //     // set 'new sm_reff' value same with 'sm_nota' from used 'row'
    //     $newReff = $stockMutation->sm_nota;
    //     // insert mutation
    //     $isNewMutationStored = $this->storeNewStockMutation(
    //       $stockId, $newDetailId, $mutcat, $newOutQty,
    //       $newHPP, $newNota, $newReff
    //     );
    //     // end: step 1
    //     $outQty = $outQtyResidue;
    //   }
    //   return true;
    // }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList()
    {
      $datas = d_itemout::orderBy('io_id', 'asc')
        ->with('getUnit')
        ->with('getMutcat')
        ->with('getItem')
        ->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('code', function($datas) {
          return '<td>'. $datas->getItem['i_code'] .'</td>';
        })
        ->addColumn('name', function($datas) {
          return '<td>'. $datas->getItem['i_name'] .'</td>';
        })
        ->addColumn('unit', function($datas) {
          return '<td>'. $datas->getUnit['u_name'] .'</td>';
        })
        ->addColumn('mutcat', function($datas) {
          return '<td>'. $datas->getMutcat['m_name'] .'</td>';
        })
        ->addColumn('action', function($datas) {
          return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                      <button class="btn btn-info hint--bottom-left hint--info" aria-label="Lihat Detail" onclick="Detail('. $datas->io_id .', \''. $datas->io_nota .'\')"><i class="fa fa-folder"></i>
                      </button>
                  </div>';
        })
        ->rawColumns(['code', 'name', 'unit', 'mutcat', 'action'])
        ->make(true);
    }

    /**
    * Return detail of an 'item-out'.
    *
    * @return \Illuminate\Http\Response
    */
    public function getDetail($id)
    {
      $data = d_itemout::where('io_id', $id)
        ->with('getItem')
        ->with('getItem.getUnit1')
        ->with('getMutationDetail')
        ->firstOrFail();
      return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('inventory/barangkeluar/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data['company'] = DB::table('m_company')->select('c_id', 'c_name')->get();
      $data['unit'] = DB::table('m_unit')->get();
      $data['mutcat'] = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Keluar%')->get();

      return view('inventory/barangkeluar/create', compact('data'));
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

        DB::beginTransaction();
        try {
            // insert new 'item out (d_itemout)'
            $storeItemOut = $this->storeNewItemOut($request);
            if ($storeItemOut === false) {
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Gagal, Barang keluar gagal ditambahkan !'
                ]);
            }
            $itemQtyUnitBase = $this->convertOutQtyToSmallestUnit(
                $request->itemId,
                $request->unit,
                $request->qty
            );
            // insert new mutasi-keluar
            $mutasi = Mutasi::mutasikeluar(
                $request->mutcat, // mutcat
                $request->owner, // item-owner
                $request->position, // item-position
                $request->itemId, // item-id
                $itemQtyUnitBase, // item-qty in smallest unit
                $storeItemOut->io_nota // nota
            );

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

      // // update 'main stock (d_stock)'
      // $isMainStockUpdated = $this->updateMainStock(
      //   $request->position, 'FINE',
      //   $request->itemId, $request->qty,
      //   $request->unit
      // );
      // if ($isMainStockUpdated->original['status'] != 'berhasil') {
      //   return response()->json([
      //     'status' => $isMainStockUpdated->original['status'],
      //     'message' => $isMainStockUpdated->original['message']
      //   ]);
      // }
      // // insert new 'item out (d_itemout)'
      // $isItemOutStored = $this->storeNewItemOut($request);
      // if ($isItemOutStored == false) {
      //   return response()->json([
      //     'status' => 'gagal',
      //     'message' => 'Gagal, hubungi pengembang !'
      //   ]);
      // }
      // // insert new 'stock mutation (d_stock_mutation)'
      // $isNewStockMutationCreated = $this->createNewStockMutation(
      //   $request->itemId, $request->unit, $request->qty, $request->mutcat
      // );
      // if ($isNewStockMutationCreated == true) {
      //   return response()->json([
      //     'status' => 'berhasil'
      //   ]);
      // } else {
      //   return response()->json([
      //     'status' => 'gagal',
      //     'message' => 'Gagal, hubungi pengembang !'
      //   ]);
      // }
    }
}
