<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use App\m_item;
use App\m_item_auth;
use DB;
use DataTables;
use Currency;
use CodeGenerator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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


    // ================== Opname =================
    public function getopname()
    {
        $data = DB::table('d_opnameauth')->join('m_item', 'i_id', '=', 'oa_item')->get();

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
            $setujui = '<button class="btn btn-warning btn-primary" type="button" title="Setujui" onclick="approve(\''. Crypt::encrypt($data->oa_id) .'\')"><i class="fa fa-check"></i></button>';
            $tolak = '<button class="btn btn-danger btn-disable" type="button" title="Tolak" onclick="rejected(\''. Crypt::encrypt($data->oa_id) .'\')"><i class="fa fa-remove"></i></button>';
            return '<center><div class="btn-group btn-group-sm">' . $setujui . $tolak . '</div></center>';
        })
        ->rawColumns(['nota','aksi'])
        ->make(true);
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
            $auth = DB::table('d_opnameauth')->where('oa_id', '=', $id)->first();

            $authdt = DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->get();

            // dd($auth, $authdt);
            $id = DB::table('d_opname')->max('o_id')+1;
            DB::table('d_opname')->insert([
                'o_id'         => $id,
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
                    'od_opname'   => $id,
                    'od_code'     => $authdt[$i]->oad_code,
                    'od_detailid' => $authdt[$i]->oad_detailid,
                    'od_qty'      => $authdt[$i]->oad_qty
                ]);
            }

            DB::table('d_opnameauth')->where('oa_id', '=', $id)->delete();

            DB::table('d_opnameauthdt')->where('oad_opname', '=', $id)->delete();

            DB::commit();
            return response()->json([
            'status' => 'berhasil'
            ]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response()->json([
            'status' => 'gagal'
            ]);
        }
    }
    public function rejectedopname($id)
    {
        DB::beginTransaction();
        try
        {
            DB::table('d_opnameauth')->where('oa_id', Crypt::decrypt($id))->delete();

            DB::commit();
            return response()->json([
            'status' => 'berhasil'
            ]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response()->json([
            'status' => 'gagal'
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
        $data = DB::table('d_adjusmentauth')->join('m_item', 'i_id', '=', 'aa_item')->get();

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
            $tmp = $data->aa_qtysystem - $data->aa_qtyreal;
            return $tmp;
        })
        ->addColumn('unitsystem', function($data){
            $tmp = DB::table('m_unit')->where('u_id', '=', $data->aa_unitreal)->first();
            return $tmp->u_name;
        })
        ->addColumn('unitreal', function($data){
            $tmp = DB::table('m_unit')->where('u_id', '=', $data->aa_unitsystem)->first();
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
            
            $data = DB::table('d_adjusmentauth')->where('aa_id', $id)->first();

            $date = Carbon::now('Asia/Jakarta');

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

            DB::table('d_adjusment')->insert([
                'a_id'         => $data->aa_id,
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

            $codeAuth = DB::table('d_adjustmentcodeauth')->where('aca_adjustment', '=', $data->aa_id)->get();
            $listPC = [];
            $listQtyPC = [];
            for ($i=0; $i < count($codeAuth); $i++) {
                $adjDt = DB::table('d_adjustmentcode')->where('ac_adjustment', '=', $data->aa_id)->max('ac_detailid') + 1;
                DB::table('d_adjustmentcode')->insert([
                    'ac_adjustment' => $data->aa_id,
                    'ac_detailid'   => $adjDt,
                    'ac_code'       => $codeAuth[$i]->aca_code,
                    'ac_qty'        => $codeAuth[$i]->aca_qty
                ]);

                array_push($listPC, $codeAuth[$i]->aca_code);
                array_push($listQtyPC, $codeAuth[$i]->aca_qty);
            }


            DB::table('d_adjusmentauth')->where('aa_id', $id)->delete();
            DB::table('d_adjustmentcodeauth')->where('aca_adjustment', $id)->delete();

            // Create to mutation ------------>>
            Mutasi::opname((int)$mutcat, $comp, $position, (int)$data->aa_item, $qtysistem, $qtyreal, $sisa, $nota, $reff, $listPC, $listQtyPC);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal'
            ]);
        }
    }
    public function rejectadjusment($id)
    {
        DB::beginTransaction();
        try {
            $id = Decrypt($id);

            DB::table('d_adjusmentauth')->where('aa_id', $id)->delete();
            DB::table('d_adjustmentcode')->where('ac_adjustment', '=', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal'
            ]);
        }

    }
    public function revisi()
    {
        return view('notifikasiotorisasi.otorisasi.revisi.index');
    }


    // ==================Order Produksi=================
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
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return response()->json(['status'=>'Failed']);
        }

        DB::beginTransaction();
        try{
            $data = DB::table('d_productionorderauth')
            ->where('poa_id', '=', $id)->first();

            $values = [
            'po_id'         => $data->poa_id,
            'po_nota'       => CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-'),
            'po_date'       => $data->poa_date,
            'po_supplier'   => $data->poa_supplier,
            'po_totalnet'   => $data->poa_totalnet,
            'po_status'     => $data->poa_status,
            ];

            DB::table('d_productionorder')->insert($values);

            DB::table('d_productionorderauth')
            ->where('poa_id', '=', $id)
            ->delete();

            DB::commit();
            return response()->json(['status'=>'Success']);
        }
        catch (\Exception $e){
            DB::commit();
            return response()->json([
                'status' => 'Failed',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function rejected($id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
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
        ->select('pc_id', 'pc_name', DB::raw('concat(i_code, "-", i_name) as nama'), 'u_name', 'd_priceclassauthdt.*')
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
        }catch (DecryptException $e){
            return response()->json([
            'status' => 'gagal',
            'message' => $e
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
            DB::commit();
            return response()->json([
            'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
            'status' => 'gagal',
            'message' => $e
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
            'message' => $e
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
                    if ($item_auth->ia_image != '')
                    {
                        // get-directory based on item-id
                        $mainDirectory = storage_path('uploads\produk\original\\') . $id;
                        $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                        if (!is_dir($mainDirectory))
                        {
                            mkdir($mainDirectory, 0777, true);
                        }
                        // is image exist in auth-directory ?
                        if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
                        {
                            // delete image if exist in main-directory
                            if (file_exists($mainDirectory . $item_main->i_image)) {
                                unlink($mainDirectory .'\\'. $item_main->i_image);
                            }
                            // move image from item-auth to original
                            $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
                            $newPath = $mainDirectory .'\\'. $item_auth->ia_image;
                            rename($oldPath, $newPath);
                        }
                    }

                    // update $item-main based on $item-auth
                    $item_main->i_code = $item_auth->ia_code;
                    $item_main->i_type = $item_auth->ia_type;
                    $item_main->i_codegroup = $item_auth->ia_codegroup;
                    $item_main->i_name = $item_auth->ia_name;
                    $item_main->i_detail = $item_auth->ia_detail;
                    $item_main->i_image = $item_auth->ia_image;
                    $item_main->save();

                }
            }
            // if $item-main == null
            else
            {
                if ($item_auth->ia_image != '')
                {
                    // make-directory based on item-id
                    $mainDirectory = storage_path('uploads\produk\original\\') . $id;
                    $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                    if (!is_dir($mainDirectory))
                    {
                        mkdir($mainDirectory, 0777, true);
                    }
                    if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
                    {
                        // move image from auth-directory to main-directory
                        $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
                        $newPath = $mainDirectory .'\\'. $item_auth->ia_image;
                        rename($oldPath, $newPath);
                    }
                }

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
                // get-directory based on item-id
                $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                if (file_exists($authDirectory .'\\'. $item_auth->ia_image))
                {
                    // delete image inside auth-directory
                    $oldPath = $authDirectory .'\\'. $item_auth->ia_image;
                    unlink($oldPath);
                }
            }
            // delete $item_auth after approval success
            $item_auth->delete();

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
}
