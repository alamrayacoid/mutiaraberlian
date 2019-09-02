<?php

namespace App\Http\Controllers\Keuangan\penerimaanpiutang;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

use Auth;
use App\d_sales;
use App\d_salescomp;
use App\d_salescomppayment;
use App\m_company;
use App\m_paymentmethod;
use Carbon\Carbon;
use DataTables;
use DB;

class PenerimaanPiutangController extends Controller
{
    public function index()
    {
        $start = new Carbon('first day of this month');
        $end = new Carbon('last day of this month');

        return view('keuangan.penerimaan_piutang.index', compact('start', 'end'));
    }
    public function getDetailTransaksi(Request $request)
    {
        $user = Auth::user()->getCompany;
        $type = $request->type;
        try {
            $nota = Crypt::decrypt($request->nota);
        } catch (\Exception $e){
            return response()->json([
                'status' => 'gagal',
                'message' => 'tidak diketahui'
            ]);
        }

        if ($type == 'AGEN') {
            $data = d_salescomp::where('sc_nota', $nota)
                ->with('getAgent')
                ->with(['getSalesCompDt' => function ($q) {
                    $q->with('getItem')->with('getUnit');
                }])
                ->first();
            //     ->join('m_company', 'c_id', '=', 'sc_member')
            //     ->join('d_salescompdt', 'scd_sales', '=', 'sc_id')
            //     ->join('m_item', 'i_id', '=', 'scd_item')
            //     ->join('m_unit', 'u_id', '=', 'scd_unit')
            //     ->where('sc_id', '=', $id)
            // ->select('sc_nota', 'c_name', DB::raw('date_format(sc_datetop, "%d-%m-%Y") as sc_datetop'),
            // DB::raw('floor(sc_total) as sc_total'), DB::raw('floor(scd_value) as scd_value'),
            // DB::raw('floor(scd_discvalue) as scd_discvalue'), DB::raw('floor(scd_totalnet) as scd_totalnet'),
            // 'scd_qty', 'i_name', 'u_name')
            // ->get();
        }
        else {
            $salesComp = d_salescomp::where('sc_nota', $nota)->first();
            if (is_null($salesComp)) {
                $data = d_sales::where('s_nota', $nota)
                    ->with('getComp')
                    ->with(['getSalesDt' => function ($q) {
                        $q->with('getItem')->with('getUnit');
                    }])
                    ->first();
                $data->source = 'Sales';
                $data->nota = $data->s_nota;
                $data->agent = $data->getComp->c_name;
                $data->total = $data->s_total;
            }
            else {
                $data = d_salescomp::where('sc_nota', $nota)
                    ->with('getComp')
                    ->with(['getSalesCompDt' => function ($q) {
                        $q->with('getItem')->with('getUnit');
                    }])
                    ->first();
                $data->source = 'SalesComp';
                $data->nota = $data->sc_nota;
                $data->agent = $data->getComp->c_name;
                $data->total = $data->sc_total;
            }
        }

        $pembayaran = d_salescomppayment::where('scp_salescomp', '=', $data->sc_id)
            ->select(
                DB::raw('date_format(scp_date, "%d-%m-%Y") as scp_date'),
                DB::raw('floor(scp_pay) as scp_pay'),
                'scp_salescomp',
                'scp_detailid'
            )
            ->orderBy('scp_date')
            ->get();

        $jenis = [];
        if ($user->c_type == "PUSAT") {
            $jenis = m_paymentmethod::where('pm_isactive', 'Y')
                ->with('getAkun')
                ->where('pm_comp', '=', $user->c_id)
                ->get();
        }
        else {
            $jenis = m_paymentmethod::where('pm_isactive', 'Y')
                ->with('getAkun')
                ->where('pm_comp', '=', $user->c_id)
                ->get();

            if (count($jenis) < 1) {
                $idAkun = dk_akun::max('ak_id') + 1;

                DB::table('dk_akun')
                    ->insert([
                        'ak_id' => $idAkun,
                        'ak_nomor' => '1.001.001',
                        'ak_tahun' => Carbon::now('Asia/Jakarta')->format('Y'),
                        'ak_comp' => $user->c_id,
                        'ak_nama' => 'KAS ' . $user->c_name,
                        'ak_sub_id' => '001',
                        'ak_kelompok' => 13,
                        'ak_posisi' => 'D',
                        'ak_opening_date' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                        'ak_opening' => 0,
                        'ak_setara_kas' => '0',
                        'ak_isactive' => 1
                    ]);

                $pmId = m_paymentmethod::max('pm_id') + 1;
                DB::table('m_paymentmethod')
                    ->insert([
                        'pm_id' => $pmId,
                        'pm_comp' => $user->c_id,
                        'pm_name' => 'KAS ' . $user->c_name,
                        'pm_akun' => $idAkun,
                        'pm_note' => '',
                        'pm_isactive' => 'Y'
                    ]);
            }

            $jenis = m_paymentmethod::where('pm_isactive', 'Y')
                ->with('getAkun')
                ->where('pm_comp', '=', $user->c_id)
                ->get();
        }

        return response()->json([
            'data' => $data,
            'pay' => $pembayaran,
            'jenis' => $jenis
        ]);
    }

// Start Penerimaan Piutang Agen ==========================================================================
    public function getDataPenerimaanAgen(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $agen = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $info = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name',
                DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as sc_total'),
                'sc_nota as nota',)
            ->where('sc_paidoff', '=', 'N')
            ->groupBy('sc_id')
            ->where('sc_comp', '=', $user->u_company);

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $info = $info->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $info = $info->where('scc.sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->agen) && $request->agen != '' && $request->agen !== null){
            $agen = $request->agen;
            $info = $info->where('sc_member', '=', $agen);
        }

        return DataTables::of($info)
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="showPaymentProcessAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                if ($info->pembayaran == null || $info->pembayaran == '') {
                    return "Rp. " . number_format($info->sc_total, '0', ',', '.');
                }
                return "Rp. " . number_format($info->sisa, '0', ',', '.');
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function payPiutangAgen(Request $request)
    {
        dd($request->all());
        $nota = $request->nota;
        $bayar = (int)$request->bayar;
        $tanggal = $request->tanggal;
        $paymentmethod = $request->paymentmethod;

        try {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e){
            return response()->json([
                'status' => 'gagal',
                'message' => 'Tanggal tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            $salescomp = DB::table('d_salescomp')
                ->leftJoin('d_salescomppayment', 'scp_salescomp', '=', 'sc_id')
                ->select('d_salescomp.*', DB::raw('sum(coalesce(scp_pay, 0)) as jumlah'))
                ->where('sc_nota', '=', $nota)
                ->orderBy('sc_id')
                ->get();

            if (count($salescomp) < 1){
                DB::rollback();
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Nota tidak ditemukan'
                ]);
            }

            $sisa = (int)$salescomp[0]->sc_total - (int)$salescomp[0]->jumlah;

            if ($bayar > $sisa) {
                DB::rollback();
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Pembayaran melebihi jumlah piutang'
                ]);
            }

            $detailid = DB::table('d_salescomppayment')
                ->where('scp_salescomp', '=', $salescomp[0]->sc_id)
                ->max('scp_detailid');

            ++$detailid;

            DB::table('d_salescomppayment')
                ->insert([
                    'scp_salescomp' => $salescomp[0]->sc_id,
                    'scp_detailid' => $detailid,
                    'scp_date' => $tanggal,
                    'scp_pay' => $bayar,
                    'scp_payment' => $paymentmethod
                ]);

            $cek = DB::table('d_salescomp')
                ->join('d_salescomppayment', 'scp_salescomp', '=', 'sc_id')
                ->select(DB::raw('sum(scp_pay) as bayar'), 'sc_total', 'sc_member')
                ->where('sc_id', '=', $salescomp[0]->sc_id)
                ->groupBy('sc_id')
                ->first();

            // if 'piutang' is paid-off
            if ($cek->bayar == $cek->sc_total){
                DB::table('d_salescomp')
                    ->where('sc_id', '=', $salescomp[0]->sc_id)
                    ->update([
                        'sc_paidoff' => 'Y'
                    ]);

                $salesCompDt = d_salescompdt::whereHas('getSalesComp', function ($q) use ($nota) {
                        $q->where('sc_nota', $nota);
                    })
                    ->with('getSalesComp')
                    ->with('getProdCode')
                    ->get();

                $member = m_company::where('c_id', $cek->sc_member)->first();

                // sell all item to consument if konsinyasi in 'Apotek/Radio'
                if ($member->c_type == 'APOTEK/RADIO') {
                    foreach ($salesCompDt as $key => $value) {
                        $listPC = array();
                        $listQtyPC = array();
                        $listUnitPC = array();

                        foreach ($value->getProdCode as $idx => $val) {
                            array_push($listPC, $val->ssc_code);
                            array_push($listQtyPC, $val->ssc_qty);
                        }
                        // get qty in smallest unit
                        $data_check = DB::table('m_item')
                            ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                                'm_item.i_unit3 as unit3')
                            ->where('i_id', '=', $value->scd_item)
                            ->first();

                        $qty_compare = 0;
                        if ($value->scd_unit == $data_check->unit1) {
                            $qty_compare = $value->scd_qty;
                        } else if ($value->scd_unit == $data_check->unit2) {
                            $qty_compare = $value->scd_qty * $data_check->compare2;
                        } else if ($value->scd_unit == $data_check->unit3) {
                            $qty_compare = $value->scd_qty * $data_check->compare3;
                        }


                        $nota = $value->getSalesComp->sc_nota . '-PAID';
                        // insert stock mutation sales 'out'
                        $mutationOut = Mutasi::salesOut(
                            $value->getSalesComp->sc_member, // from
                            null, // to
                            $value->scd_item, // item-id
                            $qty_compare, // qty of smallest-unit
                            $nota, // nota
                            $listPC, // list of production-code
                            $listQtyPC, // list of production-code-qty
                            $listUnitPC, // list of production-code-unit
                            null, // sellprice
                            14, // mutcat
                            $tanggal
                        );
                        if ($mutationOut->original['status'] !== 'success') {
                            return $mutationOut;
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

// Start Penerimaan Piutang Cabang ==========================================================================
    public function getDataPenerimaanCabang(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $cabang = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $idPusat = m_company::where('c_type', 'PUSAT')->first();
        $idPusat = $idPusat->c_id;

        $infoSalescomp = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->join('m_company as cabang', 'cabang.c_id', '=', 'scc.sc_comp')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name as agen',
                DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as piutang'),
                'sc_nota as nota',
                'cabang.c_name as cabang')
            ->where('sc_paidoff', '=', 'Y')
            ->where('sc_paidoffbranch', '=', 'N')
            ->groupBy('sc_id')
            ->where('sc_comp', '!=', $idPusat)
            ->where('cabang.c_type', '=', 'CABANG');;

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSalescomp = $infoSalescomp->where('sc_comp', $cabang);
        }

        $infoSales = DB::table('d_sales')
            ->join('m_company', 's_comp', '=', 'c_id')
            ->join('m_member', 'm_code', '=', 's_member')
            ->select(DB::raw('"0" AS pembayaran'),
                DB::raw('floor(s_total) as sisa'),
                DB::raw('"-" AS status'),
                'c_name as agen',
                DB::raw('date_format(s_date, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(s_total) as piutang'),
                's_nota as nota',
                'c_name as cabang')
            ->where('s_paidoffbranch', '=', 'N')
            ->groupBy('s_id')
            ->where('s_comp', '!=', $idPusat)
            ->where('c_type', '=', 'CABANG');

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '<=', $end);
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSales = $infoSales->where('s_comp', '=', $cabang);
        }

        $info = $infoSalescomp->union($infoSales);

        return DataTables::of($info)
            ->addColumn('cabang', function ($info) {
                return $info->cabang;
            })
            ->addColumn('agen', function ($info) {
                return $info->agen;
            })
            ->addColumn('date_top', function ($info) {
                return $info->sc_datetop;
            })
            ->addColumn('piutang', function ($info) {
                return "Rp. " . number_format($info->piutang, '0', ',', '.');
            })
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="showPaymentProcessCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-money"></i></button>
                </div></center>';
            })
            ->rawColumns(['cabang', 'agen', 'date_top', 'piutang', 'aksi'])
            ->make(true);
    }
    public function payPiutangCabang(Request $request)
    {
        dd($request->all());
        $nota = $request->nota;
        $bayar = (int)$request->bayar;
        $tanggal = $request->tanggal;
        $paymentmethod = $request->paymentmethod;

        try {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e){
            return response()->json([
                'status' => 'gagal',
                'message' => 'Tanggal tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            $salescomp = d_salescomp::where('sc_nota', $nota)
                ->orderBy('sc_id')
                ->first();

            if (is_null($salescomp)){
                DB::rollback();
                return response()->json([
                    'status' => 'gagal',
                    'message' => 'Nota tidak ditemukan'
                ]);
            }

            $salesComp->sc_paymentmethod = $paymentmethod;
            $salesComp->sc_paidoffbranch = 'Y';
            $salesComp->sc_paiddate = $tanggal;


            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

// Start History Penerimaan Piutang Agen ==========================================================================
    public function getDataHistoryAgen(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $agen = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $info = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name',
                DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as sc_total'),
                'sc_nota as nota',)
            ->where('sc_paidoff', 'Y')
            ->groupBy('sc_id')
            ->where('sc_comp', '=', $user->u_company);

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $info = $info->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $info = $info->where('scc.sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $info = $info->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->agen) && $request->agen != '' && $request->agen !== null){
            $agen = $request->agen;
            $info = $info->where('sc_member', '=', $agen);
        }

        return DataTables::of($info)
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="showPaymentProcessAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                if ($info->pembayaran == null || $info->pembayaran == '') {
                    return "Rp. " . number_format($info->sc_total, '0', ',', '.');
                }
                return "Rp. " . number_format($info->sisa, '0', ',', '.');
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

// Start History Penerimaan Piutang Cabang ==========================================================================
    public function getDataHistoryCabang(Request $request)
    {
        $start = 'all';
        $end = 'all';
        $status = 'all';
        $cabang = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $idPusat = m_company::where('c_type', 'PUSAT')->first();
        $idPusat = $idPusat->c_id;

        $infoSalescomp = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->join('m_company as cabang', 'cabang.c_id', '=', 'scc.sc_comp')
            ->select(DB::raw('floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'),
                DB::raw('case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END AS status'),
                'member.c_name as agen',
                DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as piutang'),
                'sc_nota as nota',
                'cabang.c_name as cabang')
            ->where('sc_paidoff', '=', 'Y')
            ->where('sc_paidoffbranch', '=', 'Y')
            ->groupBy('sc_id')
            ->where('sc_comp', '!=', $idPusat)
            ->where('cabang.c_type', '=', 'CABANG');;

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('sc_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSalescomp = $infoSalescomp->where('sc_date', '<=', $end);
        }
        if (isset($request->status) && $request->status != '' && $request->status !== null){
            $status = $request->status;
            if ($status == 'Melebihi'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Melebihi"');
            } elseif ($status == 'Belum'){
                $infoSalescomp = $infoSalescomp->whereRaw('(SELECT(case when sc_datetop < NOW() then "Melebihi" when sc_datetop >= NOW() then "Belum" END)) = "Belum"');
            }
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSalescomp = $infoSalescomp->where('sc_comp', $cabang);
        }

        $infoSales = DB::table('d_sales')
            ->join('m_company', 's_comp', '=', 'c_id')
            ->join('m_member', 'm_code', '=', 's_member')
            ->select(DB::raw('"0" AS pembayaran'),
                DB::raw('floor(s_total) as sisa'),
                DB::raw('"-" AS status'),
                'c_name as agen',
                DB::raw('date_format(s_date, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(s_total) as piutang'),
                's_nota as nota',
                'c_name as cabang')
            ->where('s_paidoffbranch', '=', 'Y')
            ->groupBy('s_id')
            ->where('s_comp', '!=', $idPusat)
            ->where('c_type', '=', 'CABANG');

        if (isset($request->start) && $request->start != '' && $request->start !== null){
            $start = Carbon::createFromFormat('d-m-Y', $request->start)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '>=', $start);
        }
        if (isset($request->end) && $request->end != '' && $request->end !== null){
            $end = Carbon::createFromFormat('d-m-Y', $request->end)->format('Y-m-d');
            $infoSales = $infoSales->where('s_date', '<=', $end);
        }
        if (isset($request->cabang) && $request->cabang != '' && $request->cabang !== null){
            $cabang = $request->cabang;
            $infoSales = $infoSales->where('s_comp', '=', $cabang);
        }

        $info = $infoSalescomp->union($infoSales);

        return DataTables::of($info)
            ->addColumn('cabang', function ($info) {
                return $info->cabang;
            })
            ->addColumn('agen', function ($info) {
                return $info->agen;
            })
            ->addColumn('date_top', function ($info) {
                return $info->sc_datetop;
            })
            ->addColumn('piutang', function ($info) {
                return "Rp. " . number_format($info->piutang, '0', ',', '.');
            })
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="showPaymentProcessCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-money"></i></button>
                </div></center>';
            })
            ->rawColumns(['cabang', 'agen', 'date_top', 'piutang', 'aksi'])
            ->make(true);
    }
}
