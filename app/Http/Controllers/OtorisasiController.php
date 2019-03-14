<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Currency;
use CodeGenerator;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class OtorisasiController extends Controller
{
    public function otorisasi(){
        return view('notifikasiotorisasi.otorisasi.index');
    }
    public function perubahanhargajual(){
        return view('notifikasiotorisasi.otorisasi.perubahanhargajual.index');
    }
    public function pengeluaranlebih(){
        return view('notifikasiotorisasi.otorisasi.pengeluaranlebih.index');
    }
    public function opname_otorisasi(){
        return view('notifikasiotorisasi.otorisasi.opname.index');
    }
    public function adjustment(){
        return view('notifikasiotorisasi.otorisasi.adjustment.index');
    }
    public function revisi(){
        return view('notifikasiotorisasi.otorisasi.revisi.index');
    }

//    ==================Order Produksi=================
    public function getProduksi()
    {
        $data = DB::table('d_productionorderauth')
                ->select('d_productionorderauth.poa_id', 'd_productionorderauth.poa_date', 'm_supplier.s_name', 'd_productionorderauth.poa_nota')
                ->join('m_supplier', function ($q){
                    $q->on('d_productionorderauth.poa_supplier', '=', 'm_supplier.s_id');
                })->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function($data){
                return date('d-m-Y', strtotime($data->poa_date));
            })
            ->addColumn('supplier', function($data){
                return $data->s_name;
            })
            ->addColumn('nota', function($data){
                return $data->poa_nota;
            })
            ->addColumn('aksi', function($data){
                $detail = '<button class="btn btn-primary btn-modal" type="button" title="Detail Data" onclick="detailOrderProduksi(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-folder"></i></button>';
                $setujui = '<button class="btn btn-warning btn-edit" type="button" title="Setujui" onclick="agree(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-check"></i></button>';
                $tolak = '<button class="btn btn-danger btn-disable" type="button" title="Tolak" onclick="rejected(\''. Crypt::encrypt($data->poa_id) .'\')"><i class="fa fa-remove"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $setujui . $tolak . '</div>';
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

    public function agree($id = null) {
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
        }catch (\Exception $e){
            DB::commit();
            return response()->json(['status'=>'Failed']);
        }
    }

    public function rejected($id = null) {
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
        }catch (\Exception $e){
            DB::commit();
            return response()->json(['status'=>'Failed']);
        }
    }
//    ================End Order Produksi===============

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
                    return $data->pcad_rangeqtystart . '-' . $data->pcad_rangeqtyend . ' ' . $data->u_name;
                }
            })
            ->addColumn('aksi', function ($data){
                return '<div class="text-center"><div class="btn-group btn-group-sm">
												<button class="btn btn-info btn-detail" onclick="detail(\''.encrypt($data->pcad_classprice).'\',\'' .encrypt($data->pcad_detailid). '\')" type="button"><i class="fa fa-folder"></i></button>
												<button class="btn btn-success" type="button" onclick="approve(\''.encrypt($data->pcad_classprice).'\',\'' .encrypt($data->pcad_detailid). '\')" title="Setuju"><i class="fa fa-check-circle"></i></button>
												<button class="btn btn-danger" type="button" onclick="reject(\''.encrypt($data->pcad_classprice).'\',\'' .encrypt($data->pcad_detailid). '\')" title="Tolak"><i class="fa fa-times-circle"></i></button>
											</div></div>';
            })
            ->editColumn('pcad_price', function ($data){
                $harga = (int)$data->pcad_price;
                return '<div class="text-right">Rp. ' . number_format($harga, '0', '', '.') .'</div>';
            })
            ->rawColumns(['aksi', 'pcad_price'])
            ->make(true);
    }

    public function approvePerubahanHarga($id, $detail)
    {

    }
}
