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
use App\m_agen;
use App\m_company;
use App\m_wil_provinsi;
use App\d_salescomp;
use App\d_salescomppayment;
use Validator;

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
                            </div></center>';
                }
            })
            ->rawColumns(['p_isapproved', 'action', 'p_budget'])
            ->make(true);
    }

    public function month_promotion_save(Request $request)
    {
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

    public function carikonsignerselect2($prov = null, $kota = null)
    {
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('m_agen.a_provinsi', '=', $prov)
            ->where('m_agen.a_kabupaten', '=', $kota)
            ->where('m_company.c_type', '=', 'AGEN')
            ->get();

        return Response::json($nama);
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
                ->groupBy('d_stock.s_id')
                ->get();
        }else{
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp){
                    $s->on('i_id', '=', 's_item');
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
                ->groupBy('d_stock.s_id')
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
        return Response::json(floor($qty_compare));
    }

    public function checkStockOld($stock = null, $item = null, $oldSatuan = null, $satuan = null, $qtyOld = null, $qty = null)
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

        $qty_compare_old = 0;
        if ($oldSatuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa) {
                $qty_compare_old = $data->sisa + $qtyOld;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit2) {
            $compare = (int)$qty * (int)$data_check->compare2;
            if ((int)$compare > (int)($data->sisa + $qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare2;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)($data->sisa+$qtyOld)) {
                $qty_compare_old = (int)($data->sisa+$qtyOld)/(int)$data_check->compare3;
            } else {
                $qty_compare_old = $qty;
            }
        }


        return Response::json(floor($qty_compare_old));
    }

    function existsInArray($entry, $array)
    {
        $x = false;
        foreach ($array as $compare) {
            if ($compare->pcd_type == $entry) {
                $x = true;
            }
        }
        return $x;
    }

    function inRange($value, $array)
    {
//        in_array($request->rangestartedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend));
        $idx = null;
        foreach ($array as $key =>  $val) {
            $x = in_array($value, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend));
            if ($x == true) {
                $idx = $key;
                break;
            }
        }
        return $idx;
    }

    public function checkHarga($konsigner, $item, $unit, $qty)
    {

        $type = DB::table('m_agen')
            ->where('a_code', '=', $konsigner)
            ->first();

        $get_price = DB::table('m_priceclassdt')
            ->join('m_priceclass', 'pcd_classprice', 'pc_id')
            ->select('m_priceclassdt.*', 'm_priceclass.*')
            ->where('pc_id', '=', $type->a_class)
            ->where('pcd_payment', '=', 'K')
            ->where('pcd_item', '=', $item)
            ->where('pcd_unit', '=', $unit)
            ->get();

        $harga = 0;
        $z = false;
        foreach ($get_price as $key => $price) {
            if ($qty == 1) {
                if ($this->existsInArray("U", $get_price) == true) {
                    if ($get_price[$key]->pcd_type == "U") {
                        $harga = $get_price[$key]->pcd_price;
                    }
                } else {
                    if ($price->pcd_rangeqtystart == 1) {
                        $harga = $get_price[$key]->pcd_price;
                    }
                }
            } else if ($qty > 1) {
                if ($price->pcd_rangeqtyend == 0){
                    if ($qty >= $price->pcd_rangeqtystart) {
                        $harga = $price->pcd_price;
                    }
                } else {
                    $z = $this->inRange($qty, $get_price);
                    if ($z !== null) {
                        $harga = $get_price[$z]->pcd_price;
                    }
                }

            }
        }

        return Response::json(number_format($harga, 0, '', ''));
    }

    public function add_penempatanproduk(Request $request)
    {
        $data   = $request->all();
        $comp   = Auth::user()->u_company;
//        $member = $data['kodeKonsigner'];
        $member = $data['idKonsigner'];
        $user   = Auth::user()->u_id;
        $type   = 'K';
        $date   = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $total  = $data['tot_hrg'];
        $insert = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota   = CodeGenerator::codeWithSeparator('d_salescomp', 'sc_nota', 8, 10, 3, 'SK', '-');
        $idSales= (DB::table('d_salescomp')->max('sc_id')) ? DB::table('d_salescomp')->max('sc_id') + 1 : 1;

        DB::beginTransaction();
        try{
            $val_sales = [
                'sc_id'      => $idSales,
                'sc_comp'    => $comp,
                'sc_member'  => $member,
                'sc_type'    => $type,
                'sc_date'    => $date,
                'sc_nota'    => $nota,
                'sc_total'   => $total,
                'sc_user'    => $user,
                'sc_insert'  => $insert,
                'sc_update'  => $update
            ];

            $sddetail = (DB::table('d_salescompdt')->where('scd_sales', '=', $idSales)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $idSales)->max('sd_detailid')) + 1 : 1;
            $detailsd = $sddetail;
            $val_salesdt = [];
            for ($i = 0; $i < count($data['idItem']); $i++) {
                $val_salesdt[] = [
                    'scd_sales' => $idSales,
                    'scd_detailid' => $detailsd,
                    'scd_comp' => $comp,
                    'scd_item' => $data['idItem'][$i],
                    'scd_qty' => $data['jumlah'][$i],
                    'scd_unit' => $data['satuan'][$i],
                    'scd_value' => Currency::removeRupiah($data['harga'][$i]),
                    'scd_discpersen' => 0,
                    'scd_discvalue' => 0,
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
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
                    ->where('s_position', '=', $comp)
                    ->where('s_item', '=', $data['idItem'][$i])
                    ->where('s_status', '=', 'ON DESTINATION')
                    ->where('s_condition', '=', 'FINE')
                    ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $stock->s_id)
                    ->first();

                $posisi = DB::table('m_company')
                    ->where('c_id', '=', $member)
                    ->first();

                Mutasi::mutasikeluartanpapemilik(13, $comp, $data['idItem'][$i], $qty_compare, $nota);
                Mutasi::mutasimasuk(12, $stock->s_comp, $posisi->c_id, $data['idItem'][$i], $qty_compare, 'ON DESTINATION', 'FINE', $stock_mutasi->sm_hpp, $stock_mutasi->sm_sell, $nota, $stock_mutasi->sm_nota);
            }

            DB::table('d_salescomp')->insert($val_sales);
            DB::table('d_salescompdt')->insert($val_salesdt);
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
            $detail = DB::table('d_salescomp')
                ->where('d_salescomp.sc_id', '=', $id)
                ->join('m_company', function ($c){
                    $c->on('m_company.c_id', '=', 'd_salescomp.sc_member');
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
                ->select(DB::raw('DATE_FORMAT(sc_date, "%d-%m-%Y") AS tanggal'),
                    DB::raw("CONCAT(m_wil_provinsi.wp_name, ' - ', m_wil_kota.wc_name) as area"),
                    'd_salescomp.sc_nota as nota', 'm_company.c_name as konsigner', 'd_salescomp.sc_type as tipe',
                    DB::raw("CONCAT('Rp. ',FORMAT(d_salescomp.sc_total, 0, 'de_DE')) as total"))
                ->first();

            return Response::json($detail);
        } else {
            $data = DB::table('d_salescomp')
                ->where('sc_id', '=', $id)
                ->join('d_salescompdt', function ($sd){
                    $sd->on('scd_sales', '=', 'sc_id');
                })
                ->join('m_item', function ($i){
                    $i->on('i_id', '=', 'scd_item');
                })
                ->join('m_unit', function ($u){
                    $u->on('u_id', '=', 'scd_unit');
                })
                ->select('i_name as barang',
                    DB::raw("CONCAT(scd_qty, ' - ', u_name) as jumlah"),
                    DB::raw("CONCAT('Rp. ',FORMAT(scd_value, 0, 'de_DE')) as harga"),
                    DB::raw("CONCAT('Rp. ',FORMAT(scd_totalnet, 0, 'de_DE')) as total_harga"));

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
        $data = DB::table('d_salescomp')
            ->join('d_salescompdt', function ($sd){
                $sd->on('scd_sales', '=', 'sc_id');
            })
            // changed from c_user to c_id --> rowi
            ->join('m_company', 'c_id', '=', 'sc_member')
            ->where('sc_type', '=', 'K')
            ->groupBy('d_salescomp.sc_nota')
            ->select('sc_id as id', 'sc_date as tanggal', 'sc_nota as nota', 'c_name as konsigner', DB::raw("CONCAT('Rp. ',FORMAT(sc_total, 0, 'de_DE')) as total"));

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
                $delete = '<button class="btn btn-danger" type="button" title="Hapus" onclick="hapusKonsinyasi(\''.Crypt::encrypt($data->id).'\', \''.$data->nota.'\')"><i class="fa fa-trash"></i></button>';
                return '<div class="btn-group btn-group-sm">'. $detail . $edit . $delete . '</div>';
            })
            ->rawColumns(['tanggal','nota', 'konsigner', 'total','action'])
            ->make(true);
    }

    public function konsinyasipusat()
    {
        $provinsi = DB::table('m_wil_provinsi')
        ->get();

    	return view('marketing/konsinyasipusat/index', compact('provinsi'));
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
//            $member = $data['kodeKonsigner'];
            $member = $data['idKonsigner'];
            $user   = Auth::user()->u_id;
            $total  = $data['tot_hrg'];
            $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $nota   = $data['nota'];

            DB::beginTransaction();
            try{
                //Rollback mutasi
                $rollback_mutasi = Mutasi::rollback($request->nota); //return true/false(error)
                //end rollback mutasi

                //Reinsert
                if ($rollback_mutasi == true){
                    //hapus salesdt
                    DB::table('d_salescompdt')
                        ->where('scd_sales', '=', $id)
                        ->delete();

                    $val_sales = [
                        'sc_comp'    => $comp,
                        'sc_member'  => $member,
                        'sc_total'   => $total,
                        'sc_user'    => $user,
                        'sc_update'  => $update
                    ];

                    //Update d_sales
                    DB::table('d_salescomp')
                        ->where('sc_id', '=', $id)
                        ->update($val_sales);

                    $sddetail = (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) + 1 : 1;
                    $detailsd = $sddetail;
                    $val_salesdt = [];
                    for ($i = 0; $i < count($data['idItem']); $i++) {
                        $val_salesdt[] = [
                            'scd_sales' => $id,
                            'scd_detailid' => $detailsd,
                            'scd_comp' => $comp,
                            'scd_item' => $data['idItem'][$i],
                            'scd_qty' => $data['jumlah'][$i],
                            'scd_unit' => $data['satuan'][$i],
                            'scd_value' => Currency::removeRupiah($data['harga'][$i]),
                            'scd_discpersen' => 0,
                            'scd_discvalue' => 0,
                            'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
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
                            ->where('s_position', '=', $comp)
                            ->where('s_item', '=', $data['idItem'][$i])
                            ->where('s_status', '=', 'ON DESTINATION')
                            ->where('s_condition', '=', 'FINE')
                            ->first();

                        $stock_mutasi = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $stock->s_id)
                            ->first();

                        $posisi = DB::table('m_company')
                            ->where('c_id', '=', $member)
                            ->first();

                        Mutasi::mutasikeluar(13, $comp, $comp, $data['idItem'][$i], $qty_compare, $nota);
                        Mutasi::mutasimasuk(12, $posisi->c_id, $posisi->c_id, $data['idItem'][$i], $qty_compare, 'ON DESTINATION', 'FINE', $stock_mutasi->sm_hpp, $stock_mutasi->sm_sell, $nota, $stock_mutasi->sm_nota);
                    }

                    DB::table('d_salescompdt')->insert($val_salesdt);

                    DB::commit();
                    return Response::json([
                        'status' => "Success",
                        'message'=> "Data berhasil diperbarui"
                    ]);
                } else {
                    DB::rollBack();
                    return Response::json([
                        'status' => "Failed",
                        'message'=> $rollback_mutasi
                    ]);
                }
                //End reinsert

            }catch (Exception $e){
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> $e
                ]);
            }
        } else {
            $detail = DB::table('d_salescomp')
                ->where('d_salescomp.sc_id', '=', $id)
                ->join('m_company', function ($c){
                    $c->on('m_company.c_id', '=', 'd_salescomp.sc_member');
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

            $data_item = DB::table('d_salescomp')
                ->where('d_salescomp.sc_id', '=', $id)
                ->join('d_salescompdt', function ($sd){
                    $sd->on('d_salescompdt.scd_sales', '=', 'd_salescomp.sc_id');
                })
                ->join('m_item', function ($i){
                    $i->on('m_item.i_id', '=', 'd_salescompdt.scd_item');
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
                    $sm->on('d_stock_mutation.sm_nota', '=', 'd_salescomp.sc_nota');
                    $sm->where('d_stock_mutation.sm_mutcat', '=', 13);
                })
                ->join('d_stock', function ($s){
                    $s->on('d_stock.s_id', '=', 'd_stock_mutation.sm_stock');
                    $s->on('d_stock.s_item', '=', 'd_salescompdt.scd_item');
                })
                ->select('d_salescompdt.scd_item as itemId', 'd_salescompdt.scd_unit as unit', 'd_salescompdt.scd_qty as qty',
                    'd_salescompdt.scd_value as harga', 'd_salescompdt.scd_totalnet as totalnet', 'm_item.i_code as itemCode', 'm_item.i_name as item',
                    'd_stock_mutation.sm_stock as stock',
                    'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2',
                    'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
                ->get();

            $ids = Crypt::encrypt($id);

            return view('marketing/konsinyasipusat/penempatanproduk/edit')->with(compact('detail', 'data_item', 'ids'));
        }
    }

    public function deletePenempatanproduk(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }

        DB::beginTransaction();
        try{
            $rollback_mutasi = Mutasi::rollback($request->nota); //return true/false(error)

            if ($rollback_mutasi == true){
                DB::table('d_salescompdt')
                    ->where('scd_sales', '=', $id)
                    ->delete();
                DB::table('d_salescomp')
                    ->where('sc_id', '=', $id)
                    ->delete();

                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message'=> 'Data berhasil dihapus'
                ]);
            }else{
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> $rollback_mutasi
                ]);
            }

        }catch (Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }
    }

    // retrive data-table konsinyasi-monitoring-penjualan
    public function getListMP(Request $request)
    {
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        $datas = d_salescomp::whereBetween('sc_date', [$from, $to])
        ->where('sc_type', 'K')
        ->where('sc_paidoff', 'N')
        ->where('sc_member', $agentCode)
        ->with(['getMutation' => function($query) {
            $query->where('sm_mutcat', 12)->get();
        }])
        ->with(['getMutationReff'])
        ->with('getAgent')
        ->with('getSalesCompDt.getItem')
        ->orderBy('sc_date', 'desc')
        ->get();

        // get total-qty each salescomp
        foreach ($datas as $data) {
            $totalQty = 0;
            foreach ($data->getMutation as $mutation) {
                $totalQty += $mutation->sm_qty;
            }
            $data->totalQty = $totalQty;
        }
        // get sold-status each salescomp
        foreach ($datas as $data) {
            $totalSold = 0;
            foreach ($data->getMutation as $mutation) {
                $totalSold += ($mutation->sm_qty - $mutation->sm_residue);
            }
            $data->totalSold = $totalSold;
            $data->totalSoldPerc = $totalSold / $data->totalQty * 100;
        }
        // get sold-amount each salescomp
        foreach ($datas as $data) {
            $soldAmount = 0;
            foreach ($data->getMutationReff as $mutation) {
                $soldAmount += ($mutation->sm_qty * $mutation->sm_sell);
            }
            $data->soldAmount = $soldAmount;
        }

        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('placement', function($datas) {
            return $datas->getAgent->c_name;
        })
        ->addColumn('items', function($datas) {
            return '<div><button class="btn btn-primary" onclick="showDetailSalescomp('. $datas->sc_id .')" type="button"><i class="fa fa-folder"></i></button></div>';
        })
        ->addColumn('total_qty', function($datas) {
            return number_format($datas->totalQty, 0, ',', '.');
        })
        ->addColumn('total_price', function($datas) {
            return '<span class="pull-right">Rp '. number_format((int)$datas->sc_total, 2, ',', '.') .'</span>';
        })
        ->addColumn('sold_status', function($datas) {
            return '<span class="pull-right"> '. number_format($datas->totalSoldPerc, 0, ',', '.') .' %</span>';
        })
        ->addColumn('sold_amount', function($datas) {
            return '<span class="pull-right">Rp '. number_format((int)$datas->soldAmount, 2, ',', '.') .'</span>';
        })
        ->rawColumns(['placement', 'items', 'total_qty', 'total_price', 'sold_status', 'sold_amount'])
        ->make(true);
    }
    public function getSalesCompDetail($id)
    {
        $detail = d_salescomp::where('sc_id', $id)
        ->with('getAgent')
        ->with('getSalesCompDt.getItem')
        ->with('getSalesCompDt.getUnit')
        ->first();
        $detail->dateFormated = Carbon::parse($detail->sc_date)->format('d M Y');

        return response()->json($detail);
    }
    // get list-cities based on province-id
    public function getCitiesMP(Request $request)
    {
        $cities = m_wil_provinsi::where('wp_id', $request->provId)
        ->with('getCities')
        ->firstOrFail();
        return response()->json($cities);
    }
    // get list-agents based on citiy-id
    public function getAgentsMP(Request $request)
    {
        $agents = m_agen::where('a_area', $request->cityId)
        ->where('a_type', 'AGEN')
        ->with('getProvince')
        ->with('getCity')
        ->with('getCompany')
        ->orderBy('a_code', 'desc')
        ->get();

        return response()->json($agents);
    }
    // find agents and retrieve it by autocomple.js
    public function findAgentsByAu(Request $request)
    {
        $term = $request->termToFind;

        // startu query to find specific item
        $agents = m_company::where('c_name', 'like', '%'.$term.'%')
            ->orWhere('c_id', 'like', '%'.$term.'%')
            ->get();

        if (count($agents) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($agents as $agent) {
                $results[] = ['id' => $agent->c_id, 'label' => $agent->c_id . ' - ' .strtoupper($agent->c_name)];
            }
        }
        return response()->json($results);
    }
    // =========================================================================
    // Penerimaan Uang Pembayaran
    // get list-nota based on agent-code in Penerimaan-Uang-Pembayaran
    public function getListNotaPP(Request $request)
    {
        $agentCode = $request->agentCode;
        $listNota = d_salescomp::where('sc_member', $agentCode)
        ->where('sc_paidoff', 'N')
        ->select('sc_id', 'sc_nota')
        ->get();

        return $listNota;
    }
    public function getPaymentPP(Request $request)
    {
        $salescompId = $request->salescompId;
        $sales = d_salescomp::where('sc_id', $salescompId)
        ->with('getSalesCompPayment')
        ->first();
        $sales->paidBill = 0;
        $sales->restBill = $sales->sc_total;

        // return if getSalesCompPayment is empty
        if (sizeof($sales->getSalesCompPayment) < 1) {
            return $sales;
        }
        // count paidBill and restBill when getSalesCompPayment not null
        foreach ($sales->getSalesCompPayment as $payment)
        {
            $sales->paidBill += $payment->scp_pay;
        }
        $sales->restBill -= $sales->paidBill;
        return $sales;
    }
    // validate form before input
    public function validatePP(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'salescompId' => 'required',
            'paymentType' => 'required',
            'cashAccount' => 'required',
            'paymentVal' => 'required'
        ],
        [
            'salescompId.required' => 'Silahkan pilih \'Nota\' terlebih dahulu !',
            'paymentType.required' => 'Silahkan pilih \'Jenis Penerimaan\' terlebih dahulu !',
            'cashAccount.required' => 'Silahkan pilih \'Akun Kas\' terlebih dahulu !',
            'paymentVal.required' => '\'Nominal Penerimaan\' tidak boleh kosong !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }
    // store item
    public function storePP(Request $request)
    {
        // validate request
        $isValidRequest = $this->validatePP($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        // need to test first !!
        DB::beginTransaction();
        try {
            $detailId = d_salescomppayment::where('scp_salescomp', $request->salescompId)->max('scp_detailid') + 1;
            $salescompPayment = new d_salescomppayment();
            $salescompPayment->scp_salescomp = $request->salescompId;
            $salescompPayment->scp_detailid = $detailId;
            $salescompPayment->scp_date = Carbon::now();
            $salescompPayment->scp_pay = $request->paymentVal;
            $salescompPayment->save();

            // update salescomp to 'lunas'
            if ($request->restBill == $request->paymentVal) {
                $salescomp = d_salescomp::where('sc_id', $request->salescompId)->first();
                $salescomp->sc_paidoff = 'Y';
                $salescomp->save();
            }

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
}
