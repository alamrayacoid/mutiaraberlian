<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function foo\func;
use App\Helper\keuangan\jurnal\jurnal;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;

use App\d_stockdt;
use App\m_item;
use App\m_item_auth;
use App\m_paymentmethod;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use DB;
use DataTables;

use Auth;

class OtorisasiController extends Controller
{
    public function otorisasi()
    {
        return view('notifikasiotorisasi.otorisasi.index');
    }
    public function perubahanhargajual()
    {
        return view('notifikasiotorisasi.otorisasi.perubahanhargajual.index');
    }
    public function pengeluaranlebih()
    {
        return view('notifikasiotorisasi.otorisasi.pengeluaranlebih.index');
    }
    public function opname_otorisasi()
    {
        return view('notifikasiotorisasi.otorisasi.opname.index');
    }
    public function sdm()
    {
        return view('notifikasiotorisasi.otorisasi.sdm.index');
    }


// ================== Opname =================
    public function getopname()
    {
        $data = DB::table('d_opnameauth')
            ->join('m_item', 'i_id', '=', 'oa_item')
            ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('item', function($data){
            $tmp = $data->i_code . ' ' . $data->i_name;
            return $tmp;
        })
        ->addColumn('nota', function($data){
            return $data->oa_nota;
        })
        ->addColumn('selisih', function($data){
            $tmp = $data->oa_qtysystem - $data->oa_qtyreal;
            return $tmp;
        })
        ->addColumn('aksi', function($data){
            // $setujui = '<button class="btn btn-warning btn-primary" type="button" title="Setujui" onclick="approve(\''. Crypt::encrypt($data->oa_id) .'\')"><i class="fa fa-check"></i></button>';
            $setujui = '<button class="btn btn-warning btn-primary" type="button" title="Setujui" onclick="showDetailApp(\''. Crypt::encrypt($data->oa_id) .'\')"><i class="fa fa-check"></i></button>';
            $tolak = '<button class="btn btn-danger btn-disable" type="button" title="Tolak" onclick="rejected(\''. Crypt::encrypt($data->oa_id) .'\')"><i class="fa fa-remove"></i></button>';
            return '<center><div class="btn-group btn-group-sm">' . $setujui . $tolak . '</div></center>';
        })
        ->rawColumns(['nota','aksi'])
        ->make(true);
    }
    public function detailApproveOpname($id)
    {
        $temp = DB::table('d_opnameauth')
            ->join('m_item', 'i_id', 'oa_item')
            ->where('oa_id', '=', Crypt::decrypt($id))->first();
        $datas = DB::table('d_opnameauthdt')->where('oad_opname', '=', $temp->oa_id)->get();

        return response()->json([
            "auth"    => $temp,
            "id_auth" => Crypt::encrypt($temp->oa_id),
            "code"    => $datas
        ]);

    }
    public function approveopname($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try
        {
            // return json_encode($id);
            $auth = DB::table('d_opnameauth')->where('oa_id', '=', $id)->first();

            $authdt = DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->get();

            // dd($auth, $authdt);
            $o_id = DB::table('d_opname')->max('o_id')+1;
            DB::table('d_opname')->insert([
                'o_id'         => $o_id,
                'o_comp'       => $auth->oa_comp,
                'o_position'   => $auth->oa_position,
                'o_date'       => $auth->oa_date,
                'o_nota'       => $auth->oa_nota,
                'o_item'       => $auth->oa_item,
                'o_qtyreal'    => $auth->oa_qtyreal,
                'o_qtysystem'  => $auth->oa_qtysystem,
                'o_unitreal'   => $auth->oa_unitreal,
                'o_unitsystem' => $auth->oa_unitsystem,
                'o_insert'     => Carbon::now('Asia/Jakarta')
            ]);

            for ($i=0; $i < count($authdt); $i++) {
                DB::table('d_opnamedt')->insert([
                    'od_opname'   => $o_id,
                    'od_code'     => $authdt[$i]->oad_code,
                    'od_detailid' => $authdt[$i]->oad_detailid,
                    'od_qty'      => $authdt[$i]->oad_qty
                ]);
            }

            DB::table('d_opnameauth')->where('oa_id', '=', $id)->delete();

            DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Opname');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function rejectedopname($id)
    {
        DB::beginTransaction();
        try
        {
            DB::table('d_opnameauth')->where('oa_id', Crypt::decrypt($id))->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Opname');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }


// ================== Adjustment =================
    public function adjustment()
    {
        return view('notifikasiotorisasi.otorisasi.adjustment.index');
    }
    public function getadjusment()
    {
        $data = DB::table('d_adjusmentauth')
            ->join('m_item', 'i_id', '=', 'aa_item')
            ->join('m_unit', 'm_item.i_unit1', '=', 'u_id')
            ->select(
                'd_adjusmentauth.*',
                'm_item.i_code',
                'm_item.i_name',
                'm_unit.u_name AS unit_base',
                \DB::RAW('(CASE
                    WHEN aa_unitsystem = m_item.i_unit1 THEN aa_qtysystem
                    WHEN aa_unitsystem = m_item.i_unit2 THEN (aa_qtysystem * m_item.i_unitcompare2)
                    WHEN aa_unitsystem = m_item.i_unit3 THEN (aa_qtysystem * m_item.i_unitcompare3)
                    END) AS aa_qtysystem_base'),
                \DB::RAW('(CASE
                    WHEN aa_unitreal = m_item.i_unit1 THEN aa_qtyreal
                    WHEN aa_unitreal = m_item.i_unit2 THEN (aa_qtyreal * m_item.i_unitcompare2)
                    WHEN aa_unitreal = m_item.i_unit3 THEN (aa_qtyreal * m_item.i_unitcompare3)
                    END) AS aa_qtyreal_base')
            )
            ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('item', function($data){
            $tmp = $data->i_code . ' ' . $data->i_name;
            return $tmp;
        })
        ->addColumn('nota', function($data){
            return $data->aa_nota;
        })
        ->addColumn('selisih', function($data){
            $tmp = $data->aa_qtysystem_base - $data->aa_qtyreal_base;
            $tmp = number_format($tmp, 0, ',', '.');
            return $tmp . ' (' . $data->unit_base . ')';
        })
        ->addColumn('unitsystem', function($data){
            $tmp = DB::table('m_unit')->where('u_id', '=', $data->aa_unitsystem)->first();
            return $tmp->u_name;
        })
        ->addColumn('unitreal', function($data){
            $tmp = DB::table('m_unit')->where('u_id', '=', $data->aa_unitreal)->first();
            return $tmp->u_name;
        })
        ->addColumn('aksi', function($data){
            // $setujui = '<button class="btn btn-warning btn-primary" type="button" title="Setujui" onclick="approve(\''. Crypt::encrypt($data->aa_id) .'\')"><i class="fa fa-check"></i></button>';
            $setujui = '<button class="btn btn-warning btn-primary" type="button" title="Setujui" onclick="showDetailApp(\''. Crypt::encrypt($data->aa_id) .'\')"><i class="fa fa-check"></i></button>';
            $tolak = '<button class="btn btn-danger btn-disable" type="button" title="Tolak" onclick="rejected(\''. Crypt::encrypt($data->aa_id) .'\')"><i class="fa fa-remove"></i></button>';
            return '<center><div class="btn-group btn-group-sm">' . $setujui . $tolak . '</div></center>';
        })
        ->rawColumns(['nota','aksi'])
        ->make(true);
    }
    public function detailApprove($id)
    {
        $temp = DB::table('d_adjusmentauth')
            ->join('m_item', 'i_id', 'aa_item')
            ->where('aa_id', '=', Crypt::decrypt($id))->first();
        $datas = DB::table('d_adjustmentcodeauth')->where('aca_adjustment', '=', $temp->aa_id)->get();

        return response()->json([
            "auth"    => $temp,
            "id_auth" => Crypt::encrypt($temp->aa_id),
            "code"    => $datas
        ]);

    }
    public function agreeadjusment($id)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($id);
            // get adjustment-auth
            $data = DB::table('d_adjusmentauth')->where('aa_id', $id)->first();

            $date = Carbon::now('Asia/Jakarta');
            // get detail item
            $item = DB::table('m_item')->where('i_id', $data->aa_item)->first();

            if ($item->i_unit1 == $data->aa_unitreal) {
                $tmp = $item->i_unitcompare1 * $data->aa_qtyreal;
            } elseif ($item->i_unit2 == $data->aa_unitreal) {
                $tmp = $item->i_unitcompare2 * $data->aa_qtyreal;
            } elseif ($item->i_unit3 == $data->aa_unitreal) {
                $tmp = $item->i_unitcompare3 * $data->aa_qtyreal;
            }

            $sisa = (int)$data->aa_qtysystem - (int)$tmp;

            if ($sisa < 0) {
                $tmp    = DB::table('m_mutcat')->where('m_name', 'Barang Masuk Dari Opname')->first();
                $mutcat = $tmp->m_id;
            } else {
                $tmp    = DB::table('m_mutcat')->where('m_name', 'Barang Keluar Dari Opname')->first();
                $mutcat = $tmp->m_id;
            }

            $comp      = $data->aa_comp;
            $position  = $data->aa_position;
            $qtysistem = $data->aa_qtysystem;
            $qtyreal   = $data->aa_qtyreal;
            $nota      = $data->aa_nota;
            $reff      = $data->aa_nota;
            $a_id = DB::table('d_adjusment')->max('a_id') +1;

            DB::table('d_adjusment')->insert([
                'a_id'         => $a_id,
                'a_comp'       => $data->aa_comp,
                'a_position'   => $data->aa_position,
                'a_date'       => $data->aa_date,
                'a_nota'       => $data->aa_nota,
                'a_item'       => $data->aa_item,
                'a_qtyreal'    => $data->aa_qtyreal,
                'a_unitreal'   => $data->aa_unitreal,
                'a_qtysystem'  => $data->aa_qtysystem,
                'a_unitsystem' => $data->aa_unitsystem,
                'a_insert'     => $data->aa_insert
            ]);

            // get stock-detail (production code)
            $stockDt = d_stockdt::whereHas('getStock', function ($q) use ($data) {
                $q->where('s_comp', $data->aa_comp)
                ->where('s_position', $data->aa_position)
                ->where('s_item', $data->aa_item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE');
            })
            ->get();

            $codeAuth = DB::table('d_adjustmentcodeauth')->where('aca_adjustment', '=', $data->aa_id)->get();
            $listPC = [];
            $listQtyPC = [];
            for ($i=0; $i < count($codeAuth); $i++) {
                $adjDt = DB::table('d_adjustmentcode')->where('ac_adjustment', '=', $a_id)->max('ac_detailid') + 1;
                DB::table('d_adjustmentcode')->insert([
                    'ac_adjustment' => $a_id,
                    'ac_detailid'   => $adjDt,
                    'ac_code'       => $codeAuth[$i]->aca_code,
                    'ac_qty'        => $codeAuth[$i]->aca_qty
                ]);

                foreach ($stockDt as $key => $value) {
                    if ($value->sd_code == $codeAuth[$i]->aca_code) {
                        $qtyCode = $value->sd_qty - $codeAuth[$i]->aca_qty;
                        $qtyCode = abs($qtyCode);
                        array_push($listQtyPC, $qtyCode);
                    }
                }
                array_push($listPC, $codeAuth[$i]->aca_code);
            }

            DB::table('d_adjusmentauth')->where('aa_id', $id)->delete();
            DB::table('d_adjustmentcodeauth')->where('aca_adjustment', $id)->delete();

            // Create to mutation ------------>>
            $mutAdjustmentOpname = Mutasi::opname(
                $mutcat, // mutation category
                $comp, // item-owner
                $position, // item position
                $data->aa_item, // item id
                $qtysistem, // qty in system
                $qtyreal, // qty in real
                $sisa, // difference between qty-system with qty-real
                $nota, // nota
                $reff, // nota refference
                $listPC, // list production-code
                $listQtyPC // list qty each production-code
            );
            if ($mutAdjustmentOpname->original['status'] !== 'success') {
                return $mutAdjustmentOpname;
            }

            // tambahan dirga
                $dataHpp = DB::table('d_stock_mutation')
                                    ->whereIn('sm_stock', function($query) use ($data){
                                        $query->select('s_id')
                                                ->from('d_stock')
                                                ->where('s_item', $data->aa_item)->get();
                                    })
                                    ->whereIn('sm_mutcat', function($query){
                                        $query->select('m_id')->from('m_mutcat')->where('m_status', 'M')->get();
                                    })
                                    ->select('*')->get()->last();

                $hpp = ($dataHpp) ? (float) $dataHpp->sm_hpp : 0;

                $selisih = ($data->aa_qtysystem - $data->aa_qtyreal) * $hpp;

                if($selisih != 0) {
                    $acc_persediaan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Adjustment Stok')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan Item')
                                        ->first();

                    $acc_beban_selisih = DB::table('dk_pembukuan_detail')
                                            ->where('pd_pembukuan', function($query){
                                                $query->select('pe_id')->from('dk_pembukuan')
                                                            ->where('pe_nama', 'Adjustment Stok')
                                                            ->where('pe_comp', Auth::user()->u_company)->first();
                                            })->where('pd_nama', 'COA beban selisih stok')
                                            ->first();

                    $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Adjustment Stok')
                                ->where('pe_comp', Auth::user()->u_company)->first();

                    $details = [];

                    if(!$parrent || !$acc_persediaan || !$acc_beban_selisih){
                        return response()->json([
                            'status' => 'gagal',
                            'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                        ]);
                    }

                    array_push($details, [
                        "jrdt_nomor"        => 1,
                        "jrdt_akun"         => ($selisih > 0) ? $acc_beban_selisih->pd_acc : $acc_persediaan->pd_acc,
                        "jrdt_value"        => ($selisih > 0) ? $selisih : $selisih * -1,
                        "jrdt_dk"           => "D",
                        "jrdt_keterangan"   => ($selisih > 0) ? $acc_beban_selisih->pd_keterangan : $acc_persediaan->pd_keterangan,
                        "jrdt_cashflow"     => ($selisih > 0) ? $acc_beban_selisih->pd_cashflow : $acc_persediaan->pd_cashflow
                    ]);

                    array_push($details, [
                        "jrdt_nomor"        => 2,
                        "jrdt_akun"         => ($selisih > 0) ? $acc_persediaan->pd_acc : $acc_beban_selisih->pd_acc,
                        "jrdt_value"        => ($selisih > 0) ? $selisih : $selisih * -1,
                        "jrdt_dk"           => "K",
                        "jrdt_keterangan"   => ($selisih > 0) ? $acc_persediaan->pd_keterangan : $acc_beban_selisih->pd_keterangan,
                        "jrdt_cashflow"     => ($selisih > 0) ? $acc_persediaan->pd_cashflow : $acc_beban_selisih->pd_cashflow,
                    ]);
                }

                $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $data->aa_nota, $parrent->pe_nama, 'TM', Auth::user()->u_company);

                if($jurnal['status'] == 'error'){
                    return json_encode($jurnal);
                }

            // end dirga

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Adjustment');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function rejectadjusment($id)
    {
        DB::beginTransaction();
        try {
            $id = Decrypt($id);

            DB::table('d_adjusmentauth')->where('aa_id', $id)->delete();
            DB::table('d_adjustmentcodeauth')->where('aca_adjustment', '=', $id)->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Adjustment');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
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
    public function revisi()
    {
        return view('notifikasiotorisasi.otorisasi.revisi.index');
    }


// ================== Order Produksi =================
    public function getProduksi()
    {
        $data = DB::table('d_productionorderauth')
        ->select('d_productionorderauth.poa_id', 'd_productionorderauth.poa_date', 'm_supplier.s_name', 'd_productionorderauth.poa_nota', 'm_supplier.s_company')
        ->join('m_supplier', function ($q){
            $q->on('d_productionorderauth.poa_supplier', '=', 'm_supplier.s_id');
        })->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('date', function($data){
            return date('d-m-Y', strtotime($data->poa_date));
        })
        ->addColumn('supplier', function($data){
            return $data->s_company;
        })
        ->addColumn('nota', function($data){
            return $data->poa_nota;
        })
        ->addColumn('aksi', function($data){
            $detail = '<button class="btn btn-primary btn-modal" style="margin-right: 10px;" type="button" title="Detail Data" onclick="detailOrderProduksi(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-folder"></i></button>';
            $setujui = '<button class="btn btn-warning btn-edit" type="button" title="Setujui" onclick="agree(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-check"></i></button>';
            $tolak = '<button class="btn btn-danger btn-disable" type="button" title="Tolak" onclick="rejected(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-remove"></i></button>';
            return '<div class="text-center">'. $detail .'<div class="btn-group btn-group-sm">' . $setujui . $tolak . '</div></div>';
        })
        ->rawColumns(['date','supplier','nota','aksi'])
        ->make(true);
    }
    public function getProduksiDetailItem(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        $data = DB::table('d_productionorderdt')
        ->select('m_item.i_name',
        'm_unit.u_name',
        'd_productionorderdt.pod_qty',
        'd_productionorderdt.pod_value',
        'd_productionorderdt.pod_totalnet')
        ->join('m_item', function ($q){
            $q->on('d_productionorderdt.pod_item', '=', 'm_item.i_id');
        })->join('m_unit', function ($q){
            $q->on('d_productionorderdt.pod_unit', '=', 'm_unit.u_id');
        })
        ->where('d_productionorderdt.pod_productionorder', '=', $id)
        ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('item', function($data){
            return $data->i_name;
        })
        ->addColumn('unit', function($data){
            return $data->u_name;
        })
        ->addColumn('qty', function($data){
            return '<p class="text-center">'. $data->pod_qty .'</p>';
        })
        ->addColumn('value', function($data){
            return '<p class="text-right">'. Currency::addRupiah($data->pod_value) .'</p>';
        })
        ->addColumn('totalnet', function($data){
            return '<p class="text-right">'. Currency::addRupiah($data->pod_totalnet) .'</p><input type="hidden" class="totalnetdetail" name="totalnetdetail[]" value="'.number_format($data->pod_totalnet,0,'','').'">';
        })
        ->rawColumns(['item','unit','qty','value','totalnet'])
        ->make(true);
    }
    public function getProduksiDetailTermin(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        $data = DB::table('d_productionorderpayment')
        ->select('pop_termin',
        'pop_datetop',
        'pop_value')
        ->where('pop_productionorder', '=', $id)
        ->orderBy('pop_termin', 'asc')
        ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('termin', function($data){
            return $data->pop_termin;
        })
        ->addColumn('date', function($data){
            return date('d-m-Y', strtotime($data->pop_datetop));
        })
        ->addColumn('value', function($data){
            return '<p class="text-right">'. Currency::addRupiah($data->pop_value) .'</p><input type="hidden" class="totaltermin" value="'.number_format($data->pop_value,0,'','').'">';
        })
        ->rawColumns(['termin','date','value'])
        ->make(true);
    }
    public function agree($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        DB::beginTransaction();
        try{
            $data = DB::table('d_productionorderauth')
            ->where('poa_id', '=', $id)->first();

            $values = [
                'po_id'         => $data->poa_id,
                // 'po_nota'       => CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-'),
                'po_nota'       => $data->poa_nota,
                'po_date'       => $data->poa_date,
                'po_supplier'   => $data->poa_supplier,
                'po_totalnet'   => $data->poa_totalnet,
                'po_status'     => $data->poa_status,
            ];

            DB::table('d_productionorder')->insert($values);

            // Tambahan Dirga
                $detail = DB::table('d_productionorderdt')->where('pod_productionorder', $id)->get();

                $acc_persediaan = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Persediaan dalam perjalanan')
                                        ->first();

                $hutang = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Hutang')
                                        ->first();

                $details = []; $count = 0;

                if(!$hutang || !$acc_persediaan){
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                    ]);
                }

                foreach ($detail as $key => $value) {
                    $count += $value->pod_value * $value->pod_qty;
                }

                array_push($details, [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $acc_persediaan->pd_acc,
                    "jrdt_value"        => $count,
                    "jrdt_dk"           => "D",
                    "jrdt_keterangan"   => "Persediaan Dalam Perjalanan Order Produksi",
                    "jrdt_cashflow"     => null
                ]);

                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $hutang->pd_acc,
                    "jrdt_value"        => $count,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => "Hutang Order Produksi",
                    "jrdt_cashflow"     => null
                ]);

                $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $data->poa_nota, 'Order Produksi', 'TM', Auth::user()->u_company);

                if($jurnal['status'] == 'error'){
                    return json_encode($jurnal);
                }

            // selesai dirga

            DB::table('d_productionorderauth')
                ->where('poa_id', '=', $id)
                ->delete();

            pushOtorisasi::otorisasiup('Otorisasi Revisi Data');

            DB::commit();
            return response()->json(['status'=>'Success']);
        }
        catch (\Exception $e){
            // DB::commit();
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function rejected($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        DB::beginTransaction();
        try{

            DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $id)
                ->delete();

            DB::table('d_productionorderdt')
                ->where('pod_productionorder', '=', $id)
                ->delete();

            DB::table('d_productionorderauth')
                ->where('poa_id', '=', $id)
                ->delete();

            pushOtorisasi::otorisasiup('Otorisasi Revisi Data');

            DB::commit();
            return response()->json(['status'=>'Success']);
        }
        catch (\Exception $e){
            DB::commit();
            return response()->json([
                'status'=>'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }


// ============= Perubahan Harga Jual ==============
    public function getDataPerubahanHarga()
    {
        $data = DB::table('m_priceclass')
        ->join('d_priceclassauthdt', 'pc_id', '=', 'pcad_classprice')
        ->join('m_item', 'i_id', '=', 'pcad_item')
        ->join('m_unit', 'u_id', '=', 'pcad_unit')
        ->select('pc_id', 'pc_name', DB::raw('concat(i_code, " - ", i_name) as nama'), 'u_name', 'd_priceclassauthdt.*')
        ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('pcad_type', function ($data){
            if ($data->pcad_type == 'U'){
                return 'Unit';
            } else {
                return 'Range';
            }
        })
        ->editColumn('pcad_payment', function ($data){
            if ($data->pcad_payment == 'C'){
                return 'Cash';
            } else {
                return 'Konsinyasi';
            }
        })
        ->addColumn('qty', function ($data){
            if ($data->pcad_type == 'U'){
                return '1 ' . $data->u_name;
            } else {
                if ($data->pcad_rangeqtyend == 0) {
                    $end = "~";
                }else{
                    $end = $data->pcad_rangeqtyend;
                }
                return $data->pcad_rangeqtystart . '-' . $end . ' ' . $data->u_name;
            }
        })
        ->addColumn('aksi', function ($data){
            // return '<div class="text-center"><div class="btn-group btn-group-sm">
            // 								<button class="btn btn-info" onclick="detail(\''.Crypt::encrypt($data->pcad_classprice).'\',\'' .Crypt::encrypt($data->pcad_detailid). '\')" type="button"><i class="fa fa-folder"></i></button>
            // 								<button class="btn btn-success" type="button" onclick="approve(\''.Crypt::encrypt($data->pcad_classprice).'\',\'' .Crypt::encrypt($data->pcad_detailid). '\')" title="Setuju"><i class="fa fa-check-circle"></i></button>
            // 								<button class="btn btn-danger" type="button" onclick="reject(\''.Crypt::encrypt($data->pcad_classprice).'\',\'' .Crypt::encrypt($data->pcad_detailid). '\')" title="Tolak"><i class="fa fa-times-circle"></i></button>
            // 							</div></div>';
            return '<div class="text-center"><div class="btn-group btn-group-sm">
                <button class="btn btn-success" type="button" onclick="approve(\''.Crypt::encrypt($data->pcad_classprice).'\',\'' .Crypt::encrypt($data->pcad_detailid). '\')" title="Setuju"><i class="fa fa-check-circle"></i></button>
                <button class="btn btn-danger" type="button" onclick="reject(\''.Crypt::encrypt($data->pcad_classprice).'\',\'' .Crypt::encrypt($data->pcad_detailid). '\')" title="Tolak"><i class="fa fa-times-circle"></i></button>
            </div></div>';
        })
        ->editColumn('pcad_price', function ($data){
            $harga = (int)$data->pcad_price;
            return '<div class="text-right">Rp. ' . number_format($harga, '0', '', '.') .'</div>';
        })
        ->rawColumns(['aksi', 'pcad_price'])
        ->make(true);
    }
    public function getDataPerubahanHargaHPA()
    {
        $data = DB::table('d_salesprice')
        ->join('d_salespriceauth', 'sp_id', '=', 'spa_salesprice')
        ->join('m_item', 'i_id', '=', 'spa_item')
        ->join('m_unit', 'u_id', '=', 'spa_unit')
        ->select('sp_id', 'sp_name', DB::raw('concat(i_code, "-", i_name) as nama'), 'u_name', 'd_salespriceauth.*')
        ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('spa_type', function ($data){
            if ($data->spa_type == 'U'){
                return 'Unit';
            } else {
                return 'Range';
            }
        })
        ->editColumn('spa_payment', function ($data){
            if ($data->spa_payment == 'C'){
                return 'Cash';
            } else {
                return 'Konsinyasi';
            }
        })
        ->addColumn('qty', function ($data){
            if ($data->spa_type == 'U'){
                return '1 ' . $data->u_name;
            } else {
                if ($data->spa_rangeqtyend == 0) {
                    $end = "~";
                }else{
                    $end = $data->spa_rangeqtyend;
                }
                return $data->spa_rangeqtystart . '-' . $end . ' ' . $data->u_name;
            }
        })
        ->addColumn('aksi', function ($data){
            // return '<div class="text-center"><div class="btn-group btn-group-sm">
            // 								<button class="btn btn-info" onclick="detailHPA(\''.Crypt::encrypt($data->spa_salesprice).'\',\'' .Crypt::encrypt($data->spa_detailid). '\')" type="button"><i class="fa fa-folder"></i></button>
            // 								<button class="btn btn-success" type="button" onclick="approveHPA(\''.Crypt::encrypt($data->spa_salesprice).'\',\'' .Crypt::encrypt($data->spa_detailid). '\')" title="Setuju"><i class="fa fa-check-circle"></i></button>
            // 								<button class="btn btn-danger" type="button" onclick="rejectHPA(\''.Crypt::encrypt($data->spa_salesprice).'\',\'' .Crypt::encrypt($data->spa_detailid). '\')" title="Tolak"><i class="fa fa-times-circle"></i></button>
            // 							</div></div>';
            return '<div class="text-center"><div class="btn-group btn-group-sm">
                <button class="btn btn-success" type="button" onclick="approveHPA(\''.Crypt::encrypt($data->spa_salesprice).'\',\'' .Crypt::encrypt($data->spa_detailid). '\')" title="Setuju"><i class="fa fa-check-circle"></i></button>
                <button class="btn btn-danger" type="button" onclick="rejectHPA(\''.Crypt::encrypt($data->spa_salesprice).'\',\'' .Crypt::encrypt($data->spa_detailid). '\')" title="Tolak"><i class="fa fa-times-circle"></i></button>
            </div></div>';
        })
        ->addColumn('spa_price', function ($data){
            $harga = (int)$data->spa_price;
            return '<div class="text-right">Rp. ' . number_format($harga, '0', '', '.') .'</div>';
        })
        ->rawColumns(['aksi', 'spa_price'])
        ->make(true);
    }
    public function detailPerubahanHarga($id, $detail)
    {
        //
    }
    public function approvePerubahanHarga($id, $detail)
    {
        try{
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }
        catch (DecryptException $e){
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try {
            $data = DB::table('d_priceclassauthdt')
                ->where('pcad_classprice', '=', $id)
                ->where('pcad_detailid', '=', $detail)
                ->first();

            if ($data == null) {
                return response()->json([
                    'status' => 'gagal'
                ]);
            }

            $max = DB::table('m_priceclassdt')
                ->where('pcd_classprice', '=', $data->pcad_classprice)
                ->max('pcd_detailid');

            ++$max;

            DB::table('m_priceclassdt')
                ->insert([
                    'pcd_classprice' => $data->pcad_classprice,
                    'pcd_detailid' => $max,
                    'pcd_item' => $data->pcad_item,
                    'pcd_unit' => $data->pcad_unit,
                    'pcd_type' => $data->pcad_type,
                    'pcd_payment' => $data->pcad_payment,
                    'pcd_rangeqtystart' => $data->pcad_rangeqtystart,
                    'pcd_rangeqtyend' => $data->pcad_rangeqtyend,
                    'pcd_price' => $data->pcad_price,
                    'pcd_user' => $data->pcad_user
                ]);

            DB::table('d_priceclassauthdt')
                ->where('pcad_classprice', '=', $id)
                ->where('pcad_detailid', '=', $detail)
                ->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Perubahan Harga Jual');

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function approvePerubahanHargaHPA($id, $detail)
    {
        try
        {
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        }
        catch (DecryptException $e)
        {
            return response()->json([
            'status' => 'gagal',
            'message' => $e
            ]);
        }

        DB::beginTransaction();
        try
        {
            $data = DB::table('d_salespriceauth')
                ->where('spa_salesprice', '=', $id)
                ->where('spa_detailid', '=', $detail)
                ->first();

            if ($data == null)
            {
                return response()->json([
                    'status' => 'gagal'
                ]);
            }

            $max = DB::table('d_salespricedt')
                ->where('spd_salesprice', '=', $data->spa_salesprice)
                ->max('spd_detailid');

            ++$max;

            DB::table('d_salespricedt')
                ->insert([
                    'spd_salesprice'        => $data->spa_salesprice,
                    'spd_detailid'          => $max,
                    'spd_item'              => $data->spa_item,
                    'spd_unit'              => $data->spa_unit,
                    'spd_type'              => $data->spa_type,
                    'spd_payment'           => $data->spa_payment,
                    'spd_rangeqtystart'     => $data->spa_rangeqtystart,
                    'spd_rangeqtyend'       => $data->spa_rangeqtyend,
                    'spd_price'             => $data->spa_price,
                    'spd_user'              => $data->spa_user
                ]);

            DB::table('d_salespriceauth')
                ->where('spa_salesprice', '=', $id)
                ->where('spa_detailid', '=', $detail)
                ->delete();

            // pusher -> push notification
            pushOtorisasi::otorisasiup('Otorisasi Perubahan Harga Jual');

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }


// ======================== Otorisasi Revisi Data ========================
    public function getListRevDataProduk()
    {
        $datas = m_item_auth::with('getItem')
            ->get();

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('produk', function ($datas) {
            if ($datas->getItem != null) {
                return $datas->getItem->i_name;
            } else {
                return $datas->ia_name;
            }
        })
        ->addColumn('authorizationType', function ($datas) {
            if ($datas->ia_isactive === 'N') {
                return 'Non-aktifkan produk';
            } elseif ($datas->getItem != null) {
                return 'Edit produk';
            } else {
                return 'Tambah produk';
            }
        })
        ->addColumn('action', function ($datas) {
            return
            '<div class="btn-group btn-group-sm">
                <button class="btn btn-primary btn-detailRevisiP" type="button" title="Detail" onclick="showDetailRevisiP('. $datas->ia_id .')"><i class="fa fa-folder"></i></button>
                <button class="btn btn-warning btn-appRevisiP" type="button" title="Setujui" onclick="appRevisiP('. $datas->ia_id .')"><i class="fa fa-check"></i></button>
                <button class="btn btn-danger btn-rejRevisiP" type="button" title="Tolak" onclick="rejRevisiP('. $datas->ia_id .')"><i class="fa fa-ban"></i></button>
            </div>';
        })
        ->rawColumns(['produk', 'authorizationType', 'action'])
        ->make(true);
    }
    // retrieve detail revisi-data-produk
    public function getDetailRevDataProduk($id)
    {
        $data = m_item_auth::where('ia_id', $id)
            ->with('getItem')
            ->with('getItemType')
            ->first();
        return $data;
    }
    // approve revisi-data-produk
    public function approveRevisiProduk($id)
    {
        // $item-auth = (get m_item_auth where id = $id)
        $item_auth = m_item_auth::where('ia_id', $id)->first();
        // $item-main = (get m_item where id = $id)
        $item_main = m_item::where('i_id', $id)->first();
        // if $item-main != null
        DB::beginTransaction();
        try
        {
            if ($item_main != null)
            {
                if ($item_auth->ia_isactive === 'N')
                {
                    $item_main->i_isactive = 'N';
                    $item_main->save();
                }
                else
                {
                    // if ($item_auth->ia_image != '')
                    // {
                    //     // get-directory based on item-id
                    //     $mainDirectory = storage_path('app\Products\original\\') . $id;
                    //     $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                    //     if (!is_dir($mainDirectory))
                    //     {
                    //         mkdir($mainDirectory, 0777, true);
                    //     }
                    //     // is image exist in auth-directory ?
                    //     if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
                    //     {
                    //         // delete image if exist in main-directory
                    //         if (file_exists($mainDirectory . $item_main->i_image)) {
                    //             unlink($mainDirectory .'\\'. $item_main->i_image);
                    //         }
                    //         // move image from item-auth to original
                    //         $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
                    //         $newPath = $mainDirectory .'\\'. $item_auth->ia_image;
                    //         rename($oldPath, $newPath);
                    //     }
                    // }

                    // update $item-main based on $item-auth
                    $item_main->i_code = $item_auth->ia_code;
                    $item_main->i_type = $item_auth->ia_type;
                    $item_main->i_codegroup = $item_auth->ia_codegroup;
                    $item_main->i_name = $item_auth->ia_name;
                    $item_main->i_detail = $item_auth->ia_detail;
                    $item_main->i_image = $item_auth->ia_image;
                    $item_main->i_isactive = 'Y';
                    $item_main->save();

                }
            }
            // if $item-main == null
            else
            {
                // if ($item_auth->ia_image != '')
                // {
                //     // make-directory based on item-id
                //     $mainDirectory = storage_path('uploads\produk\original\\') . $id;
                //     $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                //     if (!is_dir($mainDirectory))
                //     {
                //         mkdir($mainDirectory, 0777, true);
                //     }
                //     if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
                //     {
                //         // move image from auth-directory to main-directory
                //         $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
                //         $newPath = $mainDirectory .'\\'. $item_auth->ia_image;
                //         rename($oldPath, $newPath);
                //     }
                // }

                // insert $item-auth to $item-main
                $item = new m_item();
                $item->i_id = $item_auth->ia_id;
                $item->i_code = $item_auth->ia_code;
                $item->i_type = $item_auth->ia_type;
                $item->i_codegroup = $item_auth->ia_codegroup;
                $item->i_name = $item_auth->ia_name;
                $item->i_unit1 = $item_auth->ia_unit1;
                $item->i_unit2 = $item_auth->ia_unit2;
                $item->i_unit3 = $item_auth->ia_unit3;
                $item->i_unitcompare1 = $item_auth->ia_unitcompare1;
                $item->i_unitcompare2 = $item_auth->ia_unitcompare2;
                $item->i_unitcompare3 = $item_auth->ia_unitcompare3;
                $item->i_detail = $item_auth->ia_detail;
                $item->i_isactive = $item_auth->ia_isactive;
                $item->i_image = $item_auth->ia_image;
                $item->save();

            }
            // delete $item_auth after approval success
            $item_auth->delete();

            pushOtorisasi::otorisasiup('Otorisasi Revisi Data');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json([
            'status' => 'gagal',
            'message' => $e->getMessage()
            ]);
        }
    }
    // reject revisi-data-produk
    public function rejectRevisiProduk($id)
    {
        DB::beginTransaction();
        try
        {
            // $item-auth = (get m_item_auth where id = $id)
            $item_auth = m_item_auth::where('ia_id', $id)->first();
            // delete image in storage
            if ($item_auth->ia_image != '')
            {
                // Storage::delete($item_auth->ia_image);
            //     // get-directory based on item-id
            //     $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
            //     if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
            //     {
            //         // delete image inside auth-directory
            //         $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
            //         unlink($oldPath);
            //     }
            }
            // delete $item_auth after approval success
            $item_auth->delete();

            pushOtorisasi::otorisasiup('Otorisasi Revisi Data');

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

//============ Approval promosi ================
    public function promotion()
    {
        return view('notifikasiotorisasi.otorisasi.promotion.index');
    }
    public function getDataPromotion()
    {
        $data = DB::table('d_promotion')
            ->where('p_isapproved', '=', 'P')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data){
                return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-success btn-xs done hint--top hint--info" onclick="ApprovePromosi(\''.Crypt::encrypt($data->p_id).'\', \''. intval($data->p_budget) .'\')" rel="tooltip" data-placement="top" aria-label="Setujui"><i class="fa fa-check"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="TolakPromosi(\''.Crypt::encrypt($data->p_id).'\')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Tolak"><i class="fa fa-close"></i></button>
                            </div></center>';
            })
            ->addColumn('jenis', function ($data){
                if ($data->p_type == 'T'){
                    return 'Tahunan';
                } elseif ($data->p_type == 'B'){
                    return 'Bulanan';
                }
            })
            ->editColumn('p_budget', function ($data){
                return "Rp. " . number_format(intval($data->p_budget), '0', ',', '.');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function getListPaymentMethod()
    {
        // get list akun-payment
        $userCode = Auth::user()->u_company;
        $paymentMethod = m_paymentmethod::where('pm_isactive', 'Y')
            ->where('pm_comp', $userCode)
            ->get();

        $listPaymentMethod = [];
        $opt = [
            'id' => '-',
            'text' => 'Pilih Akun Kas'
        ];
        array_push($listPaymentMethod, $opt);
        foreach ($paymentMethod as $key => $value) {
            $opt = [
                'id' => $value->pm_akun,
                'text' => $value->pm_name
            ];
            array_push($listPaymentMethod, $opt);
        }
        return response()->json([
            'listPaymentMethod' => $listPaymentMethod
        ]);
    }
    public function approvePromotion(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $realisasi = $request->realisasi;

        DB::beginTransaction();
        try {
            DB::table('d_promotion')
                ->where('p_id', '=', $id)
                ->update([
                    'p_budgetrealization' => $realisasi,
                    'p_isapproved' => 'Y'
                ]);

            pushOtorisasi::otorisasiup('Otorisasi Promosi');

            // Jurnal ------------------------------------------
            $acc_promosi_beban = DB::table('dk_pembukuan_detail')
                                    ->where('pd_pembukuan', function($query){
                                        $query->select('pe_id')->from('dk_pembukuan')
                                                    ->where('pe_nama', 'Pembayaran Promosi')
                                                    ->where('pe_comp', Auth::user()->u_company)->first();
                                    })->where('pd_nama', 'COA Beban Promosi')
                                    ->first();
            $acc_promosi_kas = DB::table('dk_pembukuan_detail')
                                    ->where('pd_pembukuan', function($query){
                                        $query->select('pe_id')->from('dk_pembukuan')
                                                    ->where('pe_nama', 'Pembayaran Promosi')
                                                    ->where('pe_comp', Auth::user()->u_company)->first();
                                    })->where('pd_nama', 'COA Kas/Setara Kas')
                                    ->first();

            $parrent = DB::table('dk_pembukuan')->where('pe_nama', 'Pembayaran Promosi')
                        ->where('pe_comp', Auth::user()->u_company)->first();
            $details = [];

            if(!$parrent || !$acc_promosi_beban || !$acc_promosi_kas){
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                ]);
            }

            array_push($details, [
                "jrdt_nomor"        => 1,
                "jrdt_akun"         => $acc_promosi_beban->pd_acc,
                "jrdt_value"        => $request->realisasi,
                "jrdt_dk"           => "D",
                "jrdt_keterangan"   => $acc_promosi_beban->pd_keterangan,
                "jrdt_cashflow"     => $acc_promosi_beban->pd_cashflow
            ]);

            // set cash-account to auto-generate
            if ($request->cashAccount == '-') {
                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $acc_promosi_kas->pd_acc,
                    "jrdt_value"        => $request->realisasi,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $acc_promosi_kas->pd_keterangan,
                    "jrdt_cashflow"     => $acc_promosi_kas->pd_cashflow
                ]);
            }
            // set cash-account to selected paymentMethod
            else {
                array_push($details, [
                    "jrdt_nomor"        => 2,
                    "jrdt_akun"         => $request->cashAccount,
                    "jrdt_value"        => $request->realisasi,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $acc_promosi_kas->pd_keterangan,
                    "jrdt_cashflow"     => $acc_promosi_kas->pd_cashflow
                ]);
            }

            $nota = DB::table('d_promotion')->where('p_id', $id)->first();
            $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $nota->p_reff, $parrent->pe_nama, 'TK', Auth::user()->u_company);

            if($jurnal['status'] == 'error'){
                return json_encode($jurnal);
            }

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
    public function rejectPromotion(Request $request)
    {
        $id = Crypt::decrypt($request->id);

        DB::beginTransaction();
        try {
            DB::table('d_promotion')
                ->where('p_id', '=', $id)
                ->update([
                    'p_isapproved' => 'N'
                ]);

            pushOtorisasi::otorisasiup('Otorisasi Promosi');

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

//============ Approval sdm-submission ================
    public function getListPengajuanInOtorisasi()
    {
        $pengajuan = DB::table('d_sdmsubmission')
            ->join('m_divisi', 'ss_department','m_id')
            ->join('m_jabatan', 'ss_position', 'j_id')
            ->where('ss_isactive', '=', 'Y')
            ->where('ss_isapproved',['P','N'])
            ->get();

        return Datatables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('tanggal', function($pengajuan) {
                return '<td>'. Carbon::parse($pengajuan->ss_date)->format('d M Y') .'</td>';
            })
            ->addColumn('status', function($pengajuan) {
                if ($pengajuan->ss_isapproved == "P") {
                    return '<span class="btn-sm btn-block btn-disabled bg-danger text-light text-center" disabled>Pending</span>';
                } else if ($pengajuan->ss_isapproved == "Y") {
                    return '<span class="btn-sm btn-block btn-disabled bg-success text-light text-center" disabled>Disetujui</span>';
                } else if ($pengajuan->ss_isapproved == "N") {
                    return '<span class="btn-sm btn-block btn-disabled bg-danger text-light text-center" disabled>Ditolak</span>';
                }

            })
            ->addColumn('action', function($pengajuan) {
                if ($pengajuan->ss_isapproved == "P") {
                    return '<div class="text-center">
                    <div class="btn-group btn-group-sm">
                      <button class="btn btn-success hint--top-left hint--success" type="button"  aria-label="Terima" onclick="ApprovePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-check-circle-o"></i></button>
                      <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Tolak" onclick="DeclinePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-times-circle-o"></i></button>
                    </div>
                  </div>';
                } else if ($pengajuan->ss_isapproved == "Y"){
                    return '<div class="text-center">
                     <div class="btn-group btn-group-sm">
                      <button class="btn btn-disabled" type="button"  aria-label="Terima" onclick="ApprovePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-check-circle-o"></i></button>
                      <button class="btn btn-danger hint--top-left hint--error" type="button" aria-label="Tolak" onclick="DeclinePengajuan(\''.Crypt::encrypt($pengajuan->ss_id).'\')"><i class="fa fa-times-circle-o"></i></button>
                    </div>
                  </div>';
                } else if ($pengajuan->ss_isapproved == "N") {
                    return '<div class="text-center">
                     <div class="btn-group btn-group-sm">
                      <button class="btn btn-success hint--top-left hint--success" type="button"  aria-label="Terima" onclick="ApprovePengajuan(\'' . Crypt::encrypt($pengajuan->ss_id) . '\')"><i class="fa fa-check-circle-o"></i></button>
                      <button class="btn btn-disabled" type="button" aria-label="Tolak" onclick="DeclinePengajuan(\'' . Crypt::encrypt($pengajuan->ss_id) . '\')"><i class="fa fa-times-circle-o"></i></button>
                    </div>
                  </div>';
                }
            })
            ->rawColumns(['tanggal', 'status', 'action'])
            ->make(true);
    }
    public function simpanPublikasi(Request $request)
    {
        $date1 = strtotime($request->ss_startdate);
        $start_date = date('Y-m-d', $date1);
        $date2 = strtotime($request->ss_enddate);
        $end_date = date('Y-m-d', $date2);
        $id = $request->id_pengajuan;
        $ids = decrypt($id);

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
                ->where('ss_id', $ids)
                ->update([
                    'ss_startdate' => $start_date,
                    'ss_enddate' => $end_date,
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function ApprovePengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
                ->where('ss_id', $id)
                ->update([
                    'ss_isapproved' => "Y"
                ]);

            pushOtorisasi::otorisasiup('Otorisasi SDM');

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function DeclinePengajuan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_sdmsubmission')
                ->where('ss_id', $id)
                ->update([
                    'ss_isapproved' => "N"
                ]);

            pushOtorisasi::otorisasiup('Otorisasi SDM');

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'Gagal',
                'message' => $e
            ]);
        }
    }

}
