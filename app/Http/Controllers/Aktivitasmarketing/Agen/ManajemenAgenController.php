<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_productorder;
use App\d_sales;
use App\d_salescode;
use App\d_salesdt;
use App\d_salesprice;
use App\d_salesweb;
use App\d_stock;
use App\d_username;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_member;
use App\m_priceclass;
use App\m_wil_provinsi;
use App\m_wil_kota;
use DataTables;
use DB;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use Mockery\Exception;
use Mutasi;
use Response;
use Validator;
use Session;

class ManajemenAgenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

// oerder produk ke agen
    public function getPembeli($kode)
    {
        $data = m_agen::where('a_parent', $kode)
        ->get();

        return Response::json($data);

        // $data = DB::table('m_agen')
        //     ->join('m_company', 'a_code', '=', 'c_user')
        //     ->where('a_parent', '=', $kode)
        //     ->get();
        // return Response::json($data);
    }

    public function cariPembeli(Request $request, $kode)
    {
        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('a_parent', '=', $kode)
            ->where(function ($q) use ($cari) {
                $q->orWhere('a_name', 'like', '%' . $cari . '%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->a_id,
                    'label' => strtoupper($query->a_name),
                    'data' => $query,
                    'kode' => $query->a_code,
                    'comp' => $query->c_id
                ];
            }
        }
        return Response::json($results);
    }

    public function getPenjual($kota = null)
    {
        $data = m_agen::leftJoin('m_company', 'c_user', '=', 'a_code')
            ->where('m_agen.a_kabupaten', '=', $kota);

        if (Auth::user()->u_user == 'A') {
            if (Auth::user()->agen->a_type == 'AGEN') {
                $data = $data->where('a_code', Auth::user()->agen->a_code)->get();
            } elseif (Auth::user()->agen->a_type == 'SUB AGEN') {
                $data = $data->where('a_code', Auth::user()->agen->a_parent)->get();
            }
        } elseif (Auth::user()->u_user == 'E') {
            $data = $data->where('a_mma', Auth::user()->u_company)->get();
        }

        return Response::json($data);
    }

    public function cariPenjual(Request $request, $prov = null, $kota = null)
    {
        $cari = $request->term;
        $nama = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('m_agen.a_provinsi', '=', $prov)
            ->where('m_agen.a_kabupaten', '=', $kota)
            // ->where('m_company.c_type', '=', 'AGEN')
            ->where(function ($q) use ($cari) {
                $q->orWhere('a_name', 'like', '%' . $cari . '%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->a_id,
                    'label' => strtoupper($query->a_name),
                    'data' => $query,
                    'kode' => $query->a_code,
                    'comp' => $query->c_id
                ];
            }
        }
        return Response::json($results);
    }

    public function getProv()
    {
        // khusus tuban
        //$prov = DB::table('m_wil_provinsi')->where('wp_id', 35)->get();
        $prov = m_wil_provinsi::orderBy('wp_name', 'asc')
            ->get();
        return Response::json($prov);
    }

    public function getKota($idprov = null)
    {
        // khusus tuban
        //$kota = DB::table('m_wil_kota')->where('wc_provinsi', $idprov)->where('wc_id', 3523)->get();
        $kota = m_wil_kota::where('wc_provinsi', $idprov)
            ->orderBy('wc_name', 'asc')
            ->get();
        return Response::json($kota);
    }

    public function cariBarang(Request $request)
    {
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        // $comp = Auth::user()->u_company;
        $comp = $request->comp;
        if (count($is_item) == 0) {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->groupBy('i_id')
                ->get();
        } else {
            $nama = DB::table('m_item')
                ->whereNotIn('i_id', $is_item)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->groupBy('i_id')
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x) {
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y) {
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z) {
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
            ->join('d_stock_mutation', function ($sm) {
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
                $qty_compare = (int)$data->sisa / (int)$data_check->compare2;
            } else {
                $qty_compare = $qty;
            }
        } else if ($satuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)$data->sisa) {
                $qty_compare = (int)$data->sisa / (int)$data_check->compare3;
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
            ->join('d_stock_mutation', function ($sm) {
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
                $qty_compare_old = (int)($data->sisa + $qtyOld) / (int)$data_check->compare2;
            } else {
                $qty_compare_old = $qty;
            }
        } else if ($oldSatuan == $data_check->unit3) {
            $compare = (int)$qty * (int)$data_check->compare3;
            if ((int)$compare > (int)($data->sisa + $qtyOld)) {
                $qty_compare_old = (int)($data->sisa + $qtyOld) / (int)$data_check->compare3;
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
        // in_array($request->rangestartedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend));
        $idx = null;
        foreach ($array as $key => $val) {
            if ($value <= $val->pcd_rangeqtystart && $val->pcd_rangeqtyend == 0) {
                $val->pcd_rangeqtyend = $val->pcd_rangeqtystart + $value + 2;
            }

            if ($val->pcd_rangeqtyend == 0) {
                $val->pcd_rangeqtyend = $value + $val->pcd_rangeqtyend + 2;
            }

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
            ->where('pcd_payment', '=', 'C')
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
                if ($price->pcd_rangeqtyend == 0) {
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

    public function simpanOrderProduk(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'create'))
        {
            return Response::json([
                'status' => "Failed",
                'message' => 'Anda tidak memiliki akses ke menu ini !'
            ]);
        }
        $date = '';
        if ($request->dateOrder === null){
            $date = Carbon::now('Asia/Jakarta');
        } else {
            $date = Carbon::createFromFormat('d-m-Y', $request->dateOrder);
        }

        $data = $request->all();
        $notaPO = CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 9, 10, 3, 'PRO', '-');
        $notaDist = CodeGenerator::codeWithSeparator('d_stockdistribution', 'sd_nota', 9, 10, 3, 'PRO', '-');
        if (strcmp($notaPO, $notaDist) > 0) {
            $nota = $notaPO;
        }
        else {
            $nota = $notaDist;
        };

        if ($data['select_order'] == "1")
        {
            $po_id = (DB::table('d_productorder')->max('po_id')) ? DB::table('d_productorder')->max('po_id') + 1 : 1;
            $penjual = $data['a_compapj'];
            $pembeli = $data['a_compapb'];
            $status = "P";

            DB::beginTransaction();
            try
            {
                $val_po = [
                    'po_id' => $po_id,
                    'po_comp' => $penjual,
                    'po_agen' => $pembeli,
                    'po_date' => $date,
                    'po_nota' => $nota,
                    'po_status' => $status
                ];

                $podetail = (DB::table('d_productorderdt')
                    ->where('pod_productorder', '=', $po_id)
                    ->max('pod_detailid')) ?
                    (DB::table('d_productorderdt')
                        ->where('pod_productorder', '=', $po_id)
                        ->max('pod_detailid')) + 1 : 1;
                $detailpo = $podetail;
                $val_podt = [];
                for ($i = 0; $i < count($data['idItem']); $i++) {
                    $val_podt[] = [
                        'pod_productorder' => $po_id,
                        'pod_detailid' => $detailpo,
                        'pod_item' => $data['idItem'][$i],
                        'pod_qty' => $data['jumlah'][$i],
                        'pod_unit' => $data['satuan'][$i],
                        'pod_price' => Currency::removeRupiah($data['harga'][$i]),
                        'pod_totalprice' => Currency::removeRupiah($data['subtotal'][$i])
                    ];
                    $detailpo++;
                }

                DB::table('d_productorder')->insert($val_po);
                DB::table('d_productorderdt')->insert($val_podt);
                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message' => "Data berhasil disimpan"
                ]);
            }
            catch (Exception $e)
            {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message' => $e->getMessage()
                ]);
            }
        }
        else if ($data['select_order'] == "2" || $data['select_order'] == 'x')
        {
            $po_id = (DB::table('d_productorder')
                ->max('po_id')) ?
                DB::table('d_productorder')
                    ->max('po_id') + 1 : 1;

            $penjual = $data['c_cabang'];
            $pembeli = $data['c_compapb'];
            $status = "P";

            DB::beginTransaction();
            try
            {
                $val_po = [
                    'po_id' => $po_id,
                    'po_comp' => $penjual,
                    'po_agen' => $pembeli,
                    'po_date' => $date,
                    'po_nota' => $nota,
                    'po_status' => $status
                ];

                $podetail = (DB::table('d_productorderdt')
                    ->where('pod_productorder', '=', $po_id)
                    ->max('pod_detailid')) ?
                    (DB::table('d_productorderdt')
                        ->where('pod_productorder', '=', $po_id)
                        ->max('pod_detailid')) + 1 : 1;

                $detailpo = $podetail;
                $val_podt = [];
                for ($i = 0; $i < count($data['c_idItem']); $i++) {
                    $val_podt[] = [
                        'pod_productorder' => $po_id,
                        'pod_detailid' => $detailpo,
                        'pod_item' => $data['c_idItem'][$i],
                        'pod_qty' => $data['c_jumlah'][$i],
                        'pod_unit' => $data['c_satuan'][$i],
                        'pod_price' => Currency::removeRupiah($data['c_harga'][$i]),
                        'pod_totalprice' => Currency::removeRupiah($data['c_subtotal'][$i])
                    ];
                    $detailpo++;
                }

                DB::table('d_productorder')->insert($val_po);

                DB::table('d_productorderdt')->insert($val_podt);
                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message' => "Data berhasil disimpan"
                ]);
            }
            catch (\Exception $e)
            {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
    // end order produk ke agen

    // order produk ke cabang
    public function getCabang(Request $request)
    {
        $data = DB::table('m_company')
            ->join('m_agen', 'a_mma', '=', 'c_id')
            ->where('c_type', '!=', 'AGEN')
            ->where('a_id', '=', $request->agen)
            ->get();
        return Response::json($data);
    }

    public function getPembeliCabang($prov, $kota)
    {
        $agen = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->select('a_id', 'a_code', 'a_name', 'c_id')
            ->where('a_area', '=', $kota)
            ->where('a_isactive', '=', 'Y')
            ->get();

        return Response::json($agen);
    }

    // end order produk ke cabang

    public function create_orderprodukagencabang()
    {
        if (!AksesUser::checkAkses(23, 'create')) {
            abort('401');
        }

        $data = 'employee';
        if (Auth::user()->u_user == 'A') {
            $data = DB::table('m_agen')
                ->where('a_code', '=', Auth::user()->u_code)
                ->first();
        }
        return view('marketing/agen/orderproduk/create', compact('data'));
    }

    public function index()
    {
        if (!AksesUser::checkAkses(23, 'read')) {
            abort(401);
        }

        $provinsi = DB::table('m_wil_provinsi')
            ->select('m_wil_provinsi.*')
            ->orderBy('wp_name')
            ->get();

        $kota = DB::table('m_wil_kota')
            ->select('m_wil_kota.*')
            ->orderBy('wc_name')
            ->get();

        $kecamatan = DB::table('m_wil_kecamatan')
            ->select('m_wil_kecamatan.*')
            ->orderBy('wk_name')
            ->get();
        // get current user
        $user = Auth::user();

        if(Session::get('isPusat')){
            $cabang = DB::table('m_company')
                    ->whereIn('c_type', ['AGEN', 'CABANG'])
                    ->orderBy('c_name', 'asc')
                    ->select('c_id', 'c_name')->get();
        }else{
            $cabang = DB::table('m_company')
                    ->whereIn('c_type', ['AGEN', 'CABANG'])
                    ->where('c_id', Auth::user()->u_company)
                    ->orderBy('c_name', 'asc')
                    ->select('c_id', 'c_name')->get();
        }

        // return json_encode($cabang);

        $company = DB::table('m_company')
            ->leftJoin('m_agen', 'a_code', 'c_user')
            ->where('c_id', '=', $user->u_company)->first();

        return view('marketing/agen/index', compact('provinsi', 'kota', 'kecamatan', 'user', 'company', 'cabang'));
    }

//    order produk ke agen
    public function getOrder(Request $req)
    {
        $st = $req->status;
        $dateFrom = Carbon::parse($req->date_from);
        $datetTo = Carbon::parse($req->date_to);

        if ($st == null || $st == "") {
            $data = DB::table('d_productorder')
                ->whereBetween('po_date', [$dateFrom, $datetTo])
                ->select('d_productorder.po_id as id',
                    'd_productorder.po_date as tanggal',
                    'd_productorder.po_nota as nota',
                    'seller.c_name as penjual',
                    'buyer.c_name as pembeli',
                    'd_productorder.po_status as status', 'd_productorder.po_send as send')
                ->join('m_company as seller', function ($s) {
                    $s->on('d_productorder.po_comp', '=', 'seller.c_id');
                })->join('m_company as buyer', function ($b) {
                    $b->on('d_productorder.po_agen', '=', 'buyer.c_id');
                });
        } else {
            if ($st == 'pending' || $st == 'ditolak' || $st == 'disetujui') {
                if ($st == 'pending') {
                    $status = 'P';
                }
                if ($st == 'ditolak') {
                    $status = 'N';
                }
                if ($st == 'disetujui') {
                    $status = 'Y';
                }
                $data = DB::table('d_productorder')
                    ->whereBetween('po_date', [$dateFrom, $datetTo])
                    ->select('d_productorder.po_id as id',
                        'd_productorder.po_date as tanggal',
                        'd_productorder.po_nota as nota',
                        'seller.c_name as penjual',
                        'buyer.c_name as pembeli',
                        'd_productorder.po_status as status', 'd_productorder.po_send as send')
                    ->join('m_company as seller', function ($s) {
                        $s->on('d_productorder.po_comp', '=', 'seller.c_id');
                    })->join('m_company as buyer', function ($b) {
                        $b->on('d_productorder.po_agen', '=', 'buyer.c_id');
                    })->where('po_status', '=', $status)->where('po_send', '=', null);
            } else {
                if ($st == 'dikirim') {
                    $status = 'P';
                }
                if ($st == 'diterima') {
                    $status = 'Y';
                }
                $data = DB::table('d_productorder')
                    ->whereBetween('po_date', [$dateFrom, $datetTo])
                    ->select('d_productorder.po_id as id',
                        'd_productorder.po_date as tanggal',
                        'd_productorder.po_nota as nota',
                        'seller.c_name as penjual',
                        'buyer.c_name as pembeli',
                        'd_productorder.po_status as status', 'd_productorder.po_send as send')
                    ->join('m_company as seller', function ($s) {
                        $s->on('d_productorder.po_comp', '=', 'seller.c_id');
                    })->join('m_company as buyer', function ($b) {
                        $b->on('d_productorder.po_agen', '=', 'buyer.c_id');
                    })->where('po_send', '=', $status);
            }
        }

        if (Auth::user()->u_user == 'A') {
            $data = $data->where('po_agen', Auth::user()->u_company);
        }

        return DataTables::of($data)
            ->addColumn('tanggal', function ($data) {
                return Carbon::parse($data->tanggal)->format('d M Y');
            })
            ->addColumn('nota', function ($data) {
                return $data->nota;
            })
            ->addColumn('penjual', function ($data) {
                return $data->penjual;
            })
            ->addColumn('pembeli', function ($data) {
                return $data->pembeli;
            })
            ->addColumn('status', function ($data) {
                if ($data->send != null) {
                    if ($data->send == 'P') {
                        return '<span class="btn btn-sm btn-primary btn-khusus">Dikirim</span>';
                    } elseif ($data->send == 'Y') {
                        return '<span class="btn btn-sm btn-success btn-khusus">Diterima</span>';
                    }
                } else if ($data->send == null) {
                    if ($data->status == 'Y') {
                        return '<span class="btn btn-sm btn-success btn-khusus">Disetujui</span>';
                    } else if ($data->status == 'N') {
                        return '<span class="btn btn-sm btn-danger btn-khusus">Ditolak</span>';
                    } else {
                        return '<span class="btn btn-sm btn-danger btn-khusus">Pending</span>';
                    }
                }
            })
            ->addColumn('action', function ($data) {
                if ($data->send != null) {
                    if ($data->send == 'P') {
                        return '<center><div class="btn-group btn-group-sm">
                                    <button class="btn btn-info hint--top-left hint--info" aria-label="Detail"
                                            type="button" onclick="detailDo(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-folder"></i></button>
                                    <button class="btn btn-danger hint--top-left hint--error" type="button"
                                            aria-label="Hapus" onclick="hapusDO(\'' . Crypt::encrypt($data->id) . '\')" disabled><i class="fa fa-trash"></i></button>
                                    <button class="btn btn-success hint--top-left hint--success" type="button" aria-label="Terima" onclick="showDetailAc(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-fw fa-get-pocket"></i></button>
                                </div></center>';
                    }
                    else if ($data->send == 'Y') {
                        return '<center><div class="btn-group btn-group-sm">
                                    <button class="btn btn-info hint--top-left hint--info" aria-label="Detail"
                                            type="button" onclick="detailDo(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-folder"></i></button>
                                    <button class="btn btn-danger hint--top-left hint--error" type="button"
                                            aria-label="Hapus" onclick="hapusDO(\'' . Crypt::encrypt($data->id) . '\')" disabled><i class="fa fa-trash"></i></button>
                                </div></center>';
                    }
                }
                else if ($data->send == null) {
                    return '<center><div class="btn-group btn-group-sm">
                                <button class="btn btn-info hint--top-left hint--info" aria-label="Detail"
                                        type="button" onclick="detailDo(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-folder"></i></button>
                                <button class="btn btn-danger hint--top-left hint--error" type="button"
                                        aria-label="Hapus" onclick="hapusDO(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-trash"></i></button>
                            </div></center>';
                }

            })
            ->rawColumns(['tanggal', 'nota', 'penjual', 'pembeli', 'status', 'action'])
            ->make(true);
    }

    public function detailDO($id, $action)
    {
        if ($action == "detail") {
            try {
                $id = Crypt::decrypt($id);
            }
            catch (DecryptException $e) {
                return Response::json([
                    'status' => "Failed",
                    'message' => $e->getMessage()
                ]);
            }

            $data = DB::table('d_productorder')
                ->select('d_productorder.po_date as tanggal',
                    'd_productorder.po_nota as nota',
                    'seller.c_name as penjual',
                    'buyer.c_name as pembeli',
                    'd_productorder.po_status as status',
                    'd_productorder.po_send as pengiriman')
                ->join('m_company as seller', function ($s) {
                    $s->on('d_productorder.po_comp', '=', 'seller.c_id');
                })->join('m_company as buyer', function ($b) {
                    $b->on('d_productorder.po_agen', '=', 'buyer.c_id');
                })
                ->where('d_productorder.po_id', '=', $id)
                ->first();

            $data->tanggal = Carbon::parse($data->tanggal)->format('d M Y');

            return Response::json($data);
        }
        else if ($action == "table") {
            $data = DB::table('d_productorderdt')
                ->select('m_item.i_code as kode',
                    'm_item.i_name as barang',
                    'd_productorderdt.pod_qty as qty',
                    'm_unit.u_name as satuan',
                    DB::raw("CONCAT('Rp. ',FORMAT(d_productorderdt.pod_price, 0, 'de_DE')) as harga"),
                    DB::raw("CONCAT('Rp. ',FORMAT(d_productorderdt.pod_totalprice, 0, 'de_DE')) as total_harga"))
                ->join('m_item', function ($s) {
                    $s->on('d_productorderdt.pod_item', '=', 'm_item.i_id');
                })->join('m_unit', function ($b) {
                    $b->on('d_productorderdt.pod_unit', '=', 'm_unit.u_id');
                })
                ->where('d_productorderdt.pod_productorder', '=', Crypt::decrypt($id));

            return DataTables::of($data)
                ->addColumn('barang', function ($data) {
                    return $data->kode . ' - ' . $data->barang;
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->qty . ' - ' . $data->satuan;
                })
                ->addColumn('harga', function ($data) {
                    return $data->harga;
                })
                ->addColumn('total_harga', function ($data) {
                    return $data->total_harga;
                })
                ->rawColumns(['barang', 'jumlah', 'harga', 'total_harga'])
                ->make(true);
        }

    }

    public function deleteDO($id)
    {
        if (!AksesUser::checkAkses(23, 'delete')) {
            return Response::json([
                'status' => "Failed",
                'message' => 'Anda tidak memiliki akses ke menu ini !'
            ]);
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json([
                'status' => "Failed",
                'message' => $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorderdt')
                ->where('pod_productorder', '=', $id)
                ->delete();
            DB::table('d_productorder')
                ->where('po_id', '=', $id)
                ->delete();

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message' => "Data berhasil dihapus"
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message' => $e->getMessage()
            ]);
        }
    }

    // get detail order for acceptance
    public function getDetailDOAccept($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        try {
            // get product-order
            $productOrder = d_productorder::where('po_id', $id)
            ->with('getOrigin')
            ->with('getDestination')
            ->with(['getPODt' => function ($query) {
                $query
                ->with('getItem')
                ->with('getUnit');
            }])
            ->first();

            $productOrder->dateFormated = Carbon::parse($productOrder->po_date)->format('d M Y');
            $productOrder->poId = Crypt::encrypt($productOrder->po_id);

            return response()->json($productOrder);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getDetailDOCode(Request $request)
    {
        $id = $request->id;
        $itemId = $request->itemId;

        // get product-order
        $productOrder = d_productorder::where('po_id', $id)
        ->with(['getPODt' => function ($query) use ($itemId) {
            $query
            ->where('pod_item', $itemId)
            ->with('getProdCode')
            ->with('getItem');
        }])
        ->first();

        return response()->json($productOrder);
    }

    public function terimaDO(Request $request, $id)
    {
        if (!AksesUser::checkAkses(23, 'update')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try
        {
            $date = Carbon::createFromFormat('d-m-Y', $request->date);
            // get product-order
            $productOrder = d_productorder::where('po_id', $id)
            ->with('getPODt')
            ->first();

            // update stock using mutation distrtibution
            foreach ($productOrder->getPODt as $key => $po) {
                $mutConfirm = Mutasi::confirmSales(
                    $productOrder->po_agen, // destination
                    $po->pod_item, // itemId
                    $productOrder->po_nota, // nota
                    20, // mutcat in
                    5, // mutcat out
                    $date
                );
                if ($mutConfirm->original['status'] !== 'success') {
                    return $mutConfirm;
                }
            }

            // update product-order
            $productOrder->po_send = 'Y';
            $productOrder->save();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e->getMessage()
            ]);
        }
    }
// End order produk ke agen


    // Start: Kelola Data Inventory Agen ----------------
    public function getAgen($city)
    {
        $agen = DB::table('m_agen')
            ->join('m_company', 'a_code', 'c_user')
            ->select('a_code', 'a_name', 'c_id')
            ->where('a_kabupaten', '=', $city)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agen
        ]);
    }

    public function filterData($id)
    {
        $data = DB::table('d_stock')
            ->leftJoin('m_company as comp', 's_position', 'comp.c_id')
            ->leftJoin('m_company as agen', 's_comp', 'agen.c_id')
            ->leftJoin('m_item', 's_item', 'i_id')
            ->where('s_position', '=', $id)
            ->select('s_id', 'agen.c_name as agen', 'comp.c_name as comp', 'i_name', 's_status', 's_condition', 's_qty')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('kondisi', function ($data) {
                if ($data->s_condition == "FINE") {
                    return "Normal";
                } else {
                    return "Rusak";
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->s_status == "ON DESTINATION") {
                    return "Sampai Tujuan";
                } else {
                    return "Di Jalan";
                }
            })
            ->addColumn('qty', function ($data) {
                return "<div class='text-center'>$data->s_qty</div>";
            })
            ->addColumn('aksi', function ($data) {
                return '<button class="btn btn-sm btn-primary" onclick="detail_agen(' . $data->s_id . ')"><i class="fa fa-folder"></i></button>';
            })
            ->rawColumns(['kondisi', 'status', 'qty', 'aksi'])
            ->make(true);
    }

    public function getDetail_inventory($id)
    {
        $data = DB::table('d_stock')
            ->leftJoin('d_stockdt', 's_id', 'sd_stock')
            ->leftJoin('m_company as pemilik', 'pemilik.c_id', 's_comp')
            ->leftJoin('m_company as position', 'position.c_id', 's_comp')
            ->leftJoin('m_item', 'i_id', 's_item')
            ->select('pemilik.c_name as pemilik', 'position.c_name as position', 'i_name', 'd_stockdt.*')
            ->where('s_id', '=', $id)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }
    // End: Kelola Data Inventory Agen ----------------


    // Start: Kelola Penjualan Langsung -----------------
    /**
     * Validate request before execute command.
     *
     * @param  \Illuminate\Http\Request $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'member' => 'required',
            'itemListId.*' => 'required'
        ],
            [
                'member.required' => 'Pilih member terlebih dahulu !',
                'itemListId.*.required' => 'List item ada yang kosong !'
            ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getListKPL(Request $request)
    {
        $userType = Auth::user()->getCompany->c_type;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');


        if ($agentCode !== null) {
            $company = m_company::where('c_user', $agentCode)
                ->first();
            $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->where('s_comp', $company->c_id);
        } else {
            if ($userType == 'PUSAT') {
                $datas = d_sales::whereBetween('s_date', [$from, $to]);
            } else {
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                    ->where('s_comp', Auth::user()->u_company);
            }
        }
        // get list with nota nota 'PW'
        $datas = $datas->with('getMember')
            ->whereRaw('SUBSTR(s_nota, 1, 2) != ?', ['PW'])
            ->orderBy('s_date', 'desc')
            ->orderBy('s_nota', 'desc')
            ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->s_date)->format('d M Y');
            })
            ->addColumn('member', function ($datas) {
                return $datas->getMember['m_name'];
            })
            ->addColumn('total', function ($datas) {
                return '<div class="text-right">Rp ' . number_format($datas->s_total, '2', ',', '.') . '</div>';
            })
            ->addColumn('action', function ($datas) {
                return
                    '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-detailKPL" type="button" title="Detail" onclick="showDetailPenjualan(' . $datas->s_id . ')"><i class="fa fa-folder"></i></button>
            <button class="btn btn-warning btn-editKPL" type="button" title="Edit" onclick="editDetailPenjualan(' . $datas->s_id . ')"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-danger btn-delete" type="button" title="Delete" onclick="deleteDetailPenjualan(' . $datas->s_id . ')"><i class="fa fa-trash"></i></button>
            </div>';
            })
            ->rawColumns(['date', 'member', 'total', 'action'])
            ->make(true);
    }

    // get list-cities based on province-id
    public function getCitiesKPL(Request $request)
    {
        $cities = m_wil_provinsi::with(['getCities' => function ($q) {
            $q->orderBy('wc_name');
        }])->where('wp_id', '=', $request->provId)->firstOrFail();

        return response()->json($cities);
    }

    // get list-cities based on province-id
    public function getAgentsKPL(Request $request)
    {
        $agents = m_agen::where('a_area', $request->cityId)
            ->where('a_type', 'AGEN')
            ->with('getProvince')
            ->with('getCity')
            ->orderBy('a_code', 'desc')
            ->get();

        return response()->json($agents);
    }

    public function getAgenKPL(Request $request)
    {
        $provinsi = $request->provinsi;
        $kota = $request->kota;
        $cek = DB::table('m_company')
            ->where('c_id', '=', Auth::user()->u_company)
            ->first();
        $type = $cek->c_type;
        $data = [];

        if ($type == 'PUSAT'){
            $data = DB::table('m_company')
                ->join('m_agen', 'a_code', '=', 'c_user')
                ->where('a_area', '=', $kota)
                ->where('c_type', '=', 'AGEN')
                ->orderBy('c_name', 'asc')
                ->get();
        } elseif ($type == 'CABANG'){
            $data = DB::table('m_company')
                ->join('m_agen', 'a_code', '=', 'c_user')
                ->where('a_area', '=', $kota)
                ->where('c_type', '=', 'AGEN')
                ->where('a_mma', '=', $cek->u_company)
                ->select('m_agen.*')
                ->orderBy('a_name', 'asc')
                ->get();
        } else {
            $data = m_agen::join('m_company', 'c_user', '=', 'a_code')
                ->where('c_id', '=', $cek->c_id)
                ->select('m_agen.*')
                ->orderBy('a_name', 'asc')
                ->get();
        }

        return response()->json($data);
    }

    // get detail-kpl
    public function getDetailPenjualan(Request $request)
    {
        $detail = d_sales::where('s_id', $request->id)
            ->with('getSalesDt.getItem')
            ->with('getSalesDt.getUnit')
            ->with('getMember')
            ->first();
        return response()->json($detail);
    }

    // delete detail-kpl
    public function deleteDetailPenjualan(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'delete')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses menu ini !"
            ]);
        }

        $id = $request->id;
        DB::beginTransaction();
        try {
            $sales = d_sales::where('s_id', $id)
                ->with('getSalesDt.getProdCode')
                ->first();

            $mutcatOut = 14;
            // delete sales-detail
            foreach ($sales->getSalesDt as $key => $val) {
                // rollback mutation 'salesout'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $sales->s_nota,
                    $val->sd_item,
                    $mutcatOut
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }
                // // rollback mutasi-sales
                // $mutasi = Mutasi::rollback(
                //     $sales->s_nota,
                //     $val->sd_item,
                //     14 // mutcat
                // );
                // if (!is_bool($mutasi)) {
                //     DB::rollBack();
                //     return $mutasi;
                // }
                // delete production-code of selected sales-detail
                foreach ($val->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                $val->delete();
            }
            // delete sales
            $sales->delete();

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

    // show page to create new KPL
    public function createKPL()
    {
        if (!AksesUser::checkAkses(23, 'create')){
            abort(401);
        }

        $data['user'] = Auth::user()->u_user;
        $cek = DB::table('m_company')
            ->where('c_id', '=', Auth::user()->u_company)
            ->first();
        $type = $cek->c_type;
        $provinsi = [];
        $data['agents'] = [];
        if ($type == 'PUSAT'){
            // $data['agents'] = m_agen::get();
            $provinsi = DB::table('m_wil_provinsi')->orderBy('wp_name', 'asc')->get();
        } elseif ($type == 'CABANG'){
            // $data['agents'] = m_agen::where('a_mma', '=', $cek->c_id)->orderBy('a_name', 'asc')->get();
            $data['agents'] = m_company::where('c_id', $cek->c_id)->get();
        } else {
            $data['agents'] = m_agen::join('m_company', 'c_user', '=', 'a_code')
                ->where('c_id', '=', $cek->c_id)
                // ->select('m_agen.*')
                ->orderBy('a_name', 'asc')
                ->get();
        }
        $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->orderBy('m_name', 'asc')
            ->get();

        // dd(Auth::user()->getCompany->c_type);

        return view('marketing/agen/kelolapenjualan/create', compact('data', 'type', 'provinsi'));
    }

    // get member for KPL if the user is Employee, not Agent
    public function getMemberKPL(Request $request)
    {
        $getKode = DB::table('m_company')
            ->where('c_id', '=', $request->agentCode)
            ->get();

        $members = [];

        if (count($getKode) > 0){
            $members = m_member::where('m_id', 1)
                ->orWhere('m_agen', $getKode[0]->c_user)
                ->orderBy('m_name', 'asc')
                ->get();
        } else {
            $members = m_member::where('m_id', 1)
                ->orWhere('m_agen', $request->agentCode)
                ->orderBy('m_name', 'asc')
                ->get();
        }

        return response()->json($members);
    }

    // get items using autocomple.js
    public function findItem(Request $request)
    {
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        if (Auth::user()->getCompany->c_type == "PUSAT") {
            if ($request->agent == '' || is_null($request->agent)) {
                $results[] = ['id' => null, 'label' => 'Silahkan isi agen terlebih dahulu !'];
                return Response::json($results);
            }
            $comp = m_company::where('c_user', $request->agent)->first();
        } else {
            $comp = Auth::user()->getCompany;
        }

        // return if $comp is-null
        if (is_null($comp)) {
            $results[] = ['id' => null, 'label' => 'Agen tidak memiliki item apapun'];
            return Response::json($results);
        }

        $comp = $comp->c_id;
        $cari = $request->term;

        if (count($is_item) == 0) {
            $nama = d_stock::where('s_position', $comp)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->where('s_qty', '>', 0)
            ->whereHas('getItem', function ($q) use ($cari) {
                $q->where('i_name', 'like', '%' . $cari . '%');
            })
            ->with('getItem')
            ->get();

            // $nama = DB::table('m_item')
            //     ->join('d_stock', function ($s) use ($comp) {
            //         $s->on('i_id', '=', 's_item');
            //         $s->where('s_position', '=', $comp);
            //         $s->where('s_status', '=', 'ON DESTINATION');
            //         $s->where('s_condition', '=', 'FINE');
            //     })
            //     ->join('d_stock_mutation', function ($sm) {
            //         $sm->on('sm_stock', '=', 's_id');
            //         $sm->where('sm_residue', '!=', 0);
            //     })
            //     ->where(function ($q) use ($cari) {
            //         $q->orWhere('i_name', 'like', '%' . $cari . '%');
            //         $q->orWhere('i_code', 'like', '%' . $cari . '%');
            //     })
            //     ->groupBy('d_stock.s_id')
            //     ->get();
        }
        else {
            $nama = d_stock::where('s_position', $comp)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->where('s_qty', '>', 0)
            ->whereHas('getItem', function ($q) use ($cari) {
                $q->where('i_name', 'like', '%' . $cari . '%');
            })
            ->with('getItem')
            ->whereNotIn('s_item', $is_item)
            ->get();

            // $nama = DB::table('m_item')
            //     ->join('d_stock', function ($s) use ($comp) {
            //         $s->on('i_id', '=', 's_item');
            //         $s->where('s_position', '=', $comp);
            //         $s->where('s_status', '=', 'ON DESTINATION');
            //         $s->where('s_condition', '=', 'FINE');
            //     })
            //     ->join('d_stock_mutation', function ($sm) {
            //         $sm->on('sm_stock', '=', 's_id');
            //         $sm->where('sm_residue', '!=', 0);
            //     })
            //     ->whereNotIn('i_id', $is_item)
            //     ->where(function ($q) use ($cari) {
            //         $q->orWhere('i_name', 'like', '%' . $cari . '%');
            //         $q->orWhere('i_code', 'like', '%' . $cari . '%');
            //     })
            //     ->groupBy('d_stock.s_id')
            //     ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->getItem->i_id, 'label' => $query->getItem->i_code . ' - ' . strtoupper($query->getItem->i_name), 'data' => $query, 'stock' => $query->s_id];
            }
        }
        return Response::json($results);
    }

    // get price
    public function getPrice(Request $request)
    {
        if (Auth::user()->u_user == "E") {
            $agent = m_agen::where('a_code', $request->agentCode)
                ->first();
        } else {
            $agent = m_agen::where('a_code', Auth::user()->u_code)
                ->first();
        }
        $qty = $request->qty;
        $itemId = $request->itemId;
        $unitId = $request->unitId;


        $get_price = DB::table('d_salespricedt')
            ->join('d_salesprice', 'spd_salesprice', 'sp_id')
            ->select('d_salespricedt.*', 'd_salesprice.*')
            ->where('sp_id', '=', $agent->a_class)
            ->where('spd_payment', '=', 'C')
            ->where('spd_item', '=', $itemId)
            ->where('spd_unit', '=', $unitId)
            ->get();
        $harga = 0;
        $z = false;

        foreach ($get_price as $key => $price) {
            if ($qty == 1) {
                //satuan
                if ($this->existsInArrayKPL("U", $get_price) == true) {
                    if ($get_price[$key]->spd_type == "U") {
                        $harga = $get_price[$key]->spd_price;
                    }
                } else {
                    if ($price->spd_rangeqtystart == 1) {
                        $harga = $get_price[$key]->spd_price;
                    }
                }
            } else if ($qty > 1) {
                //range
                if ($price->spd_rangeqtyend == 0) {
                    if ($qty >= $price->spd_rangeqtystart) {
                        $harga = $price->spd_price;
                    }
                } else {
                    $z = $this->inRangeKPL($qty, $get_price);
                    if ($z !== null) {
                        $harga = $get_price[$z]->spd_price;
                    }
                }

            }
        }

        return response()->json(number_format($harga, 0, '', ''));
    }

    function existsInArrayKPL($entry, $array)
    {
        $x = false;
        foreach ($array as $compare) {
            if ($compare->spd_type == $entry) {
                $x = true;
            }
        }
        return $x;
    }

    function inRangeKPL($value, $array)
    {
        $idx = null;
        foreach ($array as $key => $val) {
            if ($value <= $val->spd_rangeqtystart && $val->spd_rangeqtyend == 0){
                $val->spd_rangeqtyend = $val->spd_rangeqtystart + $value + 2;
            }

            if ($val->spd_rangeqtyend == 0){
                $val->spd_rangeqtyend = $value + $val->spd_rangeqtyend + 2;
            }
            $x = in_array($value, range($val->spd_rangeqtystart, $val->spd_rangeqtyend));
            if ($x == true) {
                $idx = $key;
                break;
            }
        }
        return $idx;
    }

    // get satuan
    public function getUnit($id)
    {
        $data = m_item::where('i_id', $id)
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->first();

        return Response::json($data);
    }

    // store new KPL
    public function storeKPL(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'create')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $date = Carbon::createFromFormat('d-m-Y', $request->dateKPL);
            $data = $request->all();

            // get comp using agent-code
            if (Auth::user()->getCompany->c_type == "PUSAT") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            }
            else {
                $agent = Auth::user()->getCompany;
                // $agent = m_company::where('c_id', $agent->u_company)->first();
            }
            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength ,// list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                DB::rollback();
                return $validateProdCode;
            }

            // start insert data
            $salesId = d_sales::max('s_id') + 1;
            $salesNota = CodeGenerator::codeWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PC', '-');
            $sales = new d_sales();
            $sales->s_id = $salesId;
            $sales->s_comp = $agent->c_id; // user
            $sales->s_member = $request->member;
            $sales->s_type = 'C';
            $sales->s_date = $date;
            $sales->s_nota = $salesNota;
            $sales->s_total = Currency::removeRupiah($data['total_harga']);
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            // get item-position based on agent-code
            $salesDtId = d_salesdt::where('sd_sales', $salesId)->max('sd_detailid') + 1;
            for ($i = 0; $i < sizeof($data['idItem']); $i++) {
                // get itemStock based on position and item-id
                $stock = $this->getItemStock($agent->c_id, $data['idItem'][$i]);
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->sumQty;
                // is stock sufficient ?
                if ($stock === null || $itemStock < $data['jumlah'][$i]) {
                    DB::rollback();
                    // get detail item name
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    return response()->json([
                        'status' => 'invalid',
                        'message' => 'Stock ' . $item->i_name . ' tidak mencukupi. Stock tersedia: ' . $itemStock
                    ]);
                }
                // start insert sales-detail (each item)
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $salesId;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $stock->s_comp;
                $salesDt->sd_item = $data['idItem'][$i];
                $salesDt->sd_qty = (int)$data['jumlah'][$i];
                $salesDt->sd_unit = $data['satuan'][$i];
                $salesDt->sd_value = Currency::removeRupiah($data['harga'][$i]);
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = $data['diskon'][$i];
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                $sumQtyPC = 0;
                $listPC = array();
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    array_push($listPC, strtoupper($request->prodCode[$j]));
                    $detailidcode = (d_salescode::where('sc_sales', $salesId)
                        ->where('sc_item', $data['idItem'][$i])
                        ->max('sc_detailid')) ? d_salescode::where('sc_sales', $salesId)
                            ->where('sc_item', $data['idItem'][$i])
                            ->max('sc_detailid') + 1 : 1;
                    $val_salescode = [
                        'sc_sales' => $salesId,
                        'sc_item' => $data['idItem'][$i],
                        'sc_detailid' => $detailidcode,
                        'sc_code' => strtoupper($request->prodCode[$j]),
                        'sc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescode')->insert($val_salescode);
                    $sumQtyPC += (int)$request->qtyProdCode[$j];
                }

                if ($sumQtyPC != (int)$data['jumlah'][$i]) {
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }

                // get qty in smallest unit
                $data_check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                        'm_item.i_unit3 as unit3')
                    ->where('i_id', '=', $data['idItem'][$i])
                    ->first();

                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]);
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare3;
                }

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];
                // // try to use salesOut as mutation
                // insert stock mutation sales 'out'
                $mutationOut = Mutasi::salesOut(
                    $stock->s_position, // from
                    null, // to
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty of smallest-unit
                    $salesNota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sellPrice, // sellprice
                    14, // mutcat
                    $date
                );
                if ($mutationOut->original['status'] !== 'success') {
                    return $mutationOut;
                }
                $startProdCodeIdx += $prodCodeLength;
                $salesDtId++;
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

    // getITemStock
    public function getItemStock($position, $itemId)
    {
        $qtyStock = d_stock::where('s_item', $itemId)
            ->where('s_position', $position)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->sum('s_qty');

        $stock = d_stock::where('s_item', $itemId)
            ->where('s_position', $position)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->first();
        $stock->sumQty = $qtyStock;
        return $stock;
    }

    // // function to convert multidimension array to single array
    // function array_flatten($array) {
    //     if (!is_array($array)) {
    //         return FALSE;
    //     }
    //     $result = array();
    //     foreach ($array as $key => $value) {
    //         if (is_array($value)) {
    //             $result = array_merge($result, array_flatten($value));
    //         }
    //         else {
    //             $result[$key] = $value;
    //         }
    //     }
    //     return $result;
    // }

    // edit selected kpl
    public function editKPL($id)
    {
        if (!AksesUser::checkAkses(23, 'update')){
            abort(401);
        }

        $data['user'] = Auth::user();
        $data['agents'] = m_agen::get();

        $data['kpl'] = d_sales::where('s_id', $id)
            ->with(['getSalesDt' => function ($query) {
                $query
                    ->with(['getItem' => function ($query) {
                        $query
                            ->with('getUnit1')
                            ->with('getUnit2')
                            ->with('getUnit3');
                    }])
                    ->with('getUnit')
                    ->with('getProdCode');
            }])
            ->with('getMember.getAgent')
            ->first();

        // set nota
        $nota = $data['kpl']->s_nota;
        // get stock item
        foreach ($data['kpl']->getSalesDt as $key => $val) {
            $item = $val->sd_item;
            // get item stock
            $mainStock = d_stock::where('s_position', $val->sd_comp)
                ->where('s_item', $item)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')
                ->with('getItem')
                ->first();

            // add stock id to data
            $val->stockId = $mainStock->s_id;

            // calculate item-stock based on unit-compare each item
            if ($mainStock->getItem->i_unitcompare1 != null) {
                $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
            } else {
                $stock['unit1'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare2 != null) {
                $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
            } else {
                $stock['unit2'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare3) {
                $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
            } else {
                $stock['unit3'] = 0;
            }
            // add stockunit to data
            $val->stockUnit1 = $stock['unit1'];
            $val->stockUnit2 = $stock['unit2'];
            $val->stockUnit3 = $stock['unit3'];
        }
        // get agent
        if (Auth::user()->getCompany->c_type == 'PUSAT') {
            $data['kpl-agent'] = m_company::where('c_id', $data['kpl']->s_comp)
            ->with('getAgent')
            ->first();
        }
        else {
            $data['kpl-agent'] = m_company::where('c_id', $data['kpl']->s_comp)->first();
        }

        // get member
        $data['member'] = m_member::where('m_code', $data['kpl']->s_member)->first();
        $data['kpl']->s_date = Carbon::parse($data['kpl']->s_date)->format('d-m-Y');

        return view('marketing/agen/kelolapenjualan/edit', compact('data'));
    }

    // update selected kpl
    public function updateKPL(Request $request, $id)
    {
        if (!AksesUser::checkAkses(23, 'update')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $date = Carbon::createFromFormat('d-m-Y', $request->dateKPL);
            $data = $request->all();

            // // get comp using agent-code
            // if (Auth::user()->u_user == "E") {
            //     $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            // } else {
            //     $agent = Auth::user();
            //     $agent = m_company::where('c_id', $agent->u_company)->first();
            // }

            // get comp using agent-code
            if (Auth::user()->getCompany->c_type == "PUSAT") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            }
            else {
                $agent = Auth::user()->getCompany;
                // $agent = m_company::where('c_id', $agent->u_company)->first();
            }
            
            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength, // list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                DB::rollback();
                return $validateProdCode;
            }

            // update sales
            $sales = d_sales::where('s_id', $id)
                ->with('getSalesDt.getProdCode')
                ->first();
            $sales->s_total = Currency::removeRupiah($data['total']);
            $sales->s_user = Auth::user()->u_id;
            $sales->s_date = $date;
            $sales->save();

            $mutcatOut = 14;
            // delete sales-detail
            foreach ($sales->getSalesDt as $key => $val) {
                // rollback mutation 'salesout'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $sales->s_nota,
                    $val->sd_item,
                    $mutcatOut
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }

                // delete production-code of selected stockdistribution
                foreach ($val->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                $val->delete();
            }

            $salesDtId = d_salesdt::where('sd_sales', $sales->s_id)->max('sd_detailid') + 1;
            for ($i = 0; $i < sizeof($data['idItem']); $i++) {
                // get itemStock based on position and item-id
                $stock = $this->getItemStock($agent->c_id, $data['idItem'][$i]);
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->sumQty;

                // is stock sufficient ?
                if ($stock === null || $itemStock < $data['jumlah'][$i]) {
                    DB::rollback();
                    // get detail item name
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    return response()->json([
                        'status' => 'invalid',
                        'message' => 'Stock item ' . $item->i_name . ' tidak mencukupi. Stock tersedia: ' . $itemStock
                    ]);
                }

                // start insert sales-detail (each item)
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $sales->s_id;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $stock->s_comp;
                $salesDt->sd_item = $data['idItem'][$i];
                $salesDt->sd_qty = (int)$data['jumlah'][$i];
                $salesDt->sd_unit = $data['satuan'][$i];
                $salesDt->sd_value = Currency::removeRupiah($data['harga'][$i]);
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = $data['diskon'][$i];
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                $sumQtyPC = 0;
                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                $listPC = array();
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    array_push($listPC, strtoupper($request->prodCode[$j]));
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    // get sum-qty production code
                    $sumQtyPC += (int)$request->qtyProdCode[$j];

                    $detailidcode = (d_salescode::where('sc_sales', $sales->s_id)
                        ->where('sc_item', $data['idItem'][$i])
                        ->max('sc_detailid'))
                        ?
                        d_salescode::where('sc_sales', $sales->s_id)
                            ->where('sc_item', $data['idItem'][$i])
                            ->max('sc_detailid') + 1
                        :
                        1;
                    $val_salescode = array();
                    $val_salescode = [
                        'sc_sales' => $sales->s_id,
                        'sc_item' => $data['idItem'][$i],
                        'sc_detailid' => $detailidcode,
                        'sc_code' => strtoupper($request->prodCode[$j]),
                        'sc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescode')->insert($val_salescode);
                }
                // validate sum-qty of production-code
                if ($sumQtyPC != $request->jumlah[$i]) {
                    $item = m_item::where('i_id', $request->idItem[$i])->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }

                // get qty in smallest unit
                $data_check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                        'm_item.i_unit3 as unit3')
                    ->where('i_id', '=', $data['idItem'][$i])
                    ->first();

                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]);
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare3;
                }

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                // insert stock mutation sales 'out'
                $mutationOut = Mutasi::salesOut(
                    $stock->s_position, // from
                    null, // to
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty of smallest-unit
                    $sales->s_nota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sellPrice, // sellprice
                    14, // mutcat
                    $date
                );
                if ($mutationOut->original['status'] !== 'success') {
                    return $mutationOut;
                }

                $startProdCodeIdx += $prodCodeLength;
                $salesDtId++;
            }

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

    // End: Kelola Penjualan Langsung -----------------


    // Start: Kelola Penjualan via Website -----------------
    public function getListKPW(Request $request)
    {
        $userType = Auth::user()->u_user;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        if ($agentCode !== null) {
            $company = m_company::where('c_user', $agentCode)
            ->first();
            $datas = DB::table('d_salesweb')
            ->whereBetween('sw_date', [$from, $to])
            ->where('sw_agen', '=', $company->c_id);
        } else {
            if ($userType === 'E') {
                $datas = DB::table('d_salesweb')
                ->whereBetween('sw_date', [$from, $to]);
            } else {
                $datas = DB::table('d_salesweb')
                ->whereBetween('sw_date', [$from, $to])
                ->where('sw_agen', Auth::user()->u_company);
            }
        }

        $datas = $datas->join('m_company', 'sw_agen', '=', 'c_id')
        ->select('c_id', 'c_name', 'd_salesweb.*')
        ->get();


        return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('date', function ($datas) {
            return Carbon::parse($datas->sw_date)->format('d M Y');
        })
        ->addColumn('total', function ($datas) {
            return '<div class="text-right">Rp ' . number_format($datas->sw_totalprice, '0', ',', '.') . '</div>';
        })
        ->addColumn('action', function ($datas) {
            return
            '<div class="btn-group btn-group-sm">
            <button class="btn btn-primary btn-detailKPW" type="button" title="Detail" onclick="detailKPW(' . $datas->sw_id . ')"><i class="fa fa-folder"></i></button>
            <button class="btn btn-warning btn-editKPW" type="button" title="Edit" onclick="editKPW(' . $datas->sw_id . ')"><i class="fa fa-pencil"></i></button>
            <button class="btn btn-danger btn-deleteKPW" type="button" title="Delete" onclick="deleteKPW(' . $datas->sw_id . ')"><i class="fa fa-trash"></i></button>
            </div>';
        })
        ->rawColumns(['date', 'total', 'action'])
        ->make(true);
    }
    // create new KPW
    public function createKPW()
    {
        if (!AksesUser::checkAkses(23, 'create')){
            abort(401);
        }

        $data['user'] = Auth::user()->u_user;
        $cek = DB::table('m_company')
            ->where('c_id', '=', Auth::user()->u_company)
            ->first();
        $type = $cek->c_type;
        $provinsi = [];
        $data['agents'] = [];
        if ($type == 'PUSAT'){
            // $data['agents'] = m_agen::get();
            $provinsi = DB::table('m_wil_provinsi')->orderBy('wp_name', 'asc')->get();
        } elseif ($type == 'CABANG'){
            $data['agents'] = m_agen::where('a_mma', '=', $cek->c_id)->orderBy('a_name', 'asc')->get();
        } else {
            $data['agents'] = m_agen::join('m_company', 'c_user', '=', 'a_code')
                ->where('c_id', '=', $cek->c_id)
                ->select('m_agen.*')
                ->orderBy('a_name', 'asc')
                ->get();
        }
        $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->orderBy('m_name', 'asc')
            ->get();

        return view('marketing/agen/penjualanviaweb/create', compact('data', 'type', 'provinsi'));
    }
    // find item
    public function cariProduk(Request $request)
    {
        $cari = $request->term;
        $item = DB::table('m_item')
            ->select('i_id', 'i_name', 'i_code')
            ->whereRaw("i_name like '%" . $cari . "%'")
            ->orWhereRaw("i_code like '%" . $cari . "%'")
            ->get();

        if (count($item) == 0) {
            $hasilItem[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($item as $query) {
                if ($query->i_code == null) {
                    $hasilItem[] = [
                        'id' => $query->i_id,
                        'label' => $query->i_name
                    ];
                } else {
                    $hasilItem[] = [
                        'id' => $query->i_id,
                        'label' => $query->i_code . ' - ' . $query->i_name
                    ];
                }
            }
        }
        return Response::json($hasilItem);
    }
    // check item-stock
    public function getStockKPW(Request $request)
    {
        $posisi = $request->posisi;
        $item = $request->item;
        $qty = $request->qty;

        $stock = d_stock::where('s_position', '=', $posisi)
            ->where('s_item', '=', $item)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->select('s_qty')
            ->get();

        $stockQty = 0;
        if (count($stock) > 0) {
            foreach ($stock as $key => $val) {
                $stockQty += (int)$val->s_qty;
            }
        }

        if ($qty <= $stockQty) {
            return Response::json([
                'status' => 'sukses',
                'stock' => $stockQty
            ]);
        }
        else {
            return Response::json([
                'status' => 'gagal',
                'stock' => $stockQty
            ]);
        }
    }
    // cek production code
    public function cekProductionCode(Request $request)
    {
        $posisi = $request->posisi;
        $item = $request->item;
        $kode = $request->kode;
        $cek = DB::table('d_stock')
            ->join('d_stockdt', 's_id', '=', 'sd_stock')
            ->where('s_position', '=', $posisi)
            ->where('s_item', '=', $item)
            ->where('sd_code', '=', $kode)
            ->where('s_status', '=', 'ON DESTINATION')
            ->where('s_condition', '=', 'FINE')
            ->get();

        if (count($cek) > 0) {
            return Response::json([
                'status' => 'sukses'
            ]);
        } else {
            return Response::json([
                'status' => 'gagal'
            ]);
        }
    }

    // store new KPL
    public function storeKPW(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'create')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }
        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $date = Carbon::createFromFormat('d-m-Y', $request->dateKPL);
            $data = $request->all();

            // get comp using agent-code
            if (Auth::user()->getCompany->c_type == "PUSAT") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            }
            else {
                $agent = Auth::user()->getCompany;
                // $agent = m_company::where('c_id', $agent->u_company)->first();
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength ,// list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                DB::rollback();
                return $validateProdCode;
            }

            $nota = CodeGenerator::codeWithSeparator('d_salesweb', 'sw_reff', 8, 10, 3, 'PW', '-');
            // salesweb
            $sw_id = d_salesweb::max('sw_id') + 1;
            DB::table('d_salesweb')
            ->insert([
                'sw_id' => $sw_id,
                'sw_reff' => $nota,
                'sw_transactioncode' => strtoupper($request->transactionCode),
                'sw_agen' => $agent->c_id,
                'sw_website' => $request->webUrl,
                'sw_date' => $date,
                'sw_item' => null,
                'sw_qty' => null,
                'sw_unit' => null,
                'sw_price' => null,
                'sw_totalprice' => Currency::removeRupiah($data['total_harga']),
                'sw_note' => $request->note,
                'sw_insert' => Carbon::now()
            ]);

            // start insert data
            $salesId = d_sales::max('s_id') + 1;
            $salesNota = $nota;
            $sales = new d_sales();
            $sales->s_id = $salesId;
            $sales->s_comp = $agent->c_id; // user
            $sales->s_member = $request->member;
            $sales->s_type = 'C';
            $sales->s_date = $date;
            $sales->s_nota = $salesNota;
            $sales->s_total = Currency::removeRupiah($data['total_harga']);
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            // get item-position based on agent-code
            $salesDtId = d_salesdt::where('sd_sales', $salesId)->max('sd_detailid') + 1;
            for ($i = 0; $i < sizeof($data['idItem']); $i++) {
                // get itemStock based on position and item-id
                $stock = $this->getItemStock($agent->c_id, $data['idItem'][$i]);
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->sumQty;
                // is stock sufficient ?
                if ($stock === null || $itemStock < $data['jumlah'][$i]) {
                    DB::rollback();
                    // get detail item name
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    return response()->json([
                    'status' => 'invalid',
                    'message' => 'Stock ' . $item->i_name . ' tidak mencukupi. Stock tersedia: ' . $itemStock
                    ]);
                }
                // start insert sales-detail (each item)
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $salesId;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $stock->s_comp;
                $salesDt->sd_item = $data['idItem'][$i];
                $salesDt->sd_qty = (int)$data['jumlah'][$i];
                $salesDt->sd_unit = $data['satuan'][$i];
                $salesDt->sd_value = Currency::removeRupiah($data['harga'][$i]);
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = $data['diskon'][$i];
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                $sumQtyPC = 0;
                $listPC = array();
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    array_push($listPC, strtoupper($request->prodCode[$j]));
                    $detailidcode = (d_salescode::where('sc_sales', $salesId)
                        ->where('sc_item', $data['idItem'][$i])
                        ->max('sc_detailid')) ? d_salescode::where('sc_sales', $salesId)
                        ->where('sc_item', $data['idItem'][$i])
                        ->max('sc_detailid') + 1 : 1;
                    $val_salescode = [
                        'sc_sales' => $salesId,
                        'sc_item' => $data['idItem'][$i],
                        'sc_detailid' => $detailidcode,
                        'sc_code' => strtoupper($request->prodCode[$j]),
                        'sc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescode')->insert($val_salescode);
                    $sumQtyPC += (int)$request->qtyProdCode[$j];
                }

                if ($sumQtyPC != (int)$data['jumlah'][$i]) {
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }

                // get qty in smallest unit
                $data_check = DB::table('m_item')
                ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
                ->where('i_id', '=', $data['idItem'][$i])
                ->first();

                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]);
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare3;
                }

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];
                // // try to use salesOut as mutation
                // insert stock mutation sales 'out'
                $mutationOut = Mutasi::salesOut(
                    $stock->s_position, // from
                    null, // to
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty of smallest-unit
                    $salesNota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sellPrice, // sellprice
                    14, // mutcat
                    $date
                );
                if ($mutationOut->original['status'] !== 'success') {
                    return $mutationOut;
                }

                $startProdCodeIdx += $prodCodeLength;
                $salesDtId++;
            }

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
    // // store penjualan web
    // public function saveKPW(Request $request)
    // {
    //     if (!AksesUser::checkAkses(23, 'create')){
    //         return Response::json([
    //             'status' => "Failed",
    //             'message' => "Anda tidak memiliki akses ke menu ini !"
    //         ]);
    //     }
    //
    //     $date = Carbon::createFromFormat('d-m-Y', $request->date);
    //     $agen = $request->agen;
    //     $customer = $request->customer;
    //     $website = $request->website;
    //     $transaksi = $request->transaksi;
    //     $item = $request->item;
    //     $qty = $request->qty;
    //     $unit = $request->unit;
    //     $price = $request->price;
    //     $note = $request->note;
    //     $listPC = $request->code;
    //     $listQtyPC = $request->qtycode;
    //     $mutcat = 4;
    //     $sekarang = Carbon::now('Asia/Jakarta');
    //
    //     DB::beginTransaction();
    //     try {
    //         // validate production-code is exist in stock-item
    //         $listItemsId = array($item);
    //         $prodCodeLength = array(count($listPC));
    //         $validateProdCode = Mutasi::validateProductionCode(
    //             $agen, // from
    //             $listItemsId, // list item-id
    //             $listPC, // list production-code
    //             $prodCodeLength, // list production-code length each item
    //             $listQtyPC // list of qty each production-code
    //         );
    //         if ($validateProdCode !== 'validated') {
    //             DB::rollback();
    //             return $validateProdCode;
    //         }
    //
    //         $nota = CodeGenerator::codeWithSeparator('d_salesweb', 'sw_reff', 8, 10, 3, 'PW', '-');
    //         $totalPrice = intval($qty) * intval($price);
    //
    //         // salesweb
    //         $sw_id = d_salesweb::max('sw_id') + 1;
    //         DB::table('d_salesweb')
    //         ->insert([
    //             'sw_id' => $sw_id,
    //             'sw_reff' => $nota,
    //             'sw_transactioncode' => $transaksi,
    //             'sw_agen' => $agen,
    //             'sw_website' => $website,
    //             'sw_date' => $date,
    //             'sw_item' => $item,
    //             'sw_qty' => $qty,
    //             'sw_unit' => $unit,
    //             'sw_price' => $price,
    //             'sw_totalprice' => $totalPrice,
    //             'sw_note' => $note,
    //             'sw_insert' => $sekarang
    //         ]);
    //
    //         // d_sales
    //         $id_sales = d_sales::max('s_id') + 1;
    //         $sales = new d_sales;
    //         $sales->s_id = $id_sales;
    //         $sales->s_comp = $agen;
    //         $sales->s_member = $customer;
    //         $sales->s_type = 'C';
    //         $sales->s_date = $date;
    //         $sales->s_nota = $nota;
    //         $sales->s_total = $totalPrice;
    //         $sales->s_user = Auth::user()->u_id;
    //         $sales->save();
    //
    //         // get itemStock based on position and item-id
    //         $stock = $this->getItemStock($agen, $item);
    //         ($stock === null) ? $itemStock = 0 : $itemStock = $stock->sumQty;
    //         // is stock sufficient ?
    //         if ($stock === null || $itemStock < $qty) {
    //             DB::rollback();
    //             // get detail item name
    //             $item = m_item::where('i_id', $item)->first();
    //             return response()->json([
    //                 'status' => 'invalid',
    //                 'message' => 'Stock item ' . $item->i_name . ' tidak mencukupi. Stock tersedia: ' . $itemStock
    //             ]);
    //         }
    //
    //         $salesDtId = d_salesdt::where('sd_sales', $id_sales)->max('sd_detailid') + 1;
    //         // start insert sales-detail (each item)
    //         $salesDt = new d_salesdt();
    //         $salesDt->sd_sales = $id_sales;
    //         $salesDt->sd_detailid = $salesDtId;
    //         $salesDt->sd_comp = $stock->s_comp;
    //         $salesDt->sd_item = $item;
    //         $salesDt->sd_qty = (int)$qty;
    //         $salesDt->sd_unit = $unit;
    //         $salesDt->sd_value = $price;
    //         $salesDt->sd_discpersen = 0;
    //         $salesDt->sd_discvalue = 0;
    //         $salesDt->sd_totalnet = $totalPrice;
    //         $salesDt->save();
    //
    //         // insert sales-code
    //         $detailidcode = d_salescode::where('sc_sales', $id_sales)->where('sc_item', $item)->max('sc_detailid') + 1;
    //         foreach ($listPC as $key => $PC) {
    //             $val_salescode = [
    //                 'sc_sales' => $id_sales,
    //                 'sc_item' => $item,
    //                 'sc_detailid' => $detailidcode,
    //                 'sc_code' => strtoupper($listPC[$key]),
    //                 'sc_qty' => $listQtyPC[$key]
    //             ];
    //             DB::table('d_salescode')->insert($val_salescode);
    //             $detailidcode++;
    //         }
    //
    //         $listUnitPC = array();
    //         // insert stock mutation sales 'out'
    //         $mutationOut = Mutasi::salesOut(
    //             $sales->s_comp, // from
    //             null, // to
    //             $item, // item-id
    //             $qty, // qty of smallest-unit
    //             $sales->s_nota, // nota
    //             $listPC, // list of production-code
    //             $listQtyPC, // list of production-code-qty
    //             $listUnitPC, // list of production-code-unit
    //             $price, // sellprice
    //             $mutcat, // mutcat
    //             $date
    //         );
    //         if ($mutationOut->original['status'] !== 'success') {
    //             return $mutationOut;
    //         }
    //
    //         DB::commit();
    //         return Response::json([
    //             'status' => 'success'
    //         ]);
    //     }
    //     catch (DecryptException $e) {
    //         DB::rollBack();
    //         return Response::json([
    //             'status' => 'gagal',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function getDetailKPW(Request $request)
    {
        $sw_id = $request->id;
        $detail = d_sales::whereHas('getSalesWeb', function ($q) use ($sw_id) {
                $q->where('sw_id', $sw_id);
            })
            ->with('getSalesWeb')
            ->with('getSalesDt.getItem')
            ->with('getSalesDt.getUnit')
            ->with('getMember')
            ->first();

        return response()->json($detail);
    }

    // edit selected kpl
    public function editKPW($id)
    {
        if (!AksesUser::checkAkses(23, 'update')){
            abort(401);
        }

        $data['user'] = Auth::user();
        $data['agents'] = m_agen::get();

        $data['kpl'] = d_sales::whereHas('getSalesWeb', function ($q) use ($id) {
            $q->where('sw_id', $id);
        })
        ->with('getSalesWeb')
        ->with(['getSalesDt' => function ($query) {
            $query
            ->with(['getItem' => function ($query) {
                $query
                ->with('getUnit1')
                ->with('getUnit2')
                ->with('getUnit3');
            }])
            ->with('getUnit')
            ->with('getProdCode');
        }])
        ->with('getMember.getAgent')
        ->first();

        // set nota
        $nota = $data['kpl']->s_nota;
        // get stock item
        foreach ($data['kpl']->getSalesDt as $key => $val) {
            $item = $val->sd_item;
            // get item stock
            $mainStock = d_stock::where('s_position', $val->sd_comp)
            ->where('s_item', $item)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->with('getItem')
            ->first();

            // add stock id to data
            $val->stockId = $mainStock->s_id;

            // calculate item-stock based on unit-compare each item
            if ($mainStock->getItem->i_unitcompare1 != null) {
                $stock['unit1'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare1);
            } else {
                $stock['unit1'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare2 != null) {
                $stock['unit2'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare2);
            } else {
                $stock['unit2'] = 0;
            }
            if ($mainStock->getItem->i_unitcompare3) {
                $stock['unit3'] = floor($mainStock->s_qty / $mainStock->getItem->i_unitcompare3);
            } else {
                $stock['unit3'] = 0;
            }
            // add stockunit to data
            $val->stockUnit1 = $stock['unit1'];
            $val->stockUnit2 = $stock['unit2'];
            $val->stockUnit3 = $stock['unit3'];
        }
        // get agent
        if (Auth::user()->getCompany->c_type == 'PUSAT') {
            $data['kpl-agent'] = m_company::where('c_id', $data['kpl']->s_comp)
            ->with('getAgent')
            ->first();
        }
        else {
            $data['kpl-agent'] = m_company::where('c_id', $data['kpl']->s_comp)->first();
        }

        // // get agent
        // $data['kpl-agent'] = m_agen::whereHas('getCompany', function ($query) use ($data) {
        //     $query
        //     ->where('c_id', $data['kpl']->s_comp);
        // })->with('getCompany')->first();

        // get member
        $data['member'] = m_member::where('m_code', $data['kpl']->s_member)->first();
        $data['kpl']->s_date = Carbon::parse($data['kpl']->s_date)->format('d-m-Y');

        return view('marketing/agen/penjualanviaweb/edit', compact('data'));
    }

    // update selected kpw
    public function updateKPW(Request $request, $id)
    {
        if (!AksesUser::checkAkses(23, 'update')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $date = Carbon::createFromFormat('d-m-Y', $request->dateKPL);
            $data = $request->all();

            // // get comp using agent-code
            // if (Auth::user()->u_user == "E") {
            //     $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            // } else {
            //     $agent = Auth::user();
            //     $agent = m_company::where('c_id', $agent->u_company)->first();
            // }

            // get comp using agent-code
            if (Auth::user()->getCompany->c_type == "PUSAT") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            }
            else {
                $agent = Auth::user()->getCompany;
                // $agent = m_company::where('c_id', $agent->u_company)->first();
            }
            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength, // list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                DB::rollback();
                return $validateProdCode;
            }

            // get sales
            $sales = d_sales::where('s_id', $id)
            ->with('getSalesDt.getProdCode')
            ->first();
            // get and update salesweb
            $salesWeb = d_salesweb::where('sw_reff', $sales->s_nota)->first();
            $salesWeb->sw_transactioncode = strtoupper($request->transactionCode);
            $salesWeb->sw_website = $request->webUrl;
            $salesWeb->sw_date = $date;
            $salesWeb->sw_totalprice = Currency::removeRupiah($data['total']);
            $salesWeb->sw_note = $request->note;
            $salesWeb->sw_insert = Carbon::now();
            $salesWeb->save();

            // update sales
            $sales->s_total = Currency::removeRupiah($data['total']);
            $sales->s_user = Auth::user()->u_id;
            $sales->s_date = $date;
            $sales->save();

            $mutcatOut = 14;
            // delete sales-detail
            foreach ($sales->getSalesDt as $key => $val) {
                // rollback mutation 'salesout'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $sales->s_nota,
                    $val->sd_item,
                    $mutcatOut
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }

                // delete production-code of selected stockdistribution
                foreach ($val->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                $val->delete();
            }

            $salesDtId = d_salesdt::where('sd_sales', $sales->s_id)->max('sd_detailid') + 1;
            for ($i = 0; $i < sizeof($data['idItem']); $i++) {
                // get itemStock based on position and item-id
                $stock = $this->getItemStock($agent->c_id, $data['idItem'][$i]);
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->sumQty;

                // is stock sufficient ?
                if ($stock === null || $itemStock < $data['jumlah'][$i]) {
                    DB::rollback();
                    // get detail item name
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    return response()->json([
                        'status' => 'invalid',
                        'message' => 'Stock item ' . $item->i_name . ' tidak mencukupi. Stock tersedia: ' . $itemStock
                    ]);
                }

                // start insert sales-detail (each item)
                $salesDt = new d_salesdt();
                $salesDt->sd_sales = $sales->s_id;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $stock->s_comp;
                $salesDt->sd_item = $data['idItem'][$i];
                $salesDt->sd_qty = (int)$data['jumlah'][$i];
                $salesDt->sd_unit = $data['satuan'][$i];
                $salesDt->sd_value = Currency::removeRupiah($data['harga'][$i]);
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = $data['diskon'][$i];
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                $sumQtyPC = 0;
                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                $listPC = array();
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    array_push($listPC, strtoupper($request->prodCode[$j]));
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    // get sum-qty production code
                    $sumQtyPC += (int)$request->qtyProdCode[$j];

                    $detailidcode = (d_salescode::where('sc_sales', $sales->s_id)
                    ->where('sc_item', $data['idItem'][$i])
                    ->max('sc_detailid'))
                    ?
                    d_salescode::where('sc_sales', $sales->s_id)
                    ->where('sc_item', $data['idItem'][$i])
                    ->max('sc_detailid') + 1
                    :
                    1;
                    $val_salescode = array();
                    $val_salescode = [
                        'sc_sales' => $sales->s_id,
                        'sc_item' => $data['idItem'][$i],
                        'sc_detailid' => $detailidcode,
                        'sc_code' => strtoupper($request->prodCode[$j]),
                        'sc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescode')->insert($val_salescode);
                }
                // validate sum-qty of production-code
                if ($sumQtyPC != $request->jumlah[$i]) {
                    $item = m_item::where('i_id', $request->idItem[$i])->first();
                    throw new Exception("Jumlah kode produksi ". strtoupper($item->i_name) ." tidak sama dengan jumlah item yang dipesan !");
                }

                // get qty in smallest unit
                $data_check = DB::table('m_item')
                ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
                ->where('i_id', '=', $data['idItem'][$i])
                ->first();

                $qty_compare = 0;
                $sellPrice = 0;
                if ($data['satuan'][$i] == $data_check->unit1) {
                    $qty_compare = $data['jumlah'][$i];
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]);
                } else if ($data['satuan'][$i] == $data_check->unit2) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare2;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare2;
                } else if ($data['satuan'][$i] == $data_check->unit3) {
                    $qty_compare = $data['jumlah'][$i] * $data_check->compare3;
                    $sellPrice = (int)Currency::removeRupiah($data['harga'][$i]) / $data_check->compare3;
                }

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                // insert stock mutation sales 'out'
                $mutationOut = Mutasi::salesOut(
                    $stock->s_position, // from
                    null, // to
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty of smallest-unit
                    $sales->s_nota, // nota
                    $listPC, // list of production-code
                    $listQtyPC, // list of production-code-qty
                    $listUnitPC, // list of production-code-unit
                    $sellPrice, // sellprice
                    14, // mutcat
                    $date
                );
                if ($mutationOut->original['status'] !== 'success') {
                    return $mutationOut;
                }

                $startProdCodeIdx += $prodCodeLength;
                $salesDtId++;
            }

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

    public function deleteKPW(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'delete')){
            return Response::json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        $id = $request->id;
        DB::beginTransaction();
        try {
            // get sales
            $sales = d_sales::whereHas('getSalesWeb', function ($q) use ($id) {
                $q->where('sw_id', $id);
            })
            ->with('getSalesWeb')
            ->with('getSalesDt.getProdCode')
            ->first();

            // delete salesweb
            $sales->getSalesWeb->delete();

            $mutcatOut = 4;
            // delete salesdt and salescode
            foreach ($sales->getSalesDt as $key => $salesDt) {
                // rollback mutation 'salesout'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $sales->s_nota,
                    $salesDt->sd_item,
                    $mutcatOut
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }

                foreach ($salesDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                $salesDt->delete();
            }

            // delete sales
            $sales->delete();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        }
        catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    // End : Kelola Penjualan via Website -----------------

    // Tambahan dirga
        protected function getLaporan(Request $request){
            $penjualan = DB::table('d_sales')
                            ->where(DB::raw('concat(MONTH(s_date), "-", YEAR(s_date))'), date('n-Y'))
                            ->select(DB::raw('coalesce(sum(s_total), 0) as penjualan'));

            $sisahutang = DB::table('d_salescomp')
                                ->join('d_salescomppayment', 'scp_salescomp', 'sc_id')
                                ->where(DB::raw('concat(MONTH(sc_date), "-", YEAR(sc_date))'), date('n-Y'))
                                ->where('sc_comp', 'MB0000001')
                                ->select(DB::raw('coalesce((sum(sc_total) - sum(scp_pay)), 0) as tagihan'));

            if($request->search == 'all'){
                $penjualan = $penjualan->first();
                $sisahutang = $sisahutang->first();
            }else{
                $penjualan = $penjualan->where('s_comp', $request->search)->first();
                $sisahutang = $sisahutang->where('sc_member', $request->search)->first();
            }

            $dateNow = date('Y-m-d');
            $loop = 1;
            $bulanChart = $penjualanChart = $sisaHutang = [];

            do{

                $sr_penjualan = DB::table('d_sales')
                            ->where(DB::raw('concat(MONTH(s_date), "-", YEAR(s_date))'), date('n-Y', strtotime('-'.$loop.' month', strtotime($dateNow))))
                            ->select(DB::raw('coalesce(sum(s_total), 0) as penjualan'));

                $sr_sisahutang = DB::table('d_salescomp')
                                    ->join('d_salescomppayment', 'scp_salescomp', 'sc_id')
                                    ->where(DB::raw('concat(MONTH(sc_date), "-", YEAR(sc_date))'), date('n-Y', strtotime('-'.$loop.' month', strtotime($dateNow))))
                                    ->where('sc_comp', 'MB0000001')
                                    ->select(DB::raw('coalesce((sum(sc_total) - sum(scp_pay)), 0) as tagihan'));

                if($request->search == 'all'){
                    $sr_penjualan = $sr_penjualan->first();
                    $sr_sisahutang = $sr_sisahutang->first();
                }else{
                    $sr_penjualan = $sr_penjualan->where('s_comp', $request->search)->first();
                    $sr_sisahutang = $sr_sisahutang->where('sc_member', $request->search)->first();
                }

                array_push($bulanChart, date('M, Y', strtotime('-'.$loop.' month', strtotime($dateNow))));
                array_push($penjualanChart, ($sr_penjualan) ? (float) $sr_penjualan->penjualan : (float) 0);
                array_push($sisaHutang, ($sr_sisahutang) ? (float) $sr_sisahutang->tagihan : (float) 0);

                $loop++;

            }while($loop <= 5);

            // return json_encode($datachart);

            return json_encode([
                "penjualan"     => ($penjualan) ? $penjualan->penjualan : 0,
                "sisahutang"    => ($sisahutang) ? $sisahutang->tagihan : 0,
                "bulanChart"    => json_encode($bulanChart),
                "sr_penjualan"  => json_encode($penjualanChart),
                "sr_hutang"     => json_encode($sisaHutang),
            ]);
        }

}
