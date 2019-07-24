<?php

namespace App\Http\Controllers;
use Auth;
use App\d_productionordercode;
use Carbon\Carbon;
use Crypt;
use DB;
use function foo\func;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Mutasi;
use Response;
use Yajra\DataTables\DataTables;
use App\Helper\keuangan\jurnal\jurnal;

class PenerimaanProduksiController extends Controller
{
    public function penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/index');
    }

    public function create_penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/create');
    }

    public function getNotaPO()
    {
        $data = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->leftjoin('d_itemreceipt', 'ir_notapo', '=', 'po_nota')
            ->leftjoin('d_itemreceiptdt', function($x){
                $x->on('ird_itemreceipt', '=', 'ir_id');
            })
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->where('pod_received', '=', 'N')
            ->select('po_id', 'po_nota', 's_company', 'po_date')
            ->groupBy('po_id')
            ->orderBy('po_date', 'desc')
            ->orderBy('po_nota', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nota', function($data){
                return $data->po_nota;
            })
            ->addColumn('supplier', function($data){
                return $data->s_company;
            })
            ->addColumn('tanggal', function($data){
                return Carbon::createFromFormat('Y-m-d', $data->po_date)->format('d-m-Y');
            })
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--top-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-folder"></i>
                        </button>
                        <button class="btn btn-danger hint--top-left hint--info" aria-label="Terima" onclick="terima(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-arrow-right"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['nota', 'supplier', 'tanggal', 'action'])
            ->make(true);
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

    public function terimaBarang($id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch(DecryptException $e){
            return abort(404);
        }
        $order = Crypt::encrypt($id);
        return view('produksi.penerimaanbarang.penerimaan.proses-terima')->with(compact('order'));
    }

    public function listTerimaBarang($order = null)
    {
        try {
            $order = Crypt::decrypt($order);
        } catch (\DecryptException $e) {
            return Response::json(['status' => 'Failed']);
        }

        $data = DB::table('d_productionorder')
            ->where('d_productionorder.po_id', '=', $order)
            ->join('d_productionorderdt', 'd_productionorderdt.pod_productionorder', '=', 'd_productionorder.po_id')
            ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
            ->join('m_unit', 'd_productionorderdt.pod_unit', '=', 'm_unit.u_id')
            ->leftjoin('d_itemreceipt', 'd_productionorder.po_nota', '=', 'd_itemreceipt.ir_notapo')
            ->leftjoin('d_itemreceiptdt', function($y){
                $y->on('d_itemreceipt.ir_id', '=', 'd_itemreceiptdt.ird_itemreceipt');
                $y->whereRaw('d_itemreceiptdt.ird_item = d_productionorderdt.pod_item');
            })
            ->groupBy('d_productionorderdt.pod_item')
            ->select('d_productionorder.po_id', 'd_productionorder.po_nota', 'd_productionorderdt.pod_item',
                'd_productionorderdt.pod_unit', 'm_item.i_name', 'm_item.i_code', 'm_unit.u_name', 'd_productionorderdt.pod_qty',
                DB::raw('sum(d_itemreceiptdt.ird_qty) as ird_qty'))
            ->get();

        return DataTables::of($data)
            ->addColumn('barang', function($data){
                return ''.$data->i_code.' - '.$data->i_name.'';
            })
            ->addColumn('satuan', function($data){
                return $data->u_name;
            })
            ->addColumn('jumlah', function($data){
                return $data->pod_qty;
            })
            ->addColumn('terima', function($data){
                if($data->ird_qty == NULL) {
                    $qty_compare = 0;
                } else {
                    $data_check = DB::table('m_item')
                        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                            'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                            'm_item.i_unit3 as unit3')
                        ->where('m_item.i_id', '=', $data->pod_item)
                        ->first();
                    $qty_compare = 0;
                    if ($data->pod_unit == $data_check->unit1) {
                        $qty_compare = $data->ird_qty/$data_check->compare1;
                    } else if ($data->pod_unit == $data_check->unit2) {
                        $qty_compare = $data->ird_qty/$data_check->compare2;
                    } else if ($data->pod_unit == $data_check->unit3) {
                        $qty_compare = $data->ird_qty/$data_check->compare3;
                    }
                }

                return $qty_compare;
            })
            ->addColumn('action', function($data) {
                if($data->ird_qty == NULL) {
                    $qty_compare = 0;
                } else {
                    $data_check = DB::table('m_item')
                        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                            'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                            'm_item.i_unit3 as unit3')
                        ->where('m_item.i_id', '=', $data->pod_item)
                        ->first();
                    $qty_compare = 0;
                    if ($data->pod_unit == $data_check->unit1) {
                        $qty_compare = $data->ird_qty/$data_check->compare1;
                    } else if ($data->pod_unit == $data_check->unit2) {
                        $qty_compare = $data->ird_qty/$data_check->compare2;
                    } else if ($data->pod_unit == $data_check->unit3) {
                        $qty_compare = $data->ird_qty/$data_check->compare3;
                    }
                }

                if ($qty_compare < $data->pod_qty) {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-danger text-dark hint--top-left hint--error" aria-label="Terima Barang" onclick="receipt(\''.Crypt::encrypt($data->po_id).'\', \''.Crypt::encrypt($data->pod_item).'\')">
                            <i class="fa fa-arrow-down"></i>&nbsp Terima Barang
                        </button>
                    </div>';
                } else {
                   // return '<div class="text-center"><span class="status-approve" style="padding: 5px;">Diterima</span></div>';
                    return '<div class="status-termin-lunas"><p>Diterima</p></div>';
                }
            })
            ->rawColumns(['barang', 'satuan', 'jumlah', 'terima', 'action'])
            ->make(true);

    }

    public function detailTerimaBarang($id = null, $item = null)
    {
        try {
            $id = Crypt::decrypt($id);
            $item = Crypt::decrypt($item);
        } catch (\DecryptException $e) {
            return Response::json(['status' => 'Failed']);
        }

        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', function ($x) use ($item){
                $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
                $x->where('d_productionorderdt.pod_item', '=', $item);
            })
            ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
            ->join('m_unit', 'd_productionorderdt.pod_unit', '=', 'm_unit.u_id')
            ->leftjoin('d_itemreceipt', function ($x){
                $x->on('d_productionorder.po_nota', '=', 'd_itemreceipt.ir_notapo');
            })
            ->leftjoin('d_itemreceiptdt', function($y){
                $y->on('d_itemreceipt.ir_id', '=', 'd_itemreceiptdt.ird_itemreceipt');
                $y->whereRaw('d_itemreceiptdt.ird_item = d_productionorderdt.pod_item');
            })
            ->groupBy('d_productionorderdt.pod_item')
            ->where('d_productionorder.po_id', '=', $id)
            ->select('d_productionorder.po_id as id', 'd_productionorder.po_nota as nota',
                'd_productionorderdt.pod_item as item', 'm_item.i_name as barang', 'm_unit.u_name as satuan',
                'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_qty as jumlah',
                    DB::raw('sum(d_itemreceiptdt.ird_qty) as terima'))
            ->first();

        $satuan = DB::table('m_item')
            ->select('a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $item)
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

        if($data->terima == NULL) {
            $qty_compare = 0;
        }
        else {
            $check = DB::table('m_item')
                ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                    'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                    'm_item.i_unit3 as unit3')
                ->where('m_item.i_id', '=', $item)
                ->first();
            $qty_compare = 0;
            if ($data->unit == $check->unit1) {
                $qty_compare = $data->terima/$check->compare1;
            }
            else if ($data->unit == $check->unit2) {
                $qty_compare = $data->terima/$check->compare2;
            }
            else if ($data->unit == $check->unit3) {
                $qty_compare = $data->terima/$check->compare3;
            }
        }

       // $sisa = (int)$data->jumlah - (int)$qty_compare;
       // get list production-code from selected production-order
       $listProdCode = d_productionordercode::where('poc_productionorder', $data->id)
       ->where('poc_item', $data->item)
       ->get();

        $data = array(
            'id'        => Crypt::encrypt($data->id),
            'nota'      => $data->nota,
            'item'      => Crypt::encrypt($data->item),
            'barang'    => $data->barang,
            'satuan'    => $data->satuan,
            'unit'      => $data->unit,
            'jumlah'    => $data->jumlah,
            'terima'    => $qty_compare,
        );

        return Response::json(['status' => 'Success', 'data' => $data, 'satuan' => $satuan, 'prodCode' => $listProdCode]);
    }

    public function checkTerima(Request $request)
    {
        try{
            $order   = Crypt::decrypt($request->idOrder);
            $item    = Crypt::decrypt($request->idItem);
        }
        catch (\DecryptException $e){
            return Response::json(['status' => 'Failed']);
        }

        DB::beginTransaction();
        try{
            $totalqty = 0;
            for ($i = 0; $i < count($request->prodCode); $i++){
                if ($request->prodCode[$i] != " " && $request->prodCode[$i] !== null){
                    $totalqty = $totalqty + (int)$request->qtyProdCode[$i];
                }
            }
            if ((int)$request->qty !=  $totalqty){
                return Response::json(['status' => 'Failed', 'message' => "Jumlah kode produksi tidak sesuai"]);
            }

            $data_check = DB::table('d_productionorder')
                ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
                    'd_productionorderdt.pod_qty as jumlah', 'd_productionorderdt.pod_unit', DB::raw('sum(d_itemreceiptdt.ird_qty) as terima'), 'm_unit.u_name as satuan')
                ->join('d_productionorderdt', function ($x) use ($item){
                    $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
                    $x->where('d_productionorderdt.pod_item', '=', $item);
                })
                ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
                ->join('m_unit', 'd_productionorderdt.pod_unit', '=', 'm_unit.u_id')
                ->leftjoin('d_itemreceipt', function ($x){
                    $x->on('d_productionorder.po_nota', '=', 'd_itemreceipt.ir_notapo');
                })
                ->leftjoin('d_itemreceiptdt', function($y){
                    $y->on('d_itemreceipt.ir_id', '=', 'd_itemreceiptdt.ird_itemreceipt');
                    $y->whereRaw('d_itemreceiptdt.ird_item = d_productionorderdt.pod_item');
                })
                ->where('d_productionorder.po_id', '=', $order)
                ->groupBy('d_productionorderdt.pod_item')
                ->first();

            $result = null;
            $message = null;

            if($data_check->terima == NULL) {
                $qty_compare = 0;
            }
            else {
                $check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                        'm_item.i_unit3 as unit3')
                    ->where('m_item.i_id', '=', $item)
                    ->first();
                $qty_compare = 0;
                if ($data_check->pod_unit == $check->unit1) {
                    $qty_compare = $data_check->terima/$check->compare1;
                }
                else if ($data_check->pod_unit == $check->unit2) {
                    $qty_compare = $data_check->terima/$check->compare2;
                }
                else if ($data_check->pod_unit == $check->unit3) {
                    $qty_compare = $data_check->terima/$check->compare3;
                }
            }

            $sisa = (int)$data_check->jumlah - (int)$qty_compare;

            if ($request->qty > $sisa) {
                $result = "Over qty";
                $message = "Kuantitas melebihi jumlah order/sisa yang sudah diterima, sisa yang belum diterima saat ini ". $sisa . " " . $data_check->satuan;
            }
            else {
                $result = "Success";
                $message = "Success";
            }

            DB::commit();
            return Response::json(['status' => 'Success', 'result' => $result, 'message' => $message]);
        }catch (\Exception $e){
            DB::rollback();
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }
    }

    public function UpdateStatus($id, $item)
    {
        $data = DB::table('d_productionorder')
            ->join('d_productionorderdt', 'po_id', '=', 'pod_productionorder')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->where('po_id', '=', $id)
            ->where('pod_item', '=', $item)
            ->get();

        $terima = DB::table('d_itemreceipt')
            ->join('d_itemreceiptdt', 'ir_id', '=', 'ird_itemreceipt')
            ->select(DB::raw('sum(ird_qty) as diterima'), 'ird_unit')
            ->where('ir_notapo', '=', $data[0]->po_nota)
            ->where('ird_item', '=', $item)
            ->get();

        //konversi ke satuan terkecil
        $pesan = 0;
        if ($data[0]->pod_unit == $data[0]->i_unit1){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare1;
        }
        elseif ($data[0]->pod_unit == $data[0]->i_unit2){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare2;
        }
        elseif ($data[0]->pod_unit == $data[0]->i_unit3){
            $pesan = $data[0]->pod_qty * $data[0]->i_unitcompare3;
        }

        if ($pesan == $terima[0]->diterima){
            DB::table('d_productionorderdt')
                ->where('pod_item', '=', $item)
                ->where('pod_productionorder', '=', $id)
                ->update([
                    'pod_received' => 'Y'
                ]);

        }

    }

    public function receiptItem(Request $request)
    {
        try{
            $order   = Crypt::decrypt($request->idOrder);
            $item    = Crypt::decrypt($request->idItem);
        }
        catch (\DecryptException $e){
            return Response::json(['status' => 'Failed']);
        }

        DB::beginTransaction();
        try{

            $data_check = DB::table('d_productionorder')
                ->select('d_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
                    'm_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                    'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                    'm_item.i_unit3 as unit3', 'd_productionorderdt.pod_unit as unit', 'd_productionorderdt.pod_value as value')
                ->join('d_productionorderdt', function ($x) use ($item){
                    $x->on('d_productionorder.po_id', '=', 'd_productionorderdt.pod_productionorder');
                    $x->where('d_productionorderdt.pod_item', '=', $item);
                })
                ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
                ->where('d_productionorder.po_id', '=', $order)
                ->first();

            $nota_receipt = DB::table('d_itemreceipt')
                ->where('ir_notapo', '=', $data_check->nota);

            // set date received
            $receiveDate = Carbon::parse($request->receiveDate);
            if ($nota_receipt->count() > 0) {
                $detail_receipt = (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $nota_receipt->first()->ir_id)->max('ird_detailid')) ? (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $nota_receipt->first()->ir_id)->max('ird_detailid')) + 1 : 1;

                $qty_compare = 0;
                if ($request->satuan == $data_check->unit1) {
                    $qty_compare = $request->qty;
                }
                else if ($request->satuan == $data_check->unit2) {
                    $qty_compare = $request->qty * $data_check->compare2;
                }
                else if ($request->satuan == $data_check->unit3) {
                    $qty_compare = $request->qty * $data_check->compare3;
                }
                dd($request->receiveDate, Carbon::parse($request->receiveDate));
                $values = [
                    'ird_itemreceipt'  => $nota_receipt->first()->ir_id,
                    'ird_detailid'      => $detail_receipt,
                    'ird_date'          => $receiveDate,
                    'ird_item'          => $item,
                    'ird_qty'           => $qty_compare,
                    'ird_unit'          => $data_check->unit1,
                    'ird_user'          => Auth::user()->u_id
                ];
                DB::table('d_itemreceiptdt')->insert($values);
            }
            else {
                $id = (DB::table('d_itemreceipt')->max('ir_id')) ? (DB::table('d_itemreceipt')->max('ir_id'))+1 : 1;

                $receipt = [
                    'ir_id'     => $id,
                    'ir_notapo' => $data_check->nota
                ];

                $detail_receipt = (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $id)->max('ird_detailid')) ? (DB::table('d_itemreceiptdt')->where('ird_itemreceipt', '=', $id)->max('ird_detailid')) + 1 : 1;

                $qty_compare = 0;
                if ($request->satuan == $data_check->unit1) {
                    $qty_compare = $request->qty;
                } else if ($request->satuan == $data_check->unit2) {
                    $qty_compare = $request->qty * $data_check->compare2;
                } else if ($request->satuan == $data_check->unit3) {
                    $qty_compare = $request->qty * $data_check->compare3;
                }

                $values = [
                    'ird_itemreceipt'  => $id,
                    'ird_detailid'      => $detail_receipt,
                    'ird_date'          => $receiveDate,
                    'ird_item'          => $item,
                    'ird_qty'           => $qty_compare,
                    'ird_unit'          => $data_check->unit1,
                    'ird_user'          => Auth::user()->u_id
                ];
                DB::table('d_itemreceipt')->insert($receipt);
                DB::table('d_itemreceiptdt')->insert($values);
            }

            $prodCodeId = DB::table('d_productionorder')
                                ->where('po_id', $order)
                                ->select('po_id')
                                ->first() ;

            // insert production-code for each item
            if ($request->prodCode !== null) {
                $valuesProdCode = array();
                foreach ($request->prodCode as $key => $val) {
                    $detailId = d_productionordercode::where('poc_productionorder', $prodCodeId->po_id)
                    ->max('poc_detailid') + ($key + 1);

                    $detailProdCode = array(
                        'poc_productionorder' => $prodCodeId->po_id,
                        'poc_detailid' => $detailId,
                        'poc_item' => $item,
                        'poc_productioncode' => strtoupper($val),
                        'poc_qty' => $request->qtyProdCode[$key],
                        'poc_unit' => $request->satuan
                    );
                    array_push($valuesProdCode, $detailProdCode);
                }
                d_productionordercode::insert($valuesProdCode);
            }

            $listPC = array($request->prodCode);
            $listQtyPC = array($request->qtyProdCode);
            $listUnitPC = array();
            $listSellPrice = array($data_check->value);
            $listHPP = array($data_check->value);
            $listSmQty = array($qty_compare);
            // insert mutation using sales-in, 'cause it create item with new owner and position
            $mutationIn = Mutasi::salesIn(
                Auth::user()->u_company, // destination
                $item, // item id
                $data_check->nota, // nota
                $listPC, // list of list production-code
                $listQtyPC, // list of list qty of production-code
                $listUnitPC,  // list  unit of production-code
                $listSellPrice, // list of sellprice
                $listHPP, // list of hpp
                $listSmQty, // lsit of sm-qty (it got from salesOut)
                1, // mutation category
                null, // stock parent id
                'ON DESTINATION', // status
                $receiveDate
            );

            // if (!is_bool($mutasi)) {
            //     return $mutasi;

            if ($mutationIn->original['status'] !== 'success') {
                return $mutationIn;
            }

            // // repair mutasimasuk, also insert production code to param
            // // check stock-mutation again
            // $mutasi = Mutasi::mutasimasuk(
            //     1, // mutcat
            //     Auth::user()->u_company, // from
            //     Auth::user()->u_company, // destination
            //     $item, // item id
            //     $qty_compare, // qty smallest unit
            //     'ON DESTINATION', // status item
            //     'FINE', // condition item
            //     $data_check->value, // hpp
            //     $data_check->value, // sell
            //     $data_check->nota, // nota
            //     $request->nota, // nota refference
            //     $request->prodCode, // list of productioncode
            //     $request->qtyProdCode // list of qty-productioncode
            // );
            // if (!is_bool($mutasi)) {
            //     return $mutasi;
            // }

            // update received-status of an item
            $this->UpdateStatus($order, $item);

            // tambahan dirga
                $acc_persediaan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Penerimaan Barang Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan Item')
                                        ->first();

                $acc_persediaan_perjalanan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Penerimaan Barang Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan dalam perjalanan')
                                        ->first();

                $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Penerimaan Barang Produksi')
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
                    "jrdt_akun"         => $acc_persediaan->pd_acc,
                    "jrdt_value"        => $data_check->value * $request->qty,
                    "jrdt_dk"           => "D",
                    "jrdt_keterangan"   => $acc_persediaan->pd_keterangan,
                    "jrdt_cashflow"     => $acc_persediaan->pd_cashflow
                ]);

                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $acc_persediaan_perjalanan->pd_acc,
                    "jrdt_value"        => $data_check->value * $request->qty,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $acc_persediaan_perjalanan->pd_keterangan,
                    "jrdt_cashflow"     => $acc_persediaan_perjalanan->pd_cashflow
                ]);

                $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $request->nota, $parrent->pe_nama, 'TM', Auth::user()->u_company);

                if($jurnal['status'] == 'error'){
                    return json_encode($jurnal);
                }

            DB::commit();
            return Response::json([
                'status' => 'Success',
                'message' => "Data berhasil disimpan"
            ]);
        }
        catch (\Exception $e){
            DB::rollback();
            return Response::json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function searchHistory(Request $request)
    {
        $data = DB::table('d_itemreceipt')
            ->join('d_itemreceiptdt', 'ird_itemreceipt', '=', 'ir_id')
            ->whereBetween('ird_date', [Carbon::parse($request->tgl_awal)->format('Y-m-d'), Carbon::parse($request->tgl_akhir)->format('Y-m-d')])
            ->join('d_productionorder', 'po_nota', '=', 'ir_notapo')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->groupBy('ir_id')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nota', function($data){
                return $data->po_nota;
            })
            ->addColumn('supplier', function($data){
                return $data->s_company;
            })
            ->addColumn('tanggal', function($data){
                return Carbon::createFromFormat('Y-m-d', $data->po_date)->format('d-m-Y');
            })
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--top-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-folder"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['nota', 'supplier', 'tanggal', 'action'])
            ->make(true);
    }
}
