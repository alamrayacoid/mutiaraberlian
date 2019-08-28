<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use App\Http\Controllers\AksesUser;
use const http\Client\Curl\AUTH_ANY;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\d_stock;
use App\d_stock_mutation;
use App\d_salescomp;
use App\d_salescompcode;
use App\d_salescomppayment;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_wil_provinsi;
use App\m_wil_kota;
use Carbon\Carbon;
use Currency;
use CodeGenerator;
use DB;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Mockery\Exception;
use Mutasi;
use Response;

class AgenKonsinyasiController extends Controller
{
    public function index()
    {
        return view('marketing.agen.datakonsinyasi.index');
    }

    // index -> read data and display to table
    public function getListDK(Request $request)
    {
        $branchCode = $request->branchCode;

        $datas = d_salescomp::where('sc_type', '=', 'K');

        // if pusat is logged in
        if (Auth::user()->getCompany->c_type == 'PUSAT') {
            // add filter which branch wil be shown
            $datas = $datas->where('sc_comp', $branchCode);
        } // if branch is logged in
        else {
            // show konsinyasi that is made by him
            $datas = $datas->where('sc_comp', Auth::user()->u_company)
                ->where('sc_comp', '!=', 'MB0000001');
        }
        $datas = $datas
            // ->where('sc_paidoff', 'N')
            ->with('getSalesCompDt')
            ->with('getAgent')
            ->orderBy('sc_date', 'desc')
            ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->sc_date)->format('d-m-Y');
            })
            ->addColumn('agent', function ($datas) {
                return $datas->getAgent->c_name;
            })
            ->addColumn('total', function ($datas) {
                return '<div class="text-right">Rp ' . number_format($datas->sc_total, 0, 0, '.') . '</div>';
            })
            ->addColumn('status', function ($datas) {
                if ($datas->sc_paidoff == 'N') {
                    $status = 'BELUM LUNAS';
                }
                else {
                    $status = 'LUNAS';
                }
                return $status;
            })
            ->addColumn('action', function ($datas) {
                if ($datas->sc_paidoff == 'N') {
                    $aksi = '<button class="btn btn-warning btn-edit-kons" type="button" title="Edit" onclick="editDK(\'' . Crypt::encrypt($datas->sc_id) . '\')"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-delete-kons" type="button" title="Delete" onclick="deleteDK(\'' . Crypt::encrypt($datas->sc_id) . '\')"><i class="fa fa-trash"></i></button>';
                }
                else {
                    // $aksi = '<button class="btn btn-info btn-detail-kons" type="button" title="Detail" onclick="detailDK(\'' . Crypt::encrypt($datas->sc_id) . '\')"><i class="fa fa-folder"></i></button>';
                    $aksi = '';
                }
                return '<div class="btn-group btn-group-sm">'. $aksi .'</div>';
            })
            ->rawColumns(['date', 'action', 'agent', 'total', 'status'])
            ->make(true);
    }

    public function getProv()
    {
        $prov = m_wil_provinsi::orderBy('wp_name', 'asc')->get();
        return response()->json($prov);
    }

    public function getKota($idprov = null)
    {
        $kota = m_wil_kota::where('wc_provinsi', $idprov)
        ->orderBy('wc_name')
        ->get();
        return response()->json($kota);
    }
    // get branch
    public function getAgentsDK(Request $request)
    {
        $prov = $request->prov;
        $kota = $request->city;

        $nama = m_company::where('c_area', $kota)
        ->where('c_type', 'CABANG')
        ->get();

        return response()->json($nama);
    }
    // get agents
    public function getKonsignerDK(Request $request)
    {
        $branch = $request->branch;

        // get agent
        $agent = m_company::where('c_id', $branch)
        ->with('getAgent')
        ->first();

        $nama = m_agen::where('a_parent', $agent->getAgent->a_code)
        ->with('getCompany')
        ->get();

        return response()->json($nama);
    }
    // get items
    public function getItemsDK(Request $request)
    {
        // set list items that is already exist
        $is_item = array();
        for ($i = 0; $i < count($request->idItem); $i++) {
            if ($request->idItem[$i] != null) {
                array_push($is_item, $request->idItem[$i]);
            }
        }

        $cari = $request->term;
        $comp = $request->branch;
        // dd($comp);
        // start: query to get items
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
        });

        if (count($is_item) != 0) {
            $nama = $nama->whereNotIn('i_id', $is_item);
        }

        $nama = $nama->where(function ($q) use ($cari) {
            $q->orWhere('i_name', 'like', '%' . $cari . '%');
            $q->orWhere('i_code', 'like', '%' . $cari . '%');
        })
        ->groupBy('d_stock.s_id')
        ->get();

        // end: query to get items
        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = [
                    'id' => $query->i_id,
                    'label' => $query->i_code . ' - ' . strtoupper($query->i_name),
                    'data' => $query,
                    'stock' => $query->s_id
                ];
            }
        }
        return response()->json($results);
    }
    // get item-listUnits
    public function getSatuanDK($id)
    {
        $data = m_item::where('i_id', $id)
        ->with('getUnit1')
        ->with('getUnit2')
        ->with('getUnit3')
        ->first();
        return response()->json($data);
    }
    // check item stock by unit
    public function checkItemStockDK(Request $request)
    {
        $stock = $request->idStock;
        $item = $request->itemId;
        $satuan = $request->unit;
        $qty = $request->qty;

        $data_check = DB::table('m_item')
        ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
        'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
        'm_item.i_unit3 as unit3')
        ->where('i_id', '=', $item)
        ->first();

        $data = DB::table('d_stock')
        ->where('s_id', '=', $stock)
        ->first();

        $data->sisa = $data->s_qty;

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

        return response()->json(floor($qty_compare));
    }
    // check item stock by unit
    public function checkItemStockDKOld(Request $request)
    {
        $stock = $request->idStock;
        $item = $request->itemId;
        $oldSatuan = $request->unitOld;
        $satuan = $request->unit;
        $qtyOld = $request->qtyOld;
        $qty = $request->qty;

        $data_check = DB::table('m_item')
            ->select('m_item.i_unitcompare1 as compare1', 'm_item.i_unitcompare2 as compare2',
                'm_item.i_unitcompare3 as compare3', 'm_item.i_unit1 as unit1', 'm_item.i_unit2 as unit2',
                'm_item.i_unit3 as unit3')
            ->where('i_id', '=', $item)
            ->first();

        $data = DB::table('d_stock')
        ->where('s_id', '=', $stock)
        ->first();

        $data->sisa = $data->s_qty;

        $qty_compare_old = 0;
        if ($oldSatuan == $data_check->unit1) {
            if ((int)$qty > (int)$data->sisa + $qtyOld) {
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

        return response()->json(floor($qty_compare_old));
    }
    // get item price
    public function checkHargaDK(Request $request)
    {
        $agent = $request->agentCode;
        if (is_null($agent)) {
            throw new \Exception("Silahkan pilih konsigner terlebih dahulu !", 1);
        }
        $item = $request->itemId;
        $unit = $request->unit;
        $qty = $request->qty;

        $type = m_agen::whereHas('getCompany', function ($q) use ($agent) {
            $q->where('c_id', '=', $agent);
        })
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

        return response()->json(number_format($harga, 0, '', ''));
    }
    // store
    public function storeDK(Request $request)
    {
        if (!AksesUser::checkAkses(23, 'create')) {
            return response()->json([
                'status' => "Failed",
                'message' => "Anda tidak memiliki akses ke menu ini !"
            ]);
        }

        $data = $request->all();
        $comp = $data['branchCode']; // pelaku konsinyasi
        $member = $data['agentCode']; // penerima item
        $compItem = $data['idStock']; // pemilik item
        $user = Auth::user()->u_id;
        $type = 'K';
        $date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $total = $data['tot_hrg'];
        $insert = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota = CodeGenerator::codeWithSeparator('d_salescomp', 'sc_nota', 8, 10, 3, 'SK', '-');
        $idSales = (DB::table('d_salescomp')->max('sc_id')) ? DB::table('d_salescomp')->max('sc_id') + 1 : 1;

        DB::beginTransaction();
        try {
            // get item owner
            foreach ($compItem as $key => $val) {
                $owner = d_stock::where('s_id', $val)->first();
                $compItem[$key] = $owner->s_comp;
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from / position
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength, // list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );

            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }

            $val_sales = [
                'sc_id' => $idSales,
                'sc_comp' => $comp,
                'sc_member' => $member,
                'sc_type' => $type,
                'sc_date' => $date,
                'sc_nota' => $nota,
                'sc_total' => $total,
                'sc_user' => $user,
                'sc_insert' => $insert,
                'sc_update' => $update
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
                    'scd_discvalue' => $data['diskon'][$i],
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$i])
                ];

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
                    $sumQtyPC += (int)$request->qtyProdCode[$j];
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

                // validate qty production-code
                if ($sumQtyPC != $qty_compare) {
                    $item = m_item::where('i_id', $data['idItem'][$i])->first();
                    throw new Exception("Jumlah kode produksi " . strtoupper($item->i_name) . " tidak sama dengan jumlah item yang dipesan !");
                }

                $stock = DB::table('d_stock')
                ->where('s_id', '=', $data['idStock'][$i])
                ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                ->where('sm_stock', '=', $stock->s_id)
                ->first();

                $posisi = DB::table('m_company')
                ->where('c_id', '=', $member)
                ->first();

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];
                $statusKons = 'cabang';

                // insert stock mutation sales 'out'
                $mutKonsOut = Mutasi::distributionOut(
                    $comp, // from (company-id)
                    $stock->s_comp, // item-owner (company-id)
                    $data['idItem'][$i], // item id
                    $qty_compare, // qty item
                    $nota, // nota distribution
                    null, // nota refference
                    $listPC, // list production-code
                    $listQtyPC, // list qty of production-code
                    $listUnitPC, // list unit of production-code
                    $sellPrice = null, // sellprice
                    13 // mutation category
                );
                if ($mutKonsOut->original['status'] !== 'success') {
                    return $mutKonsOut;
                }
                // set stock-parent-id
                $listStockParentId = $mutKonsOut->original['listStockParentId'];
                // get list
                $listSellPrice = $mutKonsOut->original['listSellPrice'];
                $listHPP = $mutKonsOut->original['listHPP'];
                $listSmQty = $mutKonsOut->original['listSmQty'];
                $listPCReturn = $mutKonsOut->original['listPCReturn'];
                $listQtyPCReturn = $mutKonsOut->original['listQtyPCReturn'];

                // insert stock mutation using sales 'in'
                $mutKonsIn = Mutasi::distributionIn(
                    $stock->s_comp, // item-owner (company-id)
                    $member, // destination (company-id)
                    $data['idItem'][$i], // item id
                    $nota, // nota sales
                    $listPCReturn, // list of list production-code (based on how many smQty used / each smQty has a list of prod-code)
                    $listQtyPCReturn, // list of list qty of production-code
                    $listUnitPC, // list  unit of production-code (unused)
                    $listSellPrice, // list of sellprice
                    $listHPP, // list of hpp
                    $listSmQty, // lsit of sm-qty (it got from salesOut, each qty used from different stock-mutation)
                    12, // mutation category
                    null, // stock parent id
                    $status = 'ON DESTINATION', // items status in stock
                    $condition = 'FINE' // item condition in stock
                );
                if ($mutKonsIn->original['status'] !== 'success') {
                    return $mutKonsIn;
                }

                $startProdCodeIdx += $prodCodeLength;
                $detailsd++;
            }

            // insert into db
            DB::table('d_salescomp')->insert($val_sales);
            DB::table('d_salescompdt')->insert($val_salesdt);

            DB::commit();
            return response()->json([
                'status' => "Success",
                'message' => "Data berhasil disimpan"
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "Failed",
                'message' => $e->getMessage()
            ]);
        }
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
    // get payment view
    public function bayar()
    {
        // $user = DB::table('m_company')
        //     ->join('m_agen', 'a_code', '=', 'c_user')
        //     ->where('c_id', '=', Auth::user()->u_company)
        //     ->first();
        $user = Auth::user()->with('getCompany.getAgent')->first();

        $konsigner = DB::table('m_agen')
            ->join('m_company', 'c_user', '=', 'a_code')
            ->where(function ($q) use ($user){
                $q->where('a_mma', '=', $user->getCompany->c_id);
            });
        if (!is_null($user->getCompany->getAgent)) {
            $konsigner = $konsigner->orWhere('a_parent', $user->getCompany->getAgent->a_code);
        }
        $konsigner = $konsigner->get();

        return view('marketing.agen.datakonsinyasi.bayar.index', compact('konsigner'));
    }
    // get list 'konsinyasi' for payment
    public function getData(Request $request)
    {
        $agen = 'all';
        $user = Auth::user();
        $sekarang = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $info = DB::table('d_salescomp as scc')
            ->join('m_company as member', 'member.c_id', '=', 'scc.sc_member')
            ->select(DB::raw('coalesce(floor((SELECT SUM(scp_pay) FROM d_salescomppayment scpm WHERE scpm.scp_salescomp = scc.sc_id)), 0) as pembayaran'),
                DB::raw('floor(sc_total - (SELECT(pembayaran))) AS sisa'), 'member.c_name', DB::raw('date_format(scc.sc_date, "%d-%m-%Y") as sc_date'),
                DB::raw('floor(scc.sc_total) as sc_total'), 'sc_id', 'sc_nota')
            ->where('sc_paidoff', '=', 'N')
            ->groupBy('sc_id')
            ->where('sc_comp', '=', $user->u_company);

        if (isset($request->konsigner) && $request->konsigner != '' && $request->konsigner !== null && $request->konsigner != 'semua'){
            $agen = $request->konsigner;
            $info = $info->where('sc_member', '=', $agen);
        }

        return DataTables::of($info)
            ->addColumn('aksi', function ($info) {
                return '<center><div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-primary hint--top hint--info" aria-label="Detail" onclick="detailnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-folder"></i></button>
                            <button type="button" class="btn btn-sm btn-danger hint--top hint--warning" aria-label="Bayar" onclick="bayarnotapiutang(\''.Crypt::encrypt($info->sc_id).'\')"><i class="fa fa-money"></i></button>
                        </div></center>';
            })
            ->editColumn('sisa', function ($info){
                return "<span class='text-right' style='width: 100%'>Rp. " . number_format($info->sisa, '0', ',', '.') . "</span>";
            })
            ->rawColumns(['aksi', 'sisa'])
            ->make(true);
    }
    // return view to edit data-konsinyasi
    public function editDK($id)
    {
        if (!AksesUser::checkAkses(23, 'update')) {
            abort(401);
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            return view('errors.404');
        }

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
            ->with(['getComp' => function ($q) {
                $q->with('getCity');
            }])
            ->with('getAgent')
            ->with('getComp')
            ->first();
        // set nota
        $nota = $data_item->sc_nota;
        // get stock item
        foreach ($data_item->getSalesCompDt as $key => $val) {
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
                } else {
                    $val->qtyUsed += 0;
                }
            }
            // set status of the distributed item (used or unused)
            if ($val->qtyUsed > 0) {
                $val->status = 'used';
            } else {
                $val->status = 'unused';
            }
        }

        $ids = Crypt::encrypt($id);
        // $ids = $id;

        return view('marketing/agen/datakonsinyasi/edit', compact('data_item', 'ids'));
    }
    // update data-konsinyasi
    public function updateDK(Request $request, $id)
    {
        if (!AksesUser::checkAkses(23, 'update')) {
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

        $data = $request->all();
        $comp = $data['agentCode']; // pelaku konsinyasi
        $member = $data['konsignerCode']; // penerima item
        $compItem = $data['idStock']; // pemilik item
        $user = Auth::user()->u_id;
        $total = $data['tot_hrg'];
        $update = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $nota = $data['nota'];

        DB::beginTransaction();
        try {
            // get item owner
            foreach ($compItem as $key => $val) {
                $owner = d_stock::where('s_id', $val)->first();
                $compItem[$key] = $owner->s_comp;
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
                } else {
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
                        'message' => $data['barang'][$localIdx] . " sudah digunakan, tidak dapat dilakukan modifikasi data !"
                        ]);
                    } else {
                        // delete production-code of selected stockdistribution
                        foreach ($konsDt->getProdCode as $idx => $prodCode) {
                            $prodCode->delete();
                        }
                        // skip item (not rollBack)
                        continue;
                    }
                }
                // rollback mutation 'out'
                $mutRollbackOut = Mutasi::rollbackSalesOut(
                    $konsinyasi->sc_nota, // nota
                    $konsDt->scd_item, // itemId
                    13 // mutcat-out
                );
                if ($mutRollbackOut->original['status'] !== 'success') {
                    return $mutRollbackOut;
                }
                // rollback mutation 'in'
                $mutRollbackIn = Mutasi::rollbackSalesIn(
                    $konsinyasi->sc_nota, // nota
                    $konsDt->scd_item, // itemId
                    12 // mutcat-out
                );
                if ($mutRollbackIn->original['status'] !== 'success') {
                    return $mutRollbackIn;
                }
                // // rollBack mutation
                // $rollbackKons = Mutasi::rollback(
                // $konsinyasi->sc_nota, // nota
                // $konsDt->scd_item, // itemId
                // 12 // mutcat
                // );
                // if (!is_bool($rollbackKons)) {
                //     DB::rollBack();
                //     return $rollbackKons;
                // }
                // delete production-code of selected stockdistribution

                foreach ($konsDt->getProdCode as $idx => $prodCode) {
                    $prodCode->delete();
                }
                // delete konsinyasi-detail
                $konsDt->delete();
            }

            // validate production-code is exist in stock-item
            $validateProdCode = Mutasi::validateProductionCode(
                Auth::user()->u_company, // from
                $request->idItem, // list item-id
                $request->prodCode, // list production-code
                $request->prodCodeLength, // list production-code length each item
                $request->qtyProdCode // list of qty each production-code
            );
            if ($validateProdCode !== 'validated') {
                return $validateProdCode;
            }

            // update salescomp
            $val_sales = [
                'sc_comp' => $comp,
                'sc_member' => $member,
                'sc_total' => (int)$total,
                'sc_user' => $user,
                'sc_update' => $update
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
                    $salescompdt->scd_discvalue = $data['diskon'][$key];
                    $salescompdt->scd_totalnet = Currency::removeRupiah($data['subtotal'][$key]);
                    $salescompdt->save();

                    // insert new production-code
                    $prodCodeLength = (int)$request->prodCodeLength[$key];
                    $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                    $sumQtyPC = 0;
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
                        $sumQtyPC += (int)$request->qtyProdCode[$j];
                    }
                    if ($sumQtyPC != (int)$data['jumlah'][$key]) {
                        $item = m_item::where('i_id', $data['idItem'][$key])->first();
                        throw new Exception("Jumlah kode produksi " . strtoupper($item->i_name) . " tidak sama dengan jumlah item yang dipesan !");
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
                    'scd_discvalue' => $data['diskon'][$key],
                    'scd_totalnet' => Currency::removeRupiah($data['subtotal'][$key])
                ];

                $prodCodeLength = (int)$request->prodCodeLength[$key];
                $endProdCodeIdx = $startProdCodeIdx + $prodCodeLength;
                $sumQtyPC = 0;
                $listPC = array();
                for ($j = $startProdCodeIdx; $j < $endProdCodeIdx; $j++) {
                    // skip inserting when val is null or qty-pc is 0
                    if ($request->prodCode[$j] == '' || $request->prodCode[$j] == null || $request->qtyProdCode[$j] == 0) {
                        continue;
                    }
                    array_push($listPC, strtoupper($request->prodCode[$j]));
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
                    $sumQtyPC += (int)$request->qtyProdCode[$j];
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

                if ($sumQtyPC != $qty_compare) {
                    $item = m_item::where('i_id', $data['idItem'][$key])->first();
                    throw new Exception("Jumlah kode produksi " . strtoupper($item->i_name) . " tidak sama dengan jumlah item yang dipesan !");
                }

                // get item stock
                $stock = DB::table('d_stock')
                ->where('s_id', '=', $data['idStock'][$key])
                ->first();

                $stock_mutasi = DB::table('d_stock_mutation')
                ->where('sm_stock', '=', $stock->s_id)
                ->first();

                $posisi = DB::table('m_company')
                ->where('c_id', '=', $member)
                ->first();

                // declaare list of production-code
                // $listPC = array_slice($request->prodCode, $startProdCodeIdx, $prodCodeLength);
                $listQtyPC = array_slice($request->qtyProdCode, $startProdCodeIdx, $prodCodeLength);
                $listUnitPC = [];
                $statusKons = 'cabang';

                // insert stock mutation sales 'out'
                $mutKonsOut = Mutasi::distributionOut(
                    $comp, // from (company-id)
                    $stock->s_comp, // item-owner (company-id)
                    $data['idItem'][$key], // item id
                    $qty_compare, // qty item
                    $nota, // nota distribution
                    null, // nota refference
                    $listPC, // list production-code
                    $listQtyPC, // list qty of production-code
                    $listUnitPC, // list unit of production-code
                    $sellPrice = null, // sellprice
                    13 // mutation category
                );
                if ($mutKonsOut->original['status'] !== 'success') {
                    return $mutKonsOut;
                }
                // set stock-parent-id
                $listStockParentId = $mutKonsOut->original['listStockParentId'];
                // get list
                $listSellPrice = $mutKonsOut->original['listSellPrice'];
                $listHPP = $mutKonsOut->original['listHPP'];
                $listSmQty = $mutKonsOut->original['listSmQty'];
                $listPCReturn = $mutKonsOut->original['listPCReturn'];
                $listQtyPCReturn = $mutKonsOut->original['listQtyPCReturn'];

                // insert stock mutation using sales 'in'
                $mutKonsIn = Mutasi::distributionIn(
                    $stock->s_comp, // item-owner (company-id)
                    $member, // destination (company-id)
                    $data['idItem'][$key], // item id
                    $nota, // nota sales
                    $listPCReturn, // list of list production-code (based on how many smQty used / each smQty has a list of prod-code)
                    $listQtyPCReturn, // list of list qty of production-code
                    $listUnitPC, // list  unit of production-code (unused)
                    $listSellPrice, // list of sellprice
                    $listHPP, // list of hpp
                    $listSmQty, // lsit of sm-qty (it got from salesOut, each qty used from different stock-mutation)
                    12, // mutation category
                    null, // stock parent id
                    $status = 'ON DESTINATION', // items status in stock
                    $condition = 'FINE' // item condition in stock
                );
                if ($mutKonsIn->original['status'] !== 'success') {
                    return $mutKonsIn;
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
                'message' => "Data berhasil diperbarui"
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

    // rollback and delete konsinyasi
    public function deleteDK($id)
    {
        if (!AksesUser::checkAkses(23, 'delete')) {
            return response()->json([
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
        try {
            // get konsinyasi
            $kons = d_salescomp::where('sc_id', $id)
            ->with('getSalesCompDt.getProdCode')
            ->first();

            // validate konsinyasi is ready to delete or not
            $mutcatOut = 13;
            $mutcatIn = 12;
            foreach ($kons->getSalesCompDt as $key => $val) {
                // get item-stock in destination
                $item = $val->scd_item;
                $st_mutation = d_stock_mutation::where('sm_nota', '=', $kons->sc_nota)
                    ->whereHas('getStock', function ($query) use ($item) {
                        $query->where('s_item', $item);
                    })
                    ->get();
                // count used item
                $qtyUsed = 0;
                foreach ($st_mutation as $keysm => $valsm) {
                    if ($valsm->sm_use > 0) {
                        $qtyUsed += $valsm->sm_use;
                    } else {
                        $qtyUsed += 0;
                    }
                }

                // item is used, break the delete operation
                if ($qtyUsed > 0) {
                    $item = m_item::where('i_id', $item)->first();
                    throw new Exception(strtoupper($item->i_name) . " sudah digunakan, konsinyasi tidak dapat dihapus !");
                }
                // item is unused, continue to delete
                else {
                    // rollback mutation 'distributionOut'
                    $mutRollbackOut = Mutasi::rollbackSalesOut(
                        $kons->sc_nota,
                        $val->scd_item,
                        $mutcatOut
                    );
                    if ($mutRollbackOut->original['status'] !== 'success') {
                        return $mutRollbackOut;
                    }

                    // rollback mutation 'in'
                    $mutRollbackIn = Mutasi::rollbackSalesIn(
                        $kons->sc_nota, // nota
                        $val->scd_item, // itemId
                        $mutcatIn // mutcat-out
                    );
                    if ($mutRollbackIn->original['status'] !== 'success') {
                        return $mutRollbackIn;
                    }

                    // delete production-code of selected stockdistribution
                    foreach ($val->getProdCode as $idx => $prodCode) {
                        $prodCode->delete();
                    }
                    // delete konsinyasi-detail
                    $val->delete();
                }
            }

            // get and delete konsinyasi-payment
            $konsPayment = d_salescomppayment::where('scp_salescomp', $kons->sc_id)->get();
            foreach ($konsPayment as $key => $value) {
                $value->delete();
            }

            // delete konsinyasi
            $kons->delete();

            DB::commit();
            return response()->json([
                'status' => "Success",
                'message' => 'Data berhasil dihapus'
            ]);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => "Failed",
                'message' => $e->getMessage()
            ]);
        }
    }
}
