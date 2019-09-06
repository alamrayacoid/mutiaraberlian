<?php

namespace App\Http\Controllers\Keuangan\analisis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Response;

class ROEController extends Controller
{
    public function index(){
        return view('keuangan.analisis.index');
    }

    public function getData(Request $request){
        $periodeawal = Carbon::createFromFormat("d-m-Y", "01-" . $request->awal);
        $periodeakhir = Carbon::createFromFormat("d-m-Y", "01-" . $request->akhir);
        $pusat = DB::table('m_company')
            ->where('c_type', '=', 'PUSAT')
            ->first();

        //aset (Opening Aset + End Aset) / 2
        //akun aset memiliki nomor akun yang berawalan 1
        $akunAset = DB::table('dk_akun')
            ->whereRaw("LEFT(ak_nomor, 1) = '1'")
            ->where('ak_comp', '=', $pusat->c_id)
            ->get();

        $idAset = [];
        for ($i=0; $i < count($akunAset); $i++) {
            $idAset[$i] = $akunAset[$i]->ak_id;
        }

        $saldoAsetAwal = DB::table('dk_akun_saldo')
            ->select(DB::raw('round(sum(as_saldo_awal)) as saldo_awal'))
            ->whereIn('as_akun', $idAset)
            ->whereMonth('as_periode', '=', $periodeawal->format('m'))
            ->whereYear('as_periode', '=', $periodeawal->format('Y'))
            ->get();

        $saldoAsetAkhir = DB::table('dk_akun_saldo')
            ->select(DB::raw('round(sum(as_saldo_akhir)) as saldo_akhir'))
            ->whereIn('as_akun', $idAset)
            ->whereMonth('as_periode', '=', $periodeakhir->format('m'))
            ->whereYear('as_periode', '=', $periodeakhir->format('Y'))
            ->get();

        $asetAwal = 0;
        $asetAkhir = 0;
        if (count($saldoAsetAwal) > 0) {
            $asetAwal = $saldoAsetAwal[0]->saldo_awal;
        }
        if (count($saldoAsetAkhir) > 0) {
            $asetAkhir = $saldoAsetAkhir[0]->saldo_akhir;
        }

        $aset = ($asetAwal + $asetAkhir)/2;

        //sales diambil dari laporan labarugi
        //nomor akun berawalan 4
        $datasales = DB::table('dk_akun_saldo as parent')
            ->join('dk_akun as akun', function($q){
                $q->on('parent.as_akun', '=', 'akun.ak_id');
                $q->whereRaw('left(ak_nomor,1) = "4"');
            })
            ->select(
                DB::raw("(
                    SELECT COALESCE(sum(as_saldo_akhir), 0,00) FROM dk_akun_saldo childawal
                    WHERE childawal.as_akun = akun.ak_id
                    AND DATE(as_periode) <= '".$periodeakhir->format('Y-m-d')."'
                    AND DATE(as_periode) >= '".$periodeawal->format('Y-m-d')."'
                ) as sales")
            )
            ->get();

        $sales = 0;
        if (count($datasales) > 0) {
            $sales = $datasales[0]->sales;
        }

        //Equity diambil dari laporan neraca
        //nomor akun berawalan 3
        $dataekuitas = DB::table('dk_akun_saldo as parent')
            ->join('dk_akun as akun', function($q){
                $q->on('parent.as_akun', '=', 'akun.ak_id');
                $q->whereRaw('left(ak_nomor,1) = "3"');
            })
            ->select(
                DB::raw("(
                    SELECT COALESCE(sum(as_saldo_awal), 0,00) FROM dk_akun_saldo childawal
                    WHERE childawal.as_akun = akun.ak_id
                    AND MONTH(as_periode) = '".$periodeawal->format('m')."'
                    AND YEAR(as_periode) = '".$periodeawal->format('Y')."'
                ) as saldoawal"),
                DB::raw("(
                    SELECT COALESCE(sum(as_saldo_akhir), 0,00) FROM dk_akun_saldo childakhir
                    WHERE childakhir.as_akun = akun.ak_id
                    AND MONTH(as_periode) = '".$periodeakhir->format('m')."'
                    AND YEAR(as_periode) = '".$periodeakhir->format('Y')."'
                ) as saldoakhir"),
                DB::raw("(select abs((saldoawal - saldoakhir))) as ekuitas")
            )
            ->get();

        $ekuitas = 0;
        if (count($dataekuitas) > 0) {
            $ekuitas = $dataekuitas[0]->ekuitas;
        }

        //Net Profit diambil dari laporan Labarugi
        //Pendapatan - Biaya, nomor akun Pendapatan = 4; nomor akun Biaya = 5 & 6
        $datanetprofit = DB::table('dk_akun_saldo as parent')
            ->select(
                DB::raw("(
                    SELECT COALESCE(sum(as_saldo_akhir), 0,00) FROM dk_akun_saldo childpendapatan
                    JOIN dk_akun akun ON akun.ak_id = childpendapatan.as_akun
                    WHERE parent.as_akun = childpendapatan.as_akun
                    AND LEFT(akun.ak_nomor, 1) = '4'
                    AND DATE(as_periode) <= '".$periodeakhir->format('Y-m-d')."'
                    AND DATE(as_periode) >= '".$periodeawal->format('Y-m-d')."'
                ) as pendapatan"),
                DB::raw("(
                    SELECT COALESCE(sum(as_saldo_akhir), 0,00) FROM dk_akun_saldo childbiaya
                    JOIN dk_akun akun2 ON akun2.ak_id = childbiaya.as_akun
                    WHERE parent.as_akun = childbiaya.as_akun
                    AND LEFT(akun2.ak_nomor, 1) IN  ('5', '6', '7')
                    AND DATE(as_periode) <= '".$periodeakhir->format('Y-m-d')."'
                    AND DATE(as_periode) >= '".$periodeawal->format('Y-m-d')."'
                ) as biaya"),
                DB::raw("(select (pendapatan - biaya)) as netprofit")
            )
            ->get();

        $netprofit = 0;
        if (count($datanetprofit) > 0) {
            $netprofit = $datanetprofit[0]->netprofit;
        }

        //efektifitas = sales/aset
        $efektifitas = ($aset != 0.0) ? ($sales/$aset) : 0;
        $efesiensi = ($sales != 0.0) ? ($netprofit/$sales) : 0;
        $produktivitas = ($aset != 0.0) ? ($netprofit/$aset) : 0;
        $leverage = ($dataekuitas[0]->saldoakhir != 0.0) ? ($aset/$dataekuitas[0]->saldoakhir) : 0;
        $roe = ($ekuitas != 0.0) ? ($netprofit/$ekuitas) : 0;

        return Response::json([
            'aset' => number_format($aset, '2', ',', '.'),
            'sales' => number_format($sales, '2', ',', '.'),
            'ekuitas' => number_format($ekuitas, '2', ',', '.'),
            'netprofit' => number_format($netprofit, '2', ',', '.'),
            'efektifitas' => number_format($efektifitas, '2', ',', '.'),
            'efesiensi' => number_format($efesiensi, '2', ',', '.'),
            'produktivitas' => number_format($produktivitas, '2', ',', '.'),
            'leverage' => number_format($leverage, '2', ',', '.'),
            'roe' => number_format($roe, '2', ',', '.')
        ]);

    }
}
