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

  public function distribusibarang_edit()
  {
      return view('inventory/distribusibarang/distribusi/edit');
  }

  public function getitem(Request $request){
    $keyword = $request->term;
    $results = [];

    $data = DB::table('m_item')->where('i_isactive', 'Y')->where('i_name', 'LIKE', '%'.$keyword.'%')->get();

            if ($data == null) {
                $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
            } else {

                foreach ($data as $query) {
                    $results[] = ['id' => $query->i_id, 'label' => $query->i_name . ' (' . $query->i_code . ')'];
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

    return response()->json($unit);
  }

  public function simpancabang(Request $request){
    DB::beginTransaction();
    try {

    $nota = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 8, 10, 3, 'DK', '-');

    for ($x=0; $x < count($request->namabarang); $x++) {
      Mutasi::distribusicabangkeluar(Auth::user()->u_company, $request->cabang, $request->idbarang[$x], $request->qty[$x], $nota, $nota);
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

  public function table(){
    $data = DB::table('d_stockdistribution')->get();

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
              <button class="btn btn-warning btn-edit-distribusi" onclick="window.location.href=' . route('distribusibarang.edit') . '" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
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

      DB::table('d_stockdistribution')
              ->where('sd_id', $request->id)
              ->delete();

      $dt = DB::table('d_stockdistributiondt')
            ->where('sdd_stockdistribution', $request->id)
            ->get();

      DB::table('d_stockdistributiondt')
            ->where('sdd_stockdistribution', $request->id)
            ->delete();

      $tmp = DB::table('d_stock_mutation')
                ->where('sm_nota', $parrent->sd_nota)
                ->get();

      $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Masuk')->first();
      $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Distribusi Cabang Keluar')->first();

      $reff = [];
      for ($i=0; $i < count($tmp); $i++) {
        if ($tmp[$i]->sm_mutcat == $mutcatkeluar->m_id) {
          $reff[] = $tmp[$i]->sm_reff;
        }
      }

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
