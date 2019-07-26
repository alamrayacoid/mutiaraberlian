<?php

namespace App\Http\Controllers\Aktivitasmarketing\Marketingarea;

use App\d_salescomp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use DataTables;
use Response;

class MMAPenerimaanPiutangController extends Controller
{
    public function getData(Request $request)
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
                'member.c_name', DB::raw('date_format(scc.sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(scc.sc_total) as sc_total'), 'sc_id')
            ->where('sc_paidoffbranch', '=', 'N')
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
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="bayarnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                return "Rp. " . number_format($info->sisa, '0', ',', '.');
            })
            ->rawColumns(['aksi'])
            ->make();
    }

    public function getDataAgen(Request $request)
    {
        $user = Auth::user();
        $cari = $request->term;
        $nama = DB::table('d_salescomp')
            ->join('m_company as comp', 'comp.c_id', '=', 'sc_comp')
            ->join('m_company as member', 'member.c_id', '=', 'sc_member')
            ->where(function ($q) use ($cari){
                $q->orWhere('member.c_name', 'like', '%' . $cari . '%');
                $q->orWhere('member.c_user', 'like', '%' . $cari . '%');
            })
            ->select('member.c_name', 'member.c_user', 'member.c_tlp', 'member.c_id')
            ->where('sc_comp', '=', $user->u_company)
            ->where('sc_paidoffbranch', '=', 'N')
            ->groupBy('member.c_id')
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->c_id, 'label' => strtoupper($query->c_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getDetailTransaksi(Request $request)
    {
        $id = 0;
        $user = Auth::user();
        try {
            $id = Crypt::decrypt($request->id);
        } catch (\Exception $e){
            return Response::json([
                'status' => 'gagal',
                'message' => 'tidak diketahui'
            ]);
        }

        $data = DB::table('d_salescomp')
            ->join('m_company', 'c_id', '=', 'sc_member')
            ->join('d_salescompdt', 'scd_sales', '=', 'sc_id')
            ->join('m_item', 'i_id', '=', 'scd_item')
            ->join('m_unit', 'u_id', '=', 'scd_unit')
            ->where('sc_id', '=', $id)
            ->select('sc_nota', 'c_name', DB::raw('date_format(sc_datetop, "%d-%m-%Y") as sc_datetop'),
                DB::raw('floor(sc_total) as sc_total'), DB::raw('floor(scd_value) as scd_value'),
                DB::raw('floor(scd_discvalue) as scd_discvalue'), DB::raw('floor(scd_totalnet) as scd_totalnet'),
                'scd_qty', 'i_name', 'u_name')
            ->get();

        $pembayaran = DB::table('d_salescomppayment')
            ->where('scp_salescomp', '=', $id)
            ->select(DB::raw('date_format(scp_date, "%d-%m-%Y") as scp_date'), DB::raw('floor(scp_pay) as scp_pay'))
            ->orderBy('scp_date')
            ->get();

        $jenis = DB::table('dk_akun')
            ->where('ak_comp', '=', $user->u_company)
            ->select('ak_id', 'ak_nama')
            ->get();

        return Response::json([
            'data' => $data,
            'pay' => $pembayaran,
            'jenis' => $jenis
        ]);
    }

    public function bayarPiutang(Request $request)
    {
        $nota = $request->nota;
        $bayar = $request->bayar;
        $tanggal = $request->tanggal;
        $paymentmethod = $request->paymentmethod;

        try {
            $tanggal = Carbon::createFromFormat('d-m-Y', $tanggal)->format('Y-m-d');
        } catch (\Exception $e){
            return Response()->json([
                'status' => 'gagal',
                'message' => 'Tanggal tidak valid'
            ]);
        }

        DB::beginTransaction();
        try {
            $salescomp = DB::table('d_salescomp')
                ->join('d_salescomppayment', 'scp_salescomp', '=', 'sc_id')
                ->select('d_salescomp.*', DB::raw('sum(scp_pay) as jumlah'))
                ->where('sc_nota', '=', $nota)
                ->orderBy('sc_id')
                ->get();

            if (count($salescomp) < 1){
                DB::rollback();
                return Response()->json([
                    'status' => 'gagal',
                    'message' => 'Nota tidak ditemukan'
                ]);
            }

            $sisa = (int)$salescomp[0]->sc_total - (int)$salescomp[0]->jumlah;

            if ($bayar > $sisa){
                DB::rollback();
                return Response()->json([
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

            DB::commit();
            return Response()->json([
                'status' => 'sukses'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return Response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
}