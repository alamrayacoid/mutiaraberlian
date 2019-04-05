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
use Mutasi;
use Carbon\Carbon;
use Mockery\Exception;
use Response;

class MarketingController extends Controller
{
    public function marketing()
    {
    	return view('marketing/manajemenmarketing/index');
    }
    
    public function year_promotion_create()
    {
        return view('marketing/manajemenmarketing/tahunan/create');
    }

    public function year_promotion_edit()
    {
        return view('marketing/manajemenmarketing/tahunan/edit');
    }

    public function month_promotion_create()
    {
        return view('marketing/manajemenmarketing/bulanan/create');
    }

    public function month_promotion_edit()
    {
        return view('marketing/manajemenmarketing/bulanan/edit');
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

    public function getProv()
    {
        $prov = DB::table('m_wil_provinsi')->get();
        return Response::json($prov);
    }

    public function getKota($idprov = null)
    {
        $kota = DB::table('m_wil_kota')->where('wc_provinsi', $idprov)->get();
        return Response::json($kota);
    }

    public function cariKonsigner(Request $request, $prov = null, $kota = null)
    {
        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('m_agen.a_provinsi', '=', $prov)
            ->where('m_agen.a_kabupaten', '=', $kota)
            ->where('m_company.c_type', '=', 'AGEN')
            ->where(function ($q) use ($cari){
                $q->orWhere('a_name', 'like', '%'.$cari.'%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->c_id, 'label' => strtoupper($query->a_name), 'data' => $query, 'kode' => $query->a_code];
            }
        }
        return Response::json($results);
    }

    public function cariBarangKonsinyasi(Request $request)
    {
        $is_item = array();
        for($i = 0; $i < count($request->idItem); $i++){
            if($request->idItem[$i] != null){
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        $comp = Auth::user()->u_company;
        if(count($is_item) == 0){
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_comp', '=', $comp);
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->where(function ($q) use ($cari){
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                    $q->orWhere('i_code', 'like', '%'.$cari.'%');
                })
                ->get();
        }else{
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_comp', '=', $comp);
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->whereNotIn('i_id', $is_item)
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
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query, 'stock' => $query->s_id];
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

    public function checkStock($stock = null, $item = null, $satuan = null, $qty = null)
    {
        $data_check = DB::table('m_item')
            ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
            ->where('i_id', '=', $item)
            ->first();

        $data = DB::table('d_stock')
            ->join('d_stock_mutation', function($sm){
                $sm->on('sm_stock', '=', 's_id');
            })
            ->where('s_id', '=', $stock)
            ->where('s_item', '=', $item)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->select('sm_residue as sisa')
            ->first();

        $qty_compare = 0;
        if ($satuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa) {
                $qty_compare = $data->sisa;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit2) {
            $compare = (int)$qty * (int)$data_check->compare2;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa/(int)$data_check->compare2;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa/(int)$data_check->compare3;
            } else {
                $qty_compare = $qty;
            }
        }
        return Response::json($qty_compare);
    }

    public function add_penempatanproduk(Request $request)
    {
        $data   = $request->all();
        $comp   = Auth::user()->u_company;
        $member = $data['kodeKonsigner'];
        $user   = Auth::user()->u_id;
        $type   = 'K';
        $date   = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $total  = $data['tot_hrg'];
        $insert = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota   = CodeGenerator::codeWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PK', '-');
        $idSales= (DB::table('d_sales')->max('s_id')) ? DB::table('d_sales')->max('s_id') + 1 : 1;

        DB::beginTransaction();
        try{
            $val_sales = [
                's_id'      => $idSales,
                's_comp'    => $comp,
                's_member'  => $member,
                's_type'    => $type,
                's_date'    => $date,
                's_nota'    => $nota,
                's_total'   => $total,
                's_user'    => $user,
                's_insert'  => $insert,
                's_update'  => $update
            ];

            $sddetail = (DB::table('d_salesdt')->where('sd_sales', '=', $idSales)->max('sd_detailid')) ? (DB::table('d_salesdt')->where('sd_sales', '=', $idSales)->max('sd_detailid')) + 1 : 1;
            $detailsd = $sddetail;
            $val_salesdt = [];
            for ($i = 0; $i < count($data['idItem']); $i++) {
                $val_salesdt[] = [
                    'sd_sales' => $idSales,
                    'sd_detailid' => $detailsd,
                    'sd_comp' => $comp,
                    'sd_item' => $data['idItem'][$i],
                    'sd_qty' => $data['jumlah'][$i],
                    'sd_unit' => $data['satuan'][$i],
                    'sd_value' => Currency::removeRupiah($data['harga'][$i]),
                    'sd_discpersen' => 0,
                    'sd_discvalue' => 0,
                    'sd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
                ];
                $detailsd++;

                //mutasi
                $data_check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                        'm_item.i_unit3 as unit3')
                    ->where('i_id', '=', $data['idItem'][$i])
                    ->first();

                $qty_compare = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                }

                $stock = DB::table('d_stock')
                    ->where('s_id', '=', $data['idStock'][$i])
                    ->where('s_comp', '=', $comp)
                    ->where('s_position', '=', $comp)
                    ->where('s_item', '=', $data['idItem'][$i])
                    ->where('s_status', '=', 'ON DESTINATION')
                    ->where('s_condition', '=', 'FINE')
                    ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $stock->s_id)
                    ->first();

                $posisi = DB::table('m_company')
                    ->where('c_user', '=', $member)
                    ->first();

                Mutasi::mutasikeluar(13, $comp, $comp, $data['idItem'][$i], $qty_compare, $nota);
                Mutasi::mutasimasuk(12, $posisi->c_id, $posisi->c_id, $data['idItem'][$i], $qty_compare, 'ON DESTINATION', 'FINE', $stock_mutasi->sm_hpp, $stock_mutasi->sm_sell, $nota, $stock_mutasi->sm_nota);
            }

            DB::table('d_sales')->insert($val_sales);
            DB::table('d_salesdt')->insert($val_salesdt);
            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil disimpan"
            ]);
        }catch (Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }
    }

    public function detailKonsinyasi($id = null, $action = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }

        if ($action == "detail") {
            $detail = DB::table('d_sales')
                ->where('d_sales.s_id', '=', $id)
                ->join('m_company', function ($c){
                    $c->on('m_company.c_user', '=', 'd_sales.s_member');
                })
                ->join('m_agen', function ($a){
                    $a->on('m_agen.a_code', '=', 'm_company.c_user');
                })
                ->join('m_wil_provinsi', function ($p){
                    $p->on('m_wil_provinsi.wp_id', '=', 'm_agen.a_provinsi');
                })
                ->join('m_wil_kota', function ($k){
                    $k->on('m_wil_kota.wc_id', '=', 'm_agen.a_kabupaten');
                })
                ->select(DB::raw('DATE_FORMAT(s_date, "%d-%m-%Y") AS tanggal'),
                    DB::raw("CONCAT(m_wil_provinsi.wp_name, ' - ', m_wil_kota.wc_name) as area"),
                    'd_sales.s_nota as nota', 'm_company.c_name as konsigner', 'd_sales.s_type as tipe',
                    DB::raw("CONCAT('Rp. ',FORMAT(d_sales.s_total, 0, 'de_DE')) as total"))
                ->first();

            return Response::json($detail);
        } else {
            $data = DB::table('d_sales')
                ->where('s_id', '=', $id)
                ->join('d_salesdt', function ($sd){
                    $sd->on('sd_sales', '=', 's_id');
                })
                ->join('m_item', function ($i){
                    $i->on('i_id', '=', 'sd_item');
                })
                ->join('m_unit', function ($u){
                    $u->on('u_id', '=', 'sd_unit');
                })
                ->select('i_name as barang',
                    DB::raw("CONCAT(sd_qty, ' - ', u_name) as jumlah"),
                    DB::raw("CONCAT('Rp. ',FORMAT(sd_value, 0, 'de_DE')) as harga"),
                    DB::raw("CONCAT('Rp. ',FORMAT(sd_totalnet, 0, 'de_DE')) as total_harga"));

            return DataTables::of($data)
                ->addColumn('barang', function($data){
                    return $data->barang;
                })
                ->addColumn('jumlah', function($data){
                    return $data->jumlah;
                })
                ->addColumn('harga', function($data){
                    return $data->harga;
                })
                ->addColumn('total_harga', function($data){
                    return $data->total_harga;
                })
                ->rawColumns(['barang','jumlah', 'harga', 'total_harga'])
                ->make(true);
        }
    }

    public function getKonsinyasi()
    {
        $data = DB::table('d_sales')
            ->join('d_salesdt', function ($sd){
                $sd->on('sd_sales', '=', 's_id');
            })
            ->join('m_company', 'c_user', '=', 's_member')
            ->where('s_type', '=', 'K')
            ->select('s_id as id', 's_date as tanggal', 's_nota as nota', 'c_name as konsigner', DB::raw("CONCAT('Rp. ',FORMAT(s_total, 0, 'de_DE')) as total"));

        return DataTables::of($data)
            ->addColumn('tanggal', function($data){
                return date('d-m-Y', strtotime($data->tanggal));
            })
            ->addColumn('nota', function($data){
                return $data->nota;
            })
            ->addColumn('konsigner', function($data){
                return $data->konsigner;
            })
            ->addColumn('total', function($data){
                return $data->total;
            })
            ->addColumn('action', function($data){
                $detail = '<button class="btn btn-primary" type="button" title="Detail" onclick="detailKonsinyasi(\''.Crypt::encrypt($data->id).'\')"><i class="fa fa-folder"></i></button>';
                $edit = '<button class="btn btn-warning" type="button" title="Edit" onclick="editKonsinyasi(\''.Crypt::encrypt($data->id).'\')"><i class="fa fa-pencil"></i></button>';
                $delete = '<button class="btn btn-danger" type="button" title="Hapus"><i class="fa fa-trash"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $edit . $delete . '</div>';
            })
            ->rawColumns(['tanggal','nota', 'konsigner', 'total','action'])
            ->make(true);
    }

    public function konsinyasipusat()
    {
    	return view('marketing/konsinyasipusat/index');
    }

    public function create_penempatanproduk()
    {
        return view('marketing/konsinyasipusat/penempatanproduk/create');
    }

    public function edit_penempatanproduk(Request $request, $id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return abort(404);
        }

        if ($request->isMethod('post')) {
            $data   = $request->all();
            $comp   = Auth::user()->u_company;
            $member = $data['kodeKonsigner'];
            $user   = Auth::user()->u_id;
            $type   = 'K';
            $date   = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $total  = $data['tot_hrg'];
            $insert = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $nota   = $request->nota;
            $idSales= $data['idSales'];

            DB::beginTransaction();
            try{
                //Rollback mutasi
                $rollback_mutasi = Mutasi::rollback($request->nota);
                //end rollback mutasi

                //Reinsert

                //End reinsert

                DB::commit();
                dd($rollback_mutasi);
            }catch (Exception $e){
                DB::rollBack();
            }
        } else {
            $detail = DB::table('d_sales')
                ->where('d_sales.s_id', '=', $id)
                ->join('m_company', function ($c){
                    $c->on('m_company.c_user', '=', 'd_sales.s_member');
                })
                ->join('m_agen', function ($a){
                    $a->on('m_agen.a_code', '=', 'm_company.c_user');
                })
                ->join('m_wil_provinsi', function ($p){
                    $p->on('m_wil_provinsi.wp_id', '=', 'm_agen.a_provinsi');
                })
                ->join('m_wil_kota', function ($k){
                    $k->on('m_wil_kota.wc_id', '=', 'm_agen.a_kabupaten');
                })
                ->first();

            $data_item = DB::table('d_sales')
                ->where('d_sales.s_id', '=', $id)
                ->join('d_salesdt', function ($sd){
                    $sd->on('d_salesdt.sd_sales', '=', 'd_sales.s_id');
                })
                ->join('m_item', function ($i){
                    $i->on('m_item.i_id', '=', 'd_salesdt.sd_item');
                })
                ->join('m_unit as a', function ($x){
                    $x->on('m_item.i_unit1', '=', 'a.u_id');
                })
                ->leftjoin('m_unit as b', function ($y){
                    $y->on('m_item.i_unit2', '=', 'b.u_id');
                })
                ->leftjoin('m_unit as c', function ($z){
                    $z->on('m_item.i_unit3', '=', 'c.u_id');
                })
                ->join('d_stock_mutation', function ($sm){
                    $sm->on('d_stock_mutation.sm_nota', '=', 'd_sales.s_nota');
                    $sm->where('d_stock_mutation.sm_mutcat', '=', 13);
                })
                ->join('d_stock', function ($s){
                    $s->on('d_stock.s_id', '=', 'd_stock_mutation.sm_stock');
                    $s->on('d_stock.s_item', '=', 'd_salesdt.sd_item');
                })
                ->select('d_salesdt.sd_item as itemId', 'd_salesdt.sd_unit as unit', 'd_salesdt.sd_qty as qty',
                    'd_salesdt.sd_value as harga', 'd_salesdt.sd_totalnet as totalnet', 'm_item.i_code as itemCode', 'm_item.i_name as item',
                    'd_stock_mutation.sm_stock as stock',
                    'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2',
                    'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
                ->get();

            $ids = Crypt::encrypt($id);

            return view('marketing/konsinyasipusat/penempatanproduk/edit')->with(compact('detail', 'data_item', 'ids'));
        }
    }
}
