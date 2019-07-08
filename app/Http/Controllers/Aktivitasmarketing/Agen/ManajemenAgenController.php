<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\d_sales;
use App\d_salescode;
use App\d_salesdt;
use App\d_salesprice;
use App\d_stock;
use App\d_username;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_member;
use App\m_priceclass;
use App\m_wil_provinsi;
use DataTables;
use DB;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use Mockery\Exception;
use Mutasi;
use Response;
use Validator;

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
        $data = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('a_parent', '=', $kode)
            ->get();
        return Response::json($data);
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

    public function getPenjual($prov = null, $kota = null)
    {
        $data = DB::table('m_agen')
            ->join('m_company', 'a_code', '=', 'c_user')
            ->where('m_agen.a_provinsi', '=', $prov)
            ->where('m_agen.a_kabupaten', '=', $kota)
            ->get();
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
        $prov = DB::table('m_wil_provinsi')->get();
        return Response::json($prov);
    }

    public function getKota($idprov = null)
    {
        // khusus tuban
        //$kota = DB::table('m_wil_kota')->where('wc_provinsi', $idprov)->where('wc_id', 3523)->get();
        $kota = DB::table('m_wil_kota')->where('wc_provinsi', $idprov)->get();
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
            if ($value <= $val->pcd_rangeqtystart && $val->pcd_rangeqtyend == 0){
                $val->pcd_rangeqtyend = $val->pcd_rangeqtystart + $value + 2;
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
        if (!AksesUser::checkAkses(23, 'create')) {
            return Response::json([
                'status' => "Failed",
                'message' => 'Anda tidak memiliki akses'
            ]);
        }
        $data = $request->all();

        if ($data['select_order'] == "1") {
            $po_id = (DB::table('d_productorder')->max('po_id')) ? DB::table('d_productorder')->max('po_id') + 1 : 1;
            $penjual = $data['a_compapj'];
            $pembeli = $data['a_compapb'];
            $date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $nota = CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 9, 10, 3, 'PRO', '-');
            $status = "P";

            DB::beginTransaction();
            try {
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
            } catch (Exception $e) {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message' => $e
                ]);
            }
        } else if ($data['select_order'] == "2") {
            $po_id = (DB::table('d_productorder')
                ->max('po_id')) ?
                DB::table('d_productorder')
                    ->max('po_id') + 1 : 1;
            $penjual = $data['c_cabang'];
            $pembeli = $data['c_compapb'];
            $date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $nota = CodeGenerator::codeWithSeparator('d_productorder', 'po_nota', 9, 10, 3, 'PRO', '-');
            $status = "P";

            DB::beginTransaction();
            try {
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
            } catch (Exception $e) {
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message' => $e
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
        // get current user
        $user = Auth::user();
        return view('marketing/agen/index', compact('provinsi', 'user'));
    }

//    order produk ke agen
    public function getOrder(Request $req)
    {
        $st = $req->status;

        if ($st == null || $st == "") {
            $data = DB::table('d_productorder')
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

        return DataTables::of($data)
            ->addColumn('tanggal', function ($data) {
                return $data->tanggal;
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
                                            aria-label="Hapus" onclick="hapusDO(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-trash"></i></button>
                                    <button class="btn btn-success" type="button" aria-label="Terima" onclick="terimaDO(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-check"></i></button>
                                </div></center>';
                    } elseif ($data->send == 'Y') {
                        return '<center><div class="btn-group btn-group-sm">
                                    <button class="btn btn-info hint--top-left hint--info" aria-label="Detail"
                                            type="button" onclick="detailDo(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-folder"></i></button>
                                    <button class="btn btn-danger hint--top-left hint--error" type="button"
                                            aria-label="Hapus" onclick="hapusDO(\'' . Crypt::encrypt($data->id) . '\')"><i class="fa fa-trash"></i></button>
                                </div></center>';
                    }
                } elseif ($data->send == null) {
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
            } catch (DecryptException $e) {
                return Response::json([
                    'status' => "Failed",
                    'message' => $e
                ]);
            }

            $data = DB::table('d_productorder')
                ->select('d_productorder.po_date as tanggal',
                    'd_productorder.po_nota as nota',
                    'seller.c_name as penjual',
                    'buyer.c_name as pembeli',
                    'd_productorder.po_status as status')
                ->join('m_company as seller', function ($s) {
                    $s->on('d_productorder.po_comp', '=', 'seller.c_id');
                })->join('m_company as buyer', function ($b) {
                    $b->on('d_productorder.po_agen', '=', 'buyer.c_id');
                })
                ->where('d_productorder.po_id', '=', $id)
                ->first();

            return Response::json($data);
        } else if ($action == "table") {
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
                'message' => 'Anda tidak memiliki akses'
            ]);
        }
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json([
                'status' => "Failed",
                'message' => $e
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
        } catch (Exception $e) {
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message' => $e
            ]);
        }
    }

    public function terimaDO($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

        DB::beginTransaction();
        try {
            DB::table('d_productorder')
                ->where('po_id', '=', $id)
                ->update([
                    'po_send' => 'Y'
                ]);

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'Gagal',
                'message' => $e
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
            ->select('agen.c_name as agen', 'comp.c_name as comp', 'i_name', 's_condition', 's_qty')
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
            ->addColumn('qty', function ($data) {
                return "<div class='text-center'>$data->s_qty</div>";
            })
            ->rawColumns(['kondisi', 'qty'])
            ->make(true);
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
        $userType = Auth::user()->u_user;
        $agentCode = $request->agent_code;
        $from = Carbon::parse($request->date_from)->format('Y-m-d');
        $to = Carbon::parse($request->date_to)->format('Y-m-d');

        if ($agentCode !== null) {
            $company = m_company::where('c_user', $agentCode)
                ->first();
            $datas = d_sales::whereBetween('s_date', [$from, $to])
                ->where('s_comp', $company->c_id);
        } else {
            if ($userType === 'E') {
                $datas = d_sales::whereBetween('s_date', [$from, $to]);
            } else {
                $datas = d_sales::whereBetween('s_date', [$from, $to])
                    ->where('s_comp', Auth::user()->u_company);
            }
        }
        $datas = $datas->with('getMember')
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
        $id = $request->id;
        DB::beginTransaction();
        try {
            $sales = d_sales::where('s_id', $id)
                ->with('getSalesDt.getProdCode')
                ->first();

            // delete sales-detail
            foreach ($sales->getSalesDt as $key => $val) {
                // rollback mutasi-sales
                $mutasi = Mutasi::rollback(
                    $sales->s_nota,
                    $val->sd_item,
                    14 // mutcat
                );
                if (!is_bool($mutasi)) {
                    DB::rollBack();
                    return $mutasi;
                }
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
        $data['user'] = Auth::user()->u_user;
        $data['agents'] = m_agen::get();
        $data['member'] = m_member::orWhere('m_id', 1)
            ->orWhere('m_agen', Auth::user()->u_code)
            ->get();
        return view('marketing/agen/kelolapenjualan/create', compact('data'));
    }

    // get member for KPL if the user is Employee, not Agent
    public function getMemberKPL(Request $request)
    {
        $members = m_member::where('m_id', 1)
            ->orWhere('m_agen', $request->agentCode)
            ->get();
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

        if (Auth::user()->u_user === 'E') {
            $comp = m_company::where('c_user', $request->agent)->first();
        } else {
            $comp = m_company::where('c_user', Auth::user()->u_code)->first();
        }

        // return if $comp is-null
        if (is_null($comp)) {
            $results[] = ['id' => null, 'label' => 'Agen tidak memiliki item apapun'];
            return Response::json($results);
        }

        $comp = $comp->c_id;
        $cari = $request->term;

        if (count($is_item) == 0) {
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp) {
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm) {
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->groupBy('d_stock.s_id')
                ->get();
        } else {
            $nama = DB::table('m_item')
                ->join('d_stock', function ($s) use ($comp) {
                    $s->on('i_id', '=', 's_item');
                    $s->where('s_position', '=', $comp);
                    $s->where('s_status', '=', 'ON DESTINATION');
                    $s->where('s_condition', '=', 'FINE');
                })
                ->join('d_stock_mutation', function ($sm) {
                    $sm->on('sm_stock', '=', 's_id');
                    $sm->where('sm_residue', '!=', 0);
                })
                ->whereNotIn('i_id', $is_item)
                ->where(function ($q) use ($cari) {
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                    $q->orWhere('i_code', 'like', '%' . $cari . '%');
                })
                ->groupBy('d_stock.s_id')
                ->get();
        }

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query, 'stock' => $query->s_id];
            }
        }
        return Response::json($results);
    }

    // get price
    public function getPrice(Request $request)
    {
        if (Auth::user()->u_user === 'E') {
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
        // // validate request
        // $isValidRequest = $this->validate_req($request);
        // if ($isValidRequest != '1') {
        //     $errors = $isValidRequest;
        //     return response()->json([
        //         'status' => 'invalid',
        //         'message' => $errors
        //     ]);
        // }

        DB::beginTransaction();

        try {
            $data = $request->all();
            // get comp using agent-code
            if (Auth::user()->u_user == "E") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            } else {
                $agent = Auth::user();
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength // list production-code length each item
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
            $sales->s_date = Carbon::now('Asia/Jakarta');
            $sales->s_nota = $salesNota;
            $sales->s_total = Currency::removeRupiah($data['total_harga']);
            $sales->s_user = Auth::user()->u_id;
            $sales->save();

            // get item-position based on agent-code
            $salesDtId = d_salesdt::where('sd_sales', $salesId)->max('sd_detailid') + 1;
            for ($i = 0; $i < sizeof($data['idItem']); $i++) {
                // get itemStock based on position and item-id
                $stock = $this->getItemStock($agent->c_id, $data['idItem'][$i]);
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->s_qty;

                // is stock sufficient ?
                if ($stock === null || $stock->s_qty < $data['jumlah'][$i]) {
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
                $salesDt->sd_sales = $salesId;
                $salesDt->sd_detailid = $salesDtId;
                $salesDt->sd_comp = $stock->s_comp;
                $salesDt->sd_item = $data['idItem'][$i];
                $salesDt->sd_qty = (int)$data['jumlah'][$i];
                $salesDt->sd_unit = $data['satuan'][$i];
                $salesDt->sd_value = Currency::removeRupiah($data['harga'][$i]);
                $salesDt->sd_discpersen = 0;
                $salesDt->sd_discvalue = 0;
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
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
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                // mutasi keluar
                $mutasi = Mutasi::mutasikeluar(
                    14, // mutcat
                    $stock->s_comp, // item-owner
                    $stock->s_position, // item-position
                    $data['idItem'][$i], // item-id
                    $qty_compare, // item-qty in smallest unit
                    $salesNota, // nota
                    $sellPrice, // item-price
                    $listPC, // list production-code
                    $listQtyPC, // list qty roduction code
                    null // reff
                );
                if (!is_bool($mutasi)) {
                    DB::rollback();
                    return $mutasi;
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
        $stock = d_stock::where('s_item', $itemId)
            ->where('s_position', $position)
            ->where('s_status', 'ON DESTINATION')
            ->where('s_condition', 'FINE')
            ->first();

        return $stock;
    }

    // edit selected kpl
    public function editKPL($id)
    {
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
        $data['kpl-agent'] = m_agen::whereHas('getCompany', function ($query) use ($data) {
            $query
                ->where('c_id', $data['kpl']->s_comp);
        })->with('getCompany')->first();
        // get member
        $data['member'] = m_member::where('m_code', $data['kpl']->s_member)->first();

        return view('marketing/agen/kelolapenjualan/edit', compact('data'));
    }

    // update selected kpl
    public function updateKPL(Request $request, $id)
    {
        // // validate request
        // $isValidRequest = $this->validate_req($request);
        // if ($isValidRequest != '1') {
        //     $errors = $isValidRequest;
        //     return response()->json([
        //         'status' => 'invalid',
        //         'message' => $errors
        //     ]);
        // }
        DB::beginTransaction();
        try {
            $data = $request->all();
            // get comp using agent-code
            if (Auth::user()->u_user == "E") {
                $agent = DB::table('m_company')->where('c_user', '=', $request->agent)->first();
            } else {
                $agent = Auth::user();
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                $agent->c_id, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength // list production-code length each item
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
            $sales->save();

            // delete sales-detail
            foreach ($sales->getSalesDt as $key => $val) {
                // rollback mutasi-sales which is updated
                $mutasi = Mutasi::rollback(
                    $sales->s_nota,
                    $val->sd_item,
                    14 // mutcat
                );
                if (!is_bool($mutasi)) {
                    DB::rollback();
                    return $mutasi;
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
                ($stock === null) ? $itemStock = 0 : $itemStock = $stock->s_qty;

                // is stock sufficient ?
                if ($stock === null || $stock->s_qty < $data['jumlah'][$i]) {
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
                $salesDt->sd_discvalue = 0;
                $salesDt->sd_totalnet = Currency::removeRupiah($data['subtotal'][$i]);
                $salesDt->save();

                // values for insert to salescomp-code
                if ($i == 0) {
                    $startProdCodeIdx = 0;
                }
                $prodCodeLength = (int)$request->prodCodeLength[$i];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
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
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                // mutasi keluar
                $mutasi = Mutasi::mutasikeluar(
                    14, // mutcat
                    $stock->s_comp, // item-owner
                    $stock->s_position, // item-position
                    $data['idItem'][$i], // item-id
                    $qty_compare, // item-qty in smallest unit
                    $sales->s_nota, // nota
                    Currency::removeRupiah($data['harga'][$i]), // item-price
                    $listPC, // list production-code
                    $listQtyPC // list qty roduction code
                );
                if (!is_bool($mutasi)) {
                    DB::rollback();
                    return $mutasi;
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

    // End: Kelola Penjualan Langsung -----------------

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
        // dd($cek);
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

    public function saveKPW(Request $request)
    {
        $agen      = $request->agen;
        $customer  = $request->customer;
        $website   = $request->website;
        $transaksi = $request->transaksi;
        $item      = $request->item;
        $qty       = $request->qty;
        $unit      = $request->unit;
        $price     = $request->price;
        $note      = $request->note;
        $code      = $request->code;
        $qtycode   = $request->qtycode;
        $mutcat    = 4;
        $sekarang  = Carbon::now('Asia/Jakarta');

        DB::beginTransaction();
        try {
            //d_sales
            $id_sales = DB::table('d_sales')
                ->max('s_id');

            ++$id_sales;

            $nota = CodeGenerator::codeWithSeparator('d_sales', 's_nota', 8, 10, 3, 'PC', '-');
            $total = intval($qty) * intval($price);
            DB::table('d_sales')
                ->insert([
                    's_id'     => $id_sales,
                    's_comp'   => $agen,
                    's_member' => $customer,
                    's_type'   => 'C',
                    's_date'   => $sekarang->format('Y-m-d'),
                    's_nota'   => $nota,
                    's_total'  => $total,
                    's_user'   => Auth::user()->u_id,
                ]);

            $datamutcat = DB::table('m_mutcat')->where('m_status', '=', 'M')->get();

            for ($i = 0; $i < count($datamutcat); $i++) {
                $tmp[] = $datamutcat[$i]->m_id;
            }
            // dd($comp, $position, $item);
            $stock = DB::table('d_stock')
                ->join('d_stock_mutation', 'sm_stock', '=', 's_id')
                ->select('d_stock.*', 'd_stock_mutation.*', DB::raw('(sm_qty - sm_use) as sm_sisa'))
                ->where('s_position', '=', $agen)
                ->where('s_item', '=', $item)
                ->where('s_status', '=', 'ON DESTINATION')
                ->where('s_condition', '=', 'FINE')
                ->whereIn('sm_mutcat', $tmp)
                ->where('sm_residue', '>', 0);

            $stock = $stock->get();
            $permintaan = $qty;

            // set callback if stock-item-parent is empty
            if (sizeof($stock) < 1) {
                $itemx = m_item::where('i_id', $item)->select('i_name')->first();
                throw new Exception("Stock " . $itemx->i_name . " kosong !");
            }

            DB::table('d_stock')
                ->where('s_id', $stock[0]->s_id)
                ->update([
                    's_qty' => $stock[0]->s_qty - $permintaan
                ]);

            $sales_qty = 0;
            $cek_idstock = 0;
            $insert_salesdt = [];
            $id_salesdt = 0;
            // set mutation record
            for ($j = 0; $j < count($stock); $j++) {
                $continueLoopStock = false;
                $cek_idstock = $stock[$j]->s_id;
                $detailid = (DB::table('d_stock_mutation')
                    ->where('sm_stock', $stock[$j]->sm_stock)
                    ->max('sm_detailid')) ?
                    DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)
                        ->max('sm_detailid') + 1 : 1;

                // insert new stock mutation
                // use all qty from current stock-mutation
                if ($permintaan > $stock[$j]->sm_sisa && $permintaan != 0) {
                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $stock[$j]->sm_qty,
                            'sm_residue' => 0
                        ]);

                    // update qty of request after using all qty in stock-mutation parent
                    $permintaan = $permintaan - $stock[$j]->sm_sisa;
                    // qty that will store to sm_qty in new stock-mutation
                    $smQty = $stock[$j]->sm_sisa;

                    if ($cek_idstock != $stock[$j]->s_id){
                        ++$id_salesdt;
                        $temp = [
                            'sd_sales'      => $id_sales,
                            'sd_detailid'   => $id_salesdt,
                            'sd_comp'       => $stock[$j-1]->s_comp,
                            'sd_item'       => $stock[$j-1]->s_item,
                            'sd_qty'        => $sales_qty,
                            'sd_unit'       => $unit,
                            'sd_value'      => $price,
                            'sd_discpersen' => 0,
                            'sd_discvalue'  => 0,
                            'sd_totalnet'   => $price * $sales_qty
                        ];
                        array_push($insert_salesdt, $temp);
                        $sales_qty = 0;
                    } else {
                        $sales_qty = $sales_qty + $stock[$j]->sm_sisa;
                    }

                    $continueLoopStock = true;
                } // use part of qty from current stock-mutation
                elseif ($permintaan <= $stock[$j]->sm_sisa && $permintaan != 0) {
                    $detailid = (DB::table('d_stock_mutation')
                        ->where('sm_stock', $stock[$j]->sm_stock)
                        ->max('sm_detailid')) ?
                        (DB::table('d_stock_mutation')
                            ->where('sm_stock', $stock[$j]->sm_stock)
                            ->max('sm_detailid')) + 1 : 1;

                    DB::table('d_stock_mutation')
                        ->where('sm_stock', '=', $stock[$j]->sm_stock)
                        ->where('sm_detailid', '=', $stock[$j]->sm_detailid)
                        ->update([
                            'sm_use' => $permintaan + $stock[$j]->sm_use,
                            'sm_residue' => $stock[$j]->sm_residue - $permintaan
                        ]);
                    // qty that will store to sm_qty in new stock-mutation
                    $smQty = $permintaan;

                    ++$id_salesdt;
                    $temp = [
                        'sd_sales'      => $id_sales,
                        'sd_detailid'   => $id_salesdt,
                        'sd_comp'       => $stock[$j]->s_comp,
                        'sd_item'       => $stock[$j]->s_item,
                        'sd_qty'        => $permintaan,
                        'sd_unit'       => $unit,
                        'sd_value'      => $price,
                        'sd_discpersen' => 0,
                        'sd_discvalue'  => 0,
                        'sd_totalnet'   => $price * $sales_qty
                    ];
                    array_push($insert_salesdt, $temp);

                    $continueLoopStock = false;
                }

                //insert d_salescode
                $salescode = [];
                for ($i = 0; $i < count($code); $i++){
                    $temp = [
                        'sc_sales'    => $id_sales,
                        'sc_item'     => $item,
                        'sc_detailid' => $i + 1,
                        'sc_code'     => $code[$i],
                        'sc_qty'      => $qtycode[$i]
                    ];
                    array_push($salescode, $temp);
                }

                DB::table('d_salescode')
                    ->insert($salescode);

                // insert new stock-mutation out
                DB::table('d_stock_mutation')
                    ->insert([
                        'sm_stock'    => $stock[$j]->sm_stock,
                        'sm_detailid' => $detailid,
                        'sm_date'     => $sekarang,
                        'sm_mutcat'   => 4,
                        'sm_qty'      => $smQty,
                        'sm_use'      => 0,
                        'sm_residue'  => 0,
                        'sm_hpp'      => $stock[$j]->sm_hpp,
                        'sm_sell'     => $price,
                        'sm_nota'     => $nota,
                        'sm_reff'     => $stock[$j]->sm_nota,
                        'sm_user'     => Auth::user()->u_id,
                    ]);

                // currently, it's special case for 'penjualan-langsung / mutcat 14'
                if ($mutcat == 4) {
                    // insert new stock-detail production-code
                    $stockParentId = $stock[$j]->sm_stock;
                    $stockChildId = null;
                    $insertStockDt = Mutasi::insertStockDetail($stockParentId, $stockChildId, $code, $qtycode);
                    if ($insertStockDt !== 'success') {
                        throw new Exception($insertStockDt->getData()->message);
                    }

                    // insert new stock-mutation-detail production-code
                    $insertSMProdCode = Mutasi::insertStockMutationDt($stockParentId, $detailid, $code, $qtycode);
                    if ($insertSMProdCode !== 'success') {
                        throw new Exception($insertSMProdCode->getData()->message);
                    }
                }

                if ($continueLoopStock == false) {
                    $permintaan = 0;
                    break;
                }
            }

            //salesdt
            DB::table('d_salesdt')
                ->insert($insert_salesdt);

            //salesweb
            $sw_id = DB::table('d_salesweb')
                ->max('sw_id');
            ++$sw_id;
            DB::table('d_salesweb')
                ->insert([
                    'sw_id'              => $sw_id,
                    'sw_reff'            => $nota,
                    'sw_transactioncode' => $transaksi,
                    'sw_agen'            => $agen,
                    'sw_website'         => $website,
                    'sw_date'            => $sekarang,
                    'sw_item'            => $item,
                    'sw_qty'             => $qty,
                    'sw_unit'            => $unit,
                    'sw_price'           => $price,
                    'sw_totalprice'      => intval($qty) * intval($price),
                    'sw_note'            => $note,
                    'sw_insert'          => $sekarang
                ]);

            DB::commit();
            return Response::json([
                'status' => 'success'
            ]);
        } catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

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

    public function getDetailKPW(Request $request)
    {
        $sw_id = $request->sw_id;
        $data = DB::table('d_salesweb')
            ->join('m_company', 'c_id', '=', 'sw_agen')
            ->join('d_sales', 's_nota', '=', 'sw_reff')
            ->join('m_member', 'm_code', '=', 's_member')
            ->join('m_item', 'i_id', '=', 'sw_item')
            ->join('m_unit', 'u_id', '=', 'sw_unit')
            ->select('c_name', 'm_name', 'd_salesweb.*', 'i_name', 'u_name', 's_id')
            ->where('sw_id', '=', $sw_id)
            ->first();

        $kode = DB::table('d_salescode')
            ->where('sc_sales', '=', $data->s_id)
            ->get();

        return Response::json([
            'data' => $data,
            'kode' => $kode
        ]);
    }

    public function editKPW($id)
    {
        // dd($id);
        $datas = DB::table('d_salesweb')
            ->join('m_company', 'sw_agen', 'c_id')
            ->join('m_item', 'sw_item', 'i_id')
            ->join('m_unit', 'sw_unit', 'u_id')
            ->where('sw_id', '=', $id)->first();

        $units = DB::table('m_item')
            ->join('m_unit as unit1', 'i_unit1', 'unit1.u_id')
            ->join('m_unit as unit2', 'i_unit2', 'unit2.u_id')
            ->join('m_unit as unit3', 'i_unit3', 'unit3.u_id')
            ->select('unit1.u_id as id1', 'unit1.u_name as name1', 'unit2.u_id as id2', 'unit2.u_name as name2', 'unit3.u_id as id3', 'unit3.u_name as name3')
            ->where('i_id', '=', $datas->sw_item)
            ->first();

        $code = DB::table('d_salescode')
            ->join('d_sales', 'sc_sales', 'd_sales.s_id')
            ->join('d_salesweb', 's_nota', 'sw_reff')
            ->where('sw_id', '=', $id)->get();

        return Response::json([
            'datas' => $datas,
            'units' => $units,
            'code'  => $code,
            'dataId'=> Crypt::encrypt($id)
        ]);
    }

    public function updateKPW(Request $request)
    {
        // dd($request);
        try{
            $id = Crypt::decrypt($request->id);
        }catch (DecryptException $e){
            return view('errors.404');
        }

        // DB::beginTransaction();
        // try {
        $requestId = new \Illuminate\Http\Request();

        $requestId->replace(['id' => $id]);

        self::deleteKPW($requestId);
        self::saveKPW($request);

        $delete = self::deleteKPW($requestId);
        $save = self::saveKPW($request);

        $delete = $delete->original['status'];
        $save = $save->original['status'];

        if ($delete == 'sukses' && $save == 'success') {
            return Response::json([
                'status' => 'sukses'
            ]);
        } else {                
            return Response::json([
                'status'  => 'gagal'
            ]);
        }

        //     DB::commit();
        //     return Response::json([
        //         'status' => 'sukses'
        //     ]);
        // } catch (DecryptException $e) {
        //     DB::rollBack();
        //     return Response::json([
        //         'status'  => 'gagal',
        //         'message' => $e->getMessage()
        //     ]);
        // }
    }

    public function deleteKPW(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();
        try {
            //info sales
            $info = DB::table('d_salesweb')
                ->join('d_sales', 's_nota', '=', 'sw_reff')
                ->join('d_salesdt', 'sd_sales', '=', 's_id')
                ->get();
            //info kode sales
            $kode = DB::table('d_salescode')
                ->where('sc_sales', '=', $info[0]->s_id)
                ->get();
            //info stock
            $stock = DB::table('d_stock_mutation')
                ->join('d_stock', 's_id', '=', 'sm_stock')
                ->join('d_stockmutationdt', function ($q){
                    $q->on('smd_stock', '=', 's_id');
                    $q->on('smd_stockmutation', '=', 'sm_detailid');
                })
                ->where('sm_nota', '=', $info[0]->s_nota)
                ->first();

            for ($i = 0; $i < count($kode); $i++){
                //kembalikan kode ke pemilik
                $cek = DB::table('d_stockdt')
                    ->join('d_stock', 's_id', '=', 'sd_stock')
                    ->where('sd_code', '=', $kode[$i]->sc_code)
                    ->where('sd_stock', '=', $stock->s_id)
                    ->first();

                if ($cek != null){
                    //update qty
                    DB::table('d_stockdt')
                        ->where('sd_stock', '=', $cek->sd_stock)
                        ->where('sd_detailid', '=', $cek->sd_detailid)
                        ->update([
                            'sd_qty' => $cek->sd_qty + $kode[$i]->sc_qty
                        ]);

                    DB::table('d_stock')
                        ->where('s_id', '=', $cek->sd_stock)
                        ->update([
                            's_qty' => $cek->s_qty + $kode[$i]->sc_qty
                        ]);

                    //kembalikan ke stockmutasi
                    //data penjualan
                    $data = DB::table('d_stock_mutation')
                        ->where('sm_nota', '=', $info[0]->s_nota)
                        ->get();

                    for ($j = 0; $j < count($data); $j++){
                        //data pembelian
                        $pembelian = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $data[$j]->sm_stock)
                            ->where('sm_nota', '=', $data[$j]->sm_reff)
                            ->first();

                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $data[$j]->sm_stock)
                            ->where('sm_nota', '=', $data[$j]->sm_reff)
                            ->update([
                                'sm_use'     => $pembelian->sm_use - $kode[$i]->sc_qty,
                                'sm_residue' => $pembelian->sm_residue + $kode[$i]->sc_qty,
                            ]);
                    }
                } else {
                    //create
                    $cek = DB::table('d_stock')
                        ->where('s_id', '=', $stock[0]->s_id)
                        ->first();

                    $detailid = DB::table('d_stockdt')
                        ->where('sc_stock', '=', $stock[0]->s_id)
                        ->max('sd_detailid');
                    ++$detailid;
                    DB::table('d_salesdt')
                        ->insert([
                            'sd_stock'    => $stock[0]->s_id,
                            'sd_detailid' => $detailid,
                            'sd_code'     => $kode[$i]->sc_code,
                            'sd_qty'      => $kode[$i]->sc_qty
                        ]);
                    DB::table('d_stock')
                        ->where('s_id', '=', $stock[0]->s_id)
                        ->update([
                            's_qty' => $cek->s_qty + $kode[$i]->sc_qty
                        ]);

                    //kembalikan ke stockmutasi
                    //data penjualan
                    $data = DB::table('d_stock_mutation')
                        ->where('sm_nota', '=', $info[0]->s_nota)
                        ->get();
                    for ($j = 0; $j < count($data); $j++){
                        //data pembelian
                        $pembelian = DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $data[$j]->sm_stock)
                            ->where('sm_nota', '=', $data[$j]->sm_reff)
                            ->first();

                        DB::table('d_stock_mutation')
                            ->where('sm_stock', '=', $data[$j]->sm_stock)
                            ->where('sm_nota', '=', $data[$j]->sm_reff)
                            ->update([
                                'sm_use' => $pembelian->sm_use - $kode[$i]->sc_qty,
                                'sm_residue' => $pembelian->sm_residue + $kode[$i]->sc_qty,
                            ]);
                    }
                }
            }

            //delete d_salesweb
            DB::table('d_salesweb')
                ->where('sw_id', '=', $id)
                ->delete();

            //delete mutasi
            DB::table('d_stockmutationdt')
                ->join('d_stock_mutation', function ($q){
                    $q->on('sm_stock', '=', 'smd_stock');
                    $q->on('sm_detailid', '=', 'smd_stockmutation');
                })
                ->where('sm_nota', '=', $info[0]->s_nota)
                ->delete();

            DB::table('d_stock_mutation')
                ->where('sm_nota', '=', $info[0]->s_nota)
                ->delete();

            //delete d_sales
            DB::table('d_sales')
                ->where('s_nota', '=', $info[0]->s_nota)
                ->delete();

            //delete d_salesdt
            DB::table('d_salesdt')
                ->where('sd_sales', '=', $info[0]->s_id)
                ->delete();

            DB::table('d_salescode')
                ->where('sc_sales', '=', $info[0]->s_id)
                ->delete();

            DB::commit();
            return response()->json([
                'status' => 'sukses'
            ]);
        } catch (DecryptException $e) {
            DB::rollBack();
            return Response::json([
                'status'  => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

}
