<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;
use App\Http\Controllers\pushnotifikasiController as pushNotif;
use App\Helper\keuangan\jurnal\jurnal;

use App\d_itemsupplier;
use App\d_itemreceipt as ItemReceipt;
use App\d_productionorder as ProductionOrder;
use App\d_productionorderdt as ProductionOrderDT;
use App\d_productionorderauth;
use App\d_productionorderpayment;
use App\d_productionordercode;
use App\d_returnproductionorder;
use App\d_salescompcode;
use App\d_stock as Stock;
use App\d_stockdt;
use App\d_stock_mutation as StockMutation;
use App\m_item;
use App\m_supplier;
use App\m_mutcat;
use App\m_wil_provinsi;
use App\m_supplier as Supplier;
use Auth;
use Carbon\Carbon;
use CodeGenerator;
use Crypt;
use Currency;
use DB;
use Mockery\Exception;
use Response;
use Yajra\DataTables\DataTables;

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
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'Failed']);
        }

        $data = DB::table('d_productionorderdt')
            ->select('m_item.i_name',
                'm_unit.u_name',
                'd_productionorderdt.pod_qty',
                'd_productionorderdt.pod_value',
                'd_productionorderdt.pod_totalnet')
            ->join('m_item', function ($q) {
                $q->on('d_productionorderdt.pod_item', '=', 'm_item.i_id');
            })->join('m_unit', function ($q) {
                $q->on('d_productionorderdt.pod_unit', '=', 'm_unit.u_id');
            })
            ->where('d_productionorderdt.pod_productionorder', '=', $id)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('item', function ($data) {
                return $data->i_name;
            })
            ->addColumn('unit', function ($data) {
                return $data->u_name;
            })
            ->addColumn('qty', function ($data) {
                return '<p class="text-center">' . $data->pod_qty . '</p>';
            })
            ->addColumn('value', function ($data) {
                return '<p class="text-right">' . Currency::addRupiah($data->pod_value) . '</p>';
            })
            ->addColumn('totalnet', function ($data) {
                return '<p class="text-right">' . Currency::addRupiah($data->pod_totalnet) . '</p><input type="hidden" class="totalnet" value="' . number_format($data->pod_totalnet, 0, '', '') . '">';
            })
            ->rawColumns(['item', 'unit', 'qty', 'value', 'totalnet'])
            ->make(true);
    }

    public function getProduksiDetailTermin(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'Failed']);
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
            ->addColumn('termin', function ($data) {
                return $data->pop_termin;
            })
            ->addColumn('date', function ($data) {
                return date('d-m-Y', strtotime($data->pop_datetop));
            })
            ->addColumn('value', function ($data) {
                return '<p class="text-right">' . Currency::addRupiah($data->pop_value) . '</p><input type="hidden" class="totaltermin" value="' . number_format($data->pop_value, 0, '', '') . '">';
            })
            ->rawColumns(['termin', 'date', 'value'])
            ->make(true);
    }
    // retrieve DataTable for index
    public function get_order(Request $request)
    {
        $data = '';
        $getData = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
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

        // get production order that exist in itemreceipt
        $prodOrderWithReceipt = ProductionOrder::whereNotIn('po_id', $listReceivedProdOrdId)
        ->whereHas('getItemReceipt')
        ->get();
        // update $listReceivedProdOrdId
        foreach ($prodOrderWithReceipt as $idx => $val) {
            array_push($listReceivedProdOrdId, $val->po_id);
        }

        // filter user for preventing 'force delete'
        if (Auth::user()->u_level < 3) {
            $isUserHasAccessFD = true;
        }
        else {
            $isUserHasAccessFD = false;
        }

        $data = $getData->get();
        // get list of return-production where using 'ganti barang'
        $notaReturn = d_returnproductionorder::where('rpo_action', 'GB')
            ->whereHas('getPO')
            ->get();
        $listNotaReturn = array();
        foreach ($notaReturn as $key => $value) {
            array_push($listNotaReturn, $value->rpo_nota);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nota', function ($data) {
                return $data->nota;
            })
            ->addColumn('supplier', function ($data) {
                return $data->supplier;
            })
            ->addColumn('totalnet', function ($data) {
                return '<div class="text-right">' . Currency::addRupiah($data->nilai_order) . '</div>';
            })
            ->addColumn('bayar', function ($data) {
                return '<div class="text-right">' . Currency::addRupiah($data->terbayar) . '</div>';
            })
            ->addColumn('status', function ($data) {
                if ($data->status == 'BELUM') {
                    // return '<div class="status-termin-lunas"><p>BELUM LUNAS</p></div>';
                    return '<div class="text-center">BELUM LUNAS</div>';
                } else {
                    // return '<div class="status-termin-belum"><p>LUNAS</p></div>';
                    return '<div class="text-center">LUNAS</div>';
                }
            })
            ->addColumn('aksi', function ($data) use ($listReceivedProdOrdId, $isUserHasAccessFD, $listNotaReturn) {
                $detail = '<button class="btn btn-primary btn-modal" type="button" title="Detail Data" onclick="detailOrder(\'' . Crypt::encrypt($data->po_id) . '\')"><i class="fa fa-folder"></i></button>';
                if (in_array($data->po_id, $listReceivedProdOrdId)) {
                    $edit = '<button class="btn btn-warning btn-edit hint--top-left hint--warning" aria-label="Edit Order" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')" disabled><i class="fa fa-pencil"></i></button>';
                    $hapus = '<button class="btn btn-danger btn-disable hint--top-left hint--danger" aria-label="Hapus Order" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')" disabled><i class="fa fa-trash"></i></button>';
                    if ($isUserHasAccessFD == true) {
                        $forceDelete = '<button class="btn btn-danger hint--top-left hint--danger" aria-label="Paksa Hapus" type="button" title="Hapus Data" onclick="paksaHapus(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-times-circle"></i></button>';
                    }
                    else {
                        $forceDelete = '';
                    }
                }
                else {
                    $edit = '<button class="btn btn-warning btn-edit hint--top-left hint--warning" aria-label="Edit Order" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-pencil"></i></button>';
                    $hapus = '<button class="btn btn-danger hint--top-left hint--danger" aria-label="Hapus Order" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-trash"></i></button>';
                    $forceDelete = '';
                }
                if (in_array($data->nota, $listNotaReturn)) {
                    $forceDelete = '';
                    $hapus = '';
                }
            // start : temp
                $forceDelete = '';
            // end : temp
                $nota = '<button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Cetak Nota" title="Nota" type="button" onclick="printNota(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-print"></i></button>';
                return '<div class="row h-100 justify-content-center align-items-center"><div class="btn-group btn-group-sm">'. $detail . $nota . $edit . $hapus . '</div><div class="col-md-1"></div><div class="btn-group btn-group-sm">'. $forceDelete .'</div></div>';
            })
            ->rawColumns(['totalnet', 'bayar', 'status', 'aksi'])
            ->make(true);
    }

    public function create_produksi(Request $request)
    {
        if (!$request->isMethod('post')) {
            $suppliers = DB::table('m_supplier')
                ->where('s_isactive', 'Y')
                ->select('s_id', 's_company')
                ->get();

            $units = DB::table('m_unit')->get();
            return view('produksi/orderproduksi/create')->with(compact('suppliers', 'units'));
        }
        else {
            $data = $request->all();
            $productionorderauth = [];
            $productionorderdt = [];
            $productionorderpayment = [];

            DB::beginTransaction();
            try {
                $idpo = (DB::table('d_productionorderdt')->max('pod_productionorder')) ? (DB::table('d_productionorderdt')->max('pod_productionorder')) + 1 : 1;

                $notaProductionAuth = CodeGenerator::codeWithSeparator('d_productionorderauth', 'poa_nota', 8, 10, 3, 'PO', '-');
                $notaProduction = CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-');
                if (strcmp($notaProduction, $notaProductionAuth) > 0) {
                    $nota = $notaProduction;
                }
                else {
                    $nota = $notaProductionAuth;
                };

                (is_null($request->nota_return) ? $nota = $nota : $nota = $request->nota_return);

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

                // pusher -> push notification
                pushOtorisasi::otorisasiup('Otorisasi Revisi Data');

                DB::commit();
                return json_encode([
                    'status' => 'Success'
                ]);
            }
            catch (\Exception $e) {
                DB::rollBack();
                return json_encode([
                    'status' => 'Failed',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function edit_produksi(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $suppliers = DB::table('m_supplier')
            ->select('s_id', 's_company')
            ->get();

        $dataEdit = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('po_id', $id)->first();

        $dataEditDT = DB::table('d_productionorderdt')
            ->select('d_productionorderdt.*', 'm_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2',
                'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit as a', function ($x) {
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y) {
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z) {
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
        $data = $request->all();
        try {
            $id = Crypt::decrypt($data['orderId']);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        $tanggal = date('Y-m-d', strtotime($data['po_date']));
        $supplier = $data['sup'];
        $totalnet = $data['tot_hrg'];
        $productionorder = [];
        $productionorderdt = [];
        $productionorderpayment = [];

        DB::beginTransaction();
        try {
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

            // start: update jurnal
                $po = ProductionOrder::where('po_id', $id)->select('po_nota')->first();
                $detail = DB::table('d_productionorderdt')->where('pod_productionorder', $id)->get();

                $acc_persediaan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan dalam perjalanan')
                                        ->first();

                $hutang = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Hutang')
                                        ->first();

                $details = []; $count = 0;

                if(!$hutang || !$acc_persediaan){
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                    ]);
                }

                foreach ($detail as $key => $value) {
                    $count += $value->pod_value * $value->pod_qty;
                }

                array_push($details, [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $acc_persediaan->pd_acc,
                    "jrdt_value"        => $count,
                    "jrdt_dk"           => "D",
                    "jrdt_keterangan"   => "Persediaan Dalam Perjalanan Order Produksi",
                    "jrdt_cashflow"     => null
                ]);

                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $hutang->pd_acc,
                    "jrdt_value"        => $count,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => "Hutang Order Produksi",
                    "jrdt_cashflow"     => null
                ]);

                $jurnal = jurnal::updateJurnal(
                    $details,
                    date('Y-m-d'),
                    $po->po_nota,
                    'Order Produksi',
                    'TM',
                    Auth::user()->u_company
                );

                if($jurnal['status'] == 'error'){
                    return json_encode($jurnal);
                }
            // end: update jurnal

            pushNotif::notifikasiup('Notifikasi Perubahan Order Produksi');

            DB::commit();
            return json_encode([
                'status' => 'Success'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return json_encode([
                'status' => 'Failed',
                'msg' => $e->getMessage()
            ]);
        }
    }

    // delete order
    public function delete_produksi($id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            // start:  drop jurnal for production-order and payment
                $po = ProductionOrder::where('po_id', $id)->select('po_nota')->first();
                $jurnal = DB::table('dk_jurnal')
                    ->where('jr_nota_ref', 'like', '%' . $po->po_nota . '%')
                    ->groupBy('jr_id')
                    ->get();

                if (count($jurnal) > 0) {
                    foreach ($jurnal as $key => $value) {
                        $idJurnal = $value->jr_id;
                        // drop jurnal by id
                        $dropJurnal = jurnal::dropJurnal($idJurnal);
            			if ($dropJurnal['status'] == 'error') {
            				return $dropJurnal;
            			}

                        // return $dropJurnal;
                    }
                }
            // end:  drop jurnal for production-order and payment


            DB::table('d_productionorderpayment')->where('pop_productionorder', '=', $id)->delete();
            DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->delete();
            DB::table('d_productionorder')->where('po_id', '=', $id)->delete();

            DB::commit();
            return response()->json(['status' => "Success"]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => "Failed",
                'message' => $e->getMessage()
            ]);
        }
    }
    // force-delete
    public function forceDeleteProduksi($id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try {
            // get production order
            $po = ProductionOrder::where('po_id', $id)->with('getPODt')->first();
            // get item received
            $itemReceive = ItemReceipt::where('ir_notapo', $po->po_nota)->with('getIRDetail')->first();
            // get list of item-id that is exist in item-receipt
            $listItemInReceipt = array();
            foreach ($itemReceive->getIRDetail as $i => $ir) {
                array_push($listItemInReceipt, $ir->ird_item);
            }

            // rollback mutation 'in'
            foreach ($po->getPODt as $key => $val) {
                if (in_array($val->pod_item, $listItemInReceipt)) {
                    $mutRollbackIn = Mutasi::rollbackSalesIn(
                        $po->po_nota, // nota
                        $val->pod_item, // item id
                        1 // mutcat-in
                    );
                    if ($mutRollbackIn->original['status'] !== 'success') {
                        return $mutRollbackIn;
                    }
                }
            }

            // start:  drop jurnal for production-order and payment
                // $po = ProductionOrder::where('po_id', $id)->select('po_nota')->first();
                $jurnal = DB::table('dk_jurnal')
                    ->where('jr_nota_ref', 'like', '%' . $po->po_nota . '%')
                    ->groupBy('jr_id')
                    ->get();

                if (count($jurnal) > 0) {
                    foreach ($jurnal as $key => $value) {
                        $idJurnal = $value->jr_id;
                        // drop jurnal by id
                        $dropJurnal = jurnal::dropJurnal($idJurnal);
            			if ($dropJurnal['status'] == 'error') {
            				return $dropJurnal;
            			}
                    }
                }
            // end:  drop jurnal for production-order and payment

            DB::table('d_itemreceiptdt')->where('ird_itemreceipt', $itemReceive->ir_id)->delete();
            $itemReceive->delete();

            DB::table('d_productionorderpayment')->where('pop_productionorder', '=', $id)->delete();
            DB::table('d_productionordercode')->where('poc_productionorder', '=', $id)->delete();
            DB::table('d_productionorderdt')->where('pod_productionorder', '=', $id)->delete();
            $po->delete();


            DB::commit();
            return response()->json([
                'status' => 'Success'
            ]);
        }
        catch (\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteItemProduksi($order = null, $detail = null, $item = null)
    {
        try {
            $order = Crypt::decrypt($order);
            $detail = Crypt::decrypt($detail);
            $item = Crypt::decrypt($item);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            DB::table('d_productionorderdt')
                ->where('pod_productionorder', '=', $order)
                ->where('pod_detailid', '=', $detail)
                ->where('pod_item', '=', $item)
                ->delete();
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function deleteTerminProduksi($order = null, $termin = null)
    {
        try {
            $order = Crypt::decrypt($order);
            $termin = Crypt::decrypt($termin);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $order)
                ->where('pop_termin', '=', $termin)
                ->delete();
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function cariBarang(Request $request)
    {
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        if (count($is_item) == 0) {
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->get();
        }
        else {
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->whereNotIn('i_id', $is_item)
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x) {
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y) {
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z) {
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function printNota($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $header = DB::table('d_productionorder')
            ->select('d_productionorder.po_nota as nota', 'd_productionorder.po_date as tanggal', 'm_supplier.s_name as supplier')
            ->join('m_supplier', function ($q) {
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
            ->join('m_item', function ($q) {
                $q->on('d_productionorderdt.pod_item', '=', 'm_item.i_id');
            })->join('m_unit', function ($q) {
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
            ->select('rpo_id as id', 'rpo_date as tanggal', 'rpo_nota as nota', 'rpo_action as metode', 'rpo_item as idItem', 'i_name as barang',
                'rpo_qty as qty', 'u_name as satuan');

        if ($request->awal !== null) {
            $awal = Carbon::createFromFormat('d-m-Y', $request->awal)->format("Y-m-d");
            $data->where('rpo_date', '>=', $awal);
        }
        if ($request->akhir !== null) {
            $akhir = Carbon::createFromFormat('d-m-Y', $request->akhir)->format("Y-m-d");
            $data->where('rpo_date', '<=', $akhir);
        }
        if ($request->awal === null && $request->akhir === null) {
            $date = Carbon::now()->format('Y-m-d');
            $data->where('rpo_date', '=', $date);
        }
        $data = $data->get();
        return DataTables::of($data)
            ->addColumn('tanggal', function ($data) {
                return Carbon::parse($data->tanggal)->format('d-m-Y');
            })
            ->addColumn('nota', function ($data) {
                return $data->nota;
            })
            ->addColumn('metode', function ($data) {
                if ($data->metode == "GB") {
                    return "Ganti Barang";
                }
                else if ($data->metode == "PN") {
                    return "Potong Tagihan";
                }
                // else if ($data->metode == "RD") {
                //     return "Return Dana";
                // }
            })
            ->addColumn('barang', function ($data) {
                return $data->barang;
            })
            ->addColumn('qty', function ($data) {
                return $data->qty . ' ' . $data->satuan;
            })
            ->addColumn('action', function ($data) {
                $detail = '<button class="btn btn-primary" type="button" title="Detail" onclick="detailReturn(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-folder"></i></button>';
                // $edit = '<button class="btn btn-warning" type="button" title="Edit" onclick="editReturn(\'' . Crypt::encrypt($data->id) . '\', \'' . Crypt::encrypt($data->idItem) . '\')"><i class="fa fa-pencil-square-o"></i></button>';
                $hapus = '<button class="btn btn-danger" type="button" title="Hapus" onclick="hapusReturn(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-trash-o"></i></button>';
                return '<div class="btn-group btn-group-sm">' . $detail . $hapus . '</div>';
            })
            ->rawColumns(['tanggal', 'nota', 'metode', 'barang', 'qty', 'action'])
            ->make(true);
    }

    public function detailReturn($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json([
                'status' => "Failed",
                'message' => "Data tidak ditemukan"
            ]);
        }

        $data = d_returnproductionorder::where('rpo_id', $id)
            ->with('getItem')
            ->first();

        if (is_null($data)) {
            return Response::json([
                'status' => "Failed",
                'message' => "Data tidak ditemukan"
            ]);
        } else {
            if ($data->rpo_action == "GB") {
                $metode = "Ganti Barang";
            }
            else if ($data->rpo_action == "PN") {
                $metode = "Potong Tagihan";
            }
            // else if ($data->rpo_action == "RD") {
            //     $metode = "Return Dana";
            // }

            $val = [
                'tanggal' => Carbon::parse($data->rpo_date)->format('d-m-Y'),
                'nota' => $data->rpo_nota,
                'barang' => $data->getItem->i_name,
                'qty' => $data->rpo_qty . ' ' . $data->getItem->u_name,
                'metode' => $metode,
                'keterangan' => $data->rpo_note,
                'kode' => $data->rpo_code,
                'qtykode' => $data->rpo_qty,
            ];

            return Response::json([
                'status' => "Success",
                'message' => $val
            ]);
        }
    }

    public function getEditReturn($id = null, $detail = null)
    {
        try {
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        } catch (DecryptException $e) {
            return Response::json([
                'status' => "Failed",
                'message' => "Data tidak ditemukan"
            ]);
        }

        $data = DB::table('d_returnproductionorder')
            ->leftJoin('d_returnproductionorderdt', function ($q) use ($id){
                $q->on('rpo_productionorder', '=', 'rpod_productionorder');
                $q->on('rpo_detailid', '=', 'rpod_returnproductionorder');
            })
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->where('rpo_productionorder', $id)
            ->where('rpo_detailid', $detail);

        if ($data->count() == 0) {
            return Response::json([
                'status' => "Failed",
                'message' => "Data tidak ditemukan"
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
                'tanggal' => Carbon::parse($data->first()->rpo_date)->format('d-m-Y'),
                'nota' => $data->first()->rpo_nota,
                'barang' => $data->first()->i_name,
                'qty_return' => $data->first()->rpo_qty . ' ' . $data->first()->u_name,
                'qty' => $data->first()->rpo_qty,
                'unit' => $data->first()->i_unit1,
                'txtmetode' => $metode,
                'metode' => $data->first()->rpo_action,
                'keterangan' => $data->first()->rpo_note,
                'kode' => $data->first()->rpod_productioncode,
                'qtykode' => $data->first()->rpod_qty,
            ];

            return Response::json([
                'status' => "Success",
                'message' => $val,
                'satuan' => $satuan
            ]);
        }
    }

    public function create_return_produksi()
    {
        $provinsi = m_wil_provinsi::orderBy('wp_name', 'asc')->get();
        return view('produksi/returnproduksi/create', compact('provinsi'));
    }
    // get list items from m_items without stock
    public function findAllItem(Request $request)
    {
        $cari = $request->term;

        $nama = m_item::where('i_name', 'like', '%'. $cari .'%')
        ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name)];
            }
        }
        return response()->json($results);
    }
    // get items using autocomple.js
    public function findItem(Request $request)
    {
        $supplier = $request->supplier;

        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        if (count($is_item) == 0) {
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->where('is_supplier', $supplier)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->get();
        } else {
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->whereNotIn('i_id', $is_item)
                ->where('is_supplier', $supplier)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return response()->json($results);
    }
    // get satuan of an item
    public function getUnit($id)
    {
        $data = m_item::where('i_id', $id)
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->first();

        return response()->json($data);
    }
    // get list supplier
    public function getSupplier(Request $request)
    {
        $suppliers = Supplier::orderBy('s_company', 'asc')->get();

        return response()->json(array(
            'success' => true,
            'data' => $suppliers
        ));
    }
    // get production-code
    public function getProdCode(Request $request)
    {
        $itemStatus = $request->itemStatus;
        $position = Auth::user()->u_company;
        $supplier = $request->supplierCode;

        $listItemsSupplier = d_itemsupplier::where('is_supplier', $supplier)->get();
        $temp = array();
        foreach ($listItemsSupplier as $key => $value) {
            array_push($temp, $value->is_item);
        }
        $listItemsSupplier = $temp;

        $kode = d_stockdt::whereHas('getStock', function ($q) use ($position, $itemStatus, $listItemsSupplier) {
                $q
                    ->where('s_position', $position)
                    ->where('s_condition', $itemStatus)
                    ->whereIn('s_item', $listItemsSupplier);
            })
            ->with('getStock.getItem')
            ->groupBy('sd_code')
            ->get();

        return response()->json($kode);
    }
    // get data stock
    public function getData(Request $request)
    {
        $position = Auth::user()->u_company;
        $itemId = $request->itemId;
        $sd_code = $request->prodCode;
        $condition = $request->condition;

        $data = Stock::join('d_stockdt','s_id','=','sd_stock')
            ->where('s_position', $position)
            ->where('s_item', $itemId)
            ->where('s_status', 'ON DESTINATION')
            ->where('sd_code',$sd_code)
            ->where('s_condition',$condition)
            ->with('getItem')
            ->first();

        return response()->json([
            'data' => $data
        ]);
    }
    // get list Nota
    public function getNota(Request $request)
    {
        $prodCode=$request->prod_code;
        $nota = ProductionOrder::select('po_id','po_nota','po_date','po_supplier','po_totalnet')
                ->orderBy('po_update', 'asc')
                ->where('po_status','BELUM')
                ->whereHas('getProductionOrderCode',function($p) use($prodCode){
                  $p->where('poc_productioncode','=',$prodCode);
                })
                ->get();
        // $nota = d_productionordercode::
        //         where('poc_productioncode',$prodCode)
        //         ->whereHas('getProductionOrder',function($p){
        //           $p->where('po_status','=','belum');
        //         })->get();
        // dd($nota);
        return response()->json(array(
            'success' => true,
            'data' => $nota
        ));
    }
    // store data to Database
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $supplierId = $request->supplier; // supplier id
            (is_null($request->returnDate)) ? $returnDate = Carbon::now() : $returnDate = Carbon::createFromFormat('d-m-Y', $request->returnDate);

            if ($request->returnType == 'SB') {
                $notaPenjualan = null;
                $itemId = $request->itemId;
                $prodCode = $request->kodeproduksi;
                $qtyReturn = (int)$request->qtyReturn;
                $itemPrice = (int)$request->itemPriceSB;
                $type = $request->type;
            }
            elseif ($request->returnType == 'SL') {
                $notaPenjualan = null;
                $itemId = $request->itemIdSL;
                $prodCode = $request->prodCodeSL;
                $qtyReturn = (int)$request->qtyReturnSL;
                $itemPrice = (int)$request->itemPriceSL;
                $type = $request->typeSL;
            }

            if (is_null($type) || $type == '') {
                throw new Exception("Silahkan pilih Jenis Penggantian terlebih dahulu !", 1);
            }
            if (is_null($itemId) || $itemId == '') {
                throw new Exception("Silahkan mengisi item yang akan di-return terlebih dahulu !", 1);
            }
            if ($qtyReturn < 1) {
                throw new Exception("Qty item yang akan di-return tidak boleh kurang dari 1", 1);
            }

            $note = $request->keterangan;
            $comp = Auth::user()->u_company;

            $id = d_returnproductionorder::max('rpo_id') + 1;
            $nota = CodeGenerator::codeWithSeparator('d_returnproductionorder', 'rpo_nota', 15, 10, 3, 'RETURN-PO', '/');

            // set value for table d_return
            $valReturn = [
                'rpo_id' => $id,
                'rpo_supplier' => $supplierId,
                'rpo_date' => $returnDate,
                'rpo_nota' => $nota,
                'rpo_item' => $itemId,
                'rpo_qty' => $qtyReturn,
                'rpo_code' => strtoupper($prodCode),
                'rpo_action' => $type,
                'rpo_note' => $request->keterangan,
                'rpo_reff' => null
            ];
            // insert return to table d_return
            $insertReturn = d_returnproductionorder::insert($valReturn);

            if ($type == 'GB') {
                $mutcat = 16;
            }
            elseif ($type == 'PN') {
                $mutcat = 15;
            }
            elseif($type == 'PT') {
                $mutcat = 17;
            }

            // set list of production-code and qty each production-code
            $listPC = array($prodCode);
            $listQtyPC = array($qtyReturn);
            $listUnitPC = array();

            // return json_encode($qtyReturn * $request->itemPriceSB);

            if ($request->returnType == 'SL') {
                throw new \Exception("Maaf, saat ini return produksi hanya mendukung pengembalian 'Stok Baru' !", 1);
            }
            elseif ($request->returnType == 'SB') {
                // insert stock mutation using sales 'out'
                $mutationOut = Mutasi::returnOut(
                    $comp, // from position
                    $itemId, // item id
                    $qtyReturn, // qty item
                    $nota, // nota return
                    $notaPenjualan, // nota sales
                    $listPC, // list production-code
                    $listQtyPC, // list qty of production-code
                    $listUnitPC, // list unit pf production-code
                    $itemPrice,// sellPrice
                    $mutcat // mutcat
                );
                if ($mutationOut->original['status'] !== 'success') {
                    return $mutationOut;
                }
            }

            // create new production-order for 'ganti barang'
            if ($type == 'GB')
            {
                // validate 'ganti barang'
                if ((int)$request->subsValue > (int)$request->returnValue) {
                    throw new Exception("Total Nilai Pengganti tidak boleh melebihi Total Nilai Return", 1);
                }

                $termin = array(1);
                $dateTermin = Carbon::now()->addMonth();
                $estimasi = array($dateTermin);
                $nominal = array((int)$request->subsValue);
                // prepare Request to create new production-order-Auth
                $myRequest = new \Illuminate\Http\Request();
                $myRequest->setMethod('POST');
                $myRequest->request->add(['nota_return' => $nota]);
                $myRequest->request->add(['supplier' => $supplierId]);
                $myRequest->request->add(['po_date' => $returnDate]);
                $myRequest->request->add(['tot_hrg' => (int)$request->subsValue]);
                $myRequest->request->add(['idItem' => $request->idItem]); // list
                $myRequest->request->add(['jumlah' => $request->jumlah]); // list
                $myRequest->request->add(['satuan' => $request->satuan]); // list
                $myRequest->request->add(['harga' => $request->harga]); // list
                $myRequest->request->add(['subtotal' => $request->subtotal]); // list
                $myRequest->request->add(['termin' => $termin]); // list
                $myRequest->request->add(['estimasi' => $estimasi]); // list
                $myRequest->request->add(['nominal' => $nominal]); // list
                // create new production-order-auth with nota-return
                $order = $this->create_produksi($myRequest);
                if (json_decode($order)->status !== 'Success') {
                    throw new \Exception("Terjadi kesalahan saat menyelesaikan return produksi", 1);
                }
                // acc otorisasi production-order with status payment = 'LUNAS'
                $data = d_productionorderauth::where('poa_nota', $nota)->first();
                $values = [
                    'po_id'         => $data->poa_id,
                    'po_nota'       => $data->poa_nota,
                    'po_date'       => $data->poa_date,
                    'po_supplier'   => $data->poa_supplier,
                    'po_totalnet'   => $data->poa_totalnet,
                    'po_status'     => 'LUNAS'
                ];
                $insertPO = DB::table('d_productionorder')->insert($values);
                // delete production-auth
                $deletePOAuth = d_productionorderauth::where('poa_id', '=', $data->poa_id)
                    ->delete();
                // update production-payment to 'lunas'
                $POPayment = d_productionorderpayment::where('pop_productionorder', $data->poa_id)->first();
                $POPayment->pop_date = $returnDate;
                $POPayment->pop_pay = $POPayment->pop_value;
                $POPayment->pop_status = 'Y';
                $POPayment->save();


                // tambahan dirga
                    $acc_persediaan = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Return Pembelian Tukar Barang')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA Persediaan Item')
                                            ->first();

                    $acc_persediaan_perjalanan = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Return Pembelian Tukar Barang')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA Persediaan dalam perjalanan')
                                            ->first();

                    $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Return Pembelian Tukar Barang')
                                    ->where('pe_comp', Auth::user()->u_company)->first();
                    $details = [];

                    // return json_encode($parrent);

                    if(!$parrent || !$acc_persediaan || !$acc_persediaan_perjalanan){
                        return response()->json([
                            'status' => 'Failed',
                            'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                        ]);
                    }

                    array_push($details, [
                        "jrdt_nomor"        => 1,
                        "jrdt_akun"         => $acc_persediaan_perjalanan->pd_acc,
                        "jrdt_value"        => $qtyReturn * $request->itemPriceSB,
                        "jrdt_dk"           => "D",
                        "jrdt_keterangan"   => $acc_persediaan_perjalanan->pd_keterangan,
                        "jrdt_cashflow"     => $acc_persediaan_perjalanan->pd_cashflow
                    ]);

                    array_push($details, [
                        "jrdt_nomor"        => 2,
                        "jrdt_akun"         => $acc_persediaan->pd_acc,
                        "jrdt_value"        => $qtyReturn * $request->itemPriceSB,
                        "jrdt_dk"           => "K",
                        "jrdt_keterangan"   => $acc_persediaan->pd_keterangan,
                        "jrdt_cashflow"     => $acc_persediaan->pd_cashflow
                    ]);

                    // return json_encode($details);

                    $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $nota, $parrent->pe_nama, 'TM', Auth::user()->u_company);

                    if($jurnal['status'] == 'error'){
                        return json_encode($jurnal);
                    }

            }
            elseif ($type == 'PN')
            {
                // update saldo in supplier
                $supplier = m_supplier::where('s_id', $supplierId)->first();
                $supplier->s_deposit += ((int)$itemPrice * (int)$qtyReturn);
                $supplier->save();
                // tambahan dirga

            }
            elseif ($type == 'PT')
            {
              $termin = array(1);
              $poid = '';
              $bayar = (int)$request->returnValue;
              $updated = false;

              $prodPayment = ProductionOrder::where('po_nota',$request->nota)
              ->where('pop_status','N')
              ->join('d_productionorderpayment','po_id','pop_productionorder')->get();

              foreach ($prodPayment as $key => $value) {
                $poid = $value->po_id;
                $pop_value = (int)$value->pop_value;
                $pop_status = $value->pop_status;

                if ($value->pop_pay + $bayar <= $pop_value) {
                  $pop_pay = $bayar + (int)$value->pop_pay;
                  ($pop_pay == $pop_value ) ? $pop_status = 'Y' : $pop_status = 'N';
                  // Updated Status Production Order Payment
                    $updatedPayment = DB::table('d_productionorderpayment')
                          ->where('pop_productionorder', '=', $poid)
                          ->where('pop_termin', '=', $value->pop_termin)
                          ->update([
                              'pop_pay' => $pop_pay,
                              'pop_status' => $pop_status,
                              'pop_date' => Carbon::now("Asia/Jakarta")->format("Y-m-d")
                          ]);
                          $updated = true;
                          break;
                }
              }
              if ($updated == false) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Jumlah Tagiahan Kurang dari pembayaran'
                ]);
              }
              // Updated status Production Order
              $check = DB::table('d_productionorderpayment')
                  ->where('pop_productionorder', '=', $poid)
                  ->where('pop_status', '=', 'N')
                  ->get();

              if (count($check) == 0) {
                  DB::table('d_productionorder')
                      ->where('po_id', '=', $poid)
                      ->update([
                          'po_status' => "LUNAS"
                      ]);
              }
              // Pembukuan
              $data_po = DB::table('d_productionorder')
                  ->join('d_productionorderpayment', function ($x) use ($termin) {
                      $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                      $x->where('d_productionorderpayment.pop_termin', '=', $termin);
                  })
                  ->join('m_supplier', function ($y) use ($termin) {
                      $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
                  })
                  ->where('d_productionorder.po_id', '=', $poid)
                  ->select('d_productionorderpayment.pop_value as value', 'd_productionorderpayment.pop_pay as pay')
                  ->first();
              $acc_persediaan = DB::table('dk_pembukuan_detail')
                                ->where('pd_pembukuan', function($query){
                                  $query->select('pe_id')->from('dk_pembukuan')
                                        ->where('pe_nama', 'Return Pembelian Potong Nota')
                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                      })
                                ->where('pd_nama', 'COA Persediaan Item')
                                ->first();

                  $acc_hutang = DB::table('dk_pembukuan_detail')
                                          ->where('pd_pembukuan', function($query){
                                              $query->select('pe_id')->from('dk_pembukuan')
                                                          ->where('pe_nama', 'Return Pembelian Potong Nota')
                                                          ->where('pe_comp', Auth::user()->u_company)->first();
                                          })->where('pd_nama', 'COA Hutang')
                                          ->first();

                  $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Return Pembelian Potong Nota')
                                  ->where('pe_comp', Auth::user()->u_company)->first();
                  $details = [];

                  // return json_encode($parrent);

                  if(!$parrent || !$acc_persediaan || !$acc_hutang){
                      return response()->json([
                          'status' => 'Failed',
                          'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                      ]);
                  }

                  array_push($details, [
                      "jrdt_nomor"        => 1,
                      "jrdt_akun"         => $acc_hutang->pd_acc,
                      "jrdt_value"        => $qtyReturn * $request->itemPriceSB,
                      "jrdt_dk"           => "D",
                      "jrdt_keterangan"   => $acc_hutang->pd_keterangan,
                      "jrdt_cashflow"     => $acc_hutang->pd_cashflow
                  ]);

                  array_push($details, [
                      "jrdt_nomor"        => 2,
                      "jrdt_akun"         => $acc_persediaan->pd_acc,
                      "jrdt_value"        => $qtyReturn * $request->itemPriceSB,
                      "jrdt_dk"           => "K",
                      "jrdt_keterangan"   => $acc_persediaan->pd_keterangan,
                      "jrdt_cashflow"     => $acc_persediaan->pd_cashflow
                  ]);

                  // return json_encode($details);
                  $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $nota, $parrent->pe_nama, 'TM', Auth::user()->u_company);

                  if($jurnal['status'] == 'error'){
                      return json_encode($jurnal);
                  }
            }

            pushNotif::notifikasiup('Notifikasi Pembuatan Return Produksi');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // delete production-order
    public function deleteReturn($id)
    {
        if (!AksesUser::checkAkses(12, 'delete')) {
            return Response::json([
                'status' => "Gagal",
                'message' => "Anda tidak memiliki akses"
            ]);
        }

        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($id);

            $data = d_returnproductionorder::where('rpo_id', $id)->first();

            // get data in item-receipt, is item has received ?
            $itemReceived = ItemReceipt::where('ir_notapo', $data->rpo_nota)->first();
            if (!is_null($itemReceived)) {
                DB::rollback();
                return response()->json([
                    'status' => 'received'
                ]);
            }

            if ($data->rpo_action == 'GB') {
                $PO = ProductionOrder::where('po_nota', $data->rpo_nota)->first();
                $deletePOPayment = d_productionorderpayment::where('pop_productionorder', $PO->po_id)->delete();
                $deletePODetail = ProductionOrderDT::where('pod_productionorder', $PO->po_id)->delete();
                $PO->delete();

// update jurnal ?
            }
            elseif ($data->rpo_action == 'PN') {
                // get list of 'in' mutcat-list
                $inMutcatList = m_mutcat::where('m_status', 'K')
                    ->select('m_id')
                    ->get();
                for ($i = 0; $i < count($inMutcatList); $i++) {
                    $tmp[] = $inMutcatList[$i]->m_id;
                }
                $inMutcatList = $tmp;
                $itemId = $data->rpo_item;

                $supplier = m_supplier::where('s_id', $data->rpo_supplier)->first();

                $itemPrice = StockMutation::where('sm_nota', $data->rpo_nota)
                    ->whereIn('sm_mutcat', $inMutcatList)
                    ->whereHas('getStock', function($q) use ($itemId) {
                        $q->where('s_item', $itemId);
                    })
                    ->select('sm_sell')
                    ->first();

                $itemPrice = $itemPrice->sm_sell;

                $supplier->s_deposit -= ((int)$itemPrice * $data->rpo_qty);
                $supplier->save();
// update jurnal ?
            }

            // get mutcat 'out' based on return-type
            switch ($data->rpo_action) {
                case 'GB':
                    $mutcatOut = 16;
                    break;
                case 'GU':
                    $mutcatOut = 15;
                    break;
                case 'PN':
                    $mutcatOut = 17;
                    break;
                default:
                    break;
            }

            // rollback mutation 'out'
            $mutRollbackOut = Mutasi::rollbackSalesOut(
                $data->rpo_nota,
                $data->rpo_item,
                $mutcatOut
            );
            if ($mutRollbackOut->original['status'] !== 'success') {
                return $mutRollbackOut;
            }
            // delete return-ProductionOrder
            $deleteReturnPO = d_returnproductionorder::where('rpo_id', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // delete production-order
    public function forceDeleteReturn($id)
    {
        if (!AksesUser::checkAkses(12, 'delete')) {
            return Response::json([
                'status' => "Gagal",
                'message' => "Anda tidak memiliki akses"
            ]);
        }

        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($id);
            // get data return-production
            $data = d_returnproductionorder::where('rpo_id', $id)->first();
            // get data production-order
            $po = ProductionOrder::where('po_nota', $data->rpo_nota)->with('getPODt')->first();
            // get item received
            $itemReceive = ItemReceipt::where('ir_notapo', $po->po_nota)->with('getIRDetail')->first();
            // get list of item-id that is exist in item-receipt
            $listItemInReceipt = array();
            foreach ($itemReceive->getIRDetail as $i => $ir) {
                array_push($listItemInReceipt, $ir->ird_item);
            }

            // rollback mutation 'in' / item received
            foreach ($po->getPODt as $key => $val) {
                if (in_array($val->pod_item, $listItemInReceipt)) {
                    $mutRollbackIn = Mutasi::rollbackSalesIn(
                        $po->po_nota, // nota
                        $val->pod_item, // item id
                        1 // mutcat-in
                    );
                    if ($mutRollbackIn->original['status'] !== 'success') {
                        return $mutRollbackIn;
                    }
                }
            }

            // get mutcat 'out' based on return-type
            $mutcatOut = 16;
            // rollback mutation 'out'
            $mutRollbackOut = Mutasi::rollbackSalesOut(
                $data->rpo_nota,
                $data->rpo_item,
                $mutcatOut
            );
            if ($mutRollbackOut->original['status'] !== 'success') {
                return $mutRollbackOut;
            }

            // delete item-received
            DB::table('d_itemreceiptdt')->where('ird_itemreceipt', $itemReceive->ir_id)->delete();
            $itemReceive->delete();
            // delete return-ProductionOrder
            $deleteReturnPO = d_returnproductionorder::where('rpo_id', $id)->delete();
            $deletePOPayment = d_productionorderpayment::where('pop_productionorder', $po->po_id)->delete();
            $deletePODetail = ProductionOrderDT::where('pod_productionorder', $po->po_id)->delete();
            $po->delete();
// update jurnal ?

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
// start: unused ------------------
    // public function getNotaProductionOrder(Request $request)
    // {
    //
    //     if ($request->dateStart != null) {
    //         $data = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
    //             ->join('m_supplier', 's_id', '=', 'po_supplier')
    //             ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');
    //
    //         if ($request->dateStart != "") {
    //             $data->whereDate('po_date', '>=', Carbon::parse($request->dateStart)->format('Y-m-d'));
    //         }
    //         if ($request->dateEnd != "") {
    //             $data->whereDate('po_date', '<=', Carbon::parse($request->dateEnd)->format('Y-m-d'));
    //         }
    //         if ($request->supplier != "") {
    //             $data->where('po_supplier', '=', $request->supplier);
    //         }
    //         return DataTables::of($data)
    //             ->addColumn('supplier', function ($data) {
    //                 return $data->supplier;
    //             })
    //             ->addColumn('tanggal', function ($data) {
    //                 return date('d-m-Y', strtotime($data->tanggal));
    //             })
    //             ->addColumn('nota', function ($data) {
    //                 return $data->nota;
    //             })
    //             ->addColumn('action', function ($data) {
    //                 $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\'' . Crypt::encrypt($data->po_id) . '\')"><i class="fa fa-folder"></i></button>';
    //                 $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Pilih" onclick="pilih(\'' . Crypt::encrypt($data->po_id) . '\', \'' . $data->nota . '\')"><i class="fa fa-arrow-down"></i></button>';
    //                 return '<div class="btn-group btn-group-sm">' . $detail . $ambil . '</div>';
    //             })
    //             ->rawColumns(['supplier', 'tanggal', 'nota', 'action'])
    //             ->make(true);
    //     } else {
    //         $data = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
    //             ->join('m_supplier', 's_id', '=', 'po_supplier')
    //             ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');
    //
    //         return DataTables::of($data)
    //             ->addColumn('supplier', function ($data) {
    //                 return $data->supplier;
    //             })
    //             ->addColumn('tanggal', function ($data) {
    //                 return date('d-m-Y', strtotime($data->tanggal));
    //             })
    //             ->addColumn('nota', function ($data) {
    //                 return $data->nota;
    //             })
    //             ->addColumn('action', function ($data) {
    //                 $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\'' . Crypt::encrypt($data->po_id) . '\')"><i class="fa fa-folder"></i></button>';
    //                 $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Pilih" onclick="pilih(\'' . Crypt::encrypt($data->po_id) . '\', \'' . $data->nota . '\')"><i class="fa fa-arrow-down"></i></button>';
    //                 return '<div class="btn-group btn-group-sm">' . $detail . $ambil . '</div>';
    //             })
    //             ->rawColumns(['supplier', 'tanggal', 'nota', 'action'])
    //             ->make(true);
    //     }
    // }
    // public function detailNota($id = null)
    // {
    //     $data = ProductionOrder::where('po_id', Crypt::decrypt($id))
    //         ->join('d_productionorderdt', 'po_id', '=', 'pod_productionorder')
    //         ->join('m_item', 'pod_item', '=', 'i_id')
    //         ->select('m_item.i_name as barang', 'd_productionorderdt.pod_qty as qty', DB::raw("CONCAT('Rp. ',FORMAT(d_productionorderdt.pod_totalnet, 0, 'de_DE')) as harga"));
    //
    //     return DataTables::of($data)
    //         ->addColumn('barang', function ($data) {
    //             return $data->barang;
    //         })
    //         ->addColumn('qty', function ($data) {
    //             return $data->qty;
    //         })
    //         ->addColumn('harga', function ($data) {
    //             return $data->harga;
    //         })
    //         ->rawColumns(['barang', 'qty', 'harga'])
    //         ->make(true);
    // }
    // public function searchSupplier(Request $request)
    // {
    //     $cari = $request->term;
    //     $nama = Supplier::where(function ($q) use ($cari) {
    //         $q->orWhere('s_company', 'like', '%' . $cari . '%');
    //         $q->orWhere('s_name', 'like', '%' . $cari . '%');
    //     })
    //         ->get();
    //
    //     if (count($nama) == 0) {
    //         $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
    //     } else {
    //         foreach ($nama as $query) {
    //             $results[] = ['id' => $query->s_id, 'label' => strtoupper($query->s_name) . ' - ' . strtoupper($query->s_company), 'data' => $query];
    //         }
    //     }
    //     return Response::json($results);
    // }
    // public function searchNota(Request $request)
    // {
    //     $data = ProductionOrder::join('m_supplier', 's_id', '=', 'po_supplier')
    //         ->select('po_id', 'po_nota as nota', 's_company as supplier', 'po_date as tanggal');
    //
    //     if ($request->pemilik != "") {
    //         $data->where('d_stock.s_comp', '=', $request->pemilik);
    //     } else if ($request->posisi != "") {
    //         $data->where('d_stock.s_position', '=', $request->posisi);
    //     } else if ($request->item != "") {
    //         $data->where('d_stock.s_item', '=', $request->item);
    //     }
    //
    //     return DataTables::of($data)
    //         ->addColumn('supplier', function ($data) {
    //             return $data->supplier;
    //         })
    //         ->addColumn('tanggal', function ($data) {
    //             return date('d-m-Y', strtotime($data->tanggal));
    //         })
    //         ->addColumn('nota', function ($data) {
    //             return $data->nota;
    //         })
    //         ->addColumn('action', function ($data) {
    //             $detail = '<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder"></i></button>';
    //             $ambil = '<button class="btn btn-success btn-ambil" type="button" title="Ambil"><i class="fa fa-hand-lizard-o"></i></button>';
    //             return '<div class="btn-group btn-group-sm">' . $detail . $ambil . '</div>';
    //         })
    //         ->rawColumns(['supplier', 'tanggal', 'nota', 'action'])
    //         ->make(true);
    // }
    // // set return for autocomplete searching using production-code
    // public function cariProdKode(Request $request)
    // {
    //     $cari = $request->term;
    //     $prodCode = d_productionordercode::where('poc_productioncode', 'like', '%' . $cari . '%')
    //         ->with('getItem')
    //         ->groupBy('poc_productioncode')
    //         ->orderBy('poc_productioncode', 'desc')
    //         ->get();
    //
    //     if (count($prodCode) == 0) {
    //         $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
    //     } else {
    //         foreach ($prodCode as $query) {
    //             $results[] = [
    //                 'id' => Crypt::encrypt($query->poc_productionorder),
    //                 'label' => $query->poc_productioncode,
    //                 'nota' => $query->getProductionOrder->po_nota,
    //                 'prodCode' => $query->poc_productioncode
    //             ];
    //         }
    //     }
    //     return Response::json($results);
    // }
    // // set return for autocomplete searching using no-nota of production-order
    // public function cariNota(Request $request)
    // {
    //     $cari = $request->term;
    //     $nama = ProductionOrder::rightJoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
    //         ->where(function ($q) use ($cari) {
    //             $q->orWhere('ir_notapo', 'like', '%' . $cari . '%');
    //         })
    //         ->join('m_supplier', 's_id', '=', 'po_supplier')
    //         ->orderBy('po_date', 'desc')
    //         ->orderBy('po_nota', 'desc')
    //         ->get();
    //     if (count($nama) == 0) {
    //         $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
    //     } else {
    //         foreach ($nama as $query) {
    //             $results[] = [
    //                 'id' => Crypt::encrypt($query->po_id),
    //                 'label' => $query->po_nota . ' - ' . $query->s_company,
    //                 'nota' => $query->po_nota,
    //                 'prodCode' => '-'
    //             ];
    //         }
    //     }
    //     return Response::json($results);
    // }
    // find detailed selected items (after using autocomplete)
    // public function cariBarangPO(Request $request)
    // {
    //     $id = $request->id;
    //     $prodCode = $request->prodCode;
    //     $nota = $request->nota;
    //     $searchBy = $request->searchBy;
    //
    //     // if user search using nota of production-order
    //     if ($searchBy == 'nota') {
    //         $data = ProductionOrderDT::join('m_item', 'i_id', '=', 'pod_item')
    //             ->join('m_unit', 'u_id', '=', 'pod_unit')
    //             ->where('pod_productionorder', '=', Crypt::decrypt($id))
    //             ->select('pod_productionorder', 'pod_item', 'i_name', 'pod_qty', 'u_name', 'pod_value', 'pod_totalnet');
    //
    //         return DataTables::of($data)
    //             ->addColumn('barang', function ($data) {
    //                 return $data->i_name;
    //             })
    //             ->addColumn('qty', function ($data) {
    //                 return $data->pod_qty . ' - ' . $data->u_name;
    //             })
    //             ->addColumn('harga', function ($data) {
    //                 return number_format($data->pod_value, 0, ',', '.');
    //             })
    //             ->addColumn('total', function ($data) {
    //                 return number_format($data->pod_totalnet, 0, ',', '.');
    //             })
    //             ->addColumn('action', function ($data) {
    //                 $qty = $data->pod_qty . ' - ' . $data->u_name;
    //                 $pilih = '<button class="btn btn-sm btn-primary" title="Pilih" onclick="selectItem(\'' .
    //                     'nota' . '\', \'' .
    //                     Crypt::encrypt($data->pod_productionorder) . '\', \'' .
    //                     Crypt::encrypt($data->pod_item) . '\', \'' .
    //                     $data->i_name . '\', \'' .
    //                     $qty . '\', \'' .
    //                     $data->pod_value . '\', \'' .
    //                     $data->pod_totalnet .
    //                     '\')">Lakukan Return</button>';
    //                 return '<div class="btn-group btn-group-sm">' . $pilih . '</div>';
    //             })
    //             ->rawColumns(['barang', 'qty', 'harga', 'total', 'action'])
    //             ->make(true);
    //     } // if user search using production-code
    //     elseif ($searchBy == 'kodeproduksi') {
    //         $prodCd = d_productionordercode::where('poc_productionorder', Crypt::decrypt($id))
    //             ->where('poc_productioncode', $prodCode)
    //             ->with('getItem')
    //             ->with('getUnit')
    //             ->groupBy('poc_productioncode')
    //             ->selectRaw('*, SUM(poc_qty) as qty')
    //             ->get();
    //
    //         $prodOrder = ProductionOrderDT::where('pod_productionorder', Crypt::decrypt($id))
    //             ->where('pod_item', $prodCd[0]->poc_item)
    //             ->first();
    //
    //         return DataTables::of($prodCd)
    //             ->addColumn('barang', function ($prodCd) {
    //                 return $prodCd->getItem->i_name;
    //             })
    //             ->addColumn('qty', function ($prodCd) {
    //                 return $prodCd->qty . ' - ' . $prodCd->getUnit->u_name;
    //             })
    //             ->addColumn('harga', function ($prodCd) use ($prodOrder) {
    //                 return number_format($prodOrder->pod_value, 0, ',', '.');
    //             })
    //             ->addColumn('total', function ($prodCd) use ($prodOrder) {
    //                 $total = $prodCd->qty * $prodOrder->pod_value;
    //                 return number_format($total, 0, ',', '.');
    //             })
    //             ->addColumn('action', function ($prodCd) use ($prodOrder) {
    //                 $qty = $prodCd->qty . ' - ' . $prodCd->getUnit->u_name;
    //                 $pilih = '<button class="btn btn-sm btn-primary" title="Pilih" onclick="selectItem(\'' .
    //                     'kodeproduksi' . '\', \'' .
    //                     Crypt::encrypt($prodCd->poc_productionorder) . '\', \'' .
    //                     Crypt::encrypt($prodCd->poc_item) . '\', \'' .
    //                     $prodCd->getItem->i_name . '\', \'' .
    //                     $qty . '\', \'' .
    //                     $prodOrder->pod_value . '\', \'' .
    //                     $prodCd->qty * $prodOrder->pod_value .
    //                     '\')">Lakukan Return</button>';
    //                 return '<div class="btn-group btn-group-sm">' . $pilih . '</div>';
    //             })
    //             ->rawColumns(['barang', 'qty', 'harga', 'total', 'action'])
    //             ->make(true);
    //     }
    // }
    // find item-unit
    // public function setSatuan($id = null)
    // {
    //     $data = DB::table('m_item')
    //         ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
    //         ->where('m_item.i_id', '=', Crypt::decrypt($id))
    //         ->join('m_unit as a', function ($x) {
    //             $x->on('m_item.i_unit1', '=', 'a.u_id');
    //         })
    //         ->leftjoin('m_unit as b', function ($y) {
    //             $y->on('m_item.i_unit2', '=', 'b.u_id');
    //         })
    //         ->leftjoin('m_unit as c', function ($z) {
    //             $z->on('m_item.i_unit3', '=', 'c.u_id');
    //         })
    //         ->first();
    //     return Response::json($data);
    // }
    // public function addReturn(Request $request)
    // {
    //     try {
    //         $poid = Crypt::decrypt($request->idPO);
    //         $idItem = Crypt::decrypt($request->idItem);
    //     } catch (DecryptException $e) {
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    //
    //     $detailid = (DB::table('d_returnproductionorder')->where('rpo_productionorder', $poid)->max('rpo_detailid')) ? DB::table('d_returnproductionorder')->where('rpo_productionorder', $poid)->max('rpo_detailid') + 1 : 1;
    //     // return-po/001/23/03/2019
    //     $nota = CodeGenerator::codeWithSeparator('d_returnproductionorder', 'rpo_nota', 15, 10, 3, 'RETURN-PO', '/');
    //     $nota_reff = '';
    //     $data_check = DB::table('d_productionorder')
    //         ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
    //             'm_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
    //             'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
    //             'm_item.i_unit3 as unit3', 'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_value as value')
    //         ->join('d_productionorderdt', function ($x) use ($idItem) {
    //             $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
    //             $x->where('d_productionorderdt.pod_item', '=', $idItem);
    //         })
    //         ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
    //         ->where('d_productionorder.po_id', '=', $poid)
    //         ->first();
    //
    //     $qty_compare = 0;
    //     $unit = 0;
    //     if ($request->satuan_return == $data_check->unit1) {
    //         $qty_compare = (int)$request->qty_return;
    //         $unit = $data_check->unit1;
    //     } else if ($request->satuan_return == $data_check->unit2) {
    //         $qty_compare = $request->qty_return * $data_check->compare2;
    //     } else if ($request->satuan_return == $data_check->unit3) {
    //         $qty_compare = $request->qty_return * $data_check->compare3;
    //     }
    //
    //     // if searchMethod is using 'kodeproduksi'
    //     if ($request->searchMethod == 'kodeproduksi') {
    //         // get production-code by PO-id and production-code-number
    //         $data = d_productionordercode::where('poc_productionorder', $poid)
    //             ->where('poc_productioncode', $request->prodCode)
    //             ->get();
    //
    //         // get qty-item from production-code
    //         $qtydata_compare = 0;
    //         $totalQty = 0;
    //         foreach ($data as $key => $val) {
    //             if ($val->poc_unit == $data_check->unit1) {
    //                 $qtydata_compare = $val->poc_qty;
    //             } else if ($val->poc_unit == $data_check->unit2) {
    //                 $qtydata_compare = $val->poc_qty * $data_check->compare2;
    //             } else if ($val->poc_unit == $data_check->unit3) {
    //                 $qtydata_compare = $val->poc_qty * $data_check->compare3;
    //             }
    //             $totalQty += $qtydata_compare;
    //         }
    //
    //         // return-failed if qty-return > qty-item
    //         if ((int)$qty_compare > (int)$totalQty) {
    //             DB::rollBack();
    //             return Response::json([
    //                 'status' => "Failed",
    //                 'message' => "Jumlah permintaan pengembalian melebihi jumlah yang tersedia !"
    //             ]);
    //         }
    //
    //     } // if searchMethod is using 'nota'
    //     elseif ($request->searchMethod == 'nota') {
    //         // get production-order-dt by PO-id and item-id
    //         $data = ProductionOrderDT::where('pod_productionorder', $poid)
    //             ->where('pod_item', $idItem)
    //             ->get();
    //
    //         // get qty-item from production-order-dt
    //         $qtydata_compare = 0;
    //         $totalQty = 0;
    //         foreach ($data as $key => $val) {
    //             if ($val->pod_unit == $data_check->unit1) {
    //                 $qtydata_compare = $val->pod_qty;
    //             } else if ($val->pod_unit == $data_check->unit2) {
    //                 $qtydata_compare = $val->pod_qty * $data_check->compare2;
    //             } else if ($val->pod_unit == $data_check->unit3) {
    //                 $qtydata_compare = $val->pod_qty * $data_check->compare3;
    //             }
    //             $totalQty += $qtydata_compare;
    //         }
    //
    //         // return-failed if qty-return > qty-item
    //         if ((int)$qty_compare > (int)$totalQty) {
    //             DB::rollBack();
    //             return Response::json([
    //                 'status' => "Failed",
    //                 'message' => "Jumlah permintaan pengembalian melebihi jumlah yang tersedia !"
    //             ]);
    //         }
    //     }
    //
    //     DB::beginTransaction();
    //     try {
    //         $valCode = [
    //             'rpod_productionorder' => $poid,
    //             'rpod_returnproductionorder' => $detailid,
    //             'rpod_detailid' => 1,
    //             'rpod_productioncode' => $request->prodCode,
    //             'rpod_qty' => $qty_compare,
    //             'rpod_unit' => $unit
    //         ];
    //         $infocomp = DB::table('m_company')
    //             ->where('c_type', '=', 'PUSAT')
    //             ->first();
    //
    //         $comp = $infocomp->c_id;
    //         // // update stock
    //         // $get_stock = Stock::where('s_comp', $comp)
    //         // ->where('s_position', $comp)
    //         // ->where('s_item', $idItem)
    //         // ->where('s_status', 'ON DESTINATION')
    //         // ->where('s_condition', 'FINE');
    //         $mutasi = Mutasi::mutasikeluar(
    //             15, // mutcat
    //             $comp, // item owner
    //             $comp, // destination
    //             $idItem, // item id
    //             $qty_compare, // qty
    //             $nota, // nota
    //             null, // sellprice
    //             null, // list of productioncode
    //             null, // list qty of productioncode
    //             $request->notaPO // reff
    //         );
    //         if (!is_bool($mutasi)) {
    //             return $mutasi;
    //         }
    //
    //         // mengurangi qty kode sesuai jumlah return
    //         $kode = $request->prodCode;
    //         $info_stock = DB::table('d_stock')
    //             ->leftJoin('d_stockdt', function ($q) use ($kode) {
    //                 $q->on('sd_stock', '=', 's_id');
    //                 $q->where('sd_code', '=', $kode);
    //             })
    //             ->where('s_comp', '=', $comp)
    //             ->where('s_position', '=', $comp)
    //             ->where('s_item', '=', $idItem)
    //             ->where('s_status', '=', 'ON DESTINATION')
    //             ->first();
    //
    //         //update d_stockdt
    //         $qtystockdt = $qty_compare;
    //         DB::table('d_stockdt')
    //             ->where('sd_stock', '=', $info_stock->s_id)
    //             ->where('sd_code', '=', $kode)
    //             ->update([
    //                 'sd_qty' => $info_stock->sd_qty - $qtystockdt
    //             ]);
    //
    //         //create stockmutationdt
    //         $info_mutation = DB::table('d_stock_mutation')
    //             ->where('sm_nota', '=', $nota)
    //             ->get();
    //
    //         for ($i = 0; $i < count($info_mutation); $i++){
    //             $smd_detailid = DB::table('d_stockmutationdt')
    //                 ->where('smd_stock', '=', $info_mutation[$i]->sm_stock)
    //                 ->where('smd_stockmutation', '=', $info_mutation[$i]->sm_detailid)
    //                 ->max('smd_detailid');
    //
    //             ++$smd_detailid;
    //
    //             DB::table('d_stockmutationdt')
    //                 ->insert([
    //                     'smd_stock' => $info_mutation[$i]->sm_stock,
    //                     'smd_stockmutation' => $info_mutation[$i]->sm_detailid,
    //                     'smd_detailid' => $smd_detailid,
    //                     'smd_productioncode' => $kode,
    //                     'smd_qty' => $info_mutation[$i]->sm_qty,
    //                     'smd_unit' => $unit
    //                 ]);
    //         }
    //         $dataPO = DB::table('d_productionorder')
    //             ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
    //             ->where('po_nota', '=', $request->notaPO)
    //             ->where('pod_item', '=', $idItem)
    //             ->get();
    //         //metode return
    //         if ($request->methode_return == "GB"){
    //             //Ganti Barang
    //             //Pembuatan Nota Pembelian
    //             $po_id = DB::table('d_productionorderdt')
    //                 ->max('pod_productionorder');
    //             ++$po_id;
    //
    //             $po_nota = CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'RETURN', '-');
    //             $totalnet = 0;
    //             $nota_reff = $po_nota;
    //             DB::table('d_productionorder')
    //                 ->insert([
    //                     'po_id' => $po_id,
    //                     'po_nota' => $po_nota,
    //                     'po_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
    //                     'po_supplier' => $dataPO[0]->po_supplier,
    //                     'po_totalnet' => (int)$dataPO[0]->pod_value * $qty_compare,
    //                     'po_status' => 'BELUM'
    //                 ]);
    //
    //             DB::table('d_productionorderdt')
    //                 ->insert([
    //                     'pod_productionorder' => $po_id,
    //                     'pod_detailid' => 1,
    //                     'pod_item' => $idItem,
    //                     'pod_qty' => $qty_compare,
    //                     'pod_unit' => $unit,
    //                     'pod_value' => (int)$dataPO[0]->pod_value,
    //                     'pod_received' => 'N',
    //                     'pod_totalnet' => (int)$dataPO[0]->pod_value * $qty_compare
    //                 ]);
    //
    //         } elseif ($request->methode_return == "PT"){
    //             //Potong tagihan
    //         } elseif ($request->methode_return == "RD"){
    //             //Return Dana
    //         }
    //         //dd($request);
    //         // insert return
    //         DB::table('d_returnproductionorder')->insert([
    //             'rpo_productionorder' => $poid,
    //             'rpo_detailid' => $detailid,
    //             'rpo_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
    //             'rpo_nota' => $nota,
    //             'rpo_item' => $idItem,
    //             'rpo_qty' => $qty_compare,
    //             'rpo_action' => $request->methode_return,
    //             'rpo_note' => $request->note_return,
    //             'rpo_reff' => $nota_reff
    //         ]);
    //         DB::table('d_returnproductionorderdt')->insert($valCode);
    //         DB::commit();
    //         return Response::json([
    //             'status' => "Success",
    //             'message' => "Data berhasil disimpan",
    //             'id' => Crypt::encrypt($poid),
    //             'detail' => Crypt::encrypt($detailid)
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
    // public function editReturn(Request $request)
    // {
    //     $comp = Auth::user()->u_company;
    //     try {
    //         $rpoid = Crypt::decrypt($request->idRPO);
    //         $rpo_detail = Crypt::decrypt($request->idDetail);
    //         $item = Crypt::decrypt($request->idItem);
    //     } catch (DecryptException $e) {
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => $e
    //         ]);
    //     }
    //
    //     $data_check = DB::table('m_item')
    //         ->select('i_unitcompare1 as compare1', 'i_unitcompare2 as compare2',
    //             'i_unitcompare3 as compare3', 'i_unit1 as unit1', 'i_unit2 as unit2', 'i_unit3 as unit3')
    //         ->where('i_id', '=', $item)
    //         ->first();
    //
    //     $qty_compare = 0;
    //     if ($request->satuan_return_edit == $data_check->unit1) {
    //         $qty_compare = $request->qty_return_edit;
    //     } else if ($request->satuan_return_edit == $data_check->unit2) {
    //         $qty_compare = $request->qty_return_edit * $data_check->compare2;
    //     } else if ($request->satuan_return_edit == $data_check->unit3) {
    //         $qty_compare = $request->qty_return_edit * $data_check->compare3;
    //     }
    //
    //     DB::beginTransaction();
    //     try {
    //         $stock = Stock::where('s_comp', $comp)
    //             ->where('s_position', $comp)
    //             ->where('s_item', $item)
    //             ->where('s_status', 'ON DESTINATION')
    //             ->where('s_condition', 'FINE');
    //
    //         $stock_mutation = StockMutation::where('sm_stock', $stock->first()->s_id);
    //
    //         $stock_awal = $stock->first()->s_qty + $request->qty_current;
    //
    //         $sm_qtyawal = $stock_mutation->first()->sm_qty + $request->qty_current;
    //         $sm_residueawal = $sm_qtyawal - $stock_mutation->first()->sm_use;
    //         $sm_qty = $sm_qtyawal - $qty_compare;
    //         $sm_residue = $sm_qty - $stock_mutation->first()->sm_use;
    //
    //         $val_mutasi = [
    //             'sm_qty' => $sm_qty,
    //             'sm_residue' => $sm_residue
    //         ];
    //
    //         $val_stock = [
    //             's_qty' => $stock_awal - $qty_compare
    //         ];
    //
    //         $val_return = [
    //             'rpo_qty' => $qty_compare,
    //             'rpo_action' => $request->methode_return_edit,
    //             'rpo_note' => $request->note_return_edit
    //         ];
    //
    //         $stock_mutation->update($val_mutasi);
    //         $stock->update($val_stock);
    //         DB::table('d_returnproductionorder')
    //             ->where('rpo_productionorder', $rpoid)
    //             ->where('rpo_detailid', $rpo_detail)
    //             ->update($val_return);
    //
    //         DB::commit();
    //         return Response::json([
    //             'status' => "Success",
    //             'message' => "Data berhasil diperbarui",
    //             'id' => Crypt::encrypt($rpoid),
    //             'detail' => Crypt::encrypt($rpo_detail)
    //         ]);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => $e
    //         ]);
    //     }
    // }
    // function deleteReturn($id = null, $detail = null, $qty = null)
    // {
    //     if (!AksesUser::checkAkses(12, 'delete')) {
    //         return Response::json([
    //             'status' => "Gagal",
    //             'message' => "Anda tidak memiliki akses"
    //         ]);
    //     }
    //     $dataUser = DB::table('m_company')
    //         ->where('c_type', '=', 'PUSAT')
    //         ->get();
    //     $comp = $dataUser[0]->c_id;
    //     try {
    //         $id = Crypt::decrypt($id);
    //         $detail = Crypt::decrypt($detail);
    //     } catch (DecryptException $e) {
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => $e
    //         ]);
    //     }
    //
    //     if ($qty == null || $qty == "") {
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => "Kuantitas barang tidak diketahui"
    //         ]);
    //     }
    //
    //     $return_po = DB::table('d_returnproductionorder')
    //         ->where('rpo_productionorder', $id)
    //         ->where('rpo_detailid', $detail);
    //
    //     if ($return_po->count() == 0) {
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => "Data return produksi tidak ditemukan"
    //         ]);
    //     } else {
    //         $stock = Stock::where('s_comp', $comp)
    //             ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
    //             ->where('s_position', $comp)
    //             ->where('s_item', $return_po->first()->rpo_item)
    //             ->where('s_status', 'ON DESTINATION')
    //             ->where('sm_nota', '=', $return_po->first()->rpo_nota);
    //
    //         /*$stock_mutation = StockMutation::where('sm_stock', $stock->first()->s_id);
    //         //$qty = kuantitas return
    //         $s_qty = $stock->first()->s_qty + $qty;
    //         $sm_qty = $stock_mutation->first()->sm_qty;
    //         $sm_residue = $stock_mutation->first()->sm_residue + $qty;
    //         $sm_use = $stock_mutation->first()->sm_use - $qty;
    //
    //         $val_mutasi = [
    //             'sm_residue' => $sm_residue,
    //             'sm_use' => $sm_use
    //         ];
    //
    //         $val_stock = [
    //             's_qty' => $s_qty
    //         ];*/
    //
    //         DB::beginTransaction();
    //         try {
    //             $dataReturn = DB::table('d_returnproductionorder')
    //                 ->join('d_returnproductionorderdt', function ($q){
    //                     $q->on('rpo_productionorder', '=', 'rpod_productionorder');
    //                     $q->on('rpo_detailid', '=', 'rpod_returnproductionorder');
    //                 })
    //                 ->get();
    //
    //             if (count($dataReturn) > 0){
    //                 if ($dataReturn[0]->rpo_action == 'GB'){
    //                     //delete nota pengembalian barang
    //                     DB::table('d_productionorder')
    //                         ->where('po_nota', '=', $dataReturn[0]->rpo_reff)
    //                         ->delete();
    //                 }
    //             }
    //
    //             $dataStock = $stock->get();
    //             for ($i = 0; $i < count($dataReturn); $i++){
    //                 $stockDt = DB::table('d_stockdt')
    //                     ->where('sd_stock', '=', $dataStock[0]->s_id)
    //                     ->where('sd_code', '=', $dataReturn[$i]->rpod_productioncode)
    //                     ->first();
    //
    //                 $qtyawal = $stockDt->sd_qty;
    //                 $qtyReturn = $dataReturn[$i]->rpod_qty;
    //                 $qtyUpdate = (int)$qtyawal + (int)$qtyReturn;
    //
    //                 DB::table('d_stockdt')
    //                     ->where('sd_stock', '=', $dataStock[0]->s_id)
    //                     ->where('sd_code', '=', $dataReturn[$i]->rpod_productioncode)
    //                     ->update([
    //                         'sd_qty' => $qtyUpdate
    //                     ]);
    //
    //                 DB::table('d_returnproductionorderdt')
    //                     ->where('rpod_productionorder', $id)
    //                     ->where('rpod_returnproductionorder', $detail)
    //                     ->where('rpod_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                     ->delete();
    //
    //                 $datamutasi = DB::table('d_stock_mutation')
    //                     ->where('sm_stock', '=', $dataStock[0]->s_id)
    //                     ->where('sm_nota', '=', $dataReturn[$i]->rpo_nota)
    //                     ->get();
    //
    //                 for ($j = 0; $j < count($datamutasi); $j++){
    //                     DB::table('d_stockmutationdt')
    //                         ->where('smd_stock', '=', $dataStock[0]->s_id)
    //                         ->where('smd_stockmutation', '=', $datamutasi[$i]->sm_detailid)
    //                         ->where('smd_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                         ->delete();
    //
    //                     //update stock & stock mutasi
    //                     //get mutasi yang dikurangi
    //                     $mutasi = DB::table('d_stock_mutation')
    //                         ->where('sm_nota', '=', $datamutasi[$i]->sm_reff)
    //                         ->where('sm_hpp', '=', $datamutasi[$i]->sm_hpp)
    //                         ->where('sm_use', '>', 0)
    //                         ->get();
    //                     $jumlahreturn = $qty;
    //                     for ($k = 0; $k < count($mutasi); $k++){
    //                         if ($mutasi[$k]->sm_use < $jumlahreturn){
    //                             //jika sm_use kurang dari jumlah return maka data tersebut akan direset ke kondisi belum pernah terpakai
    //                             DB::table('d_stock_mutation')
    //                                 ->where('sm_detailid', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('sm_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->update([
    //                                     'sm_use' => 0,
    //                                     'sm_residue' => $mutasi[$k]->sm_qty
    //                                 ]);
    //
    //                             //update stock mutation dt
    //                             /*$mutationdt = DB::table('d_stockmutationdt')
    //                                 ->where('smd_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->where('smd_stockmutation', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('smd_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                                 ->first();
    //
    //                             DB::table('d_stockmutationdt')
    //                                 ->where('smd_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->where('smd_stockmutation', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('smd_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                                 ->update([
    //                                     'smd_qty' => (int)$mutationdt->smd_qty + (int)$mutasi[$k]->sm_use
    //                                 ]);*/
    //
    //                             $jumlahreturn = $jumlahreturn - $mutasi[$k]->sm_use;
    //                         } else {
    //                             //jika sm_use >= jumlah return maka data kuantitas yang telah digunakan akan dikurangi dengan jumlah return
    //                             $dataawal = DB::table('d_stock_mutation')
    //                                 ->where('sm_detailid', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('sm_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->first();
    //
    //                             DB::table('d_stock_mutation')
    //                                 ->where('sm_detailid', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('sm_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->update([
    //                                     'sm_use' => $dataawal->sm_use - $jumlahreturn,
    //                                     'sm_residue' => $dataawal->sm_residue + $jumlahreturn
    //                                 ]);
    //
    //                             //update stock mutation dt
    //                             /*$mutationdt = DB::table('d_stockmutationdt')
    //                                 ->where('smd_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->where('smd_stockmutation', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('smd_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                                 ->first();
    //
    //                             DB::table('d_stockmutationdt')
    //                                 ->where('smd_stock', '=', $mutasi[$k]->sm_stock)
    //                                 ->where('smd_stockmutation', '=', $mutasi[$k]->sm_detailid)
    //                                 ->where('smd_productioncode', '=', $dataReturn[$i]->rpod_productioncode)
    //                                 ->update([
    //                                     'smd_qty' => (int)$mutationdt->smd_qty + (int)$mutasi[$k]->sm_use
    //                                 ]);*/
    //                             $k = count($mutasi) + 2;
    //                             break;
    //                         }
    //                     }
    //                 }
    //
    //             }
    //             /*$stock_mutation->update($val_mutasi);
    //             $stock->update($val_stock);*/
    //             $return_po->delete();
    //             $stock->update([
    //                 's_qty' => $dataStock[0]->s_qty + $qty
    //             ]);
    //             DB::table('d_stock_mutation')
    //                 ->where('sm_nota', '=', $dataReturn[0]->rpo_nota)
    //                 ->delete();
    //
    //             DB::commit();
    //             return Response::json([
    //                 'status' => "Success",
    //                 'message' => "Data berhasil dihapus"
    //             ]);
    //         } catch (Exception $e) {
    //             DB::rollBack();
    //             return Response::json([
    //                 'status' => "Failed",
    //                 'message' => $e
    //             ]);
    //         }
    //     }
    // }
// end: unuser ---------------------------
    public function notaReturn($id = null, $detail = null)
    {
        try {
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $data = DB::table('d_returnproductionorder')
            ->join('m_item', 'rpo_item', '=', 'i_id')
            ->join('m_unit', 'i_unit1', '=', 'u_id')
            ->join('d_productionorder', 'po_id', '=', 'rpo_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->join('d_returnproductionorderdt', function ($q){
                $q->on('rpod_productionorder', '=', 'po_id');
                $q->on('rpod_returnproductionorder', '=', 'rpo_detailid');
            })
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
                'tanggal' => Carbon::parse($data->first()->rpo_date)->format('d-m-Y'),
                'nota' => $data->first()->rpo_nota,
                'nota_po' => $data->first()->po_nota,
                'supplier' => $data->first()->s_company,
                'barang' => $data->first()->i_name,
                'qty' => $data->first()->rpo_qty . ' ' . $data->first()->u_name,
                'metode' => $metode,
                'keterangan' => $data->first()->rpo_note,
                'kode' => $data->first()->rpod_productioncode,
                'qtykode' => $data->first()->rpod_qty,
            ];
        }
        return view('produksi.returnproduksi.nota')->with(compact('val'));
    }
    public function nota()
    {
        return view('produksi/orderproduksi/nota');
    }
}
