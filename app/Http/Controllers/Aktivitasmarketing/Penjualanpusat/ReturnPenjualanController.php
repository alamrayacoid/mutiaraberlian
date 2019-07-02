<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\d_salescomp;
use App\d_salescompcode;
use App\m_company;
use App\m_wil_provinsi;
use App\m_wil_kota;
use Carbon\Carbon;

class ReturnPenjualanController extends Controller
{
    public function index()
    {
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

    public function create()
    {
        $provinsi = m_wil_provinsi::get();
        return view('marketing/penjualanpusat/returnpenjualan/create', compact('provinsi'));
    }
    // get list city
    public function getCity(Request $request)
    {
        $provId = $request->provId;
        $city = m_wil_kota::select('wc_id', 'wc_name')
        ->where('wc_provinsi', '=', $provId)
        ->orderBy('wc_name', 'asc')
        ->get();

        return response()->json(array(
            'success' => true,
            'data' => $city
        ));
    }
    // get branch
    public function getAgent(Request $request)
    {
        $cityId = $request->cityId;
        $agent = m_company::where('c_type', '!=', 'PUSAT')
        ->whereHas('getAgent', function ($q) use ($cityId) {
            $q->where('a_area', '=', $cityId);
        })
        ->get();

        // $branch = m_company::where('c_type', '!=', 'PUSAT')
        // ->where('c_area', '=', $cityId)
        // ->get();

        return response()->json(array(
            'success' => true,
            'data' => $agent
        ));
    }
    // get production-code
    public function getProdCode(Request $request)
    {
        $agentCode = $request->agentCode;
        $term = $request->term;

        // get list salescomp-id by agent
        $salesComp = d_salescomp::where('sc_member', $agentCode)->select('sc_id')->get();
        $listSalesCompId = array();
        foreach ($salesComp as $key => $val) {
            array_push($listSalesCompId, $val->sc_id);
        }

        $prodCode = d_salescompcode::where('ssc_code', 'like', '%'. $term .'%')
        ->whereIn('ssc_salescomp', $listSalesCompId)
        ->get();
        // dd($prodCode, $listSalesCompId, $request->all());

        // if (count($prodCode) == 0) {
        //     $results[] = [
        //         'id' => 0,
        //         'label' => 'Kode produksi tidak ditemukan !'
        //     ];
        // }
        // else {
        //     foreach ($prodCode as $key => $val) {
        //         $results[] = [
        //             'id' => $val->ssc_code,
        //             'label' => $val->ssc_code
        //         ];
        //     }
        // }

        return response()->json($prodCode);
    }
    // get list nota based on production-code
    public function getNota(Request $request)
    {
        $prodCode = $request->prodCode;
        $listNota = d_salescompcode::where('ssc_code', 'like', '%'. $prodCode .'%')
        ->groupBy('ssc_salescomp')
        ->with(['getSalesCompById' => function($q) {
            // $q->select('sc_nota');
        }])
        ->get();

        // $nota = DB::table('d_salescompcode')
        //             ->join('d_salescomp', 'sc_id', '=', 'ssc_salescomp')
        //             ->where('ssc_code', $request->kodeproduksi)
        //             ->get();
        // dd($listNota);
        return response()->json($listNota);
    }
    // get sales-comp data
    public function getData(Request $request)
    {
        $nota = $request->nota;
        $itemId = $request->itemId;

        $data = d_salescomp::where('sc_nota', $request->nota)
            ->with('getComp')
            ->with('getAgent')
            ->with(['getSalesCompDt' => function($q) use ($itemId) {
                $q
                    ->where('scd_item', $itemId)
                    ->with('getItem');
            }])
            ->first();

        // $comp = DB::table('m_company')
        //             ->where('c_id', $data->sc_comp)
        //             ->first();

        // $agen = DB::table('m_company')
        //             ->where('c_id', $data->sc_member)
        //             ->first();

        // $item = DB::table('d_salescompdt')
        //             ->join('m_item', 'i_id', '=', 'scd_item')
        //             ->where('scd_sales', $data->sc_id)
        //             ->where('scd_item', $request->itemid)
        //             ->first();

        $data->sc_date = Carbon::parse($data->sc_date)->format('d-m-Y');

        $data->sc_total = number_format($data->sc_total,2,",",".");

        return response()->json([
            'data' => $data
            // 'comp' => $comp,
            // 'agen' => $agen,
            // 'item' => $item
        ]);
    }

    public function store(Request $request)
    {
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

    public function delete(Request $request)
    {
        $data = DB::table('d_return')
        ->where('r_id', $request->id)
        ->first();

        DB::table('d_return')
        ->where('r_id', $request->id)
        ->delete();

        mutasi::rollbackStockMutDist($data->r_nota, $data->r_item, 3);

        return response()->json(['status' => 'berhasil']);
    }

}
