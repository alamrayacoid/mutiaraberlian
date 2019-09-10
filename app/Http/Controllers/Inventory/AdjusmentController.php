<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;

use Carbon\Carbon;
use CodeGenerator;
use DB;
use Mutasi;

class AdjusmentController extends Controller
{
    public function index()
    {
        return view('inventory/manajemenstok/adjustment/index');
    }

    public function list()
    {
        $data = DB::table('d_adjusmentauth')->join('m_item', 'i_id', '=', 'aa_item')->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('tanggal', function ($data) {
            return '<td>' . Carbon::parse($data->aa_date)->format('d/m/Y') . '</td>';
        })
        ->addColumn('status', function ($data) {
            return '<td><button class="btn btn-primary status-pending" style="pointer-events: none">Pending</button></td>';
        })
        ->addColumn('action', function ($data) {
            return '<div class="btn-group btn-group-sm">
          <button class="btn btn-primary" type="button" onclick="cetak(' . $data->aa_id . ')" title="Print"><i class="fa fa-print"></i></button>
          </div>';
        })
        ->rawColumns(['tanggal', 'status', 'action'])
        ->make(true);
    }

    public function create()
    {
        $nota = DB::table('d_opname')->where('o_status', 'P')->get();

        return view('inventory/manajemenstok/adjustment/adjustmentstock/create', compact('nota'));
    }

    public function nota(Request $request)
    {
        $data = DB::table('d_opname')->join('m_item', 'i_id', '=', 'o_item')->where('o_id', $request->id)->first();

        $unitsistem = DB::table('m_unit')->where('u_id', '=', $data->o_unitsystem)->first();

        $unitreal = DB::table('m_unit')->where('u_id', '=', $data->o_unitreal)->first();

        return view('inventory/manajemenstok/adjustment/nota/index', compact('data', 'unitsistem', 'unitreal'));
    }

    public function getopname(Request $request)
    {
        $data = DB::table('d_opname')->where('o_nota', $request->nota)->first();

        $stock = DB::table('d_stock')->where('s_comp', $data->o_comp)->where('s_position', $data->o_position)->where('s_item', $data->o_item)->first();
// dd($data->o_qtysystem, $stock->s_qty);

        $item = DB::table('m_item')->where('i_id', $data->o_item)->first();

        $unit = [];

        $tmp = DB::table('m_unit')->where('u_id', $item->i_unit1)->first();

        $unit[] = $tmp;

        $tmp = DB::table('m_unit')->where('u_id', $item->i_unit2)->first();

        $unit[] = $tmp;

        $tmp = DB::table('m_unit')->where('u_id', $item->i_unit3)->first();

        $unit[] = $tmp;

        $unitsystem = DB::table('m_unit')->where('u_id', $data->o_unitsystem)->first();

        $unitreal = DB::table('m_unit')->where('u_id', $data->o_unitreal)->first();

        return response()->json([
            'item'       => $item,
            'data'       => $data,
            'unitsystem' => $unitsystem,
            'unitreal'   => $unitreal,
            'stock'      => $stock,
            'unit'       => $unit
        ]);
    }

    public function list_codeProduksi(Request $req)
    {
      $opname = DB::table('d_opname')->where('o_nota', '=', $req->nota)->first();

      $codes = DB::table('d_opnamedt')
        ->where('od_opname', '=', $opname->o_id)
        ->get();

      return Datatables::of($codes)
      ->make(true);
    }

    public function simpan(Request $request)
    {
        DB::beginTransaction();
        try {
            $notaAdjust = CodeGenerator::codeWithSeparator('d_adjusment', 'a_nota', 16, 10, 3, 'ADJUSTMENT', '-');
            $notaAdjustAuth = CodeGenerator::codeWithSeparator('d_adjusmentauth', 'aa_nota', 16, 10, 3, 'ADJUSTMENT', '-');
            if (strcmp($notaAdjust, $notaAdjustAuth) > 0) {
                $nota = $notaAdjust;
            }
            else {
                $nota = $notaAdjustAuth;
            };

            $adjId = DB::table('d_adjusmentauth')->max('aa_id') + 1;
            DB::table('d_adjusmentauth')->insert([
                'aa_id'         => $adjId,
                'aa_comp'       => $request->data['o_comp'],
                'aa_position'   => $request->data['o_position'],
                'aa_date'       => $request->data['o_date'],
                'aa_nota'       => $nota,
                'aa_item'       => $request->data['o_item'],
                'aa_qtyreal'    => $request->qtyreal,
                'aa_unitreal'   => $request->satuanreal,
                'aa_qtysystem'  => $request->data['o_qtysystem'],
                'aa_unitsystem' => $request->data['o_unitsystem'],
                'aa_insert'     => $request->data['o_insert']
            ]);

            for ($i=0; $i < count($request->code_real); $i++) {
                $adjDt = DB::table('d_adjustmentcodeauth')->where('aca_adjustment', '=', $adjId)->max('aca_detailid') + 1;
                DB::table('d_adjustmentcodeauth')->insert([
                    'aca_adjustment' => $adjId,
                    'aca_detailid'   => $adjDt,
                    'aca_code'       => $request->code_real[$i],
                    'aca_qty'        => $request->qty_code[$i]
                ]);
            }

            DB::table('d_opname')->where('o_nota', '=', $request->data['o_nota'])->update([
                'o_status' => 'Y'
            ]);

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Adjustment');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'ex' => $e->getMessage()
            ]);
        }

    }
}
