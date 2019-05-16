<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\d_stock;
use App\d_stock_mutation;
use App\m_mutcat;
use Auth;
use Carbon\Carbon;
use Mockery\Exception;

class Mutasi extends Controller
{
    static function mutasimasuk($mutcat, $comp, $position, $item, $qty, $status, $condition, $hpp = 0, $sell = 0, $nota, $reff)
    {
        DB::beginTransaction();
        try {
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

                $mutasi = array(
                    'sm_stock' => $idStok,
                    'sm_detailid' => 1,
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

                //========== update qty jika data sudah ada
            } else {
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

    static function mutasikeluar($mutcat, $comp, $position, $item, $qty, $nota)
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

                $detailid = (DB::table('d_stock_mutation')->max('sm_detailid')) ? DB::table('d_stock_mutation')->max('sm_detailid') + 1 : 1;

                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0)
                {

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
                            'sm_user' => Auth::user()->u_id,
                        ]);

                }
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0)
                {
                    //Langsung Eksekusi

                    $detailid = (DB::table('d_stock_mutation')
                        ->max('sm_detailid')) ? (DB::table('d_stock_mutation')->max('sm_detailid')) + 1 : 1;

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
                            'sm_user' => Auth::user()->u_id,
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

    // it's the same function as mutasikeluar, but there is additional parameter
    // that is $sellprice -> selling price (used in mng-agent: kelola-pjl-lgs)
    static function mutasikeluarcustomsell($mutcat, $comp, $position, $item, $qty, $nota, $sellprice)
    {
        DB::beginTransaction();
        try {

            $qty = (int)$qty;
            $sellprice = (int)$sellprice;

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

                $detailid = (DB::table('d_stock_mutation')->max('sm_detailid')) ? DB::table('d_stock_mutation')->max('sm_detailid') + 1 : 1;

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
                            'sm_sell' => $sellprice,
                            'sm_nota' => $nota,
                            'sm_reff' => $stock[$j]->sm_nota,
                            'sm_user' => Auth::user()->u_id,
                        ]);

                } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    //Langsung Eksekusi

                    $detailid = (DB::table('d_stock_mutation')
                        ->max('sm_detailid')) ? (DB::table('d_stock_mutation')->max('sm_detailid')) + 1 : 1;

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
                            'sm_sell' => $sellprice,
                            'sm_nota' => $nota,
                            'sm_reff' => $stock[$j]->sm_nota,
                            'sm_user' => Auth::user()->u_id,
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

    static function rollback($nota)
    {
        DB::beginTransaction();
        try {
            // get stock-mutation's parent
            $smParents = DB::table('d_stock_mutation')
                ->join('d_stock', 'sm_stock', '=', 's_id')
                ->where('sm_nota', '=', $nota)
                ->get();

            foreach ($smParents as $sm) {
                if ($sm->sm_mutcat == 13 || $sm->sm_mutcat == 14)
                {
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
                else if ($sm->sm_mutcat == 12)
                {
                    $select_sm = DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $sm->sm_stock)
                        ->where('sm_nota', '=', $sm->sm_nota)
                        ->first();

                    $select_stock = DB::table('d_stock')
                        ->where('s_id', '=', $select_sm->sm_stock)
                        ->first();

                    $sisa = $select_stock->s_qty - $select_sm->sm_qty;

                    DB::table('d_stock')
                        ->where('s_id', '=', $select_sm->sm_stock)
                        ->update([
                            's_qty' => $sisa
                        ]);
                }
                // // if mutcat == Distribusi ke cabang masuk
                // else if ($sm->sm_mutcat == 18)
                // {
                //     $stockMut = d_stock_mutation::where('sm_stock', '=', $sm->sm_stock)
                //         ->where('sm_nota', '=', $sm->sm_nota)
                //         ->first();
                //
                //     $select_stock = DB::table('d_stock')
                //         ->where('s_id', '=', $select_sm->sm_stock)
                //         ->first();
                //
                //     $sisa = $select_stock->s_qty - $select_sm->sm_qty;
                //
                //     DB::table('d_stock')
                //         ->where('s_id', '=', $select_sm->sm_stock)
                //         ->update([
                //             's_qty' => $sisa
                //         ]);
                // }
                // // if mutcat == Distribusi ke cabang keluar
                // else if ($sm->sm_mutcat == 19)
                // {
                //     // code...
                // }

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

    static function opname($date, $mutcat, $comp, $position, $item, $qtysistem, $qtyreal, $sisa, $nota, $reff)
    {
        DB::beginTransaction();
        try {

            $mutcatmasuk = DB::table('m_mutcat')->where('m_name', 'Distribusi Barang Masuk')->first();

            $mutcatkeluar = DB::table('m_mutcat')->where('m_name', 'Distribusi Barang Keluar')->first();

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

            if ($sisa > 0) {
                //========= mengurangi stock
                for ($i = 0; $i < count($mutasi); $i++) {
                    if ($mutasi[$i]->sm_sisa >= $sisa) {
                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->where('sm_detailid', '=', $mutasi[$i]->sm_detailid)
                            ->update([
                                'sm_use' => $mutasi[$i]->sm_use + $sisa,
                                'sm_residue' => 0
                            ]);

                        $getdetailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->max('sm_detailid');

                        $detailid = $getdetailid + 1;

                        DB::table('d_stock_mutation')
                            ->insert([
                                'sm_stock' => $mutasi[$i]->sm_stock,
                                'sm_detailid' => $detailid,
                                'sm_date' => $sekarang,
                                'sm_mutcat' => $mutcat,
                                'sm_qty' => $sisa,
                                'sm_use' => 0,
                                'sm_residue' => 0,
                                'sm_hpp' => $mutasi[$i]->sm_hpp,
                                'sm_sell' => $mutasi[$i]->sm_sell,
                                'sm_nota' => $nota,
                                'sm_reff' => $mutasi[$i]->sm_nota,
                                'sm_user' => Auth::user()->u_id,
                            ]);

                        DB::table('d_stock')
                            ->where('s_id', '=', $mutasi[$i]->s_id)
                            ->update([
                                's_qty' => DB::raw('s_qty - ' . $sisa)
                            ]);
                        $sisa = 0;
                        $i = count($mutasi);

                    } elseif ($mutasi[$i]->sm_sisa < $sisa) {
                        $sisa = $sisa - $mutasi[$i]->sm_qty;
                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->where('sm_detailid', '=', $mutasi[$i]->sm_detailid)
                            ->update([
                                'sm_use' => $mutasi[$i]->sm_qty,
                                'sm_residue' => $mutasi[$i]->sm_residue - $sisa
                            ]);

                        $getdetailid = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $mutasi[$i]->sm_stock)
                            ->max('sm_detailid');

                        $detailid = $getdetailid + 1;

                        DB::table('d_stock_mutation')
                            ->insert([
                                'sm_stock' => $mutasi[$i]->sm_stock,
                                'sm_detailid' => $detailid,
                                'sm_date' => $sekarang,
                                'sm_mutcat' => $mutcat,
                                'sm_qty' => $mutasi[$i]->sm_qty,
                                'sm_use' => 0,
                                'sm_residue' => 0,
                                'sm_hpp' => $mutasi[$i]->sm_hpp,
                                'sm_sell' => $mutasi[$i]->sm_sell,
                                'sm_nota' => $nota,
                                'sm_reff' => $mutasi[$i]->sm_nota,
                                'sm_user' => Auth::user()->u_id,
                            ]);

                        DB::table('d_stock')
                            ->where('s_id', '=', $mutasi[$i]->s_id)
                            ->update([
                                's_qty' => DB::raw('(s_qty - ' . $mutasi[$i]->sm_qty . ')')
                            ]);
                    }
                }
            } elseif ($sisa < 0) {
                //======== menambah stock
                $sisa = abs($sisa);
                $counter = count($mutasi) - 1;

                $getdetailid = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $mutasi[0]->sm_stock)
                    ->max('sm_detailid');

                $detailid = $getdetailid + 1;

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
                        'sm_user' => Auth::user()->u_id,
                    ]);

                DB::table('d_stock')
                    ->where('s_id', '=', $mutasi[0]->s_id)
                    ->update([
                        's_qty' => DB::raw('(s_qty + ' . $sisa . ')')
                    ]);
            } else {
                //======== tidak perlu ada penanganan khusus
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

    static function distribusicabangkeluar($from, $to, $item, $qty, $nota, $reff)
    {
        DB::beginTransaction();
        try {
            // get mutcat for 'distribusi'
            $mutcatIn = m_mutcat::where('m_name', 'Barang Masuk Distribusi Cabang')->first();
            $mutcatIn = $mutcatIn->m_id;
            $mutcatOut = m_mutcat::where('m_name', 'Barang Keluar Distribusi Cabang')->first();
            $mutcatOut = $mutcatOut->m_id;
            // qty item that is sending out to branch
            $qty = (int)$qty;
            // date now
            $dateNow = Carbon::now();

            // get list of in-mutcat-list
            $inMutcatList = m_mutcat::where('m_status', 'M')
            ->select('m_id')
            ->get();
            for ($i = 0; $i < count($inMutcatList); $i++) {
                $tmp[] = $inMutcatList[$i]->m_id;
            }
            $inMutcatList = $tmp;

            // get stock-selected-item
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

            // how about if there is no stock existed ? ======================
            // stock currently has 0 qty ? ===================================

            // update qty stock-selected-item
            DB::table('d_stock')
                ->where('s_id', $stock[0]->sm_stock)
                ->where('s_comp', $stock[0]->s_comp)
                ->where('s_position', $stock[0]->s_position)
                ->where('s_status', $stock[0]->s_status)
                ->where('s_condition', $stock[0]->s_condition)
                ->update([
                    's_qty' => ($stock[0]->s_qty - $permintaan)
                ]);

            // set mutation record
            for ($j = 0; $j < count($stock); $j++)
            {
                $continueLoopStock = false;
                $detailid = DB::table('d_stock_mutation')
                    ->where('sm_stock', $stock[$j]->sm_stock)
                    ->max('sm_detailid');

                // if qty-request is more than qty-selected-item-stock
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0)
                {
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

                    // insert new stock-mutation (distribution-out)
                    // using sm_sisa as sm_qty
                    DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock' => $stock[$j]->sm_stock,
                        'sm_detailid' => $detailid + 1,
                        'sm_date' => $dateNow,
                        'sm_mutcat' => $mutcatOut,
                        'sm_qty' => $stock[$j]->sm_sisa,
                        'sm_use' => 0,
                        'sm_residue' => 0,
                        'sm_hpp' => $stock[$j]->sm_hpp,
                        'sm_sell' => $stock[$j]->sm_sell,
                        'sm_nota' => $nota,
                        'sm_reff' => $stock[$j]->sm_nota,
                        'sm_user' => Auth::user()->u_id,
                    ]);

                    $continueLoopStock = true;
                }
                // execute mutation, using existed stock
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0)
                {
                    $detailid = DB::table('d_stock_mutation')
                        ->max('sm_detailid');

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_use + $permintaan,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);

                    // insert new stock-mutation (distribution-out)
                    DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock' => $stock[$j]->sm_stock,
                        'sm_detailid' => $detailid + 1,
                        'sm_date' => $dateNow,
                        'sm_mutcat' => $mutcatOut,
                        'sm_qty' => $permintaan,
                        'sm_use' => 0,
                        'sm_residue' => 0,
                        'sm_hpp' => $stock[$j]->sm_hpp,
                        'sm_sell' => $stock[$j]->sm_sell,
                        'sm_nota' => $nota,
                        'sm_reff' => $stock[$j]->sm_nota,
                        'sm_user' => Auth::user()->u_id,
                    ]);
                    $continueLoopStock = false;
                }

                // set variable for new stock
                $mutcat = $mutcatIn;
                $comp = $from;
                $position = $to;
                $status = 'ON GOING';
                $condition = 'FINE';
                $sell = (int)$stock[$j]->sm_sell;
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
                // create new stock with 'On Going' status
                if (count($stockId) < 1)
                {
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

                    // create new stock-mutation
                    $mutasi = array(
                    'sm_stock' => $stockId,
                    'sm_detailid' => 1,
                    'sm_date' => $dateNow,
                    'sm_mutcat' => $mutcat,
                    'sm_qty' => $requestQty,
                    'sm_use' => 0,
                    'sm_residue' => $requestQty,
                    'sm_hpp' => $hpp,
                    'sm_sell' => $sell,
                    'sm_nota' => $nota,
                    'sm_reff' => $reff,
                    'sm_user' => Auth::user()->u_id,
                    );
                    d_stock_mutation::insert($mutasi);

                }
                // if stock with 'On Going' status is found
                // update selected stock with 'On Going' status
                else
                {
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

                    // create new mutation with mutcat = distribution-in
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
                    'sm_user' => Auth::user()->u_id,
                    );
                    d_stock_mutation::insert($mutasi);
                }

                if ($continueLoopStock == false) {
                    // $j = count($stock) + 1;
                    $permintaan = 0;
                    break;
                }
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

    static function confirmDistribusiCabang($from, $to, $item, $nota)
    {
        // dd($from, $to, $item,$nota);
        DB::beginTransaction();
        try {

            // date now
            $dateNow = Carbon::now();
            // get stock-selected-item with 'On Going' status
            $stockBefore = d_stock::where('s_comp', $from)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON GOING')
                ->where('s_condition', 'FINE')
                ->with(['getMutation' => function ($query) use ($nota) {
                    $query->where('sm_nota', $nota);
                }])
                ->first();

            // get stock-selected-item with 'On Destination' status (stockAccepted)
            $stockAccepted = d_stock::where('s_comp', $from)
                ->where('s_position', $to)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->first();

            // get qty from stock-mutation
            $qtyFromMutation = $stockBefore->getMutation[0]->sm_qty;

            // if exist stock-Accepted
            if ($stockAccepted != null)
            {
                $stockId = $stockAccepted->s_id;
                // add qty from $stockBefore to stockAccepted
                $stockAccepted->s_qty = $stockAccepted->s_qty + $qtyFromMutation;
                $stockAccepted->save();

                // set stock-mutation detailid
                $smDetailId = d_stock_mutation::where('sm_stock', $stockId)
                ->max('sm_detailid') + 1;
            }
            // if not-exist stock-Accepted
            else
            {
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

            // insert new stock-mutation
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
                'sm_user' => Auth::user()->u_id,
            );
            d_stock_mutation::insert($mutasi);

            // delete stock-mutation before
            $stockBefore->getMutation[0]->delete();

            // update stock-before qty
            $stockBefore->s_qty = $stockBefore->s_qty - $qtyFromMutation;
            $stockBefore->save();

            // delete 'On Going' stock if qty is 0
            if ($stockBefore->s_qty == 0) {
                $stockBefore->delete();
            }

            DB::commit();
            return true;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    // watch out your code !!
    static function rollbackDistribusi($nota, $item)
    {
        DB::beginTransaction();
        try {
            // get stock-mutation with selected-item
            $smItems = d_stock_mutation::where('sm_nota', '=', $nota)
            ->whereHas('getStock', function ($query) use ($item) {
                $query->where('s_item', $item);
            })
            ->get();

            foreach ($smItems as $key => $val)
            {
                // if : mutcat is 'distribution in' (18)
                if ($val->sm_mutcat == 18)
                {
                    // get stock-item based on sm_stock
                    $stockItem = d_stock::where('s_id', $val->sm_stock)
                    ->first();
                    $returnQty = $stockItem->s_qty - $val->sm_qty;
                    // if new-qty is 0, delete stock-item
                    if ($returnQty == 0)
                    {
                        $stockItem->delete();
                    }
                    // if new-qty is > 0, update qty in stock-item
                    elseif ($returnQty > 0)
                    {
                        $stockItem->s_qty = $returnQty;
                        $stockItem->save();
                    }
                }
                // else if : mutcat is 'distribution out' (19)
                else if ($val->sm_mutcat == 19)
                {
                    // get stock-mutation parent
                    $smParents = d_stock_mutation::where('sm_nota', $val->sm_reff)
                    ->where('sm_stock', $val->sm_stock)
                    ->first();
                    // update residue and use in stock-mutation parent
                    $smParents->sm_residue = $smParents->sm_residue + $val->sm_qty;
                    $smParents->sm_use = $smParents->sm_use - $val->sm_qty;
                    $smParents->save();
                    // get stock-item
                    $stockItem = d_stock::where('s_id', $val->sm_stock)
                    ->first();
                    // update qty in stock-item
                    $stockItem->s_qty = $stockItem->s_qty + $val->sm_qty;
                    $stockItem->save();
                }
                // delete current stock-mutation with selected-item
                $val->delete();
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    // watch out your code !!
    static function updateDistribusi($nota, $item, $newQty)
    {
        DB::beginTransaction();
        try {
            // get stock-mutation with selected-item
            $smItems = d_stock_mutation::where('sm_nota', '=', $nota)
            ->whereHas('getStock', function ($query) {
                $query->where('s_item', $item);
            })
            ->get();

            foreach ($smItems as $key => $val) {
                $diffQty = $val->sm_qty - (int)$newQty;
                // if : mutcat is 'distribution in' (18)
                if ($val->sm_mutcat == 18)
                {
                    // get stock-item based on sm_stock
                    $stockItem = d_stock::where('s_id', $val->sm_stock)
                    ->first();
                    // calculate new-stock-qty after minus by diff-qty
                    $newStockQty = $stockItem->s_qty - $diffQty;
                    // update qty in stock-item
                    $stockItem->s_qty = $newStockQty;
                    $stockItem->save();
                }
                // else if : mutcat is 'distribution out' (19)
                else if ($val->sm_mutcat == 19)
                {
                    // --- start: stock-mutation update ---
                    // get stock-mutation parent
                    $smParents = d_stock_mutation::where('sm_nota', $val->sm_reff)
                    ->where('sm_stock', $val->sm_stock)
                    ->first();
                    // update residue and use in stock-mutation parent
                    $smParents->sm_residue = $smParents->sm_residue + $diffQty;
                    $smParents->sm_use = $smParents->sm_use - $diffQty;
                    $smParents->save();
                    // --- end: stock-mutation update ---

                    // --- start: stock-item update ---
                    // get stock-item
                    $stockItem = d_stock::where('s_id', $val->sm_stock)
                    ->first();
                    // calculate new-stock-qty after minus by diff-qty
                    $newStockQty = $stockItem->s_qty - $diffQty;
                    // update qty in stock-item
                    $stockItem->s_qty = $newStockQty;
                    $stockItem->save();
                    // --- end: stock-item update ---
                }
                // update current stock-mutation
                $val->sm_qty = (int)$newQty;
                $val->save();
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }

    }

    static function MutasiKeluarWithReff($mutcat, $comp, $position, $item, $qty, $nota, $reff)
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

                $detailid = (DB::table('d_stock_mutation')->max('sm_detailid')) ? DB::table('d_stock_mutation')->max('sm_detailid') + 1 : 1;

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
                            'sm_user' => Auth::user()->u_id,
                        ]);

                } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    //Langsung Eksekusi

                    $detailid = (DB::table('d_stock_mutation')
                        ->max('sm_detailid')) ? (DB::table('d_stock_mutation')->max('sm_detailid')) + 1 : 1;

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
                            'sm_user' => Auth::user()->u_id,
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

    static function mutasikeluartanpapemilik($mutcat, $position, $item, $qty, $nota)
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
                ->where('s_position', '=', $position)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
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

                $detailid = (DB::table('d_stock_mutation')->max('sm_detailid')) ? DB::table('d_stock_mutation')->max('sm_detailid') + 1 : 1;

                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0)
                {

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
                            'sm_user' => Auth::user()->u_id,
                        ]);

                }
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0)
                {
                    //Langsung Eksekusi

                    $detailid = (DB::table('d_stock_mutation')
                        ->max('sm_detailid')) ? (DB::table('d_stock_mutation')->max('sm_detailid')) + 1 : 1;

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
                            'sm_user' => Auth::user()->u_id,
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
}
