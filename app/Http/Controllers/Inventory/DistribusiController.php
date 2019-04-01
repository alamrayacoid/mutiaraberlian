<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

use Carbon\Carbon;

use Yajra\DataTables\DataTables;

use Auth;

use CodeGenerator;

use Mutasi;

class DistribusiController extends Controller
{
  public function distribusibarang_index()
  {
      return view('inventory/distribusibarang/index');
  }

  public function distribusibarang_create()
  {
      $cabang = DB::table('m_company')->where('c_type', 'CABANG')->get();

      return view('inventory/distribusibarang/distribusi/create', compact('cabang'));
  }

  public function distribusibarang_edit($id)
  {
      $data = DB::table('d_stockdistribution')->where('sd_id', decrypt($id))->first();

      $type = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

      if ($type->c_type == "CABANG") {
        $cabang = DB::table('m_company')->where('c_type', 'CABANG')->get();

        $dt = DB::table('d_stockdistributiondt')->join("m_item", 'i_id', '=', 'sdd_item')->join('m_unit', 'u_id', '=', 'sdd_unit')->where('sdd_stockdistribution', decrypt($id))->get();

        $tmp = DB::table('d_stock_mutation')
                  ->where('sm_nota', $data->sd_nota)
                  ->get();

        $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();
        $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

        $reff = [];
        $status = [];
        $batas = [];
        for ($i=0; $i < count($tmp); $i++) {
          if ($tmp[$i]->sm_mutcat == $mutcatkeluar->m_id) {
            $reff[] = $tmp[$i]->sm_reff;
          }
          if ($tmp[$i]->sm_mutcat == $mutcatmasuk->m_id) {
            if ($tmp[$i]->sm_use > 0) {
              $status[] = 'yes';
              $batas[] = $tmp[$i]->sm_use;
            } else {
              $status[] = 'no';
              $batas[] = 0;
            }
          }
        }

        $unit1 = [];
        $unit2 = [];
        $unit3 = [];
        for ($i=0; $i < count($dt); $i++) {
          $unit1[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit1)->first();
          $unit2[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit2)->first();
          $unit3[] = DB::table('m_unit', 'u_id', '=', $dt[$i]->i_unit3)->first();
        }

        return view('inventory/distribusibarang/distribusi/edit', compact('data', 'type', 'cabang', 'batas', 'dt', 'status', 'unit1', 'unit2', 'unit3'));
      } elseif ($type->c_type == "AGEN") {

      }
  }

  public function printNota(Request $request)
  {
      $data = DB::table('d_stockdistribution')->where('sd_id', $request->id)->first();

      $tujuan = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

      $cabang = DB::table('m_company')->where('c_id', $data->sd_from)->first();

      $dt = DB::table('d_stockdistributiondt')->join('m_item', 'i_id', '=', 'sdd_item')->join('m_unit', 'u_id', '=', 'sdd_unit')->where('sdd_stockdistribution', $request->id)->get();

      return view('inventory/distribusibarang/distribusi/nota', compact('data', 'tujuan', 'cabang', 'dt'));
  }

  public function getitem(Request $request){
    $keyword = $request->term;
    $results = [];

    $data = DB::table('m_item')->where('i_isactive', 'Y')->where('i_name', 'LIKE', '%'.$keyword.'%')->get();

            if ($data == null) {
                $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
            } else {

                foreach ($data as $query) {
                    $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . $query->i_name];
                }
            }

            return response()->json($results);
  }

  public function getsatuan(Request $request){
    $item = DB::table('m_item')
                ->where('i_id', $request->id)
                ->first();

    $unit = [];

    $tmp = DB::table('m_unit')->where('u_id', $item->i_unit1)->first();

    $unit[] = $tmp;

    $tmp = DB::table('m_unit')->where('u_id', $item->i_unit2)->first();

    $unit[] = $tmp;

    $tmp = DB::table('m_unit')->where('u_id', $item->i_unit3)->first();

    $unit[] = $tmp;

    $stock = DB::table('d_stock')->where('s_item', $request->id)->where('s_position', Auth::user()->u_company)->where('s_status', 'ON DESTINATION')->where('s_condition', 'FINE')->sum('s_qty');

    return response()->json([
      'unit' => $unit,
      'stock' => $stock
      ]);
  }

  public function simpancabang(Request $request){
    DB::beginTransaction();
    try {

    $nota = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 8, 10, 3, 'DK', '-');

    for ($x=0; $x < count($request->namabarang); $x++) {
      if ($request->qty[$x] != 0) {
        $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();

        if ($barang->i_unit1 == $request->satuan[$i]) {
          $convert  = $request->qty[$i] * $barang->i_unitcompare1;
        } elseif ($barang->i_unit2 == $request->satuan[$i]) {
          $convert  = $request->qty[$i] * $barang->i_unitcompare2;
        } elseif ($barang->i_unit3 == $request->satuan[$i]) {
          $convert  = $request->qty[$i] * $barang->i_unitcompare3;
        }

        Mutasi::distribusicabangkeluar(Auth::user()->u_company, $request->cabang, $request->idbarang[$x], $convert, $nota, $nota);
      }
    }

      $id = DB::table('d_stockdistribution')->max('sd_id')+1;
      DB::table('d_stockdistribution')
        ->insert([
          'sd_id' => $id,
          'sd_from' => Auth::user()->u_company,
          'sd_destination' => $request->cabang,
          'sd_date' => Carbon::now('Asia/Jakarta'),
          'sd_nota' => CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 8, 10, 3, 'DK', '-'),
          'sd_type' => 'K',
          'sd_user' => Auth::user()->u_id
        ]);

        for ($i=0; $i < count($request->namabarang); $i++) {
          if ($request->qty[$i] != 0) {

          $dt = DB::table('d_stockdistributiondt')->max('sdd_detailid')+1;
          DB::table('d_stockdistributiondt')
              ->insert([
                'sdd_stockdistribution' => $id,
                'sdd_detailid' => $dt,
                'sdd_comp' => Auth::user()->u_company,
                'sdd_item' => $request->idbarang[$i],
                'sdd_qty' => $request->qty[$i],
                'sdd_unit' => $request->satuan[$i]
              ]);
          }
        }

      DB::commit();
      return response()->json([
        'status' => 'berhasil'
      ]);
    } catch (Exception $e) {
      DB::rollback();
      return response()->json([
        'status' => 'gagal'
      ]);
    }

  }

  public function table(Request $request){
    $from = Carbon::parse($request->date_from)->format('Y-m-d');
    $to = Carbon::parse($request->date_to)->format('Y-m-d');
    $data = DB::table('d_stockdistribution')->whereBetween('sd_date', array($from, $to))->get();

    return Datatables::of($data)
    ->addIndexColumn()
    ->addColumn('tanggal', function($data) {
      return '<td>'. Carbon::parse($data->sd_date)->format('d/m/Y') .'</td>';
    })
    ->addColumn('type', function($data){
      if ($data->sd_type == 'K') {
        return '<td><button class="btn btn-primary status-approve" style="pointer-events: none">Konsinyasi</button></td>';
      } else {
        return '<td><button class="btn btn-primary status-approve" style="pointer-events: none">Cash</button></td>';
      }
    })
    ->addColumn('action', function($data) {
      return '<div class="btn-group btn-group-sm">
              <button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
              <button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Print Nota" title="Nota" type="button" onclick="printNota('.$data->sd_id.')"><i class="fa fa-print"></i></button>
              <button class="btn btn-warning btn-edit-distribusi" onclick="edit(\''.encrypt($data->sd_id).'\')" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-danger btn-disable-distribusi" onclick="hapus('.$data->sd_id.')" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
              </div>';
    })
    ->addColumn('tujuan', function($data){
      $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

      return $tmp->c_name;
    })
    ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
    ->make(true);
  }

  public function hapus(Request $request){
    DB::beginTransaction();
    try {

      $parrent = DB::table('d_stockdistribution')
              ->where('sd_id', $request->id)
              ->first();

      $tmp = DB::table('d_stock_mutation')
                ->where('sm_nota', $parrent->sd_nota)
                ->get();

      $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();
      $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

      $reff = [];
      $status = 'no';
      for ($i=0; $i < count($tmp); $i++) {
        if ($tmp[$i]->sm_mutcat == $mutcatkeluar->m_id) {
          $reff[] = $tmp[$i]->sm_reff;
        }
        if ($tmp[$i]->sm_mutcat == $mutcatmasuk->m_id) {
        if ($tmp[$i]->sm_use > 0) {
          $status = 'yes';
          }
        }

      }

      if ($status == 'no') {
        DB::table('d_stockdistribution')
                ->where('sd_id', $request->id)
                ->delete();

        $dt = DB::table('d_stockdistributiondt')
              ->where('sdd_stockdistribution', $request->id)
              ->get();

        DB::table('d_stockdistributiondt')
              ->where('sdd_stockdistribution', $request->id)
              ->delete();

        DB::table('d_stock_mutation')
                  ->where('sm_nota', $parrent->sd_nota)
                  ->delete();

        for ($i=0; $i < count($dt); $i++) {
          DB::table('d_stock')
              ->where('s_comp', $dt[$i]->sdd_comp)
              ->where('s_position', $parrent->sd_destination)
              ->where('s_item', $dt[$i]->sdd_item)
              ->where('s_status', 'ON DESTINATION')
              ->where('s_condition', 'FINE')
              ->update([
                's_qty' => DB::raw('s_qty - ' . $dt[$i]->sdd_qty)
              ]);

          DB::table('d_stock_mutation')
              ->where('sm_nota', $reff[$i])
              ->update([
                'sm_use' => DB::raw('sm_use - ' . $dt[$i]->sdd_qty),
                'sm_residue' => DB::raw('sm_residue + ' . $dt[$i]->sdd_qty)
              ]);
        }
      } elseif ($status == 'yes') {
        return response()->json([
          'status' => 'failed',
          'ex' => 'Stock yang ada digudang tujuan sudah digunakan'
        ]);
      }

      DB::commit();
      return response()->json([
        'status' => 'berhasil'
      ]);
    } catch (Exception $e) {
      DB::rollback();
      return response()->json([
        'status' => 'gagal'
      ]);
    }
  }

  public function updatecabang(Request $request){
    DB::beginTransaction();
    try {

      $status = 'no';
      for ($i=0; $i < count($request->status); $i++) {
        if ($request->status[$i] == 'yes') {
          $status = 'yes';
        }
      }

      if ($status == 'no') {
        for ($i=0; $i < count($request->status); $i++) {
          if ($request->qty[$i] != 0) {
            $parrent = DB::table('d_stockdistribution')
                    ->where('sd_id', $request->sd_id)
                    ->first();

            $tmp = DB::table('d_stock_mutation')
                      ->where('sm_nota', $request->sd_nota)
                      ->get();

            $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();
            $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

            $reff = [];
            for ($i=0; $i < count($tmp); $i++) {
              if ($tmp[$i]->sm_mutcat == $mutcatkeluar->m_id) {
                $reff[] = $tmp[$i]->sm_reff;
              }
            }

              DB::table('d_stockdistribution')
                      ->where('sd_id', $request->sd_id)
                      ->delete();

              $dt = DB::table('d_stockdistributiondt')
                    ->where('sdd_stockdistribution', $request->sd_id)
                    ->get();

              DB::table('d_stockdistributiondt')
                    ->where('sdd_stockdistribution', $request->sd_id)
                    ->delete();

              DB::table('d_stock_mutation')
                        ->where('sm_nota', $parrent->sd_nota)
                        ->delete();

              for ($i=0; $i < count($dt); $i++) {
                DB::table('d_stock')
                    ->where('s_comp', $dt[$i]->sdd_comp)
                    ->where('s_position', $parrent->sd_destination)
                    ->where('s_item', $dt[$i]->sdd_item)
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE')
                    ->update([
                      's_qty' => DB::raw('s_qty - ' . $dt[$i]->sdd_qty)
                    ]);

                DB::table('d_stock_mutation')
                    ->where('sm_nota', $reff[$i])
                    ->update([
                      'sm_use' => DB::raw('sm_use - ' . $dt[$i]->sdd_qty),
                      'sm_residue' => DB::raw('sm_residue + ' . $dt[$i]->sdd_qty)
                    ]);
              }

            $nota = $request->sd_nota;
            for ($x=0; $x < count($request->namabarang); $x++) {
              if ($request->qty[$x] != 0) {
                $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();

                if ($barang->i_unit1 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare1;
                } elseif ($barang->i_unit2 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare2;
                } elseif ($barang->i_unit3 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare3;
                }

                Mutasi::distribusicabangkeluar($request->sd_from, $request->sd_destination, $request->idbarang[$x], $convert, $nota, $nota);
              }
            }

              $id = DB::table('d_stockdistribution')->max('sd_id')+1;
              DB::table('d_stockdistribution')
                ->insert([
                  'sd_id' => $request->sd_id,
                  'sd_from' => $request->sd_from,
                  'sd_destination' => $request->sd_destination,
                  'sd_date' => $request->sd_date,
                  'sd_nota' => $nota,
                  'sd_type' => 'K',
                  'sd_user' => Auth::user()->u_id
                ]);

                for ($i=0; $i < count($request->namabarang); $i++) {
                  if ($request->qty[$i] != 0) {
                  $dt = DB::table('d_stockdistributiondt')->max('sdd_detailid')+1;
                  DB::table('d_stockdistributiondt')
                      ->insert([
                        'sdd_stockdistribution' => $request->sd_id,
                        'sdd_detailid' => $request->detailid[$i],
                        'sdd_comp' => $request->sd_from,
                        'sdd_item' => $request->idbarang[$i],
                        'sdd_qty' => $request->qty[$i],
                        'sdd_unit' => $request->satuan[$i]
                      ]);
                  }
                }
          }
        }
      } elseif ($status == 'yes') {
        for ($i=0; $i < count($request->status); $i++) {
          if ($request->qty[$i] != 0) {
            if ($request->status[$i] == 'yes') {
              DB::table('d_stockdistributiondt')
                  ->where('sdd_stockdistribution', $request->sd_id)
                  ->where('sdd_detailid', $request->sdd_detailid[$i])
                  ->update([
                    'sdd_qty' => $request->qty[$i],
                    'sdd_unit' => $request->satuan[$i]
                  ]);

                  $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();

                  $stock = DB::table('d_stock')
                            ->where('s_comp', $request->sd_from)
                            ->where('s_position', $request->sd_destination)
                            ->where('s_item', $request->idbarang[$i])
                            ->where('s_status', 'ON DESTINATION')
                            ->where('s_condition', 'FINE')
                            ->first();

                  $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();

                  if ($barang->i_unit1 == $request->satuan[$i]) {
                    $convert  = $request->qty[$i] * $barang->i_unitcompare1;
                  } elseif ($barang->i_unit2 == $request->satuan[$i]) {
                    $convert  = $request->qty[$i] * $barang->i_unitcompare2;
                  } elseif ($barang->i_unit3 == $request->satuan[$i]) {
                    $convert  = $request->qty[$i] * $barang->i_unitcompare3;
                  }

                  DB::table('d_stock')
                            ->where('s_comp', $request->sd_from)
                            ->where('s_position', $request->sd_destination)
                            ->where('s_item', $request->idbarang[$i])
                            ->where('s_status', 'ON DESTINATION')
                            ->where('s_condition', 'FINE')
                            ->update([
                              's_qty' => $convert
                            ]);

                  $data = DB::table('d_stock_mutation')
                  ->where('sm_stock', $stock->s_id)
                  ->where('sm_mutcat', $mutcatmasuk->m_id)
                  ->where('sm_nota', $request->sd_nota)
                  ->where('sm_reff', $request->sd_nota)
                  ->first();

                  DB::table('d_stock_mutation')
                      ->where('sm_stock', $stock->s_id)
                      ->where('sm_mutcat', $mutcatmasuk->m_id)
                      ->where('sm_nota', $request->sd_nota)
                      ->where('sm_reff', $request->sd_nota)
                      ->update([
                        'sm_qty' => $convert,
                        'sm_residue' => $convert - $data->sm_use
                  ]);

                  $datamutcat = DB::table('m_mutcat')->where('m_status', 'M')->get();
                  for ($x=0; $x < count($datamutcat); $x++) {
                    $tmp[] = $datamutcat[$x]->m_id;
                  }

                  $jumlahstok = DB::table('d_stock')
                      ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                      ->where('s_position', $request->sd_from)
                      ->where('s_item', $request->idbarang[$i])
                      ->where('s_status', 'ON DESTINATION')
                      ->where('s_condition', 'FINE')
                      ->whereIn('sm_mutcat', $tmp)
                      ->sum('sm_residue');

                  $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

                  $data = DB::table('d_stock')
                            ->where('s_item', $request->idbarang[$i])
                            ->where('s_status', 'ON DESTINATION')
                            ->where('s_condition', 'FINE')
                            ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                            ->where('sm_nota', $request->sd_nota)
                            ->where('sm_mutcat', $mutcatkeluar->m_id)
                            ->first();

                  DB::table('d_stock')
                      ->where('s_id', $data->s_id)
                      ->where('s_comp', $data->s_comp)
                      ->where('s_position', $data->s_position)
                      ->where('s_item', $data->s_item)
                      ->where('s_status', 'ON DESTINATION')
                      ->where('s_condition', 'FINE')
                      ->update([
                          's_qty' => $jumlahstok
                      ]);

                 DB::table('d_stock_mutation')
                      ->where('sm_stock', $data->sm_stock)
                      ->where('sm_mutcat', $data->sm_mutcat)
                      ->where('sm_nota', $data->sm_nota)
                      ->where('sm_reff', $data->sm_reff)
                      ->update([
                        'sm_qty' => $convert
                      ]);

                  $data = DB::table('d_stock_mutation')
                            ->where('sm_stock', $data->sm_stock)
                            ->where('sm_nota', $data->sm_reff)
                            ->update([
                              'sm_use' => $convert,
                              'sm_residue' => DB::raw('sm_qty - ' . $convert)
                            ]);

            } elseif ($request->status[$i] == 'no') {
              $cek = DB::table('d_stockdistributiondt')->where('sdd_stockdistribution', $request->sd_id)->where('sdd_detailid', $request->sdd_detailid[$i])->where('sdd_item', $request->idbarang[$i])->count();
              if ($cek->count() == 0) {
                $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();

                if ($barang->i_unit1 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare1;
                } elseif ($barang->i_unit2 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare2;
                } elseif ($barang->i_unit3 == $request->satuan[$i]) {
                  $convert  = $request->qty[$i] * $barang->i_unitcompare3;
                }

                $dt = DB::table('d_stockdistributiondt')->max('sdd_detailid')+1;
                DB::table('d_stockdistributiondt')
                    ->insert([
                      'sdd_stockdistribution' => $request->sd_id,
                      'sdd_detailid' => $dt,
                      'sdd_comp' => $cek->sdd_comp,
                      'sdd_item' => $request->idbarang[$i],
                      'sdd_qty' => $convert,
                      'sdd_unit' => $request->satuan[$i]
                    ]);

                Mutasi::distribusicabangkeluar($request->sd_from, $request->sd_destination, $request->idbarang[$i], $convert, $request->sd_nota, $request->sd_nota);

              } else {
                DB::table('d_stockdistributiondt')
                    ->where('sdd_stockdistribution', $request->sd_id)
                    ->where('sdd_detailid', $request->sdd_detailid[$i])
                    ->update([
                      'sdd_qty' => $request->qty[$i],
                      'sdd_unit' => $request->satuan[$i]
                    ]);

                    $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();

                    $stock = DB::table('d_stock')
                              ->where('s_comp', $request->sd_from)
                              ->where('s_position', $request->sd_destination)
                              ->where('s_item', $request->idbarang[$i])
                              ->where('s_status', 'ON DESTINATION')
                              ->where('s_condition', 'FINE')
                              ->first();

                    $barang = DB::table('m_item')->where('i_id', $request->idbarang[$i])->first();

                    if ($barang->i_unit1 == $request->satuan[$i]) {
                      $convert  = $request->qty[$i] * $barang->i_unitcompare1;
                    } elseif ($barang->i_unit2 == $request->satuan[$i]) {
                      $convert  = $request->qty[$i] * $barang->i_unitcompare2;
                    } elseif ($barang->i_unit3 == $request->satuan[$i]) {
                      $convert  = $request->qty[$i] * $barang->i_unitcompare3;
                    }

                    DB::table('d_stock')
                              ->where('s_comp', $request->sd_from)
                              ->where('s_position', $request->sd_destination)
                              ->where('s_item', $request->idbarang[$i])
                              ->where('s_status', 'ON DESTINATION')
                              ->where('s_condition', 'FINE')
                              ->update([
                                's_qty' => $convert
                              ]);

                    $data = DB::table('d_stock_mutation')
                    ->where('sm_stock', $stock->s_id)
                    ->where('sm_mutcat', $mutcatmasuk->m_id)
                    ->where('sm_nota', $request->sd_nota)
                    ->where('sm_reff', $request->sd_nota)
                    ->first();

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock->s_id)
                        ->where('sm_mutcat', $mutcatmasuk->m_id)
                        ->where('sm_nota', $request->sd_nota)
                        ->where('sm_reff', $request->sd_nota)
                        ->update([
                          'sm_qty' => $convert,
                          'sm_residue' => $convert - $data->sm_use
                    ]);

                    $datamutcat = DB::table('m_mutcat')->where('m_status', 'M')->get();
                    for ($x=0; $x < count($datamutcat); $x++) {
                      $tmp[] = $datamutcat[$x]->m_id;
                    }

                    $jumlahstok = DB::table('d_stock')
                        ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                        ->where('s_position', $request->sd_destination)
                        ->where('s_item', $request->idbarang[$i])
                        ->where('s_status', 'ON DESTINATION')
                        ->where('s_condition', 'FINE')
                        ->whereIn('sm_mutcat', $tmp)
                        ->sum('sm_residue');

                    $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

                    $data = DB::table('d_stock')
                              ->where('s_item', $request->idbarang[$i])
                              ->where('s_status', 'ON DESTINATION')
                              ->where('s_condition', 'FINE')
                              ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                              ->where('sm_nota', $request->sd_nota)
                              ->where('sm_mutcat', $mutcatkeluar->m_id)
                              ->first();

                    DB::table('d_stock')
                        ->where('s_id', $data->s_id)
                        ->where('s_comp', $data->s_comp)
                        ->where('s_position', $data->s_position)
                        ->where('s_item', $data->s_item)
                        ->where('s_status', 'ON DESTINATION')
                        ->where('s_condition', 'FINE')
                        ->update([
                            's_qty' => $jumlahstok
                        ]);

                   DB::table('d_stock_mutation')
                        ->where('sm_stock', $data->sm_stock)
                        ->where('sm_mutcat', $data->sm_mutcat)
                        ->where('sm_nota', $data->sm_nota)
                        ->where('sm_reff', $data->sm_reff)
                        ->update([
                          'sm_qty' => $convert
                        ]);

                    $data = DB::table('d_stock_mutation')
                              ->where('sm_stock', $data->sm_stock)
                              ->where('sm_nota', $data->sm_reff)
                              ->update([
                                'sm_use' => $convert,
                                'sm_residue' => DB::raw('sm_qty - ' . $convert)
                              ]);
              }
            }
          }
      }
    }

      // DB::table('')

      DB::commit();
      return response()->json([
        'status' => 'berhasil'
      ]);
    } catch (Exception $e) {
      DB::rollback();
      return response()->json([
        'status' => 'gagal'
      ]);
    }

  }

}
