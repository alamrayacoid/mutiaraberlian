<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Carbon\Carbon;
use Currency;
use CodeGenerator;
use DB;
use App\d_stock;
use App\m_agen;
use App\m_company;
use App\m_item;
use App\m_wil_provinsi;
use App\m_wil_kota;
use Mutasi;

class AgenKonsinyasiController extends Controller
{
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
    public function getBranchDK(Request $request)
    {
        $prov = $request->prov;
        $kota = $request->city;

        $nama = m_company::where('c_area', $kota)
        ->where('c_type', 'CABANG')
        ->get();

        return response()->json($nama);
    }
    // get agents
    public function getAgentsDK(Request $request)
    {
        $branch = $request->branch;

        // get agent
        $agent = m_company::where('c_id', $branch)
        ->with('getAgent')
        ->first();

        // $nama = m_agen::where('a_mma', $branch)
        // ->with('getCompany')
        // ->get();

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
        ->join('d_stock_mutation', function ($sm) {
            $sm->on('sm_stock', '=', 's_id');
        })
        ->where('s_id', '=', $stock)
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

        return response()->json(floor($qty_compare_old));
    }
    // get item price
    public function checkHargaDK(Request $request)
    {
        $agent = $request->agentCode;
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
        if (!AksesUser::checkAkses(22, 'create')) {
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





}