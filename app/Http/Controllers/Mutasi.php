<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\d_stock;
use App\d_stock_mutation;

use Auth;

use Carbon\Carbon;
use Mockery\Exception;

class Mutasi extends Controller
{
    static function mutasimasuk($mutcat, $comp, $position, $item, $qty, $status, $condition, $hpp = 0, $sell = 0, $nota, $reff){
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

    static function mutasikeluar($mutcat, $comp, $position, $item, $qty, $nota){
      DB::beginTransaction();
      try {

        $qty = (int)$qty;

        $sekarang = Carbon::now('Asia/Jakarta');

        $datamutcat = DB::table('m_mutcat')->where('m_status', '=', 'M')->get();

        for ($i=0; $i < count($datamutcat); $i++) {
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

    static function rollback($nota)
    {
        DB::beginTransaction();
        try{
            $get_sm = DB::table('d_stock_mutation')
                ->join('d_stock', 'sm_stock', '=', 's_id')
                ->where('sm_nota', '=', $nota)
                ->get();

            foreach ($get_sm as $sm){
                if ($sm->sm_mutcat == 13){
                    $select_sm = DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $sm->sm_stock)
                        ->where('sm_nota', '=', $sm->sm_reff)
                        ->first();

                    $use  = $select_sm->sm_use - $sm->sm_qty;
                    $sisa = $select_sm->sm_residue + $sm->sm_qty;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $select_sm->sm_stock)
                        ->where('sm_nota', '=', $select_sm->sm_nota)
                        ->update([
                            'sm_use'        => $use,
                            'sm_residue'    => $sisa
                        ]);

                    DB::table('d_stock')
                        ->where('s_id', '=', $select_sm->sm_stock)
                        ->update([
                            's_qty' => $sisa
                        ]);
                } else if ($sm->sm_mutcat == 12) {
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

                DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $sm->sm_stock)
                    ->where('sm_nota', '=', $sm->sm_nota)
                    ->delete();
            }
            DB::commit();
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    static function opname($date, $mutcat, $comp, $position, $item, $qtysistem, $qtyreal, $sisa, $nota, $reff){
      DB::beginTransaction();
      try {

        $mutcatmasuk = DB::table('m_mutcat')->where('m_name', 'Distribusi Barang Masuk')->first();

        $mutcatkeluar = DB::table('m_mutcat')->where('m_name', 'Distribusi Barang Keluar')->first();

        $qtyreal = (int)$qtyreal;
        $qtysistem = (int)$qtysistem;
        $sisa = (int)$sisa;

        $sekarang = Carbon::now('Asia/Jakarta');

        $datamutcat = DB::table('m_mutcat')->where('m_status', 'M')->get();
        for ($i=0; $i < count($datamutcat); $i++) {
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

            if ($sisa > 0){
                //========= mengurangi stock
                for ($i = 0; $i < count($mutasi); $i++){
                    if ($mutasi[$i]->sm_sisa >= $sisa){
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

                    } elseif ($mutasi[$i]->sm_sisa < $sisa){
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
                                's_qty' => DB::raw('(s_qty - ' . $mutasi[$i]->sm_qty. ')')
                            ]);
                    }
                }
            } elseif ($sisa < 0){
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
                        's_qty' => DB::raw('(s_qty + ' . $sisa. ')')
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

    static function distribusicabangkeluar($from, $to, $item, $qty, $nota, $reff){
      DB::beginTransaction();
      try {

        $asd = DB::table('m_mutcat')->where('m_name', 'Distribusi Cabang Masuk')->first();

        $mutcatmasuk = $asd->m_id;

        $asd = DB::table('m_mutcat')->where('m_name', 'Distribusi Cabang Keluar')->first();

        $mutcatkeluar = $asd->m_id;

        $qty = (int)$qty;

        $sekarang = Carbon::now('Asia/Jakarta');

        $datamutcat = DB::table('m_mutcat')->where('m_status', 'M')->get();

        for ($i=0; $i < count($datamutcat); $i++) {
          $tmp[] = $datamutcat[$i]->m_id;
        }

        $stock = DB::table('d_stock')
          ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
          ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
          ->where('s_position', '=', $from)
          ->where('s_item', '=', $item)
          ->where('s_status', '=', 'ON DESTINATION')
          ->where('s_condition', '=', 'FINE')
          ->whereIn('sm_mutcat', $tmp)
          ->where(DB::raw('(sm_qty - sm_use)'), '>', 0)
          ->get();

          $permintaan = $qty;


          DB::table('d_stock')
              ->where('s_id', $stock[0]->sm_stock)
              ->where('s_comp', $stock[0]->s_comp)
              ->where('s_position', $stock[0]->s_position)
              ->where('s_status', $stock[0]->s_status)
              ->where('s_condition', $stock[0]->s_condition)
              ->update([
                  's_qty' => $stock[0]->s_qty - $permintaan
              ]);

          for ($j = 0; $j < count($stock); $j++) {

              //Terdapat sisa permintaan

              $detailid = DB::table('d_stock_mutation')
                  ->max('sm_detailid');

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
                          'sm_detailid' => $detailid + 1,
                          'sm_date' => $sekarang,
                          'sm_mutcat' => $mutcatkeluar,
                          'sm_qty' => $stock[$j]->sm_sisa,
                          'sm_use' => 0,
                          'sm_residue' => 0,
                          'sm_hpp' => $stock[$j]->sm_hpp,
                          'sm_sell' => $stock[$j]->sm_sell,
                          'sm_nota' => $nota,
                          'sm_reff' => $stock[$j]->sm_nota,
                          'sm_user' => Auth::user()->u_id,
                      ]);

                      //========== cek id stock
                      $mutcat = $mutcatmasuk;
                      $comp = $from;
                      $position = $to;
                      $status = 'ON DESTINATION';
                      $condition = 'FINE';
                      $sell = (int)$stock[$j]->sm_sell;
                      $hpp = (int)$stock[$j]->sm_hpp;
                      $qty = (int)$permintaan;


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

              } elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                  //Langsung Eksekusi

                  $detailid = DB::table('d_stock_mutation')
                      ->max('sm_detailid');

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
                          'sm_detailid' => $detailid + 1,
                          'sm_date' => $sekarang,
                          'sm_mutcat' => $mutcatkeluar,
                          'sm_qty' => $permintaan,
                          'sm_use' => 0,
                          'sm_residue' => 0,
                          'sm_hpp' => $stock[$j]->sm_hpp,
                          'sm_sell' => $stock[$j]->sm_sell,
                          'sm_nota' => $nota,
                          'sm_reff' => $stock[$j]->sm_nota,
                          'sm_user' => Auth::user()->u_id,
                      ]);

                      //========== cek id stock
                      $mutcat = $mutcatmasuk;
                      $comp = $from;
                      $position = $to;
                      $status = 'ON DESTINATION';
                      $condition = 'FINE';
                      $sell = (int)$stock[$j]->sm_sell;
                      $hpp = (int)$stock[$j]->sm_hpp;
                      $qty = (int)$permintaan;

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
