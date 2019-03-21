<?php

namespace App\Http\Controllers\Produksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Crypt;
use Mockery\Exception;
use Response;
use Currency;
use Carbon\Carbon;
use App\d_productionorder;
use App\d_productionorderpayment;
use Yajra\DataTables\DataTables;
use Illuminate\Contracts\Encryption\DecryptException;

class PembayaranController extends Controller
{
    /**
    * Return DataTable list for view.
    *
    * @return Yajra/DataTables
    */
    public function getList(Request $request)
    {
      // dd($request->all());
      $datas = d_productionorderpayment::where('pop_productionorder', $request->po_id)
      ->orderBy('pop_productionorder', 'asc')
      ->orderBy('pop_termin', 'asc')
      ->get();
      return Datatables::of($datas)
      ->addIndexColumn()
      ->addColumn('estimasi', function($datas) {
        return date('d-m-Y', strtotime($datas->pop_datetop));
      })
      ->addColumn('nominal', function($datas) {
        return '<div class="label"> <p class="float-left">Rp </p>
                    <p class="float-right">' . number_format($datas->pop_value, 2, ',', '.') . '</p>
                </div>';
      })
      ->addColumn('terbayar', function($datas) {
          return '<div class="label"><p class="float-left">Rp </p>
                <p class="float-right">' . number_format($datas->pop_pay, 2, ',', '.') . '</p>
                </div>';
      })
      ->addColumn('date', function($datas) {
        return date('d-m-Y', strtotime($datas->pop_date));
      })
      ->addColumn('status', function($datas) {
        if ($datas->pop_status == 'Y') {
            return '<div class="text-center">LUNAS</div>';
//          return '<div class="text-center">
//                    <div class="status-success">
//                        <p>Lunas</p>
//                    </div>
//                   </div>';
        } elseif ($datas->pop_status == 'N') {
            return '<div class="text-center">BELUM LUNAS</div>';
//          return '<div class="text-center">
//                    <div class="status-danger">
//                        <p>Belum</p>
//                    </div>
//                  </div>';
        }
      })
      ->addColumn('action', function($datas) {
          if ($datas->pop_status == 'N') {
              return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-danger hint--top-left hint--info" aria-label="Bayar" onclick="bayar(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-money"></i>
                        </button>
                    </div>';
          }

//          return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
//                        <button class="btn btn-info hint--top-left hint--info" aria-label="Detail" onclick="Detail(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-list"></i>
//                        </button>
//                        <button class="btn btn-danger hint--top-left hint--info" aria-label="Detail" onclick="Bayar(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-money"></i>
//                        </button>
//                    </div>';
      })
      ->rawColumns(['estimasi', 'nominal', 'terbayar', 'date', 'status', 'action'])
      ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//      $data = d_productionorder::with('getSupplier')
//        ->with('getPOPayment')
//          ->selectRaw('sum(pop_pay) as terbayar, po_id, po_nota')
//        ->orderBy('po_nota', 'asc')
//        ->get();

        $data = DB::table('d_productionorder')
            ->join('m_supplier', function($s){
                $s->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->join('d_productionorderpayment', function($pop){
                $pop->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
            })
            ->groupby(['d_productionorder.po_nota', 'd_productionorderpayment.pop_productionorder'])
            ->select('d_productionorder.po_id as id', 'd_productionorder.po_nota as nota', 'm_supplier.s_name as supplier',
                DB::raw('sum(d_productionorderpayment.pop_value) as value'), DB::raw('sum(d_productionorderpayment.pop_pay) as terbayar'))
            ->get();

      return view('produksi/pembayaran/index', compact('data'));
    }

    public function show($id, $termin)
    {
        try {
            $id     = Crypt::decrypt($id);
            $termin = Crypt::decrypt($termin);
        } catch (DecryptException $e) {
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }
        
        $data = d_productionorder::where('po_id', $id)
            ->with('getPODt')
            ->with('getPODt.getItem')
            ->with('getPODt.getUnit')
            ->with(['getPOPayment' => function($query) use($termin) {
              $query->where('pop_termin',$termin);
            }])
            ->first();
        
        return Response::json(['status' => "Success", 'data' => $data]);
    }

    function listBayar(Request $request)
    {
        try {
            $id     = Crypt::decrypt($request->id);
            $termin = Crypt::decrypt($request->termin);
        } catch (DecryptException $e) {
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }

//            $data = d_productionorder::where('po_id', $id)
//                ->with('getPODt')
//                ->with('getSupplier')
//                ->with('getPODt.getItem')
//                ->with('getPODt.getUnit')
//                ->with(['getPOPayment' => function($query) use($termin) {
//                    $query->where('pop_termin',$termin);
//                }])
//                ->first();

        $data = DB::table('d_productionorder')
            ->join('d_productionorderpayment', function($x) use ($termin){
                $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                $x->where('d_productionorderpayment.pop_termin', '=', $termin);
            })
            ->join('m_supplier', function($y) use ($termin){
                $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->where('d_productionorder.po_id', '=', $id)
            ->select('d_productionorder.po_id', 'd_productionorder.po_nota', 'd_productionorder.po_date',
                'm_supplier.s_name', 'd_productionorderpayment.pop_termin', 'd_productionorderpayment.pop_value', 'd_productionorderpayment.pop_pay')
            ->first();

        if ($data->pop_pay == null || $data->pop_pay == "") {
            $pay = 0;
        } else {
            $pay = $data->pop_pay;
        }

        $kekurangan = $data->pop_value - $pay;

        $data = [
            'poid'              => Crypt::encrypt($data->po_id),
            'nota'              => $data->po_nota,
            'supplier'          => $data->s_name,
            'tanggal_pembelian' => Carbon::parse($data->po_date)->format('d-m-Y'),
            'termin'            => $data->pop_termin,
            'tagihan'           => number_format($data->pop_value, 0, ',', ''),
            'terbayar'          => number_format($pay, 0, ',', ''),
            'kekurangan'        => number_format($kekurangan, 0, ',', '')
        ];

        return Response::json(['status' => "Success", 'data' => $data]);
    }

    public function bayar(Request $request)
    {
        $bayar = Currency::removeRupiah($request->nilai_bayar);
        $termin = $request->termin;
        try {
            $poid = Crypt::decrypt($request->poid);
        } catch (DecryptException $e) {
            return Response::json(['status' => "Failed", 'message' => $e]);
        }

        $data_po = DB::table('d_productionorder')
                ->join('d_productionorderpayment', function($x) use ($termin){
                    $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                    $x->where('d_productionorderpayment.pop_termin', '=', $termin);
                })
                ->join('m_supplier', function($y) use ($termin){
                    $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
                })
                ->where('d_productionorder.po_id', '=', $poid)
                ->select('d_productionorderpayment.pop_value as value', 'd_productionorderpayment.pop_pay as pay')
                ->first();
        $value = number_format($data_po->value, 0, ',', '');
        $status = "N";
        if ($bayar > $value || $data_po->pay+$bayar > $value) {
            return Response::json(['status' => "Failed", 'message' => "Nilai pembayaran yang Anda masukkan melebihi tagihan termin ke-".$termin]);
        } else if ($bayar == $value) {
            $status = "Y";
        } else if ($data_po->pay+$bayar == $value) {
            $status = "Y";
        }

        DB::beginTransaction();
        try{
            $values = [
                'pop_date'  => Carbon::now("Asia/Jakarta")->format("Y-m-d"),
                'pop_pay'   => $data_po->pay+$bayar,
                'pop_status'=> $status
            ];

            DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $poid)
                ->where('pop_termin', '=', $termin)
                ->update($values);

            DB::commit();
            return Response::json(['status' => "Success", 'message' => "Data berhasil disimpan"]);
        }catch (Exception $e){
            DB::rollback();
            return Response::json(['status' => "Failed", 'message' => $e]);
        }
    }
}
