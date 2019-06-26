<?php

namespace App\Http\Controllers\Aktivitasmarketing\Konsinyasipusat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;

use Auth;
use App\d_salescomp;
use App\d_salescompdt;
use App\d_salescompcode;
use App\d_salescomppayment;
use App\d_stock;
use App\d_stock_mutation;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_wil_provinsi;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use DataTables;
use DB;
use Mutasi;
use Response;
use Validator;

class KonsinyasiPusatController extends Controller
{
    // =========================================================================
    // Konsinyasi Pusat
    public function getKonsinyasi()
    {
        $data = DB::table('d_salescomp')
        ->join('d_salescompdt', function ($sd){
            $sd->on('scd_sales', '=', 'sc_id');
        })
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

    public function detailKonsinyasi($id = null, $action = null)
    {
        try {
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e){
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
        }
        else{
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
        $data = m_item::where('i_id', $id)
        ->with('getUnit1')
        ->with('getUnit2')
        ->with('getUnit3')
        ->first();

        // $data = DB::table('m_item')
        // ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1','b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
        // ->where('m_item.i_id', '=', $id)
        // ->join('m_unit as a', function ($x){
        //     $x->on('m_item.i_unit1', '=', 'a.u_id');
        // })
        // ->leftjoin('m_unit as b', function ($y){
        //     $y->on('m_item.i_unit2', '=', 'b.u_id');
        // })
        // ->leftjoin('m_unit as c', function ($z){
        //     $z->on('m_item.i_unit3', '=', 'c.u_id');
        // })
        // ->first();

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
            }
            else if ($qty > 1) {
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
        foreach ($array as $key =>  $val) {
            $x = in_array($value, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend));
            if ($x == true) {
                $idx = $key;
                break;
            }
        }
        return $idx;
    }

    public function create_penempatanproduk()
    {
        return view('marketing/konsinyasipusat/penempatanproduk/create');
    }

    public function add_penempatanproduk(Request $request)
    {
        $data   = $request->all();
        $comp   = Auth::user()->u_company;
        $compItem = $data['idStock']; // pemilik item
        // $member = $data['kodeKonsigner'];
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

        // get item owner
        foreach ($compItem as $key => $val) {
            $owner = d_stock::where('s_id', $val)->first();
            $compItem[$key] = $owner->s_comp;
        }

        // validate production-code is exist in stock-item
        $validateProdCode = Mutasi::validateProductionCode(
            Auth::user()->u_company, // from
            $request->idItem, // list item-id
            $request->prodCode, // list production-code
            $request->prodCodeLength // list production-code length each item
        );
        if ($validateProdCode !== 'validated') {
            return $validateProdCode;
        }

        try {
            $val_sales = [
                'sc_id'      => $idSales,
                'sc_comp'    => $comp, // pelaku konsinyasi
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
                // values for insert to salescomp-dt
                $val_salesdt[] = [
                    'scd_sales' => $idSales,
                    'scd_detailid' => $detailsd,
                    'scd_comp' => $compItem[$i], // pemilik item
                    'scd_item' => $data['idItem'][$i],
                    'scd_qty' => $data['jumlah'][$i],
                    'scd_unit' => $data['satuan'][$i],
                    'scd_value' => Currency::removeRupiah($data['harga'][$i]),
                    'scd_discpersen' => 0,
                    'scd_discvalue' => 0,
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
                ];

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
                    $detailidcode = d_salescompcode::where('ssc_salescomp', $idSales)
                    ->where('ssc_item', $data['idItem'][$i])
                    ->max('ssc_detailid') + 1;

                    $val_salescode = [
                        'ssc_salescomp' => $idSales,
                        'ssc_item' => $data['idItem'][$i],
                        'ssc_detailid' => $detailidcode,
                        'ssc_code' => strtoupper($request->prodCode[$j]),
                        'ssc_qty' => $request->qtyProdCode[$j]
                    ];
                    DB::table('d_salescompcode')->insert($val_salescode);
                }

                // mutasi
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

                $stock = DB::table('d_stock')
                ->where('s_id', '=', $data['idStock'][$i])
                // ->where('s_position', '=', $comp)
                // ->where('s_item', '=', $data['idItem'][$i])
                // ->where('s_status', '=', 'ON DESTINATION')
                // ->where('s_condition', '=', 'FINE')
                ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                ->where('sm_stock', '=', $stock->s_id)
                ->first();

                $posisi = DB::table('m_company')
                ->where('c_id', '=', $member)
                ->first();

                // declaare list of production-code
                $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];

                // set mutation (mutation-out is called inside mutation-in)
                $mutKons = Mutasi::mutasimasuk(
                    12, // mutcat
                    $stock->s_comp, // comp / item-owner
                    $posisi->c_id, // position / destination
                    $data['idItem'][$i], // item-id
                    $qty_compare, // qty item with smallest unit
                    'ON DESTINATION', // status
                    'FINE', // condition
                    $stock_mutasi->sm_hpp, // hpp
                    $sellPrice, // sell value
                    $nota, // nota
                    $stock_mutasi->sm_nota, // nota refference
                    $listPC, // list production-code
                    $listQtyPC // list qty roduction code
                );
                if (!is_bool($mutKons)) {
                    return $mutKons;
                }

                $startProdCodeIdx += $prodCodeLength;
                $detailsd++;
            }
            // insert into db
            DB::table('d_salescomp')->insert($val_sales);
            DB::table('d_salescompdt')->insert($val_salesdt);

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil disimpan"
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }

    public function edit_penempatanproduk(Request $request, $id = null)
    {
        try{
            $id = Crypt::decrypt($id);
        }
        catch (DecryptException $e){
            return abort(404);
        }

        // post method -> update
        if ($request->isMethod('post')) {
            $data   = $request->all();
            $comp   = Auth::user()->u_company;
            $compItem = $data['idStock']; // pemilik item
            // $member = $data['kodeKonsigner'];
            $member = $data['idKonsigner'];
            $user   = Auth::user()->u_id;
            $total  = $data['tot_hrg'];
            $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $nota   = $data['nota'];

            DB::beginTransaction();
            try{
                // get item owner
                foreach ($compItem as $key => $val) {
                    $owner = d_stock::where('s_id', $val)->first();
                    $compItem[$key] = $owner->s_comp;
                }

                // validate production-code is exist in stock-item
                $validateProdCode = Mutasi::validateProductionCode(
                    Auth::user()->u_company, // from
                    $request->idItem, // list item-id
                    $request->prodCode, // list production-code
                    $request->prodCodeLength // list production-code length each item
                );
                if ($validateProdCode !== 'validated') {
                    return $validateProdCode;
                }
                // get konsinyasi by id
                $konsinyasi = d_salescomp::where('sc_id', $id)
                ->with('getSalesCompDt.getProdCode')
                ->first();
                // rollBack konsinyasi-detail
                foreach ($konsinyasi->getSalesCompDt as $key => $konsDt) {
                    // set index item by array_search
                    if (in_array($konsDt->scd_item, $data['idItem'])) {
                        $localIdx = array_search($konsDt->scd_item, $data['idItem']);
                    }
                    else {
                        $localIdx = 0;
                    }
                    // check used item is-modified
                    if (in_array($konsDt->scd_item, $data['idItem']) && $data['status'][$localIdx] == 'used') {
                        // get salescompdt from db
                        $recordFromDb = [
                            'scd_item' => $konsDt->scd_item,
                            'scd_qty' => $konsDt->scd_qty,
                            'scd_unit' => $konsDt->scd_unit,
                            'scd_value' => (int)$konsDt->scd_value,
                            'scd_totalnet' => (int)$konsDt->scd_totalnet
                        ];
                        // set salescompdt from input/request
                        $newRecord = [
                            'scd_item' => (int)$data['idItem'][$localIdx],
                            'scd_qty' => (int)$data['jumlah'][$localIdx],
                            'scd_unit' => (int)$data['satuan'][$localIdx],
                            'scd_value' => (int)Currency::removeRupiah($data['harga'][$localIdx]),
                            'scd_totalnet' => (int)Currency::removeRupiah($data['subtotal'][$localIdx])
                        ];
                        // compare the result, return failed if different
                        if (sizeof(array_diff($recordFromDb, $newRecord)) != 0) {
                            DB::rollBack();
                            return Response::json([
                                'status' => "Failed",
                                'message'=> $data['barang'][$localIdx]. " sudah digunakan, tidak dapat dilakukan modifikasi data !"
                            ]);
                        }
                        else {
                            // delete production-code of selected stockdistribution
                            foreach ($konsDt->getProdCode as $idx => $prodCode) {
                                $prodCode->delete();
                            }
                            // skip item (not rollBack)
                            continue;
                        }
                    }
                    // rollBack mutation
                    $rollbackKons = Mutasi::rollback(
                        $konsinyasi->sc_nota, // nota
                        $konsDt->scd_item, // itemId
                        12 // mutcat
                    );
                    if (!is_bool($rollbackKons)) {
                        DB::rollBack();
                        return $rollbackKons;
                    }
                    // delete production-code of selected stockdistribution
                    foreach ($konsDt->getProdCode as $idx => $prodCode) {
                        $prodCode->delete();
                    }
                    // delete konsinyasi-detail
                    $konsDt->delete();
                }

                // update salescomp
                $val_sales = [
                    'sc_comp'    => $comp,
                    'sc_member'  => $member,
                    'sc_total'   => $total,
                    'sc_user'    => $user,
                    'sc_update'  => $update
                ];
                // Update konsinyasi
                $updateSalesComp = DB::table('d_salescomp')
                ->where('sc_id', '=', $id)
                ->update($val_sales);

                // re-insert konsinyasi-detail
                $sddetail = (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) ? (DB::table('d_salescompdt')->where('scd_sales', '=', $id)->max('scd_detailid')) + 1 : 1;
                $detailsd = $sddetail;
                $val_salesdt = [];
                // values for insert to salescomp-code
                $startProdCodeIdx = 0;

                foreach ($data['idItem'] as $key => $itemId) {
                    if ($data['status'][$key] === 'used') {
                        // get konsinyasi-detail
                        $salescompdt = d_salescompdt::where('scd_sales', $id)
                        ->where('scd_item', $itemId)
                        ->first();

                        // update salescompdt
                        $salescompdt->scd_qty = $data['jumlah'][$key];
                        $salescompdt->scd_unit = $data['satuan'][$key];
                        $salescompdt->scd_value = Currency::removeRupiah($data['harga'][$key]);
                        $salescompdt->scd_totalnet = Currency::removeRupiah($data['subtotal'][$key]);
                        $salescompdt->save();

                        // insert new production-code
                        $prodCodeLength = (int)$request->prodCodeLength[$key];
                        $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                        for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                            // skip inserting when val is null or qty-pc is 0
                            if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                                continue;
                            }
                            $detailidcode = d_salescompcode::where('ssc_salescomp', $id)
                            ->where('ssc_item', $data['idItem'][$key])
                            ->max('ssc_detailid') + 1;

                            $val_salescode = [
                                'ssc_salescomp' => $id,
                                'ssc_item' => $data['idItem'][$key],
                                'ssc_detailid' => $detailidcode,
                                'ssc_code' => strtoupper($request->prodCode[$j]),
                                'ssc_qty' => $request->qtyProdCode[$j]
                            ];
                            DB::table('d_salescompcode')->insert($val_salescode);
                        }
                        // increments production-code index
                        $startProdCodeIdx += $prodCodeLength;
                        continue;
                    }

                    // set new value for re-insert konsinyasi-detail /salescompdt
                    $val_salesdt[] = [
                        'scd_sales' => $id,
                        'scd_detailid' => $detailsd,
                        'scd_comp' => $compItem[$key],
                        'scd_item' => $data['idItem'][$key],
                        'scd_qty' => $data['jumlah'][$key],
                        'scd_unit' => $data['satuan'][$key],
                        'scd_value' => Currency::removeRupiah($data['harga'][$key]),
                        'scd_discpersen' => 0,
                        'scd_discvalue' => 0,
                        'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$key])
                    ];

                    $prodCodeLength = (int)$request->prodCodeLength[$key];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                        // skip inserting when val is null or qty-pc is 0
                        if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                            continue;
                        }
                        $detailidcode = d_salescompcode::where('ssc_salescomp', $id)
                        ->where('ssc_item', $data['idItem'][$key])
                        ->max('ssc_detailid') + 1;

                        $val_salescode = [
                            'ssc_salescomp' => $id,
                            'ssc_item' => $data['idItem'][$key],
                            'ssc_detailid' => $detailidcode,
                            'ssc_code' => strtoupper($request->prodCode[$j]),
                            'ssc_qty' => $request->qtyProdCode[$j]
                        ];
                        DB::table('d_salescompcode')->insert($val_salescode);
                    }

                    // mutasi
                    $data_check = DB::table('m_item')
                    ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                    'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                    'm_item.i_unit3 as unit3')
                    ->where('i_id', '=', $data['idItem'][$key])
                    ->first();
                    // get qty with smallest unit
                    $qty_compare = 0;
                    $sellPrice = 0;
                    if ($data['satuan'][$key] == $data_check->unit1) {
                        $qty_compare = $data['jumlah'][$key];
                        $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]);
                    } else if ($data['satuan'][$key] == $data_check->unit2) {
                        $qty_compare = $data['jumlah'][$key] * $data_check->compare2;
                        $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]) / $data_check->compare2;
                    } else if ($data['satuan'][$key] == $data_check->unit3) {
                        $qty_compare = $data['jumlah'][$key] * $data_check->compare3;
                        $sellPrice = (int)Currency::removeRupiah($data['harga'][$key]) / $data_check->compare3;
                    }

                    // get item stock
                    $stock = DB::table('d_stock')
                    ->where('s_id', '=', $data['idStock'][$key])
                    ->where('s_position', '=', $comp)
                    ->where('s_item', '=', $data['idItem'][$key])
                    ->where('s_status', '=', 'ON DESTINATION')
                    ->where('s_condition', '=', 'FINE')
                    ->first();

                    $stock_mutasi = DB::table('d_stock_mutation')
                    ->where('sm_stock', '=', $stock->s_id)
                    ->first();

                    $posisi = DB::table('m_company')
                    ->where('c_id', '=', $member)
                    ->first();

                    // declaare list of production-code
                    $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                    $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                    $listUnitPC = [];

                    // set mutation (mutation-out is called inside mutation-in)
                    $mutKons = Mutasi::mutasimasuk(
                        12, // mutcat
                        $stock->s_comp, // comp / item-owner
                        $posisi->c_id, // position / destination
                        $data['idItem'][$key], // item-id
                        $qty_compare, // qty item with smallest unit
                        'ON DESTINATION', // status
                        'FINE', // condition
                        $stock_mutasi->sm_hpp, // hpp
                        $sellPrice, // sell value
                        $nota, // nota
                        $stock_mutasi->sm_nota, // nota refference
                        $listPC, // list production-code
                        $listQtyPC // list qty roduction code
                    );
                    if (!is_bool($mutKons)) {
                        return $mutKons;
                    }

                    // increments production-code index
                    $startProdCodeIdx += $prodCodeLength;
                    // increments detailid
                    $detailsd++;
                }

                // re-insert data in konsinyasi-detail
                DB::table('d_salescompdt')->insert($val_salesdt);

                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message'=> "Data berhasil diperbarui"
                ]);
            }
            catch (\Exception $e){
                DB::rollBack();
                return Response::json([
                    'status' => "Failed",
                    'message'=> $e->getMessage()
                ]);
            }
        }
        // another method -> edit
        else {
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

            $data_item = d_salescomp::where('sc_id', $id)
            ->with(['getSalesCompDt' => function ($query) {
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
            ->first();
            // set nota
            $nota = $data_item->sc_nota;
            // get stock item
            foreach ($data_item->getSalesCompDt as $key => $val)
            {
                $item = $val->scd_item;
                // get item stock
                $mainStock = d_stock::where('s_comp', $val->scd_comp)
                ->where('s_position', $data_item->sc_comp)
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

                // get item-stock in destination
                $st_mutation = d_stock_mutation::where('sm_nota', '=', $nota)
                    ->whereHas('getStock', function ($query) use ($item) {
                        $query->where('s_item', $item);
                    })
                    ->get();

                foreach ($st_mutation as $keysm => $valsm) {
                    if ($valsm->sm_use > 0) {
                        $val->qtyUsed += $valsm->sm_use;
                    }
                    else {
                        $val->qtyUsed += 0;
                    }
                }
                // set status of the distributed item (used or unused)
                if ($val->qtyUsed > 0) {
                    $val->status = 'used';
                }
                else {
                    $val->status = 'unused';
                }
            }

            $ids = Crypt::encrypt($id);

            return view('marketing/konsinyasipusat/penempatanproduk/edit')->with(compact('detail', 'data_item', 'ids'));
        }
    }

    public function deletePenempatanproduk(Request $request)
    {
        // if (!AksesUser::checkAkses(21, 'delete')){
        //     abort(401);
        // }

        try {
            $id = Crypt::decrypt($request->id);
        }
        catch (DecryptException $e){
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }

        DB::beginTransaction();
        try{
            $konsinyasi = d_salescomp::where('sc_id', $id)
            ->with('getSalesCompDt.getProdCode')
            ->first();

            foreach ($konsinyasi->getSalesCompDt as $key => $konsDt) {
                $rollbackKons = Mutasi::rollback(
                    $konsinyasi->sc_nota, // nota
                    $konsDt->scd_item, // itemId
                    12 // mutcat
                );
                if (!is_bool($rollbackKons)) {
                    DB::rollBack();
                    return $rollbackKons;
                }
                // delete production-code of selected stockdistribution
                foreach ($konsDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                // delete konsinyasi-detail
                $konsDt->delete();
            }
            // delete konsinyasi
            $konsinyasi->delete();

            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> 'Data berhasil dihapus'
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
            return Response::json([
                'status' => "Failed",
                'message'=> $e->getMessage()
            ]);
        }
    }

    // =========================================================================
    // Monitoring Penjualan
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
    // get detail
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
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }


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
}
