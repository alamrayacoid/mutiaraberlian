<?php

namespace App\Http\Controllers;
use DB;
use function foo\func;
use Yajra\DataTables\DataTables;
use Response;
use Illuminate\Http\Request;
use Crypt;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;

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
            ->join('d_productionorderdt', 'pod_productionorder', '=', 'po_id')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('pod_received', '=', 'N')
            ->groupBy('po_id')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nota', function($data){
                return $data->po_nota;
            })
            ->addColumn('supplier', function($data){
                return $data->s_name;
            })
            ->addColumn('tanggal', function($data){
                return Carbon::createFromFormat('Y-m-d', $data->po_date)->format('d-m-Y');
            })
            ->addColumn('action', function($datas) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--top-left hint--info" aria-label="Lihat Detail" onclick="detail(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-folder"></i>
                        </button>
                        <button class="btn btn-info hint--top-left hint--info" aria-label="Terima" onclick="terima(\''.Crypt::encrypt($datas->po_id).'\')"><i class="fa fa-arrow-right"></i>
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
        return view('produksi.penerimaanbarang.list')->with(compact('order'));
    }

    public function listTerimaBarang($order = null)
    {
        try {
            $order = Crypt::decrypt($order);
        } catch (\DecryptException $e) {
            return Response::json(['status' => 'Failed']);
        }

        $data = DB::table('d_productionorder')
            ->select('d_productionorder.po_id', 'd_productionorder.po_nota', 'd_productionorderdt.pod_item', 'm_item.i_name', 'm_unit.u_name',
                'd_productionorderdt.pod_qty', 'd_itemreceiptdt.ird_qty as terima')
            ->join('d_productionorderdt', 'd_productionorderdt.pod_productionorder', '=', 'd_productionorder.po_id')
            ->join('m_item', 'd_productionorderdt.pod_item', '=', 'm_item.i_id')
            ->join('m_unit', 'd_productionorderdt.pod_unit', '=', 'm_unit.u_id')
            ->leftjoin('d_itemreceipt', function ($x){
                $x->on('d_productionorder.po_nota', '=', 'd_itemreceipt.ir_notapo');
            })
            ->leftjoin('d_itemreceiptdt', function($y){
                $y->on('d_itemreceipt.ir_id', '=', 'd_itemreceiptdt.ird_goodsreceipt');
                $y->where('d_itemreceiptdt.ird_item', '=', 'd_productionorderdt.pod_item');
            })
            ->where('d_productionorder.po_id', '=', $order);

        return DataTables::of($data)
            ->addColumn('barang', function($data){
                return $data->i_name;
            })
            ->addColumn('satuan', function($data){
                return $data->u_name;
            })
            ->addColumn('jumlah', function($data){
                return $data->pod_qty;
            })
            ->addColumn('terima', function($data){
                return ($data->terima == NULL) ? 0 : $data->terima;
            })
            ->addColumn('action', function($data) {
                return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-info hint--top-left hint--info" aria-label="Terima" onclick="receipt(\''.Crypt::encrypt($data->po_id).'\', \''.Crypt::encrypt($data->pod_item).'\')"><i class="fa fa-arrow-down"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['barang', 'satuan', 'jumlah', 'terima', 'action'])
            ->make(true);

    }

    public function detailTerimaBarang($id = null, $item = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\DecryptException $e) {
            return Response::json(['status' => 'Failed']);
        }

        try {
            $item = Crypt::decrypt($item);
        } catch (\DecryptException $e) {
            return Response::json(['status' => 'Failed']);
        }

        $data = DB::table('d_productionorder')
            ->select('d_productionorder.po_id as id', 'd_productionorder.po_nota as nota', 'd_productionorderdt.pod_item as item',
                'm_item.i_name as barang', 'm_unit.u_name as satuan', 'd_productionorderdt.pod_qty as jumlah', 'd_itemreceiptdt.ird_qty as terima')
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
                $y->on('d_itemreceipt.ir_id', '=', 'd_itemreceiptdt.ird_goodsreceipt');
                $y->where('d_itemreceiptdt.ird_item', '=', 'd_productionorderdt.pod_item');
            })
            ->where('d_productionorder.po_id', '=', $id)
            ->first();

        $data = array(
            'id'        => Crypt::encrypt($data->id),
            'nota'      => $data->nota,
            'item'        => Crypt::encrypt($data->item),
            'barang'    => $data->barang,
            'satuan'    => $data->satuan,
            'jumlah'    => $data->jumlah,
            'terima'    => $data->terima,
        );

        return Response::json(['status' => 'Success', 'data' => $data]);
    }
}
