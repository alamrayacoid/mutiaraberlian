<?php

namespace App\Http\Controllers;

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
                ->select('d_productionorderauth.poa_id', 'd_productionorderauth.poa_date', 'm_supplier.s_name', 'd_productionorderauth.poa_notatemp')
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
                return $data->poa_notatemp;
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
                return '<p class="text-right">'. Currency::addRupiah($data->pod_totalnet) .'</p><input type="hidden" class="totalnet" value="'.number_format($data->pod_totalnet,0,'','').'">';
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
            ->get();
        dd($data);
    }
}
