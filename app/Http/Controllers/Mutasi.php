<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\m_company;
use App\d_stock;
use App\d_stockdt;
use App\d_stock_mutation;
use App\d_stockmutationdt;
use App\m_item;
use App\m_mutcat;
use Auth;
use Carbon\Carbon;
use Mockery\Exception;

class Mutasi extends Controller
{
    // validate production-code
    static function validateProductionCode($from, $listItemsId, $listProdCode, $listProdCodeLength)
    {
        DB::beginTransaction();
        try {
            $prodCode = $listProdCode;
            $prodCodeLength = $listProdCodeLength;
            $startProdCodeIdx = 0;
            foreach ($listItemsId as $key => $itemId) {
                // get stock-item-parent
                $stock = d_stock::where('s_position', '=', $from)
                    ->where('s_item', '=', $itemId)
                    ->where('s_status', '=', 'ON DESTINATION')
                    ->where('s_condition', '=', 'FINE')
                    ->first();

                // callback if stock item is null / empty
                if (is_null($stock)) {
                    $item = m_item::where('i_id', $itemId)->first();
                    throw new Exception("Stok '" . strtoupper($item->i_name) . "' kosong !");
                }

                $lengthPC = (int)$prodCodeLength[$key];
                $endProdCodeIdx = $startProdCodeIdx + $lengthPC;
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($prodCode[$j] == '' || $prodCode[$j] == null) {
                        continue;
                    }
                    // get stock-detail-parent
                    $stockDt = d_stockdt::where('sd_stock', $stock->s_id)
                        ->where('sd_code', $prodCode[$j])
                        ->first();

                    if (is_null($stockDt)) {
                        throw new Exception("Kode produksi '" . strtoupper($prodCode[$j]) . "' tidak ditemukan !");
                    }
                }
                $startProdCodeIdx += $lengthPC;
            }

            DB::commit();
            return 'validated';
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // ...PC = production-code
    static function distribusicabangkeluar(
        $from, $to, $item, $qty, $nota, $reff,
        $listPC, $listQtyPC, $listUnitPC,
        $sellPrice = null, $mutation = null
    )
    {
        DB::beginTransaction();
        try {
            // get mutcat for 'penjualan/pembelian'
            if ($mutation == 5) {
                $mutcatIn = m_mutcat::where('m_name', 'Barang Masuk Pembelian')->first();
                $mutcatIn = $mutcatIn->m_id;
                $mutcatOut = m_mutcat::where('m_name', 'Barang Keluar Penjualan Ke Agen')->first();
                $mutcatOut = $mutcatOut->m_id;
            } // get mutcat for 'distribusi'
            else {
                // get mutcat for 'distribusi'
                $mutcatIn = m_mutcat::where('m_name', 'Barang Masuk Distribusi Cabang')->first();
                $mutcatIn = $mutcatIn->m_id;
                $mutcatOut = m_mutcat::where('m_name', 'Barang Keluar Distribusi Cabang')->first();
                $mutcatOut = $mutcatOut->m_id;
            }
            // qty item that is sending out to branch
            $qty = (int)$qty;
            // date now
            $dateNow = Carbon::now();

            // get list of 'in' mutcat-list
            $inMutcatList = m_mutcat::where('m_status', 'M')
                ->select('m_id')
                ->get();
            for ($i = 0; $i < count($inMutcatList); $i++) {
                $tmp[] = $inMutcatList[$i]->m_id;
            }
            $inMutcatList = $tmp;

            // get stock and stock-mutation parent
            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_position', '=', $from)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $inMutcatList)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
                ->get();

            $permintaan = $qty;

            // set callback if stock-item-parent is empty
            if (sizeof($stock) <= 0) {
                $itemx = m_item::where('i_id', $item)->select('i_name')->first();
                throw new Exception("Stock " . $itemx->i_name . " kosong !");
            }

            // update qty stock-item-parent
            $stockParent = d_stock::where('s_id', $stock[0]->sm_stock)
                ->where('s_comp', $stock[0]->s_comp)
                ->where('s_position', $stock[0]->s_position)
                ->where('s_status', $stock[0]->s_status)
                ->where('s_condition', $stock[0]->s_condition)
                ->first();

            $stockParent->s_qty = $stock[0]->s_qty - $permintaan;
            $stockParent->save();

            // set mutation record
            for ($j = 0; $j < count($stock); $j++) {
                $continueLoopStock = false;
                $detailid = DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)
                        ->max('sm_detailid') + 1;

                // insert new stock mutation
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {
                    // use all qty from current stock-mutation
                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    // update qty of request (how much qty used by selected stock-mutation)
                    $permintaan = $permintaan - $stock[$j]->sm_sisa;
                    $smQty = $stock[$j]->sm_sisa;

                    $continueLoopStock = true;
                } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    $detailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', $stock[$j]->sm_stock)
                            ->max('sm_detailid') + 1;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_use + $permintaan,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);

                    $smQty = $permintaan;

                    $continueLoopStock = false;
                }

                $val_stockmut = null;
                $val_stockmut = [
                    'sm_stock' => $stock[$j]->sm_stock,
                    'sm_detailid' => $detailid,
                    'sm_date' => $dateNow,
                    'sm_mutcat' => $mutcatOut,
                    'sm_qty' => $smQty,
                    'sm_use' => 0,
                    'sm_residue' => 0,
                    'sm_hpp' => $stock[$j]->sm_hpp,
                ];
                if (!is_null($sellPrice)) {
                    $val_stockmut += [
                        'sm_sell' => $sellPrice,
                    ];
                } else {
                    $val_stockmut += [
                        'sm_sell' => $stock[$j]->sm_sell,
                    ];
                }
                $val_stockmut += [
                    'sm_nota' => $nota,
                    'sm_reff' => $stock[$j]->sm_nota,
                    'sm_user' => Auth::user()->u_id
                ];

                // insert new stock-mutation (distribution-out)
                // using sm_sisa as sm_qty
                DB::table('d_stock_mutation')->insert($val_stockmut);

                // insert new stock-mutation-detail production-code for mutcat-out
                $insertSMProdCode = self::insertStockMutationDt($stock[$j]->sm_stock, $detailid, $listPC, $listQtyPC);
                if ($insertSMProdCode !== 'success') {
                    throw new Exception($insertSMProdCode->getData()->message);
                }

                // set variable for new stock
                $mutcat = $mutcatIn;
                $comp = $from;
                $position = $to;
                $status = 'ON GOING';
                $condition = 'FINE';
                (!is_null($sellPrice)) ? $sell = $sellPrice : $sell = $stock[$j]->sm_sell;
                $hpp = (int)$stock[$j]->sm_hpp;
                $requestQty = (int)$permintaan;
                // get stock-item with 'On Going' status
                $stockId = DB::table('d_stock')
                    ->select('s_id')
                    ->where('s_comp', '=', $comp)
                    ->where('s_position', '=', $position)
                    ->where('s_item', '=', $item)
                    ->where('s_status', '=', $status)
                    ->where('s_condition', '=', $condition)
                    ->get();

                // if stock with 'On Going' status is not-found
                // insert new stock with 'On Going' status
                if (count($stockId) < 1) {
                    // insert new stock-item
                    $stockId = d_stock::max('s_id') + 1;
                    $stock = array(
                        's_id' => $stockId,
                        's_comp' => $comp,
                        's_position' => $position,
                        's_item' => $item,
                        's_qty' => $requestQty,
                        's_status' => $status,
                        's_condition' => $condition,
                        's_created_at' => $dateNow,
                        's_updated_at' => $dateNow
                    );
                    d_stock::insert($stock);
                    // detail id for stock-mutation
                    $smDetailId = 1;
                }
                // if stock with 'On Going' status is found
                // update selected stock with 'On Going' status
                else {
                    $stockId = $stockId[0]->s_id;
                    $stock = d_stock::where('s_id', '=', $stockId)
                        ->first();
                    // update qty stock-item where mutcat-in
                    $stockAkhir = $stock->s_qty + $requestQty;
                    $update = array('s_qty' => $stockAkhir);
                    d_stock::where('s_id', '=', $stockId)->update($update);

                    $smDetailId = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $stockId)
                            ->max('sm_detailid') + 1;
                }

                // insert new mutation with mutcat = distribution-in
                $mutasi = array(
                    'sm_stock' => $stockId,
                    'sm_detailid' => $smDetailId,
                    'sm_date' => $dateNow,
                    'sm_mutcat' => $mutcat,
                    'sm_qty' => $requestQty,
                    'sm_use' => 0,
                    'sm_residue' => $requestQty,
                    'sm_hpp' => $hpp,
                    'sm_sell' => $sell,
                    'sm_nota' => $nota,
                    'sm_reff' => $reff,
                    'sm_user' => Auth::user()->u_id
                );
                d_stock_mutation::insert($mutasi);

                // insert new stock-mutation-detail production-code for mutcat-in
                $insertSMProdCode = self::insertStockMutationDt($stockId, $smDetailId, $listPC, $listQtyPC);
                if ($insertSMProdCode !== 'success') {
                    throw new Exception($insertSMProdCode->getData()->message);
                }

                // insert new stock-detail production-code
                $stockParentId = $stockParent->s_id;
                $stockChildId = $stockId;
                $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                if ($insertStockDt !== 'success') {
                    throw new Exception($insertStockDt->getData()->message);
                }

                if ($continueLoopStock == false) {
                    $permintaan = 0;
                    break;
                }
            }

            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    static function mutasimasuk(
        $mutcat, // mutcat
        $comp, // u_company / (item-owner)
        $position, // destination
        $item, // item id
        $qty, // qty
        $status, // status item
        $condition, // condition item
        $hpp = 0,
        $sell = 0, // sell-price
        $nota, // nota
        $reff, // nota refference
        $listPC = null, // list of production-code
        $listQtyPC = null, // list qty of production-code
        $statusKons = null, // status konsinyasi ('pusat' / 'branch')
        $itemOwner = null // item owner
    )
    {
        DB::beginTransaction();
        try {
            // dd($mutcat,$comp,$position,$item,$qty,$status,$condition,$hpp,$sell,$nota,$reff,$listPC,$listQtyPC);

            // prevention, set null if param-value is null
            ($listPC === null) ? $listPC = null : $listPC = $listPC;
            ($listQtyPC === null) ? $listQtyPC = null : $listQtyPC = $listQtyPC;
            ($statusKons === null) ? $statusKons = null : $statusKons = $statusKons;

            //========== cek id stock
            $sell = (int)$sell;
            $hpp = (int)$hpp;
            $qty = (int)$qty;

            $sekarang = Carbon::now('Asia/Jakarta');

            $idStok = DB::table('d_stock')
                ->select('s_id')
                ->where('s_comp', '=', $comp)
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', $status)
                ->where('s_condition', '=', $condition)
                ->get();

            //========== buat data stok baru
            if (count($idStok) < 1) {
                $idStok = DB::table('d_stock')
                    ->max('s_id');
                $idStok = $idStok + 1;

                $stock = array(
                    's_id' => $idStok,
                    's_comp' => $comp,
                    's_position' => $position,
                    's_item' => $item,
                    's_qty' => $qty,
                    's_status' => $status,
                    's_condition' => $condition,
                    's_created_at' => $sekarang,
                    's_updated_at' => $sekarang
                );
                d_stock::insert($stock);

                $detailid = 1;
            } //========== update qty jika data sudah ada
            else {
                $idStok = $idStok[0]->s_id;

                $stock = DB::table('d_stock')
                    ->where('s_id', '=', $idStok)
                    ->first();

                $stockAkhir = $stock->s_qty + $qty;
                $update = array('s_qty' => $stockAkhir);

                d_stock::where('s_id', '=', $idStok)->update($update);
                $getSMdt = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $idStok)
                    ->max('sm_detailid');
                $detailid = $getSMdt + 1;
            }

            // insert mutation-in
            $mutasi = array(
                'sm_stock' => $idStok,
                'sm_detailid' => $detailid,
                'sm_date' => $sekarang,
                'sm_mutcat' => $mutcat,
                'sm_qty' => $qty,
                'sm_use' => 0,
                'sm_residue' => $qty,
                'sm_hpp' => $hpp,
                'sm_sell' => $sell,
                'sm_nota' => $nota,
                'sm_reff' => $reff,
                'sm_user' => Auth::user()->u_id,
            );
            d_stock_mutation::insert($mutasi);

            // call mutation-out with special-case
            // currently its special case for konsinyasi-in / mutcat=12
            if ($mutcat == 12 && $statusKons == 'pusat') {
                // insert mutation-out
                // add $stockChildId as param
                $comp = m_company::where('c_type', 'PUSAT')->first();
                $comp = $comp->c_id; // set comp as 'pusat'
                $konsinyasiKeluar = self::mutasikeluartanpapemilik(
                    13, // mutcat
                    $comp, // user->u_company
                    $item,
                    $qty,
                    $nota,
                    $sell, // sell-price
                    $idStok, // stock child id
                    $listPC, // list of production-code
                    $listQtyPC // list qty of production-cpde
                );
            }
            elseif ($mutcat == 12 && $statusKons == 'cabang') {
                $konsinyasiKeluar = self::mutasikeluar(
                    13, // mutcat
                    $comp, // item owner
                    $comp, // item position
                    $item, // item id
                    $qty, // qty with smalles unit
                    $nota, // nota
                    $sell,
                    $listPC, // list of production-code
                    $listQtyPC, // list qty of production-code
                    $reff, // nota reff, used for return production
                    $idStok // id stock child
                );
            }
            // // insert and update new stock-detail (production-code) with update the parent stock-detail
            // elseif (condition) {
            //     // code...
            // }
            // insert and update new stock-detail (production-code) without update the parent stock-detail
            else {
                $konsinyasiKeluar = true;

                $stockParentId = null;
                $stockChildId = $idStok;
                $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                if ($insertStockDt !== 'success') {
                    throw new Exception($insertStockDt->getData()->message);
                }
            }
            if (!is_bool($konsinyasiKeluar)) {
                return $konsinyasiKeluar;
            }

            // insert new stock-mutation-detail production-code
            $insertSMProdCode = self::insertStockMutationDt($idStok, $detailid, $listPC, $listQtyPC);
            if ($insertSMProdCode !== 'success') {
                throw new Exception($insertSMProdCode->getData()->message);
            }

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ]);
        }
    }

    static function mutasikeluar(
        $mutcat, // mutcat
        $comp, // item owner
        $position, // item position
        $item, // item id
        $qty, // qty with smalles unit
        $nota, // nota
        $sellprice = null,
        $listPC = null, // list of production-code
        $listQtyPC = null, // list qty of production-code
        $reff = null, // nota reff, used for return production
        $stockChildId = null // stock child id
    )
    {
        DB::beginTransaction();
        try {
            // dd($mutcat, $comp, $position, $item, $qty, $nota, $sellprice, $listPC, $listQtyPC, $reff, $stockChildId);

            ($sellprice === null) ? $sellprice = null : $sellprice = $sellprice;
            ($listPC === null) ? $listPC = null : $listPC = $listPC;
            ($listQtyPC === null) ? $listQtyPC = null : $listQtyPC = $listQtyPC;
            ($reff == null) ? $reff = null : $reff = $reff;

            $qty = (int)$qty;

            $sekarang = Carbon::now('Asia/Jakarta');

            $datamutcat = DB::table('m_mutcat')->where('m_status', '=', 'M')->get();

            for ($i = 0; $i < count($datamutcat); $i++) {
                $tmp[] = $datamutcat[$i]->m_id;
            }
            // dd($comp, $position, $item);
            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_comp', '=', $comp)
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $tmp)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0);

            // used for mutation that need reff such as return-production
            if ($reff != null) {
                $stock = $stock->where('sm_nota', '=', $reff);
            }
            $stock = $stock->get();
            $permintaan = $qty;

            // set callback if stock-item-parent is empty
            if (sizeof($stock) <= 0) {
                $itemx = m_item::where('i_id', $item)->select('i_name')->first();
                throw new Exception("Stock " . $itemx->i_name . " kosong !");
            }

            DB::table('d_stock')
                ->where('s_id', $stock[0]->s_id)
                ->where('s_item', $stock[0]->s_item)
                ->where('s_comp', $stock[0]->s_comp)
                ->where('s_position', $stock[0]->s_position)
                ->where('s_status', $stock[0]->s_status)
                ->where('s_condition', $stock[0]->s_condition)
                ->update([
                    's_qty' => $stock[0]->s_qty - $permintaan
                ]);

            // set mutation record
            for ($j = 0; $j < count($stock); $j++) {
                $continueLoopStock = false;
                $detailid = (DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) ? DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid') + 1 : 1;

                // insert new stock mutation
                // use all qty from current stock-mutation
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {
                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    // update qty of request after using all qty in stock-mutation parent
                    $permintaan = $permintaan - $stock[$j]->sm_sisa;
                    // qty that will store to sm_qty in new stock-mutation
                    $smQty = $stock[$j]->sm_sisa;

                    $continueLoopStock = true;
                } // use part of qty from current stock-mutation
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    $detailid = (DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) ? (DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) + 1 : 1;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $permintaan + $stock[$j]->sm_use,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);
                    // qty that will store to sm_qty in new stock-mutation
                    $smQty = $permintaan;

                    $continueLoopStock = false;
                }

                // insert new stock-mutation out
                if ($mutcat == 15){
                    $sellprice = $stock[$j]->sm_hpp;
                }
                DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock' => $stock[$j]->sm_stock,
                        'sm_detailid' => $detailid,
                        'sm_date' => $sekarang,
                        'sm_mutcat' => $mutcat,
                        'sm_qty' => $smQty,
                        'sm_use' => 0,
                        'sm_residue' => 0,
                        'sm_hpp' => $stock[$j]->sm_hpp,
                        'sm_sell' => $sellprice,
                        'sm_nota' => $nota,
                        'sm_reff' => $stock[$j]->sm_nota,
                        'sm_user' => Auth::user()->u_id,
                    ]);

                // currently, it's special case for 'penjualan-langsung / mutcat 14'
                if ($mutcat == 14) {
                    // insert new stock-detail production-code
                    $stockParentId = $stock[$j]->sm_stock;
                    $stockChildId = null;
                    $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }

                    // insert new stock-mutation-detail production-code
                    $insertSMProdCode = self::insertStockMutationDt($stockParentId, $detailid, $listPC, $listQtyPC);
                    if ($insertSMProdCode !== 'success') {
                        throw new Exception($insertSMProdCode->getData()->message);
                    }
                } // it's special case for konsinyasi from branch to agent
                else if ($mutcat == 13) {
                    // insert new stock-detail production-code
                    $stockParentId = $stock[$j]->sm_stock;
                    $stockChildId = $stockChildId;
                    $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }
                }

                if ($continueLoopStock == false) {
                    $permintaan = 0;
                    break;
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    // used for return in ProduksiController, move to mutasi keluar
    static function MutasiKeluarWithReff(
        $mutcat, // mutcat
        $comp, // pemilik item
        $position, // lokasi item
        $item, // item id
        $qty, // qty item
        $nota, // nota
        $reff // reff
    )
    {
        DB::beginTransaction();
        try {

            $qty = (int)$qty;

            $sekarang = Carbon::now('Asia/Jakarta');

            $datamutcat = DB::table('m_mutcat')->where('m_status', '=', 'M')->get();

            for ($i = 0; $i < count($datamutcat); $i++) {
                $tmp[] = $datamutcat[$i]->m_id;
            }

            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_comp', '=', $comp)
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->where('sm_nota', '=', $reff)
                ->whereIn('sm_mutcat', $tmp)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
                ->get();

            $permintaan = $qty;

            DB::table('d_stock')
                ->where('s_id', $stock[0]->s_id)
                ->where('s_item', $stock[0]->s_item)
                ->where('s_comp', $stock[0]->s_comp)
                ->where('s_position', $stock[0]->s_position)
                ->where('s_status', $stock[0]->s_status)
                ->where('s_condition', $stock[0]->s_condition)
                ->update([
                    's_qty' => $stock[0]->s_qty - $permintaan
                ]);

            for ($j = 0; $j < count($stock); $j++) {
                //Terdapat sisa permintaan

                $detailid = (DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) ? DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid') + 1 : 1;

                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    $permintaan = $permintaan - $stock[$j]->sm_sisa;

                    DB::table('d_stock_mutation')
                        ->insert([
                            'sm_stock' => $stock[$j]->sm_stock,
                            'sm_detailid' => $detailid,
                            'sm_date' => $sekarang,
                            'sm_mutcat' => $mutcat,
                            'sm_qty' => $stock[$j]->sm_sisa,
                            'sm_use' => 0,
                            'sm_residue' => 0,
                            'sm_hpp' => $stock[$j]->sm_hpp,
                            'sm_sell' => $stock[$j]->sm_sell,
                            'sm_nota' => $nota,
                            'sm_reff' => $stock[$j]->sm_nota,
                            'sm_user' => Auth::user()->u_id
                        ]);

                } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    //Langsung Eksekusi
                    $detailid = (DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) ? (DB::table('d_stock_mutation')->where('sm_stock', $stock[$j]->sm_stock)->max('sm_detailid')) + 1 : 1;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $permintaan + $stock[$j]->sm_use,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);

                    DB::table('d_stock_mutation')
                        ->insert([
                            'sm_stock' => $stock[$j]->sm_stock,
                            'sm_detailid' => $detailid,
                            'sm_date' => $sekarang,
                            'sm_mutcat' => $mutcat,
                            'sm_qty' => $permintaan,
                            'sm_use' => 0,
                            'sm_residue' => 0,
                            'sm_hpp' => $stock[$j]->sm_hpp,
                            'sm_sell' => $stock[$j]->sm_sell,
                            'sm_nota' => $nota,
                            'sm_reff' => $stock[$j]->sm_nota,
                            'sm_user' => Auth::user()->u_id
                        ]);

                    $permintaan = 0;
                    $j = count($stock) + 1;
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ]);
        }
    }

    static function mutasikeluartanpapemilik(
        $mutcat, // mutcat
        $position, // position
        $item, // item id
        $qty, // qty
        $nota, // nota
        $sellPrice, // sellprice
        $stockChildId = null, // id of stock EvChild
        $listPC = null, // list of production-code
        $listQtyPC = null // list qty of production-code
    )
    {
        DB::beginTransaction();
        try {
            // prevention, set null if param-value is null
            ($stockChildId === null) ? $stockChildId = null : $stockChildId = $stockChildId;
            ($listPC === null) ? $listPC = null : $listPC = $listPC;
            ($listQtyPC === null) ? $listQtyPC = null : $listQtyPC = $listQtyPC;

            $qty = (int)$qty;

            $sekarang = Carbon::now('Asia/Jakarta');

            $datamutcat = DB::table('m_mutcat')->where('m_status', '=', 'M')->get();

            for ($i = 0; $i < count($datamutcat); $i++) {
                $tmp[] = $datamutcat[$i]->m_id;
            }

            // get stock and stock-mutation parent
            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $tmp)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
                ->get();

            $permintaan = $qty;

            // set callback if stock-item-parent is empty
            if (sizeof($stock) <= 0) {
                $itemx = m_item::where('i_id', $item)->select('i_name')->first();
                throw new Exception("Stock " . $itemx->i_name . " kosong !");
            }

            // update qty in stock parent
            DB::table('d_stock')
                ->where('s_id', $stock[0]->s_id)
                ->where('s_item', $stock[0]->s_item)
                ->where('s_comp', $stock[0]->s_comp)
                ->where('s_position', $stock[0]->s_position)
                ->where('s_status', $stock[0]->s_status)
                ->where('s_condition', $stock[0]->s_condition)
                ->update([
                    's_qty' => $stock[0]->s_qty - $permintaan
                ]);

            // set mutation record
            for ($j = 0; $j < count($stock); $j++) {
                $continueLoopStock = false;
                $detailid = DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)
                        ->max('sm_detailid') + 1;

                // insert new stock mutation
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {
                    // use all qty from current stock-mutation
                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    // update qty of request (how much qty used by selected stock-mutation)
                    $permintaan = $permintaan - $stock[$j]->sm_sisa;
                    $smQty = $stock[$j]->sm_sisa;

                    $continueLoopStock = true;
                } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    $detailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', $stock[$j]->sm_stock)
                            ->max('sm_detailid') + 1;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $permintaan + $stock[$j]->sm_use,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);
                    $smQty = $permintaan;

                    $continueLoopStock = false;
                }

                // insert new stock-mutation (konsinyasi-out)
                // using sm_sisa as sm_qty
                DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock' => $stock[$j]->sm_stock,
                        'sm_detailid' => $detailid,
                        'sm_date' => $sekarang,
                        'sm_mutcat' => $mutcat,
                        'sm_qty' => $smQty,
                        'sm_use' => 0,
                        'sm_residue' => 0,
                        'sm_hpp' => $stock[$j]->sm_hpp,
                        'sm_sell' => $sellPrice,
                        'sm_nota' => $nota,
                        'sm_reff' => $stock[$j]->sm_nota,
                        'sm_user' => Auth::user()->u_id
                    ]);

                // currently, it's special case for konsinyasi-out
                if ($mutcat == 13) {
                    // insert new stock-detail production-code
                    $stockParentId = $stock[$j]->sm_stock;
                    $stockChildId = $stockChildId;
                    $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }

                    // insert new stock-mutation-detail production-code
                    $insertSMProdCode = self::insertStockMutationDt($stockParentId, $detailid, $listPC, $listQtyPC);
                    if ($insertSMProdCode !== 'success') {
                        throw new Exception($insertSMProdCode->getData()->message);
                    }
                }

                if ($continueLoopStock == false) {
                    $permintaan = 0;
                    break;
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    static function rollback(
        $nota,
        $itemId = null,
        $mutcat = null
    )
    {
        ($itemId === null) ? $item = null : $item = $itemId;

        DB::beginTransaction();
        try {
            // get stock-mutation's parent
            $smParents = d_stock_mutation::where('sm_nota', '=', $nota)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->get();

            foreach ($smParents as $sm) {
                // rollBack for konsinyasi-in
                if ($sm->sm_mutcat == 12) {
                    // get stock-mutation parent
                    foreach ($smParents as $key => $sm) {
                        if ($sm->sm_mutcat == 13) {
                            $smStockParent = $sm->sm_stock;
                            $smReff = $sm->sm_reff;
                        }
                    }

                    $stockItem = d_stock::where('s_id', '=', $sm->sm_stock)
                        ->first();
                    $returnQty = $stockItem->s_qty - $sm->sm_qty;

                    // if new-qty is 0, delete stock-item
                    if ($returnQty <= 0) {
                        $stockItem->delete();
                    } // if new-qty is > 0, update qty in stock-item
                    elseif ($returnQty > 0) {
                        $stockItem->s_qty = $returnQty;
                        $stockItem->save();
                    }
                } // rollback for konsinyasi-out and penjualn-langsung
                else if ($sm->sm_mutcat == 13 || $sm->sm_mutcat == 14) {
                    // set stock parent as it self
                    $smStockParent = $sm->sm_stock;
                    $smReff = $sm->sm_reff;
                    // get stock-mutation's child
                    $select_sm = DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $sm->sm_stock)
                        ->where('sm_nota', '=', $sm->sm_reff)
                        ->first();

                    $use = $select_sm->sm_use - $sm->sm_qty;
                    $sisa = $select_sm->sm_residue + $sm->sm_qty;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $select_sm->sm_stock)
                        ->where('sm_nota', '=', $select_sm->sm_nota)
                        ->update([
                            'sm_use' => $use,
                            'sm_residue' => $sisa
                        ]);

                    DB::table('d_stock')
                        ->where('s_id', '=', $select_sm->sm_stock)
                        ->update([
                            's_qty' => $sisa
                        ]);
                }

                // rollBack stock-mutation-detail and stock detail
                if ($mutcat == 14) {
                    // just for mutation-out
                    $rollStatus = 'RollMutOut';
                } else {
                    // for mutation-in and mutation-out
                    $rollStatus = 'RollMutInOut';
                }
                $rollbackStockMutDist = self::rollbackStockMutDistDetail(
                    $smStockParent,
                    $smReff,
                    $sm->sm_stock,
                    $sm->sm_detailid,
                    $rollStatus // rollback stock detail out
                );
                if ($rollbackStockMutDist !== 'success') {
                    throw new \Exception("Mut->rollback kons-in: " . $rollbackStockMutDist->getData()->message);
                }

                DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $sm->sm_stock)
                    ->where('sm_nota', '=', $sm->sm_nota)
                    ->delete();
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    static function opname(
        $mutcat, // mutation categori
        $comp, // item-owner
        $position, // item-position
        $item, // item id
        $qtysistem, // qty in system
        $qtyreal, // qty in real
        $sisa, // difference between qty-system with qty-real
        $nota, // nota
        $reff, // nota refference
        $listPC, // list production-code
        $listQtyPC // list qty each production-code
    )
    {
        // dd($mutcat,$comp,$position,$item,$qtysistem,$qtyreal,$sisa,$nota,$reff,$listPC,$listQtyPC);
        DB::beginTransaction();
        try {

            $mutcatmasuk = DB::table('m_mutcat')->where('m_name', 'Barang Masuk Dari Opname')->first();
            $mutcatkeluar = DB::table('m_mutcat')->where('m_name', 'Barang Keluar Dari Opname')->first();

            $qtyreal = (int)$qtyreal;
            $qtysistem = (int)$qtysistem;
            $sisa = (int)$sisa;

            $sekarang = Carbon::now('Asia/Jakarta');

            $datamutcat = DB::table('m_mutcat')->where('m_status', 'M')->get();
            for ($i = 0; $i < count($datamutcat); $i++) {
                $tmp[] = $datamutcat[$i]->m_id;
            }
            $mutasi = DB::table('d_stock')
                ->join('d_stock_mutation', 's_id', '=', 'sm_stock')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_comp', '=', $comp)
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $tmp)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
                ->get();

            // if qty in system is more qty in real
            if ($sisa > 0) {
                //========= mengurangi stock
                for ($i = 0; $i < count($mutasi); $i++) {
                    $continueLoopStock = false;
                    // if sm_sisa from mutasi[i] is sufficient
                    if ($mutasi[$i]->sm_sisa >= $sisa) {
                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->where('sm_detailid', '=', $mutasi[$i]->sm_detailid)
                            ->update([
                                'sm_use' => $mutasi[$i]->sm_use + $sisa,
                                'sm_residue' => $mutasi[$i]->sm_residue - $sisa
                            ]);

                        $getdetailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->max('sm_detailid');

                        $detailid = $getdetailid + 1;
                        $smQty = $sisa;

                        $continueLoopStock = false;
                    } // if sm_sisa from mutasi[i] is in-sufficient
                    elseif ($mutasi[$i]->sm_sisa < $sisa) {
                        $sisa = $sisa - $mutasi[$i]->sm_qty;
                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->where('sm_detailid', '=', $mutasi[$i]->sm_detailid)
                            ->update([
                                'sm_use' => $mutasi[$i]->sm_qty,
                                'sm_residue' => 0
                            ]);

                        $getdetailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->max('sm_detailid');

                        $detailid = $getdetailid + 1;
                        $smQty = $mutasi[$i]->sm_qty;

                        $continueLoopStock = true;
                    }
                    // insert stock-mutation-out
                    DB::table('d_stock_mutation')
                        ->insert([
                            'sm_stock' => $mutasi[$i]->sm_stock,
                            'sm_detailid' => $detailid,
                            'sm_date' => $sekarang,
                            'sm_mutcat' => $mutcat,
                            'sm_qty' => $smQty,
                            'sm_use' => 0,
                            'sm_residue' => 0,
                            'sm_hpp' => $mutasi[$i]->sm_hpp,
                            'sm_sell' => $mutasi[$i]->sm_sell,
                            'sm_nota' => $nota,
                            'sm_reff' => $mutasi[$i]->sm_nota,
                            'sm_user' => Auth::user()->u_id
                        ]);

                    // update stock
                    DB::table('d_stock')
                        ->where('s_id', '=', $mutasi[$i]->s_id)
                        ->update([
                            's_qty' => DB::raw('s_qty - ' . $smQty)
                        ]);

                    // insert new stock-mutation-detail production-code for mutcat-out
                    $insertSMProdCode = self::insertStockMutationDt($mutasi[$i]->sm_stock, $detailid, $listPC, $listQtyPC);
                    if ($insertSMProdCode !== 'success') {
                        throw new Exception($insertSMProdCode->getData()->message);
                    }
                    // insert new stock-detail production-code for mutation-out
                    $stockParentId = $mutasi[$i]->sm_stock;
                    $stockChildId = null;
                    $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }

                    if ($continueLoopStock == false) {
                        $sisa = 0;
                        break;
                    }
                }
            } // if qty in system is less than qty in real
            elseif ($sisa < 0) {
                //======== menambah stock
                $sisa = abs($sisa);
                $counter = count($mutasi) - 1;

                $getdetailid = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $mutasi[0]->sm_stock)
                    ->max('sm_detailid');

                $detailid = $getdetailid + 1;
                // insert stock-mutation in
                DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock' => $mutasi[0]->sm_stock,
                        'sm_detailid' => $detailid,
                        'sm_date' => $sekarang,
                        'sm_mutcat' => $mutcat,
                        'sm_qty' => $sisa,
                        'sm_use' => 0,
                        'sm_residue' => 0,
                        'sm_hpp' => $mutasi[$counter]->sm_hpp,
                        'sm_sell' => $mutasi[$counter]->sm_sell,
                        'sm_nota' => $nota,
                        'sm_reff' => $mutasi[$counter]->sm_nota,
                        'sm_user' => Auth::user()->u_id
                    ]);

                DB::table('d_stock')
                    ->where('s_id', '=', $mutasi[0]->s_id)
                    ->update([
                        's_qty' => DB::raw('(s_qty + ' . $sisa . ')')
                    ]);

                // insert new stock-mutation-detail production-code for mutcat-in
                $insertSMProdCode = self::insertStockMutationDt($mutasi[$counter]->sm_stock, $detailid, $listPC, $listQtyPC);
                if ($insertSMProdCode !== 'success') {
                    throw new Exception($insertSMProdCode->getData()->message);
                }
                
                // insert new stock-detail production-code for mutation-in
                $stockParentId = null;
                $stockChildId = $mutasi[$counter]->sm_stock;
                $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC, $listQtyPC);
                if ($insertStockDt !== 'success') {
                    throw new Exception($insertStockDt->getData()->message);
                }
            } // if qty in system is equal with qty in real
            else {
                // get qty prod-code in stock-dt
                $stockDT = DB::table('d_stockdt')
                    ->join('d_stock', 'sd_stock', 's_id')
                    ->where('s_comp', '=', $comp)
                    ->where('s_position', '=', $position)
                    ->where('s_item', '=', $item)
                    ->where('s_status', '=', 'ON DESTINATION')
                    ->where('s_condition', '=', 'FINE')->get();

                DB::table('d_stockdt')->where('sd_stock', '=', $stockDT[0]->s_id)->delete();

                $detailid = DB::table('d_stockdt')->where('sd_stock', '=', $stockDT[0]->s_id)->max('sd_detailid');

                for ($i = 0; $i < count($listPC); $i++) {
                    $detailid = $detailid + 1;
                    DB::table('d_stockdt')->where('sd_stock', '=', $stockDT[0]->s_id)->insert([
                        'sd_stock' => $stockDT[0]->s_id,
                        'sd_detailid' => $detailid,
                        'sd_code' => $listPC[$i],
                        'sd_qty' => $listQtyPC[$i]
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    // mutation for sales 'out'
    static function salesOut(
        $from, // from
        $to, // destination
        $item, // item id
        $qty, // qty item
        $nota, // nota sales
        $listPC, // list production-code
        $listQtyPC, // list qty of production-code
        $listUnitPC, // list unit of production-code
        $sellPrice = null, // sellprice
        $mutcat = null // mutation category
    )
    {
        DB::beginTransaction();
        try {
            // dd($from, $to, $item, $qty, $nota, $listPC, $listQtyPC, $listUnitPC, $sellPrice, $mutcat);

            // qty item that is sending out to branch
            $qty = (int)$qty;
            // date now
            $dateNow = Carbon::now();
            // get list of 'in' mutcat-list
            $inMutcatList = m_mutcat::where('m_status', 'M')
                ->select('m_id')
                ->get();
            for ($i = 0; $i < count($inMutcatList); $i++) {
                $tmp[] = $inMutcatList[$i]->m_id;
            }
            $inMutcatList = $tmp;

            // get stock and stock-mutation parent
            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_position', '=', $from)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $inMutcatList)
                ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
                ->get();

            $permintaan = $qty;
            // set callback if stock-item-parent is empty
            if (count($stock) <= 0) {
                $itemx = m_item::where('i_id', $item)->select('i_name')->first();
                throw new Exception("Stock " . $itemx->i_name . " kosong !");
            }

            // update qty in stock-item-parent
            $stockParent = d_stock::where('s_id', $stock[0]->s_id)
                ->first();
            $stockParent->s_qty = $stockParent->s_qty - $permintaan;
            $stockParent->save();

            // set list of sellPrice and hpp. used for salesIn
            $listSellPrice = array();
            $listHPP = array();
            $listSmQty = array();
            // set list of pc and qty-pc after insert stock-mutaiton 'out'
            $listPCReturn = array();
            $listQtyPCReturn = array();

            // set stock-mutation record
            for ($j = 0; $j < count($stock); $j++) {
                $continueLoopStock = false;
                $detailid = d_stock_mutation::where('sm_stock', $stockParent->s_id)
                        ->max('sm_detailid') + 1;

                // insert new stock mutation
                // use 'all' qty from current stock-mutation
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {
                    // update sm_use and sm_residue in parent
                    d_stock_mutation::where('sm_stock', $stockParent->s_id)
                        ->where('sm_detailid', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    // get prodCode
                    $prodCode = d_stockmutationdt::where('smd_stock', $stockParent->s_id)
                        ->where('smd_stockmutation', $stock[$j]->sm_detailid)
                        ->get();
                    $listSmQtyPC = array();
                    $listSmPC = array();
                    // update qty-request each production-code
                    foreach ($prodCode as $key => $pcode) {
                        if (in_array($pcode->smd_productioncode, $listPC)) {
                            $idx = array_search($pcode->smd_productioncode, $listPC);
                            $listQtyPC[$idx] = $listQtyPC[$idx] - $pcode->smd_qty;
                            array_push($listSmPC, $listPC[$idx]);
                            array_push($listSmQtyPC, $pcode->smd_qty);
                        }
                    }

                    // update qty of request (how much qty used by selected stock-mutation)
                    $permintaan = $permintaan - $stock[$j]->sm_sisa;
                    $smQty = $stock[$j]->sm_sisa;

                    $continueLoopStock = true;
                } // use 'some' qty from current stock-mutation
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    d_stock_mutation::where('sm_stock', $stockParent->s_id)
                        ->where('sm_detailid', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_use + $permintaan,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);

                    $listSmPC = $listPC;
                    $listSmQtyPC = $listQtyPC;
                    $smQty = $permintaan;

                    $continueLoopStock = false;
                }
                // set value for new stock-mutation
                $val_stockmut = null;
                $val_stockmut = [
                    'sm_stock' => $stockParent->s_id,
                    'sm_detailid' => $detailid,
                    'sm_date' => $dateNow,
                    'sm_mutcat' => $mutcat,
                    'sm_qty' => $smQty,
                    'sm_use' => 0,
                    'sm_residue' => 0,
                    'sm_hpp' => $stock[$j]->sm_hpp,
                ];
                // set sell-price is ther is any custom sell-price
                if (!is_null($sellPrice)) {
                    $val_stockmut += [
                        'sm_sell' => $sellPrice,
                    ];
                } else {
                    $val_stockmut += [
                        'sm_sell' => $stock[$j]->sm_sell,
                    ];
                }
                $val_stockmut += [
                    'sm_nota' => $nota,
                    'sm_reff' => $stock[$j]->sm_nota,
                    'sm_user' => Auth::user()->u_id
                ];
                // insert new stock-mutation
                d_stock_mutation::insert($val_stockmut);

                // insert new stock-mutation-detail (production-code) for mutcat-out
                $insertSMProdCode = self::insertStockMutationDt($stockParent->s_id, $detailid, $listSmPC, $listSmQtyPC);
                if ($insertSMProdCode !== 'success') {
                    throw new Exception($insertSMProdCode->getData()->message);
                }

                // fill list of sellPrice and listhpp
                array_push($listSellPrice, (int)$val_stockmut['sm_sell']);
                array_push($listHPP, (int)$val_stockmut['sm_hpp']);
                array_push($listSmQty, (int)$val_stockmut['sm_qty']);
                // set list of list-production code used for sales-in
                array_push($listPCReturn, $listSmPC);
                array_push($listQtyPCReturn, $listSmQtyPC);

                // insert stock-detail is executed inside sales-in

                if ($continueLoopStock == false) {
                    $permintaan = 0;
                    break;
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'stockParentId' => $stockParent->s_id,
                'listSellPrice' => $listSellPrice,
                'listHPP' => $listHPP,
                'listSmQty' => $listSmQty,
                'listPCReturn' => $listPCReturn,
                'listQtyPCReturn' => $listQtyPCReturn,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // mutation for sales 'in'
    static function salesIn(
        // $from, // from
        $to, // destination (item owner and item position)
        $item, // item id
        $nota, // nota sales
        $listPC, // list of list production-code (based on how many smQty used / each smQty has a list of prod-code)
        $listQtyPC, // list of list qty of production-code
        $listUnitPC, // list  unit of production-code (unused)
        $listSellPrice, // list of sellprice
        $listHPP, // list of hpp
        $listSmQty, // lsit of sm-qty (it got from salesOut, each qty used from different stock-mutation)
        $mutcat, // mutation category
        $stockParentId, // stock parent id
        $status = 'ON GOING', // items status in stock
        $condition = 'FINE' // item condition in stock
    )
    {
        DB::beginTransaction();
        try {
            // dd($to, $item, $nota, $listPC, $listQtyPC, $listUnitPC, $listSellPrice, $listHPP, $listSmQty, $mutcat, $stockParentId, $status, $condition);
            // insert stock-mutation 'in'
            $dateNow = Carbon::now();
            $mutcat = $mutcat;
            $comp = $to;
            $position = $to;
            $itemId = $item;
            $status = $status;
            $condition = $condition;
            $nota = $nota;
            $reff = $nota;
            $totalQty = array_sum($listSmQty);

            // get stock-item in destination-position with 'On Going' status
            $stockId = d_stock::select('s_id')
                ->where('s_comp', '=', $comp)
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $itemId)
                ->where('s_status', '=', $status)
                ->where('s_condition', '=', $condition)
                ->first();

            // if stock with 'On Going' status is not-found
            // insert new stock with 'On Going' status
            if (is_null($stockId)) {
                // insert new stock-item
                $stockId = d_stock::max('s_id') + 1;
                $stock = array(
                    's_id' => $stockId,
                    's_comp' => $comp,
                    's_position' => $position,
                    's_item' => $item,
                    's_qty' => $totalQty,
                    's_status' => $status,
                    's_condition' => $condition,
                    's_created_at' => $dateNow,
                    's_updated_at' => $dateNow
                );
                d_stock::insert($stock);
            }
            // if stock with 'On Going' status is found
            // update selected stock with 'On Going' status
            else {
                $stockId = $stockId->s_id;
                $stock = d_stock::where('s_id', '=', $stockId)
                    ->first();
                // update qty stock-item where mutcat-in
                $qtyStockAkhir = $stock->s_qty + $totalQty;
                $update = array('s_qty' => $qtyStockAkhir);
                d_stock::where('s_id', '=', $stockId)->update($update);

            }

            // insert new mutation with mutcat = pembelian-in
            foreach ($listSmQty as $key => $smQty) {
                $smDetailId = d_stock_mutation::where('sm_stock', '=', $stockId)
                        ->max('sm_detailid') + 1;

                $mutasi = array(
                    'sm_stock' => $stockId,
                    'sm_detailid' => $smDetailId,
                    'sm_date' => $dateNow,
                    'sm_mutcat' => $mutcat,
                    'sm_qty' => $smQty,
                    'sm_use' => 0,
                    'sm_residue' => $smQty,
                    'sm_hpp' => $listHPP[$key],
                    'sm_sell' => $listSellPrice[$key],
                    'sm_nota' => $nota,
                    'sm_reff' => $reff,
                    'sm_user' => Auth::user()->u_id
                );
                d_stock_mutation::insert($mutasi);

                // insert/update stock-mutation-detail production-code for mutcat-in
                $insertSMProdCode = self::insertStockMutationDt($stockId, $smDetailId, $listPC[$key], $listQtyPC[$key]);
                if ($insertSMProdCode !== 'success') {
                    throw new Exception($insertSMProdCode->getData()->message);
                }
                // dd('as');

                // insert/update stock-detail production-code
                $stockParentId = $stockParentId;
                $stockChildId = $stockId;
                $insertStockDt = self::insertStockDetail($stockParentId, $stockChildId, $listPC[$key], $listQtyPC[$key]);
                if ($insertStockDt !== 'success') {
                    throw new Exception($insertStockDt->getData()->message);
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback mutation for salesOut, include rollback mutation-detail and stock detail
    public static function rollbackSalesOut(
        $nota, // nota
        $itemId, // item-id
        $mutcatOut // mutcat-out
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation
            $stockMut = d_stock_mutation::where('sm_nota', '=', $nota)
                ->where('sm_mutcat', $mutcatOut)
                ->whereHas('getStock', function ($query) use ($itemId) {
                    $query->where('s_item', $itemId);
                })
                ->first();
            // get stock-mutation base (mutation 'in' from the first mutation)
            $stockMutBase = d_stock_mutation::where('sm_stock', $stockMut->sm_stock)
                ->where('sm_nota', $stockMut->sm_reff)
                ->first();
            // calculate qty-use and qty-residue in stock-mutation base
            $usedQty = $stockMutBase->sm_use - $stockMut->sm_qty;
            $residueQty = $stockMutBase->sm_residue + $stockMut->sm_qty;
            // update stock-mutation base
            $stockMutBase->sm_use = $usedQty;
            $stockMutBase->sm_residue = $residueQty;
            $stockMutBase->save();
            // update qty stock base
            $stockBase = d_stock::where('s_id', $stockMutBase->sm_stock)->first();
            $stockBase->s_qty = $residueQty;
            $stockBase->save();

            // rollback stock-mutation-detail 'out' and stock-detail 'out'
            $rollbackMutDetailSalesOut = self::rollbackMutDetailSalesOut(
                $stockMut->sm_stock, // stock-mutation id
                $stockMut->sm_detailid // stock-mutation detail-id
            );
            // dd('1', $rollbackMutDetailSalesOut);
            if ($rollbackMutDetailSalesOut->original['status'] != 'success') {
                return $rollbackMutDetailSalesOut;
            }

            // delete current
            $stockMut->delete();

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'rollbackSalesOut -> ' . $e->getMessage()
            ]);
        }
    }

    // rollback mutation for salesIn, include rollback mutation-detail and stock detail
    public static function rollbackSalesIn(
        $nota, // nota
        $itemId, // item-id
        $mutcatIn // mutcat-in
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation
            $stockMut = d_stock_mutation::where('sm_nota', '=', $nota)
                ->where('sm_mutcat', $mutcatIn)
                ->whereHas('getStock', function ($query) use ($itemId) {
                    $query->where('s_item', $itemId);
                })
                ->first();
            // get stock
            $stockItem = d_stock::where('s_id', '=', $stockMut->sm_stock)
                ->first();
            // calculate new qty in stock
            $returnQty = $stockItem->s_qty - $stockMut->sm_qty;

            // if new-qty is 0, delete stock-item
            if ($returnQty <= 0) {
                $stockItem->delete();
            } // if new-qty is > 0, update qty in stock-item
            elseif ($returnQty > 0) {
                $stockItem->s_qty = $returnQty;
                $stockItem->save();
            }

            // rollback stock-mutation-detail 'in' and stock-detail 'in'
            $rollbackMutDetailSalesIn = self::rollbackMutDetailSalesIn(
                $stockMut->sm_stock, // stock-mutation id
                $stockMut->sm_detailid // stock-mutation detail-id
            );
            if ($rollbackMutDetailSalesIn->original['status'] != 'success') {
                return $rollbackMutDetailSalesIn;
            }

            // delete stock-mutation
            $stockMut->delete();

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'message' => 'rollbackSalesIn -> ' . $e->getMessage()
            ]);
        }
    }

    // rollback mutation-detail for salesIn
    static function rollbackMutDetailSalesIn(
        $smStock, // stock-mutation-in id
        $smStockDetailId // stock-mutation-in detail-id
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation-detail mutation-in
            $smDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->get();

            // rollback stock-detail 'in'
            foreach ($smDetail as $key => $val) {
                $rollbackStockDetailIn = self::rollbackStockDetailIn(
                    $val->smd_stock, // stock-mutation id
                    $val->smd_productioncode, // production-code
                    $val->smd_qty // qty of production-code
                );
                if ($rollbackStockDetailIn->original['status'] != 'success') {
                    return $rollbackStockDetailIn;
                }
            }

            $smDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => 'rollbackMutDetailSalesIn -> ' . $e->getMessage()
            ]);
        }
    }

    // rollback mutation detail for salesOut
    static function rollbackMutDetailSalesOut(
        $smStock, // stock-mutation-out id
        $smStockDetailId // stock-mutation-out detail-id
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation-detail mutation-in
            $smDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->get();

            // rollback stock-detail 'out'
            foreach ($smDetail as $key => $val) {
                $rollbackStockDetailOut = self::rollbackStockDetailOut(
                    $val->smd_stock, // stock-mutation id
                    $val->smd_productioncode, // production-code
                    $val->smd_qty // qty of production-code
                );
                if ($rollbackStockDetailOut->original['status'] != 'success') {
                    return $rollbackStockDetailOut;
                }
            }

            // delete stock-mutation-detail
            $smDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => 'rollbackMutDetailSalesOut -> ' . $e->getMessage()
            ]);
        }
    }

    // rollback stock detail 'in'
    static function rollbackStockDetailIn(
        $smStock, // stock-mutation 'in' id
        $prodCode, // a production-code
        $prodCodeQty // qty of production-code
    )
    {
        DB::beginTransaction();
        try {
            // get stcok-detail for mutation-in
            $stockDt = d_stockdt::where('sd_stock', $smStock)
                ->where('sd_code', $prodCode)
                ->first();
            // update stock-item-detail
            $stockDt->sd_qty -= $prodCodeQty;
            $stockDt->save();
            // delete stock-detail child if has zero qty
            if ($stockDt->sd_qty <= 0) {
                $stockDt->delete();
            }

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback stock detail 'out'
    static function rollbackStockDetailOut(
        $smStock, // stock-mutation 'out' id
        $prodCode, // a production-code
        $prodCodeQty // qty of production-code
    )
    {
        DB::beginTransaction();
        try {
            // get stock-detail for mutation-out
            $stockDt = d_stockdt::where('sd_stock', $smStock)
                ->where('sd_code', $prodCode)
                ->first();

            // rollback stock-detail for mutation-out
            if (is_null($stockDt)) {
                (d_stockdt::where('sd_stock', $smStock)->max('sd_detailid')) ? $detailId = d_stockdt::where('sd_stock', $smStock)->max('sd_detailid') + 1 : $detailId = 1;
                $newStockDt = new d_stockdt;
                $newStockDt->sd_stock = $smStock;
                $newStockDt->sd_detailid = $detailId;
                $newStockDt->sd_code = $prodCode;
                $newStockDt->sd_qty = $prodCodeQty;
                $newStockDt->save();
            } else {
                // update stock-item-detail parent
                $stockDt->sd_qty += $prodCodeQty;
                $stockDt->save();
            }

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => 'rollbackStockDetailOut -> ' . $e->getMessage()
            ]);
        }

    }

    // insert new record in stock-mutation-dt
    public static function insertStockMutationDt(
        $stockId, //id Stock Mutation
        $smDetailId, // detail id Stock Mutation
        $listPC, // List Production Code
        $listQtyPC // List Qty Production Code
    )
    {
        // dd($stockId, $smDetailId, $listPC, $listQtyPC);
        DB::beginTransaction();
        try {
            // insert new mutation-detail (filled with production-code of the products)
            foreach ($listPC as $key => $val) {
                $val = strtoupper($val);
                $listQtyPC[$key] = (int)$listQtyPC[$key];
                // skip inserting when val is null or qty-pc is 0
                if ($val == '' || $val == null || $listQtyPC[$key] == 0) {
                    continue;
                }
                // get stock-mutation-dt
                $detailidPC = DB::table('d_stockmutationdt')
                        ->where('smd_stock', $stockId)
                        ->where('smd_stockmutation', $smDetailId)
                        ->max('smd_detailid') + 1;
                // insert new stock-mutation-dt
                $mutationDt = array(
                    'smd_stock' => $stockId,
                    'smd_stockmutation' => $smDetailId,
                    'smd_detailid' => $detailidPC,
                    'smd_productioncode' => $val,
                    'smd_qty' => $listQtyPC[$key]
                    // 'smd_unit' => $listUnitPC[$key]
                );
                d_stockmutationdt::insert($mutationDt);
            }
            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // insert or update stock-item-detail child also update stock-item-detail parent
    public static function insertStockDetail(
        $stockParentId = null, // not-null -> update stock parent (mut out)
        $stockChildId = null, // not-null -> update stock child (mut in)
        $listPC = null,
        $listQtyPC = null
    )
    {
        DB::beginTransaction();
        try {
            foreach ($listPC as $key => $prodCode) {
                $prodCode = strtoupper($prodCode);
                $qtyProdCode = (int)$listQtyPC[$key];
                // skip inserting when val is null or qty-pc is 0
                if ($prodCode == '' || $prodCode == null || $qtyProdCode == 0) {
                    continue;
                }

                // update stock-detail-parent
                if (!is_null($stockParentId)) {
                    $stockDtParent = d_stockdt::where('sd_stock', $stockParentId)
                        ->where('sd_code', $prodCode)
                        ->first();
                    if (is_null($stockDtParent)) {
                        // return error maybe ??
                        // oohh, its already validated before running here. check validateProductionCode !
                    } else {
                        $stockDtParent->sd_qty -= $qtyProdCode;
                        $stockDtParent->save();
                    }
                }
                // insert/update stock to child (mutcat 'in')
                if (!is_null($stockChildId)) {
                    // get stock-detail
                    $stockDtChild = d_stockdt::where('sd_stock', $stockChildId)
                        ->where('sd_code', $prodCode)
                        ->first();

                    // set stock-dt detailid
                    $detailidSD = d_stockdt::where('sd_stock', $stockChildId)
                            ->max('sd_detailid') + 1;

                    // insert new stock-detail
                    if (is_null($stockDtChild)) {
                        $stockDt = new d_stockdt;
                        $stockDt->sd_stock = $stockChildId;
                        $stockDt->sd_detailid = $detailidSD;
                        $stockDt->sd_code = $prodCode;
                        $stockDt->sd_qty = $qtyProdCode;
                        $stockDt->save();
                    } // update stock-detail-child
                    else {
                        $stockDtChild->sd_qty += $qtyProdCode;
                        $stockDtChild->update();
                    }
                }
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback stock-item and stock-mutation (just 'in')
    static function rollbackStockMutationIn(
        $item, // item-id
        $nota, // nota
        $mutcatIn, // mutcat-in
        $mutcatOut // mutcat-out
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation 'in'
            $stockMutationIn = d_stock_mutation::where('sm_nota', '=', $nota)
                ->where('sm_mutcat', $mutcatIn)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->first();

            // get stock-mutation 'out'
            $stockMutationOut = d_stock_mutation::where('sm_nota', '=', $nota)
                ->where('sm_mutcat', $mutcatOut)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->first();
            $smStockParent = $stockMutationOut->sm_stock;
            $smReff = $stockMutationOut->sm_reff;

            // get stock-item based on stock-mutation-in
            $stockItem = d_stock::where('s_id', $stockMutationIn->sm_stock)
                ->first();
            $returnQty = $stockItem->s_qty - $stockMutationIn->sm_qty;
            // if new-qty is 0, delete stock-item
            if ($returnQty <= 0) {
                $stockItem->delete();
            } // if new-qty is > 0, update qty in stock-item
            elseif ($returnQty > 0) {
                $stockItem->s_qty = $returnQty;
                $stockItem->save();
            }
            // for mutation-in and out
            $rollStatus = 'RollMutInOut';
            // rollBack stock-mutation-detail
            $rollbackStockMutDist = self::rollbackStockMutDetail(
                $smStockParent,
                $smReff,
                $stockMutationIn->sm_stock,
                $stockMutationIn->sm_detailid,
                $rollStatus // rollback stock detail either in and out
            );
            if ($rollbackStockMutDist->original['status'] !== 'success') {
                throw new \Exception("Mut->rollback SMD: " . $rollbackStockMutDist->getData()->message);
            }
            // delete stock-mutation 'in'
            $stockMutationIn->delete();

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback stock-item and stock-mutation
    static function rollbackStockMutDist($nota, $item, $mutcat = null)
    {
        DB::beginTransaction();
        try {
            (is_null($mutcat)) ? $mutcat = null : $mutcat = $mutcat;

            // get stock-mutation with selected-item
            $stockMutations = d_stock_mutation::where('sm_nota', '=', $nota)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })->get();

            foreach ($stockMutations as $key => $sm) {
                // rollback stock-item and stock-mutation
                // if : mutcat is 'distribution in' (18) / stock-child
                // if : mutcat is 'pembelian in' (20) / stock-child
                if ($sm->sm_mutcat == 18 || $sm->sm_mutcat == 20) {
                    // get stock-mutation parent
                    foreach ($stockMutations as $idx => $sm) {
                        // parent for penjualan-agent out
                        if ($mutcat == 5) {
                            if ($sm->sm_mutcat == 5) {
                                $smStockParent = $sm->sm_stock;
                                $smReff = $sm->sm_reff;
                            }
                            continue;
                        }
                        // parent for distribution-out
                        if ($sm->sm_mutcat == 19) {
                            $smStockParent = $sm->sm_stock;
                            $smReff = $sm->sm_reff;
                        }
                    }
                    // get stock-item based on sm_stock
                    $stockItem = d_stock::where('s_id', $sm->sm_stock)
                        ->first();
                    $returnQty = $stockItem->s_qty - $sm->sm_qty;
                    // if new-qty is 0, delete stock-item
                    if ($returnQty <= 0) {
                        $stockItem->delete();
                    } // if new-qty is > 0, update qty in stock-item
                    elseif ($returnQty > 0) {
                        $stockItem->s_qty = $returnQty;
                        $stockItem->save();
                    }
                }
                // else if : mutcat is 'distribution out' (19) / stock-parent
                // else if : mutcat is 'penjualan-ke-agen out' (5) / stock-parent
                else if ($sm->sm_mutcat == 19 || $sm->sm_mutcat == 5) {
                    // set stock parent as it self
                    $smStockParent = $sm->sm_stock;
                    $smReff = $sm->sm_reff;
                    // get stock-mutation parent
                    $smParents = d_stock_mutation::where('sm_nota', $sm->sm_reff)
                        ->where('sm_stock', $sm->sm_stock)
                        ->first();
                    // update residue and use in stock-mutation parent
                    $smParents->sm_use -= $sm->sm_qty;
                    $smParents->sm_residue += $sm->sm_qty;
                    $smParents->save();
                    // get stock-item-parent
                    $stockItemParent = d_stock::where('s_id', $smParents->sm_stock)
                        ->first();
                    // update qty in stock-item
                    $stockItemParent->s_qty += $sm->sm_qty;
                    $stockItemParent->save();
                }

                // for mutation-in and out
                $rollStatus = 'RollMutInOut';
                // rollBack stock-mutation-detail
                $rollbackStockMutDist = self::rollbackStockMutDistDetail(
                    $smStockParent,
                    $smReff,
                    $sm->sm_stock,
                    $sm->sm_detailid,
                    $rollStatus // rollback stock detail either in and out
                );
                if ($rollbackStockMutDist !== 'success') {
                    throw new \Exception("Mut->rollback SMD: " . $rollbackStockMutDist->getData()->message);
                }
                $sm->delete();
            }

            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback stock-mutation-detail
    public static function rollbackStockMutDistDetail(
        $smStockParent, // stock id mutation out
        $smReff, // nota mutation out
        $smStock, //
        $smStockDetailId, //
        $rollStatus = null // default:null, status to set rollback stockdetail in and out
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation-detail
            $smItemsDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->get();

            // rollback stock-item-detail and delete current stock-mutation detail
            foreach ($smItemsDetail as $key => $smDetail) {
                // rollBack stock detail either mutation-in and mutation-out
                if ($rollStatus == 'RollMutInOut') {
                    // rollback stock-item-detail
                    $rollbackStockDetail = self::rollbackStockDetail(
                        $smStockParent, // stock id mutation-out
                        $smReff, // nota
                        $smDetail->smd_productioncode, // a production-code
                        $smDetail->smd_qty, // qty production code
                        $smDetail->smd_stock // stock id mutation in
                    );
                } // rollBack stock detail just mutation out
                else if ($rollStatus == 'RollMutOut') {
                    // rollback stock-item-detail
                    $rollbackStockDetail = self::rollbackStockDetail(
                        $smStockParent, // stock id mutation-out
                        $smReff, // nota
                        $smDetail->smd_productioncode, // a production-code
                        $smDetail->smd_qty, // qty production code
                        null // stock id mutation in
                    );
                }
                if ($rollbackStockDetail !== 'success') {
                    throw new \Exception($rollbackStockDetail->getData()->message);
                }
                // delete current stock-mutation-detail
                $smDetail->delete();
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function rollbackStockMutDetail(
        $smStockParent, // stock-mutation-out id
        $smReff, // nota mutation-out
        $smStock, // stock-mutation-in id
        $smStockDetailId, // stock-mutation-in detail-id
        $rollStatus = null, // rollback stockdetail in and out
        $smStockParentDetailId = null // // stock-mutation-out detail-id
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation-detail mutation-in
            $smItemsDetail = d_stockmutationdt::where('smd_stock', $smStock)
                ->where('smd_stockmutation', $smStockDetailId)
                ->get();

            // rollback stock-item-detail and delete current stock-mutation detail
            foreach ($smItemsDetail as $key => $smDetail) {
                // rollBack stock detail either mutation-in and mutation-out
                if ($rollStatus == 'RollMutInOut') {
                    // rollback stock-item-detail
                    $rollbackStockDetail = self::rollbackStockDetail(
                        $smStockParent, // stock id mutation-out
                        $smReff, // nota
                        $smDetail->smd_productioncode, // a production-code
                        $smDetail->smd_qty, // qty production code
                        $smDetail->smd_stock // stock id mutation in
                    );
                } // rollBack stock detail just mutation out
                else if ($rollStatus == 'RollMutOut') {
                    // rollback stock-item-detail
                    $rollbackStockDetail = self::rollbackStockDetail(
                        $smStockParent, // stock id mutation-out
                        $smReff, // nota
                        $smDetail->smd_productioncode, // a production-code
                        $smDetail->smd_qty, // qty production code
                        null // stock id mutation in
                    );
                }
                if ($rollbackStockDetail !== 'success') {
                    throw new \Exception($rollbackStockDetail->getData()->message);
                }
                // delete current stock-mutation-detail
                $smDetail->delete();
            }

            // get stock-mutation-detail for mutation-out
            $smItemsDetail = d_stockmutationdt::where('smd_stock', $smStockParent)
                ->where('smd_stockmutation', $smStockDetailId)
                ->get();
            // delete

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // rollback/delete stock-item-detail child and update stock-item-detail parent
    public static function rollbackStockDetail(
        $smStockParent, // stock id mutation-out
        $smReff, // nota mutation-out
        $prodCode, // production code
        $prodCodeQty, // qty
        $stockDtId = null // stock id mutation-in, if null -> run rollback-mut-out only
    )
    {
        DB::beginTransaction();
        try {
            // get stock-mutation-parent
            $smParent = d_stock_mutation::where('sm_nota', $smReff)
                ->where('sm_stock', $smStockParent)
                ->first();

            // rollback stock-detail for mutation-in
            if (!is_null($stockDtId)) {
                // get stcok-detail for mutation-in
                $stockItemDt = d_stockdt::where('sd_stock', $stockDtId)
                    ->where('sd_code', $prodCode)
                    ->first();
                // update stock-item-detail
                $stockItemDt->sd_qty -= $prodCodeQty;
                $stockItemDt->save();
                // delete stock-detail child if has zero qty
                if ($stockItemDt->sd_qty <= 0) {
                    $stockItemDt->delete();
                }
            }

            // get stock-detail for mutation-out
            $stockItemDtParent = d_stockdt::where('sd_stock', $smParent->sm_stock)
                ->where('sd_code', $prodCode)
                ->first();

            // rollback stock-detail for mutation-out
            if (is_null($stockItemDtParent)) {
                (d_stockdt::where('sd_stock', $smParent->sm_stock)->max('sd_detailid')) ? $detailId = d_stockdt::where('sd_stock', $smParent->sm_stock)->max('sd_detailid') + 1 : $detailId = 1;
                $newStockDt = new d_stockdt;
                $newStockDt->sd_stock = $smParent->sm_stock;
                $newStockDt->sd_detailid = $detailId;
                $newStockDt->sd_code = $prodCode;
                $newStockDt->sd_qty = $prodCodeQty;
                $newStockDt->save();
            } else {
                // update stock-item-detail parent
                $stockItemDtParent->sd_qty += $prodCodeQty;
                $stockItemDtParent->save();
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // update distrtibution
    static function updateDistribusi($nota, $item, $qty, $listPC, $listQtyPC, $listUnitPC)
    {
        DB::beginTransaction();
        try {
            // get stock-mutation with selected-item
            $smItems = d_stock_mutation::where('sm_nota', '=', $nota)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->get();

            foreach ($smItems as $key => $val) {
                // get stock-item based on sm_stock
                $stockItem = d_stock::where('s_id', $val->sm_stock)
                    ->first();
                // get difference qty
                $diffQty = (int)$qty - $val->sm_qty;

                if ($val->sm_mutcat == 18) {
                    // update current residue of stock-mutation
                    $val->sm_residue = $qty - $val->sm_use;
                    // update qty in stock-item
                    $stockItem->s_qty += $diffQty;
                    $stockItem->save();

                    //-------------------------------//-------------------------------//------------
                    // get stock-mutation parent
                    foreach ($smItems as $key => $sm) {
                        if ($sm->sm_mutcat == 19) {
                            $smStockParent = $sm->sm_stock;
                            $smReff = $sm->sm_reff;
                        }
                    }

                    // for mutation-in and out
                    $rollStatus = 'RollMutInOut';
                    // rollBack stock-mutation-detail and stock-detail (inside rollback-mut func)
                    $rollbackStockMutDist = self::rollbackStockMutDistDetail(
                        $smStockParent,
                        $smReff,
                        $val->sm_stock,
                        $val->sm_detailid,
                        $rollStatus // rollback stock detail either in and out
                    );
                    if ($rollbackStockMutDist !== 'success') {
                        throw new \Exception("Mut->rollback: " . $rollbackStockMutDist->getData()->message);
                    }

                    // insert new stock-mutation-detail production-code
                    $insertSMProdCode = self::insertStockMutationDt(
                        $val->sm_stock,
                        $val->sm_detailid,
                        $listPC,
                        $listQtyPC
                    );
                    if ($insertSMProdCode !== 'success') {
                        throw new Exception("Mut->insert SMDT: " . $insertSMProdCode->getData()->message);
                    }

                    // insert new stock-detail production-code
                    $stockParentId = $smStockParent;
                    $stockChildId = $val->sm_stock;
                    $insertStockDt = self::insertStockDetail(
                        $stockParentId,
                        $stockChildId,
                        $listPC,
                        $listQtyPC
                    );
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }
                    //-------------------------------//-------------------------------//------------

                } else if ($val->sm_mutcat == 19) {
                    // get stock-mutation parent
                    $smParents = d_stock_mutation::where('sm_nota', $val->sm_reff)
                        ->where('sm_stock', $val->sm_stock)
                        ->first();
                    // update residue and use in stock-mutation parent
                    $smParents->sm_use = $smParents->sm_use + $diffQty;
                    $smParents->sm_residue = $smParents->sm_qty - $smParents->sm_use;
                    $smParents->save();

                    // get stock-item
                    $stockItem = d_stock::where('s_id', $val->sm_stock)
                        ->first();

                    // for mutation-in and out
                    $rollStatus = 'RollMutInOut';
                    // rollBack stock-mutation-detail and stock-detail (inside rollback-mut func)
                    $rollbackStockMutDist = self::rollbackStockMutDistDetail(
                        $stockItem->sm_stock, // sm-stock
                        $stockItem->sm_nota, // nota
                        $val->sm_stock, // sm-stock
                        $val->sm_detailid, // sm-stock-detailid
                        $rollStatus // rollback stock detail either in and out
                    );
                    if ($rollbackStockMutDist !== 'success') {
                        throw new \Exception("Mut->rollback: " . $rollbackStockMutDist->getData()->message);
                    }

                    // update qty in stock-item
                    $stockItem->s_qty -= $diffQty;
                    $stockItem->save();
                }

                // update current stock-mutation
                $val->sm_qty = (int)$qty;
                $val->save();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }

    // receive distribution items
    static function confirmDistribution(
        $itemOwner, // item owner
        $to, // destination
        $item, // item-id
        $nota, // nota distribution
        $mutcatIn, // mutation 'in'
        $mutcatOut // mutation 'out'
    )
    {
        DB::beginTransaction();
        try {
            // date now
            $dateNow = Carbon::now();
            // get stock-selected-item with 'On Going' status
            $stockBefore = d_stock::where('s_comp', $itemOwner)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON GOING')
                ->where('s_condition', 'FINE')
                ->with(['getMutation' => function ($query) use ($nota) {
                    $query->where('sm_nota', $nota);
                }])
                ->first();
            // get stock-mutation-dt by $stockBefore
            $stockMutationDt = d_stockmutationdt::where('smd_stock', $stockBefore->getMutation[0]->sm_stock)
                ->where('smd_stockmutation', $stockBefore->getMutation[0]->sm_detailid)
                ->get();

            $listPC = array();
            $listQtyPC = array();
            $listUnitPC = array();
            // get list production-code and list each qty
            foreach ($stockMutationDt as $key => $val) {
                array_push($listPC, $val->smd_productioncode);
                array_push($listQtyPC, $val->smd_qty);
                array_push($listUnitPC, $val->smd_unit);
            }

            // get stock-mutation parent (mutation out)
            $smParent = d_stock_mutation::where('sm_nota', $nota)
                ->where('sm_mutcat', $mutcatOut)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->first();
            $smStockParent = $smParent->sm_stock;
            $smReff = $smParent->sm_reff;
            // // get stock-mutation parent id and reff
            // foreach ($smParent as $key => $sm) {
            //     // parent for penjualan-agent out
            //     if ($mutcat == 5) {
            //         if ($sm->sm_mutcat == 5) {
            //             $smStockParent = $sm->sm_stock;
            //             $smReff        = $sm->sm_reff;
            //         }
            //         continue;
            //     }
            //     if ($sm->sm_mutcat == 19) {
            //         $smStockParent = $sm->sm_stock;
            //         $smReff        = $sm->sm_reff;
            //     }
            // }

            // get stock-selected-item with 'On Destination' status (stockAccepted)
            $stockAccepted = d_stock::where('s_comp', $itemOwner)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->first();

            // get qty from stock-mutation
            $qtyFromMutation = $stockBefore->getMutation[0]->sm_qty;

            // if exist stock-Accepted, update qty
            if ($stockAccepted != null) {
                $stockId = $stockAccepted->s_id;
                // add qty from $stockBefore to stockAccepted
                $stockAccepted->s_qty = $stockAccepted->s_qty + $qtyFromMutation;
                $stockAccepted->save();

                // set stock-mutation detailid
                $smDetailId = d_stock_mutation::where('sm_stock', $stockId)
                        ->max('sm_detailid') + 1;
            } // if not-exist stock-Accepted, insert new stock
            else {
                // insert new stock-item
                $stockId = d_stock::max('s_id') + 1;
                $stock = array(
                    's_id' => $stockId,
                    's_comp' => $stockBefore->s_comp,
                    's_position' => $stockBefore->s_position,
                    's_item' => $item,
                    's_qty' => $qtyFromMutation,
                    's_status' => 'ON DESTINATION',
                    's_condition' => 'FINE',
                    's_created_at' => $dateNow,
                    's_updated_at' => $dateNow
                );
                d_stock::insert($stock);

                // set stock-mutation detailid
                $smDetailId = 1;
            }

            // rollBack stock-mutation 'in', stock-mutation-detail and stock-detail (inside rollback-mut func)
            $rollbackStockMutIn = self::rollbackStockMutationIn(
                $item, // item id
                $nota, // nota
                $mutcatIn, // mutcat-in
                $mutcatOut // mutcat-out
            );
            if ($rollbackStockMutIn->original['status'] !== 'success') {
                throw new \Exception("Acc->rollback: " . $rollbackStockMutIn->getData()->message);
            }

            // duplicate current stockmutation and insert new one with different id
            $mutasi = array(
                'sm_stock' => $stockId,
                'sm_detailid' => $smDetailId,
                'sm_date' => $dateNow,
                'sm_mutcat' => $stockBefore->getMutation[0]->sm_mutcat,
                'sm_qty' => $stockBefore->getMutation[0]->sm_qty,
                'sm_use' => $stockBefore->getMutation[0]->sm_use,
                'sm_residue' => $stockBefore->getMutation[0]->sm_residue,
                'sm_hpp' => $stockBefore->getMutation[0]->sm_hpp,
                'sm_sell' => $stockBefore->getMutation[0]->sm_sell,
                'sm_nota' => $stockBefore->getMutation[0]->sm_nota,
                'sm_reff' => $stockBefore->getMutation[0]->sm_reff,
                'sm_user' => Auth::user()->u_id
            );
            d_stock_mutation::insert($mutasi);

            // insert new stock-mutation-detail
            $insertSMProdCode = self::insertStockMutationDt(
                $stockId,
                $smDetailId,
                $listPC,
                $listQtyPC
            );
            if ($insertSMProdCode !== 'success') {
                throw new \Exception("Acc->Insert mutation DT: " . $insertSMProdCode->getData()->message);
            }
            $stockParentId = $smStockParent;
            $stockChildId = $stockId;
            $insertStockDt = self::insertStockDetail(
                $stockParentId,
                $stockChildId,
                $listPC,
                $listQtyPC
            );
            if ($insertStockDt !== 'success') {
                throw new \Exception("Acc->Insert stock DT: " . $insertStockDt->getData()->message);
            }

            // update stock-before qty
            $stockBefore->s_qty = $stockBefore->s_qty - $qtyFromMutation;
            $stockBefore->save();
            // delete 'On Going' stock if qty is 0
            if ($stockBefore->s_qty == 0) {
                $stockBefore->delete();
            }

            DB::commit();
            return 'success';
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // receive sales items
    static function confirmSales(
        $to, // destination
        $item, // item id
        $nota, // nota
        $mutcatIn = null, // mutcat in
        $mutcatOut = null // mutcat out
    )
    {
        DB::beginTransaction();
        try {
            // date now
            $dateNow = Carbon::now();
            // get stock-selected-item with 'On Going' status
            $stockDestination = d_stock::where('s_comp', $to)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON GOING')
                ->where('s_condition', 'FINE')
                ->with(['getMutation' => function ($query) use ($nota) {
                    $query->where('sm_nota', $nota);
                }])
                ->first();

            // get stock-mutation-dt by stockDestination
            $stockMutationDt = d_stockmutationdt::where('smd_stock', $stockDestination->getMutation[0]->sm_stock)
                ->where('smd_stockmutation', $stockDestination->getMutation[0]->sm_detailid)
                ->get();

            $listPC = array();
            $listQtyPC = array();
            $listUnitPC = array();
            // get list production-code and list qty each production-code
            foreach ($stockMutationDt as $key => $val) {
                array_push($listPC, $val->smd_productioncode);
                array_push($listQtyPC, $val->smd_qty);
                array_push($listUnitPC, $val->smd_unit);
            }

            // get stock-mutation parent (mutation out)
            $smParent = d_stock_mutation::where('sm_nota', $nota)
                ->where('sm_mutcat', $mutcatOut)
                ->whereHas('getStock', function ($query) use ($item) {
                    $query->where('s_item', $item);
                })
                ->first();
            $smStockParent = $smParent->sm_stock;
            $smReff = $smParent->sm_reff;

            // get stock-selected-item with 'On Destination' status (stockAccepted)
            $stockDestinationAccepted = d_stock::where('s_comp', $to)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->first();

            // get qty from stock-mutation
            $qtyFromMutation = $stockDestination->getMutation[0]->sm_qty;

            // if exist stock-Accepted, update qty
            if ($stockDestinationAccepted != null) {
                $stockId = $stockDestinationAccepted->s_id;
                // add qty from $stockDestination to stockAccepted
                $stockDestinationAccepted->s_qty = $stockDestinationAccepted->s_qty + $qtyFromMutation;
                $stockDestinationAccepted->save();

                // set stock-mutation detailid
                $smDetailId = d_stock_mutation::where('sm_stock', $stockId)
                        ->max('sm_detailid') + 1;
            } // if not-exist stock-Accepted, insert new stock
            else {
                $stockId = d_stock::max('s_id') + 1;
                $stock = array(
                    's_id' => $stockId,
                    's_comp' => $stockDestination->s_comp,
                    's_position' => $stockDestination->s_position,
                    's_item' => $item,
                    's_qty' => $qtyFromMutation,
                    's_status' => 'ON DESTINATION',
                    's_condition' => 'FINE',
                    's_created_at' => $dateNow,
                    's_updated_at' => $dateNow
                );
                d_stock::insert($stock);

                // set stock-mutation detailid
                $smDetailId = 1;
            }

            // rollBack stock-mutation 'in', stock-mutation-detail and stock-detail (inside rollback-mut func)
            $rollbackStockMutIn = self::rollbackStockMutationIn(
                $item, // item id
                $nota, // nota
                $mutcatIn, // mutcat-in
                $mutcatOut // mutcat-out
            );
            if ($rollbackStockMutIn->original['status'] !== 'success') {
                throw new \Exception("Acc->rollback: " . $rollbackStockMutIn->getData()->message);
            }

            // set new value for mutataion 'in'
            $mutation = array(
                'sm_stock' => $stockId,
                'sm_detailid' => $smDetailId,
                'sm_date' => $dateNow,
                'sm_mutcat' => $stockDestination->getMutation[0]->sm_mutcat,
                'sm_qty' => $stockDestination->getMutation[0]->sm_qty,
                'sm_use' => $stockDestination->getMutation[0]->sm_use,
                'sm_residue' => $stockDestination->getMutation[0]->sm_residue,
                'sm_hpp' => $stockDestination->getMutation[0]->sm_hpp,
                'sm_sell' => $stockDestination->getMutation[0]->sm_sell,
                'sm_nota' => $stockDestination->getMutation[0]->sm_nota,
                'sm_reff' => $stockDestination->getMutation[0]->sm_reff,
                'sm_user' => Auth::user()->u_id
            );
            d_stock_mutation::insert($mutation);

            // insert new stock-mutation-detail
            $insertSMProdCode = self::insertStockMutationDt(
                $stockId, // stock-mutation id
                $smDetailId, // stock-mutation detail-id
                $listPC, // list production-code
                $listQtyPC // list qty each production-code
            );
            if ($insertSMProdCode !== 'success') {
                throw new \Exception("Acc->Insert mutation DT: " . $insertSMProdCode->getData()->message);
            }
            // insert stock-detail
            $stockParentId = $smStockParent;
            $stockChildId = $stockId;
            $insertStockDt = self::insertStockDetail(
                $stockParentId, // stock-parent id (mutation out)
                $stockChildId, // stock mutation id (mutation in)
                $listPC, // list production-code
                $listQtyPC // list qty each production-code
            );
            if ($insertStockDt !== 'success') {
                throw new \Exception("Acc->Insert stock DT: " . $insertStockDt->getData()->message);
            }

            // update stock-before qty
            $stockDestination->s_qty = $stockDestination->s_qty - $qtyFromMutation;
            $stockDestination->save();

            // delete 'On Going' stock if qty is 0
            if ($stockDestination->s_qty == 0) {
                $stockDestination->delete();
            }

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }

}
