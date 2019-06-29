<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use CodeGenerator;
use Currency;
use Carbon\Carbon;
use Mockery\Exception;
use Response;
use App\m_agen;
use App\m_company;
use App\m_wil_provinsi;
use App\d_salescomp;
use App\d_salescomppayment;
use Validator;
use Mutasi;

class MarketingController extends Controller
{
    public function marketing()
    {
        if (!AksesUser::checkAkses(19, 'read')){
            abort(401);
        }
    	return view('marketing/manajemenmarketing/index');
    }

    public function tanggal($tanggal, $format){
        //format dd-mm-yyyy; mm-yyyy;
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
        if ($format == 'dd-mm-yyyy'){
            return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
        } elseif ($format == 'mm-yyyy'){
            return $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
        }
    }

    public function getPromosiTahunan()
    {
        $data = DB::table('d_promotion')
            ->where('p_type', '=', 'T')
            ->where(function ($q){
                $q->orWhere('p_isapproved', '=', 'P');
                $q->orWhere('p_isapproved', '=', 'Y');
            })
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('p_budget', function ($data){
                if ($data->p_budget == null){
                    return 'Biaya belum diajukan';
                } else {
                    return "Rp. " . number_format($data->p_budget, "0", ",", ".");
                }
            })
            ->editColumn('p_isapproved', function ($data){
                if ($data->p_isapproved == 'P'){
                    return '<div class="status-pending"><p>Pending</p></div>';
                } elseif ($data->p_isapproved == 'Y'){
                    return '<div class="status-approve"><p>Approved</p></div>';
                }
            })
            ->addColumn('action', function ($data){
                if ($data->p_isapproved == 'P'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-warning hint--top hint--warning" onclick="EditPromosiTahunan(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="HapusPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Hapus"><i class="fa fa-close"></i></button>
                            </div></center>';
                } elseif ($data->p_isapproved == 'Y'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-success btn-xs done hint--top hint--info" onclick="DonePromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Selesai"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="HapusPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Hapus"><i class="fa fa-close"></i></button>
                            </div></center>';
                }
            })
            ->rawColumns(['p_isapproved', 'action', 'p_budget'])
            ->make(true);
    }

    public function year_promotion_create()
    {
        if (!AksesUser::checkAkses(19, 'create')){
            abort(401);
        }
        return view('marketing/manajemenmarketing/tahunan/create');
    }

    public function year_promotion_edit(Request $request)
    {
        if (!AksesUser::checkAkses(19, 'update')){
            abort(401);
        }

        $p_id = Crypt::decrypt($request->id);

        $data = DB::table('d_promotion')
            ->where('p_id', '=', $p_id)
            ->first();

        return view('marketing/manajemenmarketing/tahunan/edit', compact('data'));
    }

    public function year_promotion_save(Request $request)
    {
        if (!AksesUser::checkAkses(19, 'create')){
            return Response::json([
                "status" => "unauth"
            ]);
        }

        $judul = $request->judul;
        $tahun = $request->tahun;
        $output = $request->output;
        $outcome = $request->outcome;
        $impact = $request->impact;
        $note = $request->note;
        $budget = $request->budget;

        DB::beginTransaction();
        try {
            $code = CodeGenerator::codeWithSeparator('d_promotion', 'p_reff', 8, 10, 3, 'PR', '-');
            $id = DB::table('d_promotion')
                ->max('p_id');
            ++$id;
            DB::table('d_promotion')
                ->insert([
                    'p_id' => $id,
                    'p_name' => $judul,
                    'p_reff' => $code,
                    'p_type' => 'T',
                    'p_budget' => $budget,
                    'p_additionalinput' => $tahun,
                    'p_outputplan' => $output,
                    'p_outcomeplan' => $outcome,
                    'p_impactplan' => $impact,
                    'p_note' => $note
                ]);
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function month_promotion_create()
    {
        if (!AksesUser::checkAkses(19, 'create')){
            abort(401);
        }
        return view('marketing/manajemenmarketing/bulanan/create');
    }

    public function month_promotion_edit(Request $request)
    {
        if (!AksesUser::checkAkses(19, 'update')){
            abort(401);
        }
        $p_id = Crypt::decrypt($request->id);
        $data = DB::table('d_promotion')
            ->where('p_id', '=', $p_id)
            ->first();
        return view('marketing/manajemenmarketing/bulanan/edit', compact('data'));
    }

    public function year_promotion_update(Request $request)
    {
        $judul = $request->judul;
        $bulan = $request->bulan;
        $output = $request->output;
        $outcome = $request->outcome;
        $impact = $request->impact;
        $note = $request->note;
        $budget = $request->budget;
        $reff = $request->reff;

        DB::beginTransaction();
        try {
            DB::table('d_promotion')
                ->where('p_reff', '=', $reff)
                ->update([
                    'p_name' => $judul,
                    'p_type' => 'T',
                    'p_budget' => $budget,
                    'p_additionalinput' => $bulan,
                    'p_outputplan' => $output,
                    'p_outcomeplan' => $outcome,
                    'p_impactplan' => $impact,
                    'p_note' => $note
                ]);
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function month_promotion_delete(Request $request)
    {
        if (!AksesUser::checkAkses(19, 'delete')){
            return Response::json([
                "status" => "unauth"
            ]);
        }
        $p_id = Crypt::decrypt($request->id);
        DB::beginTransaction();
        try {
            DB::table('d_promotion')
                ->where('p_id', '=', $p_id)
                ->delete();
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function status_target()
    {
        return view('marketing/targetrealisasipenjualan/targetrealisasi/status');
    }

    public function penjualan()
    {
    	return view('marketing/penjualanpusat/index');
    }

    public function returnpenjualanagen_create()
    {
        return view('marketing/penjualanpusat/returnpenjualan/create');
    }

    public function hapus(Request $request){
        $data = DB::table('d_return')
                    ->where('r_id', $request->id)
                    ->first();

                    DB::table('d_return')
                    ->where('r_id', $request->id)
                    ->delete();

        mutasi::rollbackStockMutDist($data->r_nota, $data->r_item, 3);

        return response()->json(['status' => 'berhasil']);
    }

    public function returnpenjualanagen(){
        

        $data = DB::table("d_return")
                    ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($data) {
                return '<td>' . Carbon::parse($data->r_date)->format('d-m-Y') . '</td>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detail" type="button" onclick="detail(' . $data->r_id . ')" title="Detail"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning btn-process" type="button" onclick="edit(' . $data->r_id . ')" title="Edit"><i class="fa fa-pencil"></i></button>
                <button class="btn btn-danger btn-process" type="button" onclick="hapus(' . $data->r_id . ')" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>';
                // <button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href=\''. route('orderpenjualan.proses') .'?id='.encrypt($data->po_id).'\'"><i class="fa fa-arrow-right"></i></button>
            })
            ->addColumn('type', function($data){
                if ($data->r_type == 'GB') {
                    return '<span class="badge badge-primary">Ganti Barang</span>';
                } elseif ($data->r_type == 'GU') {
                    return '<span class="badge badge-success">Ganti Uang</span>';
                } else {
                    return '<span class="badge badge-info">Potong Nota</span>';
                }
            })
            ->addColumn('agen', function($data){
                $member = DB::table('m_company')
                            ->where('c_id', $data->r_member)
                            ->first();

                return $member->c_name;
            })
            ->rawColumns(['tanggal', 'action', 'type'])
            ->make(true);

        return response()->json($data);
    }
    
    public function returnpenjualanagen_getnota(Request $request){
        $nota = DB::table('d_salescompcode')
                    ->join('d_salescomp', 'sc_id', '=', 'ssc_salescomp')
                    ->where('ssc_code', $request->kodeproduksi)
                    ->get();

        return response()->json($nota);
    }

    public function returnpenjualanagen_getdata(Request $request){
        $data = DB::table('d_salescomp')                
                    ->where('sc_nota', $request->notapenjualan)
                    ->first();

        $comp = DB::table('m_company')
                    ->where('c_id', $data->sc_comp)
                    ->first();

        $agen = DB::table('m_company')
                    ->where('c_id', $data->sc_member)
                    ->first();                

        $item = DB::table('d_salescompdt')
                    ->join('m_item', 'i_id', '=', 'scd_item')
                    ->where('scd_sales', $data->sc_id)
                    ->where('scd_item', $request->itemid)
                    ->first();
        
        $data->sc_date = Carbon::parse($data->sc_date)->format('d-m-Y');
        
        $data->sc_total = number_format($data->sc_total,2,",",".");

        return response()->json([
            'data' => $data,
            'comp' => $comp,
            'agen' => $agen,
            'item' => $item
        ]);
    }

    public function returnpenjualanagen_simpan(Request $request){
        DB::beginTransaction();
        try {
            
            $nota = CodeGenerator::codeWithSeparator('d_return', 'r_nota', 8, 10, 3, 'RT', '-');
            $id = DB::table('d_return')
                    ->max('r_id')+1;

            DB::table('d_return')
                ->insert([
                    'r_id' => $id,
                    'r_nota' => $nota,
                    'r_reff' => $request->notapenjualan,
                    'r_date' => Carbon::now('Asia/Jakarta'),
                    'r_member' => $request->member,
                    'r_item' => $request->itemid,
                    'r_qty' => str_replace('.','', $request->qty),
                    'r_code' => $request->kodeproduksi,
                    'r_type' => $request->type
                ]);
            
            if ($request->type == 'GB') {
                $mutcat = 16;
            } elseif ($request->type == 'GU') {
                $mutcat = 15;
            } else {
                $mutcat = 17;
            }
            
            mutasi::mutasimasukreturn(3, 'MB0000001', 'MB0000001', $request->itemid, str_replace('.','', $request->qty), 'BROKEN', 'ON DESTINATION', $nota, $request->notapenjualan);
            mutasi::mutasikeluarreturn($mutcat, $request->member, $request->itemid, str_replace('.','', $request->qty), $nota);
                    
            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'error' => $th
            ]);
        }
    }

    public function getPromosiBulanan()
    {
        $data = DB::table('d_promotion')
            ->where('p_type', '=', 'B')
            ->where(function ($q){
                $q->orWhere('p_isapproved', '=', 'P');
                $q->orWhere('p_isapproved', '=', 'Y');
            })
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('p_budget', function ($data){
                if ($data->p_budget == null){
                    return 'Biaya belum diajukan';
                } else {
                    return "Rp. " . number_format($data->p_budget, "0", ",", ".");
                }
            })
            ->editColumn('p_additionalinput', function ($data){
                $bulan = $this->tanggal("01-" . $data->p_additionalinput, "mm-yyyy");
                return $bulan;
            })
            ->editColumn('p_isapproved', function ($data){
                if ($data->p_isapproved == 'P'){
                    return '<div class="status-pending"><p>Pending</p></div>';
                } elseif ($data->p_isapproved == 'Y'){
                    return '<div class="status-approve"><p>Approved</p></div>';
                }
            })
            ->addColumn('action', function ($data){
                if ($data->p_isapproved == 'P'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-warning hint--top hint--warning" onclick="EditPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="HapusPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Hapus"><i class="fa fa-close"></i></button>
                            </div></center>';
                } elseif ($data->p_isapproved == 'Y'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-success btn-xs done hint--top hint--info" onclick="DonePromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Selesai"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="HapusPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Hapus"><i class="fa fa-close"></i></button>
                            </div></center>';
                }
            })
            ->rawColumns(['p_isapproved', 'action', 'p_budget'])
            ->make(true);
    }

    public function month_promotion_save(Request $request)
    {
        if (!AksesUser::checkAkses(19, 'create')){
            return Response::json([
                "status" => "unauth"
            ]);
        }
        $judul = $request->judul;
        $bulan = $request->bulan;
        $output = $request->output;
        $outcome = $request->outcome;
        $impact = $request->impact;
        $note = $request->note;
        $budget = $request->budget;

        DB::beginTransaction();
        try {
            $code = CodeGenerator::codeWithSeparator('d_promotion', 'p_reff', 8, 10, 3, 'PR', '-');
            $id = DB::table('d_promotion')
                ->max('p_id');
            ++$id;
            DB::table('d_promotion')
                ->insert([
                    'p_id' => $id,
                    'p_name' => $judul,
                    'p_reff' => $code,
                    'p_type' => 'B',
                    'p_budget' => $budget,
                    'p_additionalinput' => $bulan,
                    'p_outputplan' => $output,
                    'p_outcomeplan' => $outcome,
                    'p_impactplan' => $impact,
                    'p_note' => $note
                ]);
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function month_promotion_update(Request $request)
    {
        $judul = $request->judul;
        $bulan = $request->bulan;
        $output = $request->output;
        $outcome = $request->outcome;
        $impact = $request->impact;
        $note = $request->note;
        $budget = $request->budget;
        $reff = $request->reff;

        DB::beginTransaction();
        try {
            DB::table('d_promotion')
                ->where('p_reff', '=', $reff)
                ->update([
                    'p_name' => $judul,
                    'p_type' => 'B',
                    'p_budget' => $budget,
                    'p_additionalinput' => $bulan,
                    'p_outputplan' => $output,
                    'p_outcomeplan' => $outcome,
                    'p_impactplan' => $impact,
                    'p_note' => $note
                ]);
            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function detailPromotion(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        $data = DB::table('d_promotion')
            ->where('p_id', '=', $id)
            ->first();

        return Response::json([
            'data' => $data
        ]);
    }

    public function donePromotion(Request $request)
    {
        DB::beginTransaction();
        try {
            $tanggal = Carbon::createFromFormat("d-m-Y", $request->tanggal)->format('Y-m-d');
            $outputreal = $request->outputreal;
            $outputpersen = $request->outputpersen;
            $outcomereal = $request->outcomereal;
            $outcomepersen = $request->outcomepersen;
            $impactreal = $request->impactreal;
            $impactpersen = $request->impactpersen;
            $catatan = $request->catatan;
            $kode = $request->kode;

            DB::table('d_promotion')
                ->where('p_reff', '=', $kode)
                ->update([
                    'p_date' => $tanggal,
                    'p_outputachieved' => $outputreal,
                    'p_outputpersentation' => $outputpersen,
                    'p_outcomeachieved' => $outcomereal,
                    'p_outcomepersentation' => $outcomepersen,
                    'p_impactachieved' => $impactreal,
                    'p_impactpersentation' => $impactpersen,
                    'p_note' => $catatan,
                    'p_isapproved' => 'D'
                ]);

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'gagal'
            ]);
        }
    }

    public function getHistoryPromotion()
    {
        $data = DB::table('d_promotion')
            ->where('p_isapproved', '=', 'D')
            ->get();

        return DataTables::of($data)
            ->editColumn('p_budgetrealization', function ($data){
                if ($data->p_budget == null){
                    return 'Biaya belum diajukan';
                } else {
                    return "Rp. " . number_format($data->p_budgetrealization, "0", ",", ".");
                }
            })
            ->editColumn('p_type', function ($data){
                if ($data->p_type == 'B'){
                    return 'Bulanan';
                } elseif ($data->p_type == 'T'){
                    return 'Tahunan';
                }
            })
            ->editColumn('p_isapproved', function ($data){
                if ($data->p_isapproved == 'P'){
                    return '<div class="status-pending"><p>Pending</p></div>';
                } elseif ($data->p_isapproved == 'Y'){
                    return '<div class="status-approve"><p>Approved</p></div>';
                }
            })
            ->addColumn('action', function ($data){
                return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            </div></center>';
            })
            ->rawColumns(['p_isapproved', 'action', 'p_budget'])
            ->make(true);
    }
}
