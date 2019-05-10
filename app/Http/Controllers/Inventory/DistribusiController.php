<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use CodeGenerator;
use Carbon\Carbon;
use DB;
use App\d_stockdistribution;
use App\d_stockdistributiondt;
use App\m_item;
use Mutasi;
use Validator;
use Yajra\DataTables\DataTables;

class DistribusiController extends Controller
{
    public function index()
    {
        return view('inventory/distribusibarang/index');
    }

    public function create()
    {
        $branch = DB::table('m_company')->where('c_type', 'CABANG')->get();

        return view('inventory/distribusibarang/distribusi/create', compact('branch'));
    }

    public function edit($id)
    {
        $data = DB::table('d_stockdistribution')->where('sd_id', decrypt($id))->first();

        $type = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

        if ($type->c_type == "CABANG") {
            $cabang = DB::table('m_company')->where('c_type', 'CABANG')->get();

            $dt = DB::table('d_stockdistributiondt')->join("m_item", 'i_id', '=', 'sdd_item')->join('m_unit', 'u_id', '=', 'sdd_unit')->where('sdd_stockdistribution', decrypt($id))->get();

            $tmp = DB::table('d_stock_mutation')
            ->where('sm_nota', $data->sd_nota)
            ->get();

            $mutcatmasuk = DB::table('m_mutcat')->where('m_name','Barang Masuk Distribusi Cabang')->first();
            $mutcatkeluar = DB::table('m_mutcat')->where('m_name','Barang Keluar Distribusi Cabang')->first();

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
            return view('inventory/distribusibarang/distribusi/edit');
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
    // get list items for AutoComplete
    public function getItem(Request $request)
    {
        // get list of existed-items (id)
        if ($request->existedItems != null)
        {
            $existedItems = array();
            for ($i = 0; $i < count($request->existedItems); $i++) {
                if ($request->existedItems[$i] != null) {
                    array_push($existedItems, $request->existedItems[$i]);
                }
            }
        }
        else
        {
            $existedItems = array();
        }
        // dd($request->existedItems, $existedItems);
        $cari = $request->term;
        $data = m_item::where('i_isactive', 'Y')
            ->whereNotIn('i_id', $existedItems)
            ->where(function ($query) use ($cari) {
                $query
                    ->where('i_name', 'like', '%'. $cari .'%')
                    ->orWhere('i_code', 'like', '%'. $cari .'%');
            })
            ->get();

        if (count($data) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($data as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . $query->i_name];
            }
        }

        return response()->json($results);
    }
    // get list unit for selct-option
    public function getListUnit(Request $request)
    {
        $units = m_item::where('i_id', $request->itemId)
        ->with('getUnit1')
        ->with('getUnit2')
        ->with('getUnit3')
        ->first();
        return $units;
    }
    // store new-distribusibarang to db
    public function store(Request $request)
    {
        // validate request
        $isValidRequest = $this->validateDist($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }
        DB::beginTransaction();
        try {
            $nota = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 16, 10, 3, 'DISTRIBUSI', '-');
            // dd($nota);

            foreach ($request->itemsId as $i => $itemId) {
                if ((int)$request->qty[$i] != 0) {
                    $item = m_item::where('i_id', $itemId)->first();

                    if ($item->i_unit1 == $request->units[$i]) {
                        $convert  = (int)$request->qty[$i] * $item->i_unitcompare1;
                    } elseif ($item->i_unit2 == $request->units[$i]) {
                        $convert  = (int)$request->qty[$i] * $item->i_unitcompare2;
                    } elseif ($item->i_unit3 == $request->units[$i]) {
                        $convert  = (int)$request->qty[$i] * $item->i_unitcompare3;
                    }
                    // waiit, on progress . . .
                    Mutasi::distribusicabangkeluar(
                        Auth::user()->u_company,
                        $request->selectBranch,
                        $itemId,
                        $convert,
                        $nota,
                        $nota
                    );
                }
            }

            $id = d_stockdistribution::max('sd_id') + 1;
            $dist = new d_stockdistribution;
            $dist->sd_id = $id;
            $dist->sd_from = Auth::user()->u_company;
            $dist->sd_destination = $request->selectBranch;
            $dist->sd_date = Carbon::now();
            $dist->sd_nota = $nota;
            $dist->sd_user = Auth::user()->u_id;
            $dist->save();

            foreach ($request->itemsId as $i => $itemId) {
                if ($request->qty[$i] != 0) {
                    $detailid = d_stockdistributiondt::max('sdd_detailid') + 1;
                    $distdt = new d_stockdistributiondt;
                    $distdt->sdd_stockdistribution = $id;
                    $distdt->sdd_detailid = $detailid;
                    $distdt->sdd_comp = Auth::user()->u_company;
                    $distdt->sdd_item = $itemId;
                    $distdt->sdd_qty = $request->qty[$i];
                    $distdt->sdd_unit = $request->units[$i];
                    $distdt->save();
                }
            }

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

        dd($request->all());
    }
    // validate request
    public function validateDist(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'selectBranch' => 'required',
            'itemsId.*' => 'required',
            'qty.*' => 'required'
        ],
        [
            'selectBranch.required' => 'Silahkan pilih \'Cabang\' terlebih dahulu !',
            'itemsId.*.required' => 'Masih terdapat baris item yang kosong !',
            'qty.*.required' => 'Masih terdapat \'Jumlah Item\' yang kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    // start: unused (maybe) -> deleted soon ===========================
    public function simpancabang(Request $request)
    {
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
    // end: unused (maybe) -> deleted soon ===========================
    // retrive DataTable for distribusibarang
    public function table(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = DB::table('d_stockdistribution')->whereBetween('sd_date', [$from, $to])->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('tanggal', function($data) {
            return '<td>'. Carbon::parse($data->sd_date)->format('d-m-Y') .'</td>';
        })
        ->addColumn('action', function($data) {
            return '<div class="btn-group btn-group-sm">
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
    // retrieve DataTable for history distribusibarang
    public function tableHistory(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->orderBy('sd_date', 'desc')
            ->orderBy('sd_nota', 'desc')
            ->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('tanggal', function($data) {
            return '<td>'. Carbon::parse($data->sd_date)->format('d-m-Y') .'</td>';
        })
        ->addColumn('tujuan', function($data){
            $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();
            return $tmp->c_name;
        })
        ->addColumn('action', function($data) {
            return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary" onclick="showDetailHt('. $data->sd_id .')"><i class="fa fa-folder"></i></button>
            </div>';
        })
        ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
        ->make(true);
    }
    // retrieve detail distribusibarang
    public function showDetailHt($id)
    {
        $detail = d_stockdistribution::where('sd_id', $id)
        ->with('getOrigin')
        ->with('getDestination')
        ->with(['getDistributionDt' => function ($query) {
            $query
                ->with('getItem')
                ->with('getUnit');
        }])
        ->first();
        $detail->dateFormated = Carbon::parse($detail->sd_date)->format('d M Y');

        return response()->json($detail);
    }

    public function hapus(Request $request)
    {
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

    public function updatecabang(Request $request)
    {
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
