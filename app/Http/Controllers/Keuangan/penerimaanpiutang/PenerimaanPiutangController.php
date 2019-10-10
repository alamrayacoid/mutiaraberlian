<?php

namespace App\Http\Controllers\Keuangan\penerimaanpiutang;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

use Auth;
use App\d_sales;
use App\d_salescomp;
use App\d_salescompdt;
use App\d_salescomppayment;
use App\m_company;
use App\m_paymentmethod;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
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
        $asalPiutang = $request->asalPiutang;

        try {
            $nota = Crypt::decrypt($request->nota);
        }
        catch (\Exception $e){
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

            $data->nota = $data->sc_nota;
            $data->agent = $data->getAgent->c_name;
            $data->total = $data->sc_total;
            $data->paidDate = Carbon::parse($data->sc_paiddate)->format('d M Y');

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
            if ($asalPiutang == 'sales') {
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
                $data->paidDate = Carbon::parse($data->s_paymentdate)->format('d M Y');
            }
            else if ($asalPiutang == 'salescomp') {
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
                $data->paidDate = Carbon::parse($data->sc_paiddate)->format('d M Y');
            }


            // $salesComp = d_salescomp::where('sc_nota', $nota)->first();
            // if (is_null($salesComp)) {
            //     $data = d_sales::where('s_nota', $nota)
            //         ->with('getComp')
            //         ->with(['getSalesDt' => function ($q) {
            //             $q->with('getItem')->with('getUnit');
            //         }])
            //         ->first();
            //     $data->source = 'Sales';
            //     $data->nota = $data->s_nota;
            //     $data->agent = $data->getComp->c_name;
            //     $data->total = $data->s_total;
            //     $data->paidDate = Carbon::parse($data->s_paymentdate)->format('d M Y');
            // }
            // else {
            //     $data = d_salescomp::where('sc_nota', $nota)
            //         ->with('getComp')
            //         ->with(['getSalesCompDt' => function ($q) {
            //             $q->with('getItem')->with('getUnit');
            //         }])
            //         ->first();
            //     $data->source = 'SalesComp';
            //     $data->nota = $data->sc_nota;
            //     $data->agent = $data->getComp->c_name;
            //     $data->total = $data->sc_total;
            //     $data->paidDate = Carbon::parse($data->sc_paiddate)->format('d M Y');
            // }
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
    public function getListAgen(Request $request)
    {
        $user = Auth::user();
        $cari = $request->term;

        $agents = d_salescomp::where('sc_comp', $user->u_company)
            ->where('sc_paidoff', 'N')
            ->whereHas('getAgent', function ($q) use ($cari){
                $q->where('c_name', 'like', '%'. $cari .'%');
                $q->orWhere('c_user', 'like', '%'. $cari .'%');
            })
            ->with('getAgent')
            ->groupBy('sc_member')
            ->get();

        if (count($agents) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($agents as $query) {
                $results[] = ['id' => $query->getAgent->c_id, 'label' => strtoupper($query->getAgent->c_name), 'data' => $query];
            }
        }
        return response()->json($results);
    }
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
                'sc_nota as nota')
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
                return '<center>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-warning hint--top hint--warning" aria-label="Edit" onclick="showDetailEditHistoryAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-pencil"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--danger" aria-label="Bayar" onclick="showPaymentProcessAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-money"></i></button>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--danger" aria-label="Batalkan pembayaran" onclick="declineAllPaymentsAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-ban"></i></button>
                        </div
                        </center>';
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
                        'sc_paidoff' => 'Y',
                        'sc_paiddate' => $tanggal
                    ]);

                $member = m_company::where('c_id', $cek->sc_member)->first();

                // sell all item to consument if konsinyasi in 'Apotek/Radio'
                if ($member->c_type == 'APOTEK/RADIO') {
                    $salesCompDt = d_salescompdt::whereHas('getSalesComp', function ($q) use ($nota) {
                        $q->where('sc_nota', $nota);
                    })
                    ->with('getSalesComp')
                    ->with('getProdCode')
                    ->get();

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
    public function getListCabang(Request $request)
    {
        $user = Auth::user();
        $cari = $request->term;

        $idPusat = m_company::where('c_type', 'PUSAT')->first();
        $idPusat = $idPusat->c_id;

        $branch = d_salescomp::where('sc_comp', '!=', $idPusat)
            ->where('sc_paidoff', 'Y')
            ->where('sc_paidoffbranch', 'N')
            ->whereHas('getComp', function ($query) {
                $query->where('c_type', 'CABANG');
            })
            ->whereHas('getAgent', function ($q) use ($cari){
                $q->where('c_name', 'like', '%'. $cari .'%');
                $q->orWhere('c_user', 'like', '%'. $cari .'%');
            })
            ->with('getAgent')
            ->groupBy('sc_member')
            ->get();

        $anotherBranch = d_sales::where('s_comp', '!=', $idPusat)
            ->where('s_paidoffbranch', 'N')
            ->whereHas('getComp', function ($query) {
                $query->where('c_type', 'CABANG');
            })
            ->whereHas('getComp', function ($q) use ($cari){
                $q
                    ->where('c_name', 'like', '%'. $cari .'%')
                    ->orWhere('c_user', 'like', '%'. $cari .'%');
            })
            ->with('getComp')
            ->groupBy('s_comp')
            ->get();


        if (count($branch) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($branch as $query) {
                $results[] = ['id' => $query->getAgent->c_id, 'label' => strtoupper($query->getAgent->c_name), 'data' => $query];
            }
            foreach ($anotherBranch as $q) {
                $results[] = ['id' => $q->getComp->c_id, 'label' => strtoupper($q->getComp->c_name), 'data' => $query];
            }
        }
        return response()->json($results);
    }
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
                'cabang.c_name as cabang',
                DB::raw('"salescomp" AS asalPiutang'),
                )
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
                'c_name as cabang',
                DB::raw('"sales" AS asalPiutang'),
                )
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

        $info = $infoSales->union($infoSalescomp);
        // $info = $info->get();

        return Datatables::of($info)
            ->addIndexColumn()
            ->addColumn('cabang', function ($info) {
                return $info->cabang;
            })
            ->addColumn('agen', function ($info) {
                return $info->agen;
            })
            ->addColumn('piutang', function ($info) {
                return "Rp. " . number_format($info->piutang, '0', ',', '.');
            })
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailNotaPiutangCabang(\''.Crypt::encrypt($info->nota).'\', \''. $info->asalPiutang .'\')"><i class="fa fa-folder"></i></button>
                <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="showPaymentProcessCabang(\''.Crypt::encrypt($info->nota).'\', \''. $info->asalPiutang .'\', )"><i class="fa fa-money"></i></button>
                </div></center>';
            })
            ->rawColumns(['cabang', 'agen', 'piutang', 'aksi'])
            ->make(true);
    }
    public function payPiutangCabang(Request $request)
    {
        $nota = $request->nota;
        // $bayar = (int)$request->bayar;
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

            if (is_null($salescomp)) {
                $sales = d_sales::where('s_nota', $nota)
                    ->with('getComp')
                    ->with(['getSalesDt' => function ($q) {
                        $q->with('getItem')->with('getUnit');
                    }])
                    ->first();

                if (is_null($sales)) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Nota tidak ditemukan'
                    ]);
                }

                $sales->s_paymentmethod = $paymentmethod;
                $sales->s_paidoffbranch = 'Y';
                $sales->s_paymentdate = $tanggal;
                $sales->save();

            }
            else {
                $salescomp->sc_paymentmethod = $paymentmethod;
                $salescomp->sc_paidoffbranch = 'Y';
                $salescomp->sc_paiddate = $tanggal;
                $salescomp->save();
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
                DB::raw('floor(scc.sc_total) as piutang'),
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
            ->addColumn('date_top', function ($info) {
                return $info->sc_datetop;
            })
            ->addColumn('piutang', function ($info){
                return "Rp. " . number_format($info->piutang, '0', ',', '.');
            })
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="showDetailHistoryAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                <button type="button" class="btn btn-sm btn-warning hint--top hint--warning" aria-label="Edit" onclick="showDetailEditHistoryAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-danger hint--top hint--danger" aria-label="Batalkan pembayaran" onclick="declineAllPaymentsHistoryAgen(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-ban"></i></button>
                </div></center>';
            })
            ->rawColumns(['date_top', 'piutang', 'aksi'])
            ->make(true);
    }
    // get detail payment for edit-payment by detailid
    public function getDetailPayment(Request $request)
    {
        $user = Auth::user()->getCompany;
        $id = $request->id;
        $detailId = $request->detailId;

        $detailPayment = d_salescomppayment::where('scp_salescomp', $id)
            ->where('scp_detailid', $detailId)
            ->with(['getSalesComp' => function ($q) {
                $q
                    ->with('getAgent')
                    ->with(['getSalesCompDt' => function ($query) {
                        $query
                            ->with('getItem')
                            ->with('getUnit');
                    }]);
            }])
            ->first();

        $totalPayment = d_salescomppayment::where('scp_salescomp', $id)
            ->sum('scp_pay');

        $detailPayment->totalPayment = $totalPayment;
        $detailPayment->remainingPayment = $detailPayment->getSalesComp->sc_total - $totalPayment;

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

        $detailPayment->method = $jenis;
        return response()->json($detailPayment);
    }
    // update detail history-payment
    public function updateHistoryPayment(Request $request)
    {
        $salesCompId = $request->salesCompId;
        $paymentDetailId = $request->paymentDetailId;
        // $nota = $request->nota;
        $bayar = (int)$request->bayar;
        $tanggal = $request->tanggal;
        $paymentmethod = $request->paymentmethod;

        DB::beginTransaction();
        try {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');

            $salesCompPayment = d_salescomppayment::where('scp_salescomp', $salesCompId)
                ->where('scp_detailid', $paymentDetailId)
                ->with('getSalesComp')
                ->first();
            $nota = $salesCompPayment->getSalesComp->sc_nota;

            // get total-payment
            $totalPayment = d_salescomppayment::where('scp_salescomp', $salesCompId)
                ->sum('scp_pay');
            // get new taotal-payment after minus by selected payment
            $newTotalPayment = $totalPayment - (int)$salesCompPayment->scp_pay;
            // get remainingPayment
            $remainingPayment = (int)$salesCompPayment->getSalesComp->sc_total - $newTotalPayment;
            // validate payment with total
            if ($bayar > $remainingPayment) {
                throw new \Exception("Pembayaran melebihi jumlah piutang !", 1);
            }
            // update payment
            $salesCompPayment->scp_date = $tanggal;
            $salesCompPayment->scp_pay = $bayar;
            $salesCompPayment->scp_payment = $paymentmethod;
            $salesCompPayment->save();

            // validate 'lunas' atau 'belum'
            $totalPayment = d_salescomppayment::where('scp_salescomp', $salesCompId)
                ->sum('scp_pay');

            $salesCompDt = d_salescompdt::whereHas('getSalesComp', function ($q) use ($nota) {
                    $q->where('sc_nota', $nota);
                })
                ->with('getSalesComp.getAgent')
                ->with('getProdCode')
                ->get();
            $member = $salesCompDt[0]->getSalesComp->getAgent;
            // mutcat 'penjualan langsung ke konsumen'
            $mutcatOut = 14;
            $notaSales = $salesCompDt[0]->getSalesComp->sc_nota . '-PAID';

            // if already 'paid-off'
            if ($salesCompPayment->getSalesComp->sc_paidoff == 'Y') {
                if ($totalPayment != $salesCompPayment->getSalesComp->sc_total) {
                    // update salescomp
                    $salesCompPayment->getSalesComp->sc_paidoff = 'N';
                    $salesCompPayment->getSalesComp->save();

                    // rollback if 'APOTEK/RADIO'
                    if ($member->c_type == 'APOTEK/RADIO') {
                        foreach ($salesCompDt as $key => $value) {
                            // rollback mutation 'salesout'
                            $mutRollbackOut = Mutasi::rollbackSalesOut(
                                $notaSales,
                                $value->scd_item,
                                $mutcatOut
                            );
                            if ($mutRollbackOut->original['status'] !== 'success') {
                                return $mutRollbackOut;
                            }
                        }
                    }
                }
            }
            // if currently 'not paid-off'
            else {
                if ($totalPayment == $salesCompPayment->getSalesComp->sc_total) {
                    // update salescomp
                    $salesCompPayment->getSalesComp->sc_paidoff = 'Y';
                    $salesCompPayment->getSalesComp->save();

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

                            // insert stock mutation sales 'out'
                            $mutationOut = Mutasi::salesOut(
                                $value->getSalesComp->sc_member, // from
                                null, // to
                                $value->scd_item, // item-id
                                $qty_compare, // qty of smallest-unit
                                $notaSales, // nota
                                $listPC, // list of production-code
                                $listQtyPC, // list of production-code-qty
                                $listUnitPC, // list of production-code-unit
                                null, // sellprice
                                $mutcatOut, // mutcat
                                $tanggal
                            );
                            if ($mutationOut->original['status'] !== 'success') {
                                return $mutationOut;
                            }
                        }
                    }
                }
            }

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    // delete selected payment
    public function declineSelectedPaymentAgen(Request $request)
    {
        DB::beginTransaction();
        try {
            $salesCompId = $request->salesCompId;
            $paymentDetailId = $request->paymentDetailId;

            $salesCompPayment = d_salescomppayment::where('scp_salescomp', $salesCompId)
                ->where('scp_detailid', $paymentDetailId)
                ->with('getSalesComp.getAgent')
                ->first();
            // get nota salesComp
            $nota = $salesCompPayment->getSalesComp->sc_nota;
            // get total-payment
            $totalPayment = d_salescomppayment::where('scp_salescomp', $salesCompId)
                ->sum('scp_pay');
            // get new taotal-payment after minus by selected payment
            $newTotalPayment = $totalPayment - (int)$salesCompPayment->scp_pay;

            $salesCompDt = d_salescompdt::whereHas('getSalesComp', function ($q) use ($nota) {
                    $q->where('sc_nota', $nota);
                })
                ->get();
            $member = $salesCompPayment->getSalesComp->getAgent;
            // mutcat 'penjualan langsung ke konsumen'
            $mutcatOut = 14;
            $notaSales = $nota . '-PAID';

            // if already 'paid-off'
            if ($salesCompPayment->getSalesComp->sc_paidoff == 'Y') {
                // update salescomp
                $salesCompPayment->getSalesComp->sc_paidoff = 'N';
                $salesCompPayment->getSalesComp->save();

                // rollback if 'APOTEK/RADIO'
                if ($member->c_type == 'APOTEK/RADIO') {
                    foreach ($salesCompDt as $key => $value) {
                        // rollback mutation 'salesout'
                        $mutRollbackOut = Mutasi::rollbackSalesOut(
                            $notaSales,
                            $value->scd_item,
                            $mutcatOut
                        );
                        if ($mutRollbackOut->original['status'] !== 'success') {
                            return $mutRollbackOut;
                        }
                    }
                }
            }

            // delete payment
            $salesCompPayment->delete();

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
    // delete all payment
    public function declineAllPaymentsAgen(Request $request)
    {
        DB::beginTransaction();
        try {
            $nota = Crypt::decrypt($request->nota);
            // get all salescomp-payment based on 'nota'
            $salesCompPayment = d_salescomppayment::whereHas('getSalesComp', function ($q) use ($nota) {
                    $q->where('sc_nota', $nota);
                })
                ->with('getSalesComp.getAgent')
                ->get();

            // get all salescomp-detail based on nota
            $salesCompDt = d_salescompdt::whereHas('getSalesComp', function ($q) use ($nota) {
                    $q->where('sc_nota', $nota);
                })
                ->with('getProdCode')
                ->get();

            $member = $salesCompPayment[0]->getSalesComp->getAgent;

            // mutcat 'penjualan langsung ke konsumen'
            $mutcatOut = 14;
            $notaSales = $nota . '-PAID';

            if ($salesCompPayment[0]->getSalesComp->sc_paidoff == 'Y') {
                // rollback if 'APOTEK/RADIO'
                if ($member->c_type == 'APOTEK/RADIO') {
                    foreach ($salesCompDt as $key => $value) {
                        // rollback mutation 'salesout'
                        $mutRollbackOut = Mutasi::rollbackSalesOut(
                            $notaSales,
                            $value->scd_item,
                            $mutcatOut
                        );
                        if ($mutRollbackOut->original['status'] !== 'success') {
                            return $mutRollbackOut;
                        }
                    }
                }
            }

            // update salescomp
            $salesCompPayment[0]->getSalesComp->sc_paidoff = 'N';
            $salesCompPayment[0]->getSalesComp->save();
            // delete all salescomp-payment
            foreach ($salesCompPayment as $key => $value) {
                $value->delete();
            }

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

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
                <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="showDetailHistoryCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-folder"></i></button>
                <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Batalkan Pembayaran" onclick="declineCabang(\''.Crypt::encrypt($info->nota).'\')"><i class="fa fa-ban"></i></button>
                </div></center>';
            })
            ->rawColumns(['cabang', 'agen', 'date_top', 'piutang', 'aksi'])
            ->make(true);
    }
    public function declinePaymentCabang(Request $request)
    {
        DB::beginTransaction();
        try {
            $nota = Crypt::decrypt($request->nota);

            $salescomp = d_salescomp::where('sc_nota', $nota)
                ->orderBy('sc_id')
                ->first();

            if (is_null($salescomp)) {
                $sales = d_sales::where('s_nota', $nota)
                    ->with('getComp')
                    ->with(['getSalesDt' => function ($q) {
                        $q->with('getItem')->with('getUnit');
                    }])
                    ->first();

                if (is_null($sales)) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'gagal',
                        'message' => 'Nota tidak ditemukan'
                    ]);
                }

                $sales->s_paymentmethod = null;
                $sales->s_paidoffbranch = 'N';
                $sales->s_paymentdate = null;
                $sales->save();
            }
            else {
                $salescomp->sc_paymentmethod = null;
                $salescomp->sc_paidoffbranch = 'N';
                $salescomp->sc_paiddate = null;
                $salescomp->save();
            }


            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

    }
}
