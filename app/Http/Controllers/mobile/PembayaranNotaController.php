<?php

namespace App\Http\Controllers\mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use Response;

class PembayaranNotaController extends Controller
{
    public function getSupplier()
    {
        $data = DB::table('d_productionorder')
            ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->select('s_company as s_name', 'po_nota', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_date'), DB::raw('replace(format(round(pop_value - pop_pay),0), ",", ".") as pop_value'))
            ->where('po_status', '=', 'BELUM')
            ->where('pop_status', '=', 'N')
            ->orderBy('pop_datetop', 'desc')
            ->groupBy('po_id')
            ->get();

        return json_encode(["NotaOrderProduksi" => $data]);
    }

    public function getTermin(Request $request)
    {
        $nota = $request->nota;
        $data = DB::table('d_productionorderpayment')
            ->join('d_productionorder', 'po_id', '=', 'pop_productionorder')
            ->select('pop_termin', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_datetop'), 'pop_value', DB::raw('replace(format(round(pop_value - pop_pay),0), ",", ".") as pop_value'), DB::raw('replace(format(round(pop_value - pop_pay),0), ",", "") as pop_valueint'), DB::raw('round(pop_value) as tagihan'), DB::raw('replace(format(round(pop_value),0), ",", ".") as tagihanrp'))
            ->where('po_nota', '=', $nota)
            ->where('pop_status', '=', 'N')
            ->get();

        return json_encode(["ListTerminNota" => $data]);
    }

    public function updatePembayaran(Request $request)
    {
        $nota = $request->nota;
        $termin = $request->termin;
        $bayar = $request->bayar;

        DB::beginTransaction();
        try {
            $id = DB::table('d_productionorder')
                ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
                ->where('po_nota', '=', $nota)
                ->where('po_termin', '=', $termin)
                ->first();

            if (($bayar + $id->pop_pay) < $id->pop_value){
                DB::table('d_productionorderpayment')
                    ->where('pop_productionorder', '=', $id->po_id)
                    ->where('pop_termin', '=', $termin)
                    ->update([
                        "pop_pay" => ($bayar + $id->pop_pay),
                        "pop_date" => Carbon::now('Asia/Jakarta')->format('Y-m-d')
                    ]);
            } else {
                DB::table('d_productionorderpayment')
                    ->where('pop_productionorder', '=', $id->po_id)
                    ->where('pop_termin', '=', $termin)
                    ->update([
                        "pop_pay" => ($bayar + $id->pop_pay),
                        "pop_date" => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                        "pop_status" => "Y"
                    ]);
            }
            DB::commit();
            return Response::json(['status' => 'Success']);
        } catch (\Exception $e){
            DB::rollBack();
            return Response::json(['status' => 'Failed']);
        }
    }
}
