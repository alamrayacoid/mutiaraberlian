<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use Carbon\Carbon;
use CodeGenerator;
use Yajra\DataTables\DataTables;
use Crypt;
use Currency;

class ProduksiController extends Controller
{
    ////////////////////////////////////////////////////
    // Order Produksi
    public function removeCurrency($angka)
    {
        $angka = implode("", explode('Rp. ', $angka));
        $angka = implode("", explode('.', $angka));
        return $angka;
    }
    public function order_produksi()
    {
    	return view('produksi/orderproduksi/index');
    }
    public function detail_produksi()
    {
        $getDetail = DB::table('d_productionorderdt')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit', 'u_id', '=', 'pod_unit')
            ->select('i_code', 'i_name', 'pod_qty', 'u_name')
            ->get();
        
        return DataTables::of($getDetail)
            ->make(true);
    }
    public function get_history(Request $request){
        $data = '';
        $getData = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier');

        if($request->tglAwal != ""){
            $getData->where('po_date', '>=',Carbon::createFromFormat('d-m-Y', $request->tglAwal)->format('Y-m-d'));
        }
        if($request->tglAkhir != ""){
            $getData->where('po_date', '<=',Carbon::createFromFormat('d-m-Y', $request->tglAkhir)->format('Y-m-d'));
        }
        $data = $getData->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('detail', function($data){
                return '<button class="btn btn-primary btn-modal" type="button" onclick="detail(\''. Crypt::encrypt($data->po_id) .'\')">Detail</button>';
            })
            ->addColumn('totalnet', function($data){
                return Currency::addRupiah($data->po_totalnet);
            })
            ->addColumn('bayar', function($data){
                return Currency::addRupiah(0);
            })
            ->addColumn('status', function($data){
                if($data->po_status == 'BELUM'){
                    return 'BELUM LUNAS';
                }else{
                    return '';
                }
            })
            ->addColumn('aksi', function($data){
                $edit = '<button class="btn btn-warning btn-edit" type="button" title="Edit Data" onclick="edit(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-pencil"></i></button>';
                $hapus = '<button class="btn btn-danger btn-disable" type="button" title="Hapus Data" onclick="hapus(\''. Crypt::encrypt($data->po_id) .'\')"><i class="fa fa-times-circle"></i></button>';
                return '<div class="btn-group btn-group-sm">' . $edit . '&nbsp;' . $hapus . '</div>';
            })
            ->rawColumns(['detail','totalnet','bayar','aksi'])
            ->make(true);
    }
    public function create_produksi(Request $request)
    {
        if (!$request->isMethod('post')) {
            $suppliers = DB::table('m_supplier')
                ->select('s_id', 's_company')
                ->get();

            $units = DB::table('m_unit')->get();
            return view('produksi/orderproduksi/create')->with(compact('suppliers', 'units'));
        } else {
            $data = $request->all();
            $productionorder = [];
            $productionorderdt = [];
            $productionorderpayment = [];
            DB::beginTransaction();
            try{
                // dd($request);
                $idpo= (DB::table('d_productionorder')->max('po_id')) ? (DB::table('d_productionorder')->max('po_id')) + 1 : 1;
                $nota = CodeGenerator::codeWithSeparator('d_productionorder', 'po_nota', 8, 10, 3, 'PO', '-');
                $productionorder = [
                    'po_id' => $idpo,
                    'po_nota' => $nota,
                    'po_date' => date('Y-m-d', strtotime($data['po_date'])),
                    'po_supplier' => $data['supplier'],
                    'po_totalnet' => $data['tot_hrg'],
                    'po_status' => 'BELUM'
                ];

                $poddetail = (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) ? (DB::table('d_productionorderdt')->where('pod_productionorder', '=', $idpo)->max('pod_detailid')) + 1 : 1;
                $detailpod = $poddetail;
                for ($i = 0; $i < count($data['idItem']); $i++) {
                    $productionorderdt[] = [
                        'pod_productionorder' => $idpo,
                        'pod_detailid' => $detailpod,
                        'pod_item' => $data['idItem'][$i],
                        'pod_qty' => $data['jumlah'][$i],
                        'pod_unit' => $data['satuan'][$i],
                        'pod_value' => $this->removeCurrency($data['harga'][$i]),
                        'pod_totalnet' => $this->removeCurrency($data['subtotal'][$i])
                    ];
                    $detailpod++;
                }

                for ($i = 0; $i < count($data['termin']); $i++) {
                    $productionorderpayment[] = [
                        'pop_productionorder' => $idpo,
                        'pop_termin' => $data['termin'][$i],
                        'pop_datetop' => date('Y-m-d', strtotime($data['estimasi'][$i])),
                        'pop_value' => $this->removeCurrency($data['nominal'][$i]),
                    ];
                }

                // dd($productionorderpayment);
                DB::table('d_productionorder')->insert($productionorder);
                DB::table('d_productionorderdt')->insert($productionorderdt);
                DB::table('d_productionorderpayment')->insert($productionorderpayment);
                DB::commit();
                return json_encode([
                    'status' => 'sukses'
                ]);
            }catch (\Exception $e){
                DB::rollBack();
                return json_encode([
                    'status' => 'gagal',
                    'msg' => $e
                ]);
            }
        }
    }
    public function edit_produksi(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        $dataEdit = DB::table('d_productionorder')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->where('po_id', $id)->first();
        $dataEditDT = DB::table('d_productionorderdt')
            ->join('m_item', 'i_id', '=', 'pod_item')
            ->join('m_unit', 'u_id', '=', 'pod_unit')
            ->where('pod_productionorder', $id)->get();
        $dataEditPmt = DB::table('d_productionorderpayment')->where('pop_productionorder', $id)->get();

        return view('produksi/orderproduksi/edit')->with(compact('dataEdit', 'dataEditDT', 'dataEditPmt'));
    }
    public function cariBarang(Request $request)
    {
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        if(count($is_item) == 0){
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }else{
            $nama = DB::table('m_item')
                ->join('d_itemsupplier', 'is_item', '=', 'i_id')
                ->whereNotIn('i_id', $is_item)
                ->where('is_supplier', $request->supp)
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }
    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x){
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y){
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z){
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }
    public function hapus_produksi($id){
        $id = Crypt::decrypt($id);
        DB::beginTransaction();
        try {
            DB::table('d_productionorderpayment')->where('pop_productionorder', $id)->delete();
            DB::table('d_productionorderdt')->where('pod_productionorder', $id)->delete();
            DB::table('d_productionorder')->where('po_id', $id)->delete();

            DB::commit();
            return json_encode([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode([
                'status' => 'gagal',
                'msg' => $e
            ]);
        }
    }

    /////////////////////////////////////////////////////
    // Penerimaan Barang


    /////////////////////////////////////////////////////
    // Pembayaran
    public function pembayaran()
    {
    	return view('produksi/pembayaran/index');
    }

    /////////////////////////////////////////////////////
    // Return Produksi
    public function return_produksi()
    {
    	return view('produksi/returnproduksi/index');
    }
    public function create_return_produksi()
    {
        return view('produksi/returnproduksi/create');
    }
}
