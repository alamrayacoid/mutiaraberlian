<?php

namespace App\Http\Controllers\Produksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Crypt;
use Response;
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
          return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                        <button class="btn btn-danger hint--top-left hint--info" aria-label="Bayar" onclick="bayar(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-money"></i>
                        </button>
                    </div>';
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

    function bayar(Request $request)
    {
        if ($request->isMethod("get")) {
            try {
                $id     = Crypt::decrypt($request->id);
                $termin = Crypt::decrypt($request->termin);
            } catch (DecryptException $e) {
                return Response::json(['status' => 'Failed', 'message' => $e]);
            }

            $data = d_productionorder::where('po_id', $id)
                ->with('getPODt')
                ->with('getSupplier')
                ->with('getPODt.getItem')
                ->with('getPODt.getUnit')
                ->with(['getPOPayment' => function($query) use($termin) {
                    $query->where('pop_termin',$termin);
                }])
                ->first();


            $kekurangan = (int)$data->get_p_o_payment[0]['pop_value'] - (int)$data->get_p_o_payment[0]['pop_pay'];

            $terbayar = $data->get_p_o_payment[0]['pop_pay'];

//            $data = [
//                'poid'              => Crypt::encrypt($data->po_id),
//                'terminid'          => Crypt::encrypt($data->get_p_o_payment[0]['pop_termin']),
//                'nota'              => $data->po_nota,
//                'supplier'          => $data->get_supplier['s_name'],
//                'tanggal_pembelian' => Carbon::parse($data->po_date)->format('d-m-Y'),
//                'terbayar'          => $terbayar,
//                'kekurangan'        => $kekurangan
//            ];

//            $data = [
//                'supplier'          => $data
//            ];

            return $data;

//            return Response::json(['status' => "Success", 'data' => $data]);
        }
    }
}
