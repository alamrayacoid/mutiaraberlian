<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\d_productionorder as ProductionOrder;
use App\d_productionorderdt as ProductionOrderDT;
use App\d_productionordercode;
use App\m_supplier as Supplier;
use App\d_stock as Stock;
use App\d_stock_mutation as StockMutation;
use DB;
use Auth;
use Mockery\Exception;
use Response;
use Carbon\Carbon;
use CodeGenerator;
use Yajra\DataTables\DataTables;
use Crypt;
use Currency;
use Illuminate\Contracts\Encryption\DecryptException;

class ProduksiController extends Controller
{
    ////////////////////////////////////////////////////
    // Order Produksi
    public function removeCurrency($angka)
    {
        $angka = implode("", explode('Rp. ', $angka));
        $angka = implode("", explode('.', $angka));
        return $angka;
    }

    public function order_produksi()
    {
    	return view('produksi/orderproduksi/index');
    }

    public function getProduksiDetailItem(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        $data = DB::table('d_productionorderdt')
            ->select('m_item.i_name',
                'm_unit.u_name',
                'd_productionorderdt.pod_qty',
                'd_productionorderdt.pod_value',
                'd_productionorderdt.pod_totalnet')
            ->join('m_item', function ($q){
                $q->on('d_productionorderdt.pod_item', '=', 'm_item.i_id');
            })->join('m_unit', function ($q){
                $q->on('d_productionorderdt.pod_unit', '=', 'm_unit.u_id');
            })
            ->where('d_productionorderdt.pod_productionorder', '=', $id)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('item', function($data){
                return $data->i_name;
            })
            ->addColumn('unit', function($data){
                return $data->u_name;
            })
            ->addColumn('qty', function($data){
                return '<p class="text-center">'. $data->pod_qty .'</p>';
            })
            ->addColumn('value', function($data){
                return '<p class="text-right">'. Currency::addRupiah($data->pod_value) .'</p>';
            })
            ->addColumn('totalnet', function($data){
                return '<p class="text-right">'. Currency::addRupiah($data->pod_totalnet) .'</p><input type="hidden" class="totalnet" value="'.number_format($data->pod_totalnet,0,'','').'">';
            })
            ->rawColumns(['item','unit','qty','value','totalnet'])
            ->make(true);
    }

    public function getProduksiDetailTermin(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        $data = DB::table('d_productionorderpayment')
            ->select('pop_termin',
                'pop_datetop',
                'pop_value')
            ->where('pop_productionorder', '=', $id)
            ->orderBy('pop_termin', 'asc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('termin', function($data){
                return $data->pop_termin;
            })
            ->addColumn('date', function($data){
                return date('d-m-Y', strtotime($data->pop_datetop));
            })
            ->addColumn('value', function($data){
                return '<p class="text-right">'. Currency::addRupiah($data->pop_value) .'</p><input type="hidden" class="totaltermin" value="'.number_format($data->pop_value,0,'','').'">';
            })
            ->rawColumns(['termin','date','value'])
            ->make(true);
    }

    public function get_order(Request $request){
        $data = '';
        $getData = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
            // ->join('d_productionorderdt', function ($q){
            //     $q->on('pod_productionorder', '=', 'po_id');
            //     $q->on('pod_productionorder', '=', 'pop_productionorder');
            // })
            // ->where('pod_received', '=', 'N')
            ->groupBy('po_id')
            ->select(
                'po_id',
                'po_nota as nota',
                's_company as supplier',
                'po_totalnet as nilai_order',
                'po_status as status',
                DB::raw('sum(pop_pay) as terbayar')
            )
            ->orderBy('po_date', 'desc')
            ->orderBy('po_nota', 'desc');

        // get list production-order that is the item has been received
        $prodOrderReceived = ProductionOrder::whereHas('getPODt', function ($q) {
            $q->where('pod_received', 'Y');
        })
        ->select('po_id')
        ->get()
        ->toArray();
        $listReceivedProdOrdId = array();
        foreach ($prodOrderReceived as $key => $value) {
            array_push($listReceivedProdOrdId, $value['po_id']);
        }

        // // filter getData to just display un-received production-order
        // $getData = $getData->whereNotIn('po_id', $listReceivedProdOrdId);
        //
        $data = $getData->get();
        // $data = $getData;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nota', function($data){
                return $data->nota;
            })
            ->addColumn('supplier', function($data){
                return $data->supplier;
            })
            ->addColumn('totalnet', function($data){
                return '<div class="text-right">'.Currency::addRupiah($data->nilai_order).'</div>';
            })
            ->addColumn('bayar', function($data){
                return '<div class="text-right">'.Currency::addRupiah($data->terbayar).'</div>';
            })
            ->addColumn('status', function($data){
                if($data->status == 'BELUM'){
                   // return '<div class="status-termin-lunas"><p>BELUM LUNAS</p></div>';
                    return '<div class="text-center">BELUM LUNAS</div>';
                }else{
                   // return '<div class="status-termin-belum"><p>LUNAS</p></div>';
                    return '<div class="text-center">LUNAS</div>';
                }
            })
            ->addColumn('aksi', function($data) use ($listReceivedProdOrdId) {
                $detail = '<button class="btn btn-primary btn-modal" type="button" title="Detail Data" onclick="detailOrder(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-folder"></i></button>';
                if (in_array($data->po_id, $listReceivedProdOrdId)) {
                    $edit = '<button class="btn btn-warning btn-edit" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')" disabled><i class="fa fa-pencil"></i></button>';
                    $hapus = '<button class="btn btn-danger btn-disable" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')" disabled><i class="fa fa-trash"></i></button>';
                }
                else {
                    $edit = '<button class="btn btn-warning btn-edit" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-pencil"></i></button>';
                    $hapus = '<button class="btn btn-danger btn-disable" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-trash"></i></button>';
                }
                $nota = '<button class="btn btn-info btn-nota" title="Nota" type="button" onclick="printNota(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-print"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $nota . $edit . $hapus . '</div>';
            })
            ->rawColumns(['totalnet','bayar', 'status','aksi'])
            ->make(true);
    }

    public function create_produksi(Request $request)
    {
        if (!$request->isMethod('post')) {
            $suppliers = DB::table('m_supplier')
                ->select('s_id', 's_company')
                ->get();

            $units = DB::table('m_unit')->get();
            return view('produksi/orderproduksi/create')->with(compact('suppliers', 'units'));
        } else {
            $data = $request->all();
            $productionorderauth = [];
            $productionorderdt = [];
            $productionorderpayment = [];
            DB::beginTransaction();
            try{
                // dd($request);
                $idpo= (DB::table('d_productionorderdt')->max('pod_productionorder')) ? (DB::table('d_productionorderdt')->max('pod_productionorder')) + 1 : 1;
                $nota = CodeGenerator::codeWithSeparator('d_productionorderauth', 'poa_nota', 8, 10, 3, 'PO', '-');
                $productionorderauth[] = [
                    'poa_id' => $idpo,
                    'poa_nota' => $nota,
                    'poa_date' => date('Y-m-d', strtotime($data['po_date'])),
                    'poa_supplier' => $data['supplier'],
                    'poa_totalnet' => $data['tot_hrg'],
                    'poa_status' => 'BELUM'
                ];

                $poddetail = (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) ? (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) + 1 : 1;
                $detailpod = $poddetail;
                for ($i = 0; $i < count($data['idItem']); $i++) {
                    $productionorderdt[] = [
                        'pod_productionorder' => $idpo,
                        'pod_detailid' => $detailpod,
                        'pod_item' => $data['idItem'][$i],
                        'pod_qty' => $data['jumlah'][$i],
                        'pod_unit' => $data['satuan'][$i],
                        'pod_value' => $this->removeCurrency($data['harga'][$i]),
                        'pod_totalnet' => $this->removeCurrency($data['subtotal'][$i])
                    ];
                    $detailpod++;
                }

                for ($i = 0; $i < count($data['termin']); $i++) {
                    $productionorderpayment[] = [
                        'pop_productionorder' => $idpo,
                        'pop_termin' => $data['termin'][$i],
                        'pop_datetop' => date('Y-m-d', strtotime($data['estimasi'][$i])),
                        'pop_value' => $this->removeCurrency($data['nominal'][$i]),
                    ];
                }

                // dd($productionorderpayment);
                DB::table('d_productionorderauth')->insert($productionorderauth);
                DB::table('d_productionorderdt')->insert($productionorderdt);
                DB::table('d_productionorderpayment')->insert($productionorderpayment);
                DB::commit();
                return json_encode([
                    'status' => 'Success'
                ]);
            }catch (\Exception $e){
                DB::rollBack();
                return json_encode([
                    'status' => 'Failed',
                    'msg' => $e
                ]);
            }
        }
    }

    public function edit_produksi(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return abort(404);
        }

        $suppliers = DB::table('m_supplier')
            ->select('s_id', 's_company')
            ->get();

        $dataEdit = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('po_id', $id)->first();

        $dataEditDT = DB::table('d_productionorderdt')
            ->select('d_productionorderdt.*', 'm_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2',
                'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->where('pod_productionorder', $id)->get();

        $dataEditPmt = DB::table('d_productionorderpayment')
            ->where('pop_productionorder', $id)
            ->get();

        $oid = Crypt::encrypt($id);

        return view('produksi/orderproduksi/edit')->with(compact('dataEdit', 'dataEditDT', 'dataEditPmt', 'suppliers', 'oid'));
    }

    public function editOrderProduksi(Request $request)
    {
        $data                   = $request->all();
        try{
            $id = Crypt::decrypt($data['orderId']);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        $tanggal                = date('Y-m-d', strtotime($data['po_date']));
        $supplier               = $data['sup'];
        $totalnet               = $data['tot_hrg'];
        $productionorder        = [];
        $productionorderdt      = [];
        $productionorderpayment = [];

        DB::beginTransaction();
        try{
            $productionorder = [
                'po_date' => $tanggal,
                'po_supplier' => $supplier,
                'po_totalnet' => $totalnet
            ];

            $poddetail = (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->max('pod_detailid')) ? (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->max('pod_detailid')) + 1 : 1;
            $detailpod = $poddetail;
            for ($i = 0; $i < count($data['idItem']); $i++) {
                $productionorderdt[] = [
                    'pod_productionorder' => $id,
                    'pod_detailid' => $detailpod,
                    'pod_item' => $data['idItem'][$i],
                    'pod_qty' => $data['jumlah'][$i],
                    'pod_unit' => $data['satuan'][$i],
                    'pod_value' => $this->removeCurrency($data['harga'][$i]),
                    'pod_totalnet' => $this->removeCurrency($data['subtotal'][$i])
                ];
                $detailpod++;
            }

            for ($i = 0; $i < count($data['termin']); $i++) {
                $productionorderpayment[] = [
                    'pop_productionorder' => $id,
                    'pop_termin' => $data['termin'][$i],
                    'pop_datetop' => date('Y-m-d', strtotime($data['estimasi'][$i])),
                    'pop_value' => $this->removeCurrency($data['nominal'][$i]),
                ];
            }

            // dd($productionorderpayment);
            DB::table('d_productionorder')->where('po_id', '=', $id)->update($productionorder);
            DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->delete();
            DB::table('d_productionorderdt')->insert($productionorderdt);
            DB::table('d_productionorderpayment')->where('pop_productionorder', '=', $id)->delete();
            DB::table('d_productionorderpayment')->insert($productionorderpayment);
            DB::commit();
            return json_encode([
                'status' => 'Success'
            ]);
        }catch (\Exception $e){
            DB::rollBack();
            return json_encode([
                'status' => 'Failed',
                'msg' => $e
            ]);
        }
    }

    public function delete_produksi($id = null){
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try{

            DB::table('d_productionorderpayment')->where('pop_productionorder', '=', $id)->delete();
            DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->delete();
            DB::table('d_productionorder')->where('po_id', '=', $id)->delete();
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function deleteItemProduksi($order = null, $detail = null, $item = null)
    {
        try{
            $order = Crypt::decrypt($order);
            $detail = Crypt::decrypt($detail);
            $item = Crypt::decrypt($item);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try{
            DB::table('d_productionorderdt')
            ->where('pod_productionorder', '=', $order)
            ->where('pod_detailid', '=', $detail)
            ->where('pod_item', '=', $item)
            ->delete();
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function deleteTerminProduksi($order = null, $termin = null)
    {
        try{
            $order = Crypt::decrypt($order);
            $termin = Crypt::decrypt($termin);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try{
            DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $order)
                ->where('pop_termin', '=', $termin)
                ->delete();
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function cariBarang(Request $request)
    {
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        if(count($is_item) == 0){
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }else{
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->whereNotIn('i_id', $is_item)
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function printNota($id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return abort(404);
        }

        $header = DB::table('d_productionorder')
            ->select('d_productionorder.po_nota as nota', 'd_productionorder.po_date as tanggal', 'm_supplier.s_name as supplier')
            ->join('m_supplier', function ($q){
                $q->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->where('po_id', '=', $id)
            ->first();

        $item = DB::table('d_productionorderdt')
            ->select('m_item.i_name as barang',
                'm_unit.u_name as satuan',
                'd_productionorderdt.pod_qty as qty',
                'd_productionorderdt.pod_value as value',
                'd_productionorderdt.pod_totalnet as totalnet')
            ->join('m_item', function ($q){
                $q->on('d_productionorderdt.pod_item', '=', 'm_item.i_id');
            })->join('m_unit', function ($q){
                $q->on('d_productionorderdt.pod_unit', '=', 'm_unit.u_id');
            })
            ->where('d_productionorderdt.pod_productionorder', '=', $id)
            ->get();

        $termin = DB::table('d_productionorderpayment')
            ->select('pop_termin as termin',
                'pop_datetop as tanggal',
                'pop_value as value')
            ->where('pop_productionorder', '=', $id)
            ->orderBy('pop_termin', 'asc')
            ->get();

        return view('produksi.orderproduksi.nota')->with(compact('item', 'termin', 'header'));
    }

    /////////////////////////////////////////////////////
    // Penerimaan Barang


    /////////////////////////////////////////////////////
    // Pembayaran
    public function pembayaran()
    {
    	return view('produksi/pembayaran/index');
    }

    /////////////////////////////////////////////////////
    // Return Produksi
    public function return_produksi()
    {
    	return view('produksi/returnproduksi/index');
    }

    public function listReturn(Request $request)
    {
        $data = DB::table('d_returnproductionorder')
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->select('rpo_productionorder as id', 'rpo_detailid as detail', 'rpo_date as tanggal', 'rpo_nota as nota', 'rpo_action as metode', 'rpo_item as idItem', 'i_name as barang',
                'rpo_qty as qty', 'u_name as satuan');

        if ($request->awal != null){
            $awal = Carbon::createFromFormat('d-m-Y', $request->awal)->format("Y-m-d");
            $data->where('rpo_date', '>=', $awal);
        }
        if ($request->akhir != null){
            $akhir = Carbon::createFromFormat('d-m-Y', $request->akhir)->format("Y-m-d");
            $data->where('rpo_date', '<=', $akhir);
        }
        if ($request->awal == null && $request->akhir == null){
            $date = Carbon::now()->format('Y-m-d');
            $data->where('rpo_date', '=', $date);
        }

        return DataTables::of($data)
            ->addColumn('tanggal', function($data){
                return Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->addColumn('nota', function($data){
                return $data->nota;
            })
            ->addColumn('metode', function($data){
                if ($data->metode == "GB") {
                    return "Ganti Barang";
                } else if ($data->metode == "PT") {
                    return "Potong Tagihan";
                } else if ($data->metode == "RD") {
                    return "Return Dana";
                }
            })
            ->addColumn('barang', function($data){
                return $data->barang;
            })
            ->addColumn('qty', function($data){
                return $data->qty . ' ' . $data->satuan;
            })
            ->addColumn('action', function($data){
                $detail = '<button class="btn btn-primary" type="button" title="Detail" onclick="detailReturn(\''.Crypt::encrypt($data->id).'\', \''.Crypt::encrypt($data->detail).'\')"><i class="fa fa-folder"></i></button>';
                $edit = '<button class="btn btn-warning" type="button" title="Edit" onclick="editReturn(\''.Crypt::encrypt($data->id).'\', \''.Crypt::encrypt($data->detail).'\', \''.Crypt::encrypt($data->idItem).'\')"><i class="fa fa-pencil-square-o"></i></button>';
                $hapus = '<button class="btn btn-danger" type="button" title="Hapus" onclick="hapusReturn(\''.Crypt::encrypt($data->id).'\', \''.Crypt::encrypt($data->detail).'\', \''.$data->qty.'\')"><i class="fa fa-trash-o"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $edit . $hapus .'</div>';
            })
            ->rawColumns(['tanggal','nota', 'metode', 'barang', 'qty', 'action'])
            ->make(true);
    }

    public function detailReturn($id = null, $detail = null)
    {
        try{
            $id     = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> "Data tidak ditemukan"
            ]);
        }

        $data = DB::table('d_returnproductionorder')
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->where('rpo_productionorder', $id)
            ->where('rpo_detailid', $detail);

        if ($data->count() == 0) {
            return Response::json([
                'status' => "Failed",
                'message'=> "Data tidak ditemukan"
            ]);
        } else {
            if ($data->first()->rpo_action == "GB") {
                $metode = "Ganti Barang";
            } else if ($data->first()->rpo_action == "PT") {
                $metode = "Potong Tagihan";
            } else if ($data->first()->rpo_action == "RD") {
                $metode = "Return Dana";
            }

            $val = [
                'tanggal'    => Carbon::parse($data->first()->rpo_date)->format('d-m-Y'),
                'nota'       => $data->first()->rpo_nota,
                'barang'     => $data->first()->i_name,
                'qty'        => $data->first()->rpo_qty . ' ' . $data->first()->u_name,
                'metode'     => $metode,
                'keterangan' => $data->first()->rpo_note
            ];

            return Response::json([
                'status' => "Success",
                'message'=> $val
            ]);
        }
    }

    public function getEditReturn($id = null, $detail = null)
    {
        try{
            $id     = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> "Data tidak ditemukan"
            ]);
        }

        $data = DB::table('d_returnproductionorder')
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->where('rpo_productionorder', $id)
            ->where('rpo_detailid', $detail);

        if ($data->count() == 0) {
            return Response::json([
                'status' => "Failed",
                'message'=> "Data tidak ditemukan"
            ]);
        } else {

            $satuan = $this->setSatuan(Crypt::encrypt($data->first()->rpo_item));

            if ($data->first()->rpo_action == "GB") {
                $metode = "Ganti Barang";
            } else if ($data->first()->rpo_action == "PT") {
                $metode = "Potong Tagihan";
            } else if ($data->first()->rpo_action == "RD") {
                $metode = "Return Dana";
            }

            $val = [
                'tanggal'    => Carbon::parse($data->first()->rpo_date)->format('d-m-Y'),
                'nota'       => $data->first()->rpo_nota,
                'barang'     => $data->first()->i_name,
                'qty_return' => $data->first()->rpo_qty . ' ' . $data->first()->u_name,
                'qty'        => $data->first()->rpo_qty,
                'unit'       => $data->first()->i_unit1,
                'txtmetode'     => $metode,
                'metode'     => $data->first()->rpo_action,
                'keterangan' => $data->first()->rpo_note
            ];

            return Response::json([
                'status' => "Success",
                'message'=> $val,
                'satuan' => $satuan
            ]);
        }
    }

    public function create_return_produksi()
    {
        return view('produksi/returnproduksi/create');
    }

    public function getNotaProductionOrder(Request $request)
    {

        if ($request->dateStart != null) {
            $data = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
                ->join('m_supplier', 's_id', '=', 'po_supplier')
                ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');

            if ($request->dateStart != ""){
                $data->whereDate('po_date', '>=', Carbon::parse($request->dateStart)->format('Y-m-d'));
            }
            if ($request->dateEnd != ""){
                $data->whereDate('po_date', '<=', Carbon::parse($request->dateEnd)->format('Y-m-d'));
            }
            if ($request->supplier != ""){
                $data->where('po_supplier', '=', $request->supplier);
            }
            return DataTables::of($data)
                ->addColumn('supplier', function($data){
                    return $data->supplier;
                })
                ->addColumn('tanggal', function($data){
                    return date('d-m-Y', strtotime($data->tanggal));
                })
                ->addColumn('nota', function($data){
                    return $data->nota;
                })
                ->addColumn('action', function($data){
                    $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\''.Crypt::encrypt($data->po_id).'\')"><i class="fa fa-folder"></i></button>';
                    $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Pilih" onclick="pilih(\''.Crypt::encrypt($data->po_id).'\', \''.$data->nota.'\')"><i class="fa fa-arrow-down"></i></button>';
                    return '<div class="btn-group btn-group-sm">'. $detail . $ambil . '</div>';
                })
                ->rawColumns(['supplier','tanggal', 'nota','action'])
                ->make(true);
        } else {
            $data = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
                ->join('m_supplier', 's_id', '=', 'po_supplier')
                ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');

            return DataTables::of($data)
                ->addColumn('supplier', function($data){
                    return $data->supplier;
                })
                ->addColumn('tanggal', function($data){
                    return date('d-m-Y', strtotime($data->tanggal));
                })
                ->addColumn('nota', function($data){
                    return $data->nota;
                })
                ->addColumn('action', function($data){
                    $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\''.Crypt::encrypt($data->po_id).'\')"><i class="fa fa-folder"></i></button>';
                    $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Pilih" onclick="pilih(\''.Crypt::encrypt($data->po_id).'\', \''.$data->nota.'\')"><i class="fa fa-arrow-down"></i></button>';
                    return '<div class="btn-group btn-group-sm">'. $detail . $ambil . '</div>';
                })
                ->rawColumns(['supplier','tanggal', 'nota','action'])
                ->make(true);
        }
    }

    public function detailNota($id = null)
    {
        $data = ProductionOrder::where('po_id', Crypt::decrypt($id))
            ->join('d_productionorderdt', 'po_id', '=', 'pod_productionorder')
            ->join('m_item', 'pod_item', '=', 'i_id')
            ->select('m_item.i_name as barang', 'd_productionorderdt.pod_qty as qty', DB::raw("CONCAT('Rp. ',FORMAT(d_productionorderdt.pod_totalnet, 0, 'de_DE')) as harga"));

        return DataTables::of($data)
            ->addColumn('barang', function($data){
                return $data->barang;
            })
            ->addColumn('qty', function($data){
                return $data->qty;
            })
            ->addColumn('harga', function($data){
                return $data->harga;
            })
            ->rawColumns(['barang','qty', 'harga'])
            ->make(true);
    }

    public function searchSupplier(Request $request)
    {
        $cari = $request->term;
        $nama = Supplier::where(function ($q) use ($cari){
                $q->orWhere('s_company', 'like', '%'.$cari.'%');
                $q->orWhere('s_name', 'like', '%'.$cari.'%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->s_id, 'label' => strtoupper($query->s_name) . ' - ' .strtoupper($query->s_company), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function searchNota(Request $request)
    {
        $data = ProductionOrder::join('m_supplier', 's_id', '=', 'po_supplier')
            ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');

        if ($request->pemilik != "") {
            $data->where('d_stock.s_comp', '=', $request->pemilik);
        } else if ($request->posisi != "") {
            $data->where('d_stock.s_position', '=', $request->posisi);
        } else if ($request->item != "") {
            $data->where('d_stock.s_item', '=', $request->item);
        }

        return DataTables::of($data)
            ->addColumn('supplier', function($data){
                return $data->supplier;
            })
            ->addColumn('tanggal', function($data){
                return date('d-m-Y', strtotime($data->tanggal));
            })
            ->addColumn('nota', function($data){
                return $data->nota;
            })
            ->addColumn('action', function($data){
                $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder"></i></button>';
                $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Ambil"><i class="fa fa-hand-lizard-o"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $ambil . '</div>';
            })
            ->rawColumns(['supplier','tanggal', 'nota','action'])
            ->make(true);
    }

    // set return for autocomplete searching using production-code
    public function cariProdKode(Request $request)
    {
        $cari = $request->term;
        $prodCode = d_productionordercode::where('poc_productioncode', 'like', '%'.$cari.'%')
        ->with('getItem')
        ->groupBy('poc_productioncode')
        ->orderBy('poc_productioncode', 'desc')
        ->get();

        if (count($prodCode) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($prodCode as $query) {
                $results[] = [
                    'id' => Crypt::encrypt($query->poc_productionorder),
                    'label' => $query->poc_productioncode,
                    'nota' => $query->getProductionOrder->po_nota,
                    'prodCode' => $query->poc_productioncode
                ];
            }
        }
        return Response::json($results);
    }

    // set return for autocomplete searching using no-nota of production-order
    public function cariNota(Request $request)
    {
        $cari = $request->term;
        $nama = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
        ->where(function ($q) use ($cari){
            $q->orWhere('ir_notapo', 'like', '%'.$cari.'%');
        })
        ->join('m_supplier', 's_id', '=', 'po_supplier')
        ->orderBy('po_date', 'desc')
        ->orderBy('po_nota', 'desc')
        ->get();
        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => Crypt::encrypt($query->po_id),
                    'label' => $query->po_nota . ' - ' . $query->s_company,
                    'nota' => $query->po_nota,
                    'prodCode' => '-'
                ];
            }
        }
        return Response::json($results);
    }

    // find detailed selected items (after using autocomplete)
    public function cariBarangPO(Request $request)
    {
        $id = $request->id;
        $prodCode = $request->prodCode;
        $nota = $request->nota;
        $searchBy = $request->searchBy;

        // if user search using nota of production-order
        if ($searchBy == 'nota') {
            $data = ProductionOrderDT::join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit', 'u_id', '=', 'pod_unit')
            ->where('pod_productionorder', '=', Crypt::decrypt($id))
            ->select('pod_productionorder', 'pod_item', 'i_name', 'pod_qty', 'u_name', 'pod_value', 'pod_totalnet');

            return DataTables::of($data)
            ->addColumn('barang', function($data){
                return $data->i_name;
            })
            ->addColumn('qty', function($data){
                return $data->pod_qty . ' - ' . $data->u_name;
            })
            ->addColumn('harga', function($data){
                return number_format($data->pod_value, 2, '.', ',');
            })
            ->addColumn('total', function($data){
                return number_format($data->pod_totalnet, 2, '.', ',');
            })
            ->addColumn('action', function($data){
                $qty = $data->pod_qty . ' - ' . $data->u_name;
                $pilih = '<button class="btn btn-sm btn-primary" title="Pilih" onclick="selectItem(\''.
                    'nota'.'\', \''.
                    Crypt::encrypt($data->pod_productionorder).'\', \''.
                    Crypt::encrypt($data->pod_item).'\', \''.
                    $data->i_name.'\', \''.
                    $qty.'\', \''.
                    $data->pod_value.'\', \''.
                    $data->pod_totalnet.
                    '\')"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $pilih . '</div>';
            })
            ->rawColumns(['barang','qty', 'harga', 'total', 'action'])
            ->make(true);
        }
        // if user search using production-code
        elseif ($searchBy == 'kodeproduksi') {
            $prodCd = d_productionordercode::where('poc_productionorder', Crypt::decrypt($id))
            ->where('poc_productioncode', $prodCode)
            ->with('getItem')
            ->with('getUnit')
            ->groupBy('poc_productioncode')
            ->selectRaw('*, SUM(poc_qty) as qty')
            ->get();

            $prodOrder = ProductionOrderDT::where('pod_productionorder', Crypt::decrypt($id))
            ->where('pod_item', $prodCd[0]->poc_item)
            ->first();

            return DataTables::of($prodCd)
            ->addColumn('barang', function($prodCd){
                return $prodCd->getItem->i_name;
            })
            ->addColumn('qty', function($prodCd){
                return $prodCd->qty . ' - ' . $prodCd->getUnit->u_name;
            })
            ->addColumn('harga', function($prodCd) use ($prodOrder){
                return number_format($prodOrder->pod_value, 2, '.', ',');
            })
            ->addColumn('total', function($prodCd) use ($prodOrder){
                $total = $prodCd->qty * $prodOrder->pod_value;
                return number_format($total, 2, '.', ',');
            })
            ->addColumn('action', function($prodCd) use ($prodOrder){
                $qty = $prodCd->qty . ' - ' . $prodCd->getUnit->u_name;
                $pilih = '<button class="btn btn-sm btn-primary" title="Pilih" onclick="selectItem(\''.
                    'kodeproduksi'.'\', \''.
                    Crypt::encrypt($prodCd->poc_productionorder).'\', \''.
                    Crypt::encrypt($prodCd->poc_item).'\', \''.
                    $prodCd->getItem->i_name.'\', \''.
                    $qty.'\', \''.
                    $prodOrder->pod_value.'\', \''.
                    $prodCd->qty * $prodOrder->pod_value.
                    '\')"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $pilih . '</div>';
            })
            ->rawColumns(['barang','qty', 'harga', 'total', 'action'])
            ->make(true);
        }
    }

    // find item-unit
    public function setSatuan($id = null)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', Crypt::decrypt($id))
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function addReturn(Request $request)
    {
        try{
            $poid = Crypt::decrypt($request->idPO);
            $idItem = Crypt::decrypt($request->idItem);
        }
        catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }

        $detailid = (DB::table('d_returnproductionorder')->where('rpo_productionorder', $poid)->max('rpo_detailid')) ? DB::table('d_returnproductionorder')->where('rpo_productionorder', $poid)->max('rpo_detailid')+1 : 1;
        // return-po/001/23/03/2019
        $nota = CodeGenerator::codeWithSeparator('d_returnproductionorder', 'rpo_nota', 15, 10, 3, 'RETURN-PO', '/');

        $data_check = DB::table('d_productionorder')
        ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
        'm_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
        'm_item.i_unit3 as unit3', 'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_value as value')
        ->join('d_productionorderdt', function ($x) use ($idItem){
            $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
            $x->where('d_productionorderdt.pod_item', '=', $idItem);
        })
        ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
        ->where('d_productionorder.po_id', '=', $poid)
        ->first();

        $qty_compare = 0;
        if ($request->satuan_return == $data_check->unit1) {
            $qty_compare = (int)$request->qty_return;
        } else if ($request->satuan_return == $data_check->unit2) {
            $qty_compare = $request->qty_return * $data_check->compare2;
        } else if ($request->satuan_return == $data_check->unit3) {
            $qty_compare = $request->qty_return * $data_check->compare3;
        }

        // if searchMethod is using 'kodeproduksi'
        if ($request->searchMethod == 'kodeproduksi')
        {
            // get production-code by PO-id and production-code-number
            $data = d_productionordercode::where('poc_productionorder', $poid)
            ->where('poc_productioncode', $request->prodCode)
            ->get();

            // get qty-item from production-code
            $qtydata_compare = 0;
            $totalQty = 0;
            foreach ($data as $key => $val) {
                if ($val->poc_unit == $data_check->unit1) {
                    $qtydata_compare = $val->poc_qty;
                } else if ($val->poc_unit == $data_check->unit2) {
                    $qtydata_compare = $val->poc_qty * $data_check->compare2;
                } else if ($val->poc_unit == $data_check->unit3) {
                    $qtydata_compare = $val->poc_qty * $data_check->compare3;
                }
                $totalQty += $qtydata_compare;
            }

            // return-failed if qty-return > qty-item
            if ((int)$qty_compare > (int)$totalQty) {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> "Jumlah permintaan pengembalian melebihi jumlah yang tersedia !"
                ]);
            }

        }
        // if searchMethod is using 'nota'
        elseif ($request->searchMethod == 'nota')
        {
            // get production-order-dt by PO-id and item-id
            $data = ProductionOrderDT::where('pod_productionorder', $poid)
            ->where('pod_item', $idItem)
            ->get();

            // get qty-item from production-order-dt
            $qtydata_compare = 0;
            $totalQty = 0;
            foreach ($data as $key => $val) {
                if ($val->pod_unit == $data_check->unit1) {
                    $qtydata_compare = $val->pod_qty;
                } else if ($val->pod_unit == $data_check->unit2) {
                    $qtydata_compare = $val->pod_qty * $data_check->compare2;
                } else if ($val->pod_unit == $data_check->unit3) {
                    $qtydata_compare = $val->pod_qty * $data_check->compare3;
                }
                $totalQty += $qtydata_compare;
            }

            // return-failed if qty-return > qty-item
            if ((int)$qty_compare > (int)$totalQty) {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> "Jumlah permintaan pengembalian melebihi jumlah yang tersedia !"
                ]);
            }
        }

        DB::beginTransaction();
        try{
            $values = [
                'rpo_productionorder' => $poid,
                'rpo_detailid'        => $detailid,
                'rpo_date'            => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                'rpo_nota'            => $nota,
                'rpo_item'            => $idItem,
                'rpo_qty'             => $qty_compare,
                'rpo_action'          => $request->methode_return,
                'rpo_note'            => $request->note_return
            ];

            $comp = Auth::user()->u_company;
            // // update stock
            // $get_stock = Stock::where('s_comp', $comp)
            // ->where('s_position', $comp)
            // ->where('s_item', $idItem)
            // ->where('s_status', 'ON DESTINATION')
            // ->where('s_condition', 'FINE');

            $mutasi = Mutasi::mutasikeluar(
                15, // mutcat
                $comp, // item owner
                $comp, // destination
                $idItem, // item id
                $request->qty_return, // qty
                $nota, // nota
                null, // sellprice
                null, // list of productioncode
                null, // list qty of productioncode
                $request->notaPO // reff
            );
            if (!is_bool($mutasi)) {
                return $mutasi;
            }
            // dd('x');
            //
            // $get_stockmutation = StockMutation::where('sm_stock', $get_stock->first()->s_id)
            // ->where('sm_nota', $request->notaPO);
            //
            // if ($get_stock->count() > 0)
            // {
            //     $val_stock = [
            //         's_qty' => $get_stock->first()->s_qty - $qty_compare
            //     ];
            // }
            // else
            // {
            //     return Response::json([
            //         'status' => "Failed",
            //         'message' => "Stock tidak ditemukan"
            //     ]);
            // }
            //
            // if ($get_stockmutation->count() > 0)
            // {
            //     if ($get_stockmutation->first()->sm_use == $get_stockmutation->first()->sm_qty || $get_stockmutation->first()->sm_residue == 0)
            //     {
            //         return Response::json([
            //             'status' => "Failed",
            //             'message' => "Jumlah barang tidak tersedia"
            //         ]);
            //     }
            //     else if ($get_stockmutation->first()->sm_use < $get_stockmutation->first()->sm_qty)
            //     {
            //         Mutasi::mutasikeluar(
            //             15,
            //             $comp,
            //             $comp,
            //             $idItem,
            //             $request->qty_return,
            //             $nota,
            //             $request->notaPO,
            //
            //         );
            //     }
            // }
            // else
            // {
            //     return Response::json([
            //         'status' => "Failed",
            //         'message' => "Stock mutasi tidak ditemukan"
            //     ]);
            // }
            // $get_stock->update($val_stock);

            // insert return
            DB::table('d_returnproductionorder')->insert($values);
            // $get_stockmutation->update($val_stockmutation);
            DB::commit();

            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil disimpan",
                'id'     => Crypt::encrypt($poid),
                'detail' => Crypt::encrypt($detailid)
            ]);
        }
        catch (Exception $e)
        {
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }

    public function editReturn(Request $request)
    {
        $comp = Auth::user()->u_company;
        try{
            $rpoid      = Crypt::decrypt($request->idRPO);
            $rpo_detail = Crypt::decrypt($request->idDetail);
            $item       = Crypt::decrypt($request->idItem);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }

        $data_check = DB::table('m_item')
            ->select('i_unitcompare1 as compare1', 'i_unitcompare2 as compare2',
                'i_unitcompare3 as compare3', 'i_unit1 as unit1', 'i_unit2 as unit2', 'i_unit3 as unit3')
            ->where('i_id', '=', $item)
            ->first();

        $qty_compare = 0;
        if ($request->satuan_return_edit == $data_check->unit1) {
            $qty_compare = $request->qty_return_edit;
        } else if ($request->satuan_return_edit == $data_check->unit2) {
            $qty_compare = $request->qty_return_edit * $data_check->compare2;
        } else if ($request->satuan_return_edit == $data_check->unit3) {
            $qty_compare = $request->qty_return_edit * $data_check->compare3;
        }

        DB::beginTransaction();
        try{
            $stock = Stock::where('s_comp', $comp)
                    ->where('s_position', $comp)
                    ->where('s_item', $item)
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE');

            $stock_mutation = StockMutation::where('sm_stock', $stock->first()->s_id);

            $stock_awal = $stock->first()->s_qty + $request->qty_current;

            $sm_qtyawal     = $stock_mutation->first()->sm_qty + $request->qty_current;
            $sm_residueawal = $sm_qtyawal - $stock_mutation->first()->sm_use;
            $sm_qty         = $sm_qtyawal - $qty_compare;
            $sm_residue     = $sm_qty - $stock_mutation->first()->sm_use;

            $val_mutasi = [
                'sm_qty'     => $sm_qty,
                'sm_residue' => $sm_residue
            ];

            $val_stock = [
                's_qty' => $stock_awal - $qty_compare
            ];

            $val_return = [
                'rpo_qty'    => $qty_compare,
                'rpo_action' => $request->methode_return_edit,
                'rpo_note'   => $request->note_return_edit
            ];

            $stock_mutation->update($val_mutasi);
            $stock->update($val_stock);
            DB::table('d_returnproductionorder')
                ->where('rpo_productionorder', $rpoid)
                ->where('rpo_detailid', $rpo_detail)
                ->update($val_return);

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil diperbarui",
                'id'     => Crypt::encrypt($rpoid),
                'detail' => Crypt::encrypt($rpo_detail)
            ]);
        }catch (Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }
    }

    function deleteReturn($id = null, $detail = null, $qty = null)
    {
        $comp = Auth::user()->u_company;
        try{
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }

        if ($qty == null || $qty == "") {
            return Response::json([
                'status' => "Failed",
                'message'=> "Kuantitas barang tidak diketahui"
            ]);
        }

        $return_po = DB::table('d_returnproductionorder')
            ->where('rpo_productionorder', $id)
            ->where('rpo_detailid', $detail);

        if ($return_po->count() == 0) {
            return Response::json([
                'status' => "Failed",
                'message'=> "Data return produksi tidak ditemukan"
            ]);
        } else {
            $stock = Stock::where('s_comp', $comp)
                ->where('s_position', $comp)
                ->where('s_item', $return_po->first()->rpo_item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE');

            $stock_mutation = StockMutation::where('sm_stock', $stock->first()->s_id);

            $s_qty      = $stock->first()->s_qty + $qty;
            $sm_qty     = $stock_mutation->first()->sm_qty + $qty;
            $sm_residue = $sm_qty - $stock_mutation->first()->sm_use;

            $val_mutasi = [
                'sm_qty'     => $sm_qty,
                'sm_residue' => $sm_residue
            ];

            $val_stock = [
                's_qty' => $s_qty
            ];

            DB::beginTransaction();
            try{
                $stock_mutation->update($val_mutasi);
                $stock->update($val_stock);
                $return_po->delete();
                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message'=> "Data berhasil dihapus"
                ]);
            }catch (Exception $e){
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> $e
                ]);
            }
        }
    }

    public function notaReturn($id = null, $detail = null)
    {
        try{
            $id     = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }catch (DecryptException $e){
            return abort(404);
        }

        $data = DB::table('d_returnproductionorder')
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->join('d_productionorder', 'po_id', '=', 'rpo_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('rpo_productionorder', $id)
            ->where('rpo_detailid', $detail);

        if ($data->count() == 0) {
            $val = [];
        } else {
            if ($data->first()->rpo_action == "GB") {
                $metode = "Ganti Barang";
            } else if ($data->first()->rpo_action == "PT") {
                $metode = "Potong Tagihan";
            } else if ($data->first()->rpo_action == "RD") {
                $metode = "Return Dana";
            }

            $val = [
                'tanggal'       => Carbon::parse($data->first()->rpo_date)->format('d-m-Y'),
                'nota'          => $data->first()->rpo_nota,
                'nota_po'       => $data->first()->po_nota,
                'supplier'      => $data->first()->s_company,
                'barang'        => $data->first()->i_name,
                'qty'           => $data->first()->rpo_qty . ' ' . $data->first()->u_name,
                'metode'        => $metode,
                'keterangan'    => $data->first()->rpo_note
            ];
        }
        return view('produksi.returnproduksi.nota')->with(compact('val'));
    }

    public function nota(){
        return view('produksi/orderproduksi/nota');
    }
}
