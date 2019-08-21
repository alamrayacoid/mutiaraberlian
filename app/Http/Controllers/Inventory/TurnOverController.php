<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class TurnOverController extends Controller
{
    public function index()
    {
        return view('inventory.manajemenstok.analisastockto.index');
    }

    public function getDataPeriode(Request $request)
    {
        $startdate = Carbon::createFromFormat('d-m-Y', $request->start);
        $enddate = Carbon::createFromFormat('d-m-Y', $request->end);
        $item = $request->id_item;
        $comp = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();
        
        $qtyAwal = 0;
        $qtyAkhir = 0;
        
        $stock_awal = DB::select("SELECT sm_stock, sm_detailid, i_name, DATE_FORMAT(sm_date, '%m-%Y') AS periode, sm_qty,
            (SELECT SUM(sm_qty) 
                FROM d_stock_mutation child2
                JOIN m_mutcat mutcat2 ON m_id = child2.sm_mutcat
                WHERE child2.sm_stock = parent.sm_stock 
                AND mutcat2.m_status = 'M') AS masuk,
            (SELECT SUM(sm_qty) 
                FROM d_stock_mutation child1 
                JOIN m_mutcat mutcat1 ON m_id = child1.sm_mutcat
                WHERE child1.sm_stock = parent.sm_stock 
                AND mutcat1.m_status = 'K') AS keluar,
            (SELECT(masuk))-(SELECT(keluar)) AS stockakhir
            FROM d_stock_mutation parent
            JOIN d_stock stock ON s_id = sm_stock
            JOIN m_item ON s_item = i_id
            WHERE i_id = '".$item."' 
            AND s_comp = '".$comp->c_id."'
            AND s_position = '".$comp->c_id."'
            AND sm_date <= '".$startdate->format('Y-m-d')."'
            GROUP BY i_id");

        if (count($stock_awal) > 0) {
            $qtyAwal = $stock_awal[0]->stockakhir;
        }

        $stock_akhir = DB::select("SELECT sm_stock, sm_detailid, i_name, DATE_FORMAT(sm_date, '%m-%Y') AS periode, sm_qty,
            (SELECT SUM(sm_qty) 
                FROM d_stock_mutation child2
                JOIN m_mutcat mutcat2 ON m_id = child2.sm_mutcat
                WHERE child2.sm_stock = parent.sm_stock 
                AND mutcat2.m_status = 'M') AS masuk,
            (SELECT SUM(sm_qty) 
                FROM d_stock_mutation child1 
                JOIN m_mutcat mutcat1 ON m_id = child1.sm_mutcat
                WHERE child1.sm_stock = parent.sm_stock 
                AND mutcat1.m_status = 'K') AS keluar,
            (SELECT(masuk))-(SELECT(keluar)) AS stockakhir
            FROM d_stock_mutation parent
            JOIN d_stock stock ON s_id = sm_stock
            JOIN m_item ON s_item = i_id
            WHERE i_id = '".$item."' 
            AND s_comp = '".$comp->c_id."'
            AND s_position = '".$comp->c_id."'
            AND sm_date <= '".$enddate->format('Y-m-d')."'
            AND sm_date >= '".$startdate->format('Y-m-d')."'
            GROUP BY i_id");

        if (count($stock_akhir) > 0) {
            $qtyAkhir = $stock_akhir[0]->stockakhir;
        }

        $totalHPP = DB::table('d_stock_mutation')
            ->join('d_stock', 's_id', '=', 'sm_stock')
            ->join('m_mutcat', 'm_id', '=', 'sm_mutcat')
            ->select(DB::raw("sum(sm_hpp*sm_qty) as sm_hpp"))
            ->where('s_comp', '=', $comp->c_id)
            ->where('s_position', '=', $comp->c_id)
            ->where('s_item', '=', $item)
            ->where('sm_date', '<=', $enddate->format('Y-m-d'))
            ->where('sm_date', '>=', $startdate->format('Y-m-d'))
            ->where('m_status', '=', 'K')
            ->groupBy('s_id')
            ->first();

        $hpp = 0;
        if ($totalHPP !== null) {
            $hpp = (int)$totalHPP->sm_hpp;
        }

        $hasil = 0;
        if ($hpp == 0 && (($qtyAwal+$qtyAkhir)/2) == 0) {
            $hasil = 0;
        } else {
            $hasil = $hpp/(($qtyAwal+$qtyAkhir)/2);
            $hasil = number_format($hasil, 2, ',', '.');
        }

        $data = DB::table('m_item')
            ->select('i_name', 'i_code')
            ->where('i_id', '=', $item)
            ->first();

        $data->hasil = $hasil;
        $data->qtyawal = $qtyAwal;
        $data->qtyakhir = $qtyAkhir;
        $data->totalhpp = number_format($hpp, 0, ',', '.');

        return response()->json(
            $data
        );
    }


}
