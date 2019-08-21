<?php

namespace App\Http\Controllers\Inventory;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TurnOverController extends Controller
{
    public function index()
    {
        return view('inventory.manajemenstok.analisastockto.index');
    }

    public function getData(Request $request)
    {

    }

    public function getDataPeriode(Request $request)
    {
        $periode = '01-' . $periode;
        $periode = Carbon::createFromFormat('d-m-Y', $periode);
        $comp = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();
dd($periode);
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
                    WHERE i_id = ".$item."
                    AND s_comp = '".$comp->u_company."'
                    AND s_position = '".$comp->u_company."'
                    AND sm_date <= '".$periode."'
                    GROUP BY i_id");

        dd($stock_awal);
    }


}
