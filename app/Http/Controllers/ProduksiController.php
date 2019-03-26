<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\d_productionorder as ProductionOrder;
use DB;
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
            ->groupBy('po_id')
            ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_totalnet as nilai_order', 'po_status as status', DB::raw('sum(pop_pay) as terbayar'));

        $data = $getData->get();

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
//                    return '<div class="status-termin-lunas"><p>BELUM LUNAS</p></div>';
                    return '<div class="text-center">BELUM LUNAS</div>';
                }else{
//                    return '<div class="status-termin-belum"><p>LUNAS</p></div>';
                    return '<div class="text-center">LUNAS</div>';
                }
            })
            ->addColumn('aksi', function($data){
                $detail = '<button class="btn btn-primary btn-modal" type="button" title="Detail Data" onclick="detailOrder(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-folder"></i></button>';
                $edit = '<button class="btn btn-warning btn-edit" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-pencil"></i></button>';
                $hapus = '<button class="btn btn-danger btn-disable" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-trash"></i></button>';
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

    public function create_return_produksi()
    {
        return view('produksi/returnproduksi/create');
    }

    public function getNotaProductionOrder()
    {
        $data = ProductionOrder::join('m_supplier', 's_id', '=', 'po_supplier')
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
                $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder"></i></button>';
                $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Ambil"><i class="fa fa-hand-lizard-o"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $ambil . '</div>';
            })
            ->rawColumns(['supplier','tanggal', 'nota','action'])
            ->make(true);
    }

    public function nota(){
        return view('produksi/orderproduksi/nota');
    }
}
