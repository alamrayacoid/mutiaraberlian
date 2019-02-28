<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use Carbon\Carbon;

class ProduksiController extends Controller
{
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
    public function create_produksi(Request $request)
    {
        if (!$request->isMethod('post')) {
            $suppliers = DB::table('m_supplier')
                ->select('s_id', 's_name')
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
                    if ($data['satuan'][$i] == 1) {
                        $qty = $data['jumlah'][$i];
                    } else if ($data['satuan'][$i] == 2) {
                        $getCompare = DB::table('m_item')
                            ->where('i_id', '=', $data['idItem'][$i])
                            ->first();
                        $qty = $data['jumlah'][$i] * $getCompare->i_unitcompare1;
                    } else if ($data['satuan'][$i] == 3) {
                        $getCompare = DB::table('m_item')
                            ->where('i_id', '=', $data['idItem'][$i])
                            ->first();
                        $qty = $data['jumlah'][$i] * $getCompare->i_unitcompare1;
                    }

                    $productionorderdt[] = [
                        'pod_productionorder' => $idpo,
                        'pod_detailid' => $detailpod,
                        'pod_item' => $data['idItem'][$i],
                        'pod_qty' => $qty,
                        'pod_value' => $this->removeCurrency($data['harga'][$i]),
                        'pod_totalnet' => $this->removeCurrency($data['subtotal'][$i])
                    ];
                    $detailpod++;
                }

                for ($i = 0; $i < count($data['termin']); $i++) {
                    $productionorderpayment[] = [
                        'pop_productionorder' => $idpo,
                        'pop_termin' => $data['termin'][$i],
                        'pop_estimasi' => date('Y-m-d', strtotime($data['estimasi'][$i])),
                        'pop_date' => date('Y-m-d', strtotime($data['tanggal'][$i])),
                        'pop_value' => $this->removeCurrency($data['nominal'][$i]),
                    ];
                }
                DB::table('d_productionorder')->insert($productionorder);
                DB::table('d_productionorderdt')->insert($productionorderdt);
                DB::table('d_productionorderpayment')->insert($productionorderpayment);
                DB::commit();
                return response()->json([
                    'status' => 'sukses'
                ]);
            }catch (\Exception $e){
                DB::rollBack();
//                return $e;
                return response()->json([
                    'status' => 'gagal'
                ]);
            }
        }
    }

    public function cariBarang(Request $request)
    {
        $cari = $request->term;
        $results = [];
        $kode = [];
        if (isset($request->kode)) {
            $kode = $request->kode;
            if (($key = array_search(null, $kode)) !== false) {
                unset($kode[$key]);
            }
            $temp = [];
            foreach ($kode as $code){
                array_push($temp, $code);
            }
            $kode = $temp;
        }

        if (count($kode) > 0) {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari, $kode){
//                    $q->whereNotIn('i_code', $kode);
                    $q->where('i_code', 'like', '%'.$cari.'%');
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                })
                ->whereNotIn('i_code', $kode)->get();
        } else {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari){
                    $q->where('i_code', 'like', '%'.$cari.'%');
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                })->get();
        }

        if (count($nama) < 1) {
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

    public function edit_produksi()
    {
        return view('produksi/orderproduksi/edit');
    }
    public function penerimaan_barang()
    {
    	return view('produksi/penerimaanbarang/index');
    }
    public function create_penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/create');
    }    
    public function pembayaran()
    {
    	return view('produksi/pembayaran/index');
    }
    public function return_produksi()
    {
    	return view('produksi/returnproduksi/index');
    }
    public function create_return_produksi()
    {
        return view('produksi/returnproduksi/create');
    }
}
