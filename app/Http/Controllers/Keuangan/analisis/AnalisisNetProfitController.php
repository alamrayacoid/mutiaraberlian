<?php

namespace App\Http\Controllers\Keuangan\analisis;

use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalisisNetProfitController extends Controller
{
    public function index()
    {
        $cabang = json_encode(DB::table('m_company')
            ->where('c_id', Auth::user()->u_company)
            ->select('c_id as id', 'c_name as text')
            ->get());

        return view('keuangan.analisis.netprofit.index', compact('cabang'));
    }

    public function getData(Request $request){
        // return json_encode($request->all());

        $cabang = json_encode(DB::table('m_company')
            ->where('c_id', Auth::user()->u_company)
            ->select('c_id as id', 'c_name as text')
            ->get());

        if($request->type == 'bulan'){
            
            $waktu_awal  = explode('/', $request->lap_tanggal_awal)[1].'-'.explode('/', $request->lap_tanggal_awal)[0].'-01';
            $waktu_akhir = explode('/', $request->lap_tanggal_akhir)[1].'-'.explode('/', $request->lap_tanggal_akhir)[0].'-01';
            
            $data = [
                'periode'   => [],
                'ocf'       => [],
                'netProfit' => []
            ];

            while (strtotime($waktu_awal) <= strtotime($waktu_akhir)) {
                $ocf = DB::table('dk_jurnal_detail')
                            ->whereIn('jrdt_cashflow', function($query){
                                $query->select('ac_id')->from('dk_akun_cashflow')->where('ac_type', 'OCF');
                            })
                            ->join('dk_jurnal', 'jr_id', 'jrdt_jurnal')
                            ->where(DB::raw('DATE_FORMAT(jr_tanggal_trans, "%m/%Y")'), date('m/Y', strtotime($waktu_awal)))
                            ->select(DB::raw("coalesce(sum(if(jrdt_dk = 'K', (jrdt_value * -1), jrdt_value)), 0) as value"))->first();

                $profit = DB::table('dk_akun')
                        ->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                        ->where('as_periode', $waktu_awal)
                        ->where(DB::raw('substring(ak_nomor, 1, 1)'), '4')
                        ->where('ak_isactive', '1')
                        ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '8')
                        ->where('ak_isactive', '1')
                        ->where('as_periode', $waktu_awal)
                        ->select(
                            DB::raw('coalesce(sum(as_saldo_akhir - as_saldo_awal), 0) as saldo_akhir')
                        )->first();

                // return json_encode($profit);

                $beban = DB::table('dk_akun')
                                ->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                                ->where('as_periode', $waktu_awal)
                                ->where(DB::raw('substring(ak_nomor, 1, 1)'), '5')
                                ->where('ak_isactive', '1')
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '6')
                                ->where('ak_isactive', '1')
                                ->where('as_periode', $waktu_awal)
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '7')
                                ->where('ak_isactive', '1')
                                ->where('as_periode', $waktu_awal)
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '9')
                                ->where('ak_isactive', '1')
                                ->where('as_periode', $waktu_awal)
                                ->select(
                                    DB::raw('coalesce(sum(as_saldo_akhir - as_saldo_awal), 0) as saldo_akhir')
                                )->first();

                array_push($data['periode'], date('M y', strtotime($waktu_awal)));
                array_push($data['ocf'], $ocf->value / 1000);
                array_push($data['netProfit'], (($profit->saldo_akhir - $beban->saldo_akhir) / 1000));

                $waktu_awal = date('Y-m-d', strtotime('+1 month', strtotime($waktu_awal)));
            }

            // return response()->json($data);

        }else{
            $waktu_awal  = $request->lap_tanggal_awal;
            $waktu_akhir = $request->lap_tanggal_akhir;

            $time  = $request->lap_tanggal_awal.'-01-01';
            $timeEnd = $request->lap_tanggal_akhir.'-01-01';

            // return json_encode($waktu_awal);
            
            $data = [
                'periode'   => [],
                'ocf'       => [],
                'netProfit' => []
            ];

            while (strtotime($time) <= strtotime($timeEnd)) {

                $ocf = DB::table('dk_jurnal_detail')
                            ->whereIn('jrdt_cashflow', function($query){
                                $query->select('ac_id')->from('dk_akun_cashflow')->where('ac_type', 'OCF');
                            })
                            ->join('dk_jurnal', 'jr_id', 'jrdt_jurnal')
                            ->where(DB::raw('DATE_FORMAT(jr_tanggal_trans, "%Y")'), date('Y', strtotime($time)))
                            ->select(DB::raw("coalesce(sum(if(jrdt_dk = 'K', (jrdt_value * -1), jrdt_value)), 0) as value"))->first();

                $profit = DB::table('dk_akun')
                        ->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                        ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                        ->where(DB::raw('substring(ak_nomor, 1, 1)'), '4')
                        ->where('ak_isactive', '1')
                        ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '8')
                        ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                        ->where('ak_isactive', '1')
                        ->select(
                            DB::raw('coalesce(sum(as_saldo_akhir - as_saldo_awal), 0) as saldo_akhir')
                        )->first();

                // return json_encode($profit);

                $beban = DB::table('dk_akun')
                                ->leftJoin('dk_akun_saldo', 'dk_akun_saldo.as_akun', 'dk_akun.ak_id')
                                ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                                ->where(DB::raw('substring(ak_nomor, 1, 1)'), '5')
                                ->where('ak_isactive', '1')
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '6')
                                ->where('ak_isactive', '1')
                                ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '7')
                                ->where('ak_isactive', '1')
                                ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                                ->orWhere(DB::raw('substring(ak_nomor, 1, 1)'), '9')
                                ->where('ak_isactive', '1')
                                ->where(DB::raw('DATE_FORMAT(as_periode, "%Y")'), date('Y', strtotime($time)))
                                ->select(
                                    DB::raw('coalesce(sum(as_saldo_akhir - as_saldo_awal), 0) as saldo_akhir')
                                )->first();

                array_push($data['periode'], date('Y', strtotime($time)));
                array_push($data['ocf'], $ocf->value / 1000);
                array_push($data['netProfit'], (($profit->saldo_akhir - $beban->saldo_akhir) / 1000));

                $time = date('Y-m-d', strtotime('+1 year', strtotime($time)));
            }
        } 

        return response()->json($data);
    }
}
