<?php

namespace App\Http\Controllers\Inventory\Distribusi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_stock;
use App\d_stockdistribution;
use App\d_stockdistributiondt;
use App\m_expedition;
use Auth;
use Carbon\Carbon;
use DataTables;
use DB;

class ProsesOrderController extends Controller
{
    // retrive dataTable: list order
    public function getListOrder(Request $request)
    {
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');
        $data = d_stockdistribution::whereBetween('sd_date', [$from, $to])
            ->where('sd_from', Auth::user()->u_company)
            ->where('sd_status', 'N')
            ->orderBy('sd_date', 'desc')
            ->orderBy('sd_nota', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->sd_date)->format('d M Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-warning btn-approve-order" onclick="approveOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Proses Order"><i class="fa fa-get-pocket"></i></button>
                <button class="btn btn-danger btn-reject-order" onclick="rejectOrder(\'' . encrypt($data->sd_id) . '\')" type="button" title="Tolak Order"><i class="fa fa-ban"></i></button>
            </div>';
            // <button class="btn btn-info btn-nota hint--top-left hint--info" aria-label="Print Nota" title="Nota" type="button" onclick="printNota(' . $data->sd_id . ')"><i class="fa fa-print"></i></button>
            })
            ->addColumn('tujuan', function ($data) {
                $tmp = DB::table('m_company')->where('c_id', $data->sd_destination)->first();

                return $tmp->c_name;
            })
            ->rawColumns(['tanggal', 'action', 'tujuan', 'type'])
            ->make(true);
    }
    // process and approve order
    public function approveOrder($id)
    {
        // if (!AksesUser::checkAkses(7, 'update')){
        //     abort(401);
        // }

        // get stockdistribution by id
        $data['stockdist'] = d_stockdistribution::where('sd_id', decrypt($id))
            ->with('getOrigin')
            ->with('getDestination')
            ->with(['getDistributionDt' => function ($query) {
                $query
                    ->with(['getItem' => function ($query) {
                        $query
                            ->with('getUnit1')
                            ->with('getUnit2')
                            ->with('getUnit3');
                    }])
                    ->with('getUnit')
                    ->with('getProdCode');
            }])
            // ->with('getProductDelivery')
            ->first();
        // set variabel to store nota number
        $nota = $data['stockdist']->sd_nota;
        // change number format to int before send it to view
        // $data['stockdist']->getProductDelivery->pd_price = (int)$data['stockdist']->getProductDelivery->pd_price;
        // dd($data);
        // get data item-stock
        foreach ($data['stockdist']->getDistributionDt as $key => $val)
        {
            $item = $val->sdd_item;
            // get item-stock in pusat/werehouse
            $mainStock = d_stock::where('s_position',  Auth::user()->u_company)
                ->where('s_item', 'asd')
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            // dd($mainStock);

            if (is_null($mainStock)) {
                $val->stockUnit1 = 0;
                $val->stockUnit2 = 0;
                $val->stockUnit3 = 0;
            }
            else {
                // calculate item-stock based on unit-compare each item
                if ($mainStock->getItem->i_unitcompare1 != null) {
                    $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
                } else {
                    $stock['unit1'] = 0;
                }
                if ($mainStock->getItem->i_unitcompare2 != null) {
                    $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
                } else {
                    $stock['unit2'] = 0;
                }
                if ($mainStock->getItem->i_unitcompare3) {
                    $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
                } else {
                    $stock['unit3'] = 0;
                }

                $val->stockUnit1 = $stock['unit1'];
                $val->stockUnit2 = $stock['unit2'];
                $val->stockUnit3 = $stock['unit3'];
            }

        }

        $data['expeditions'] = m_expedition::get();

        return view('inventory/distribusibarang/prosesorder/edit', compact('data'));
    }
}
