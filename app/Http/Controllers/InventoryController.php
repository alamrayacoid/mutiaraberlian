<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\m_company as Company;
use App\d_stock as Stock;
use App\m_item as Item;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Mockery\Exception;
use DataTables;
use Response;

class InventoryController extends Controller
{
    // BARANG MASUK
    public function barangmasuk_index()
    {
        return view('inventory/barangmasuk/index');
    }

    public function barangmasuk_create()
    {
        return view('inventory/barangmasuk/create');
    }

    public function barangmasuk_edit()
    {
        return view('inventory/barangmasuk/edit');
    }

    // BARANG KELUAR

    public function barangkeluar_index()
    {
        return view('inventory/barangkeluar/index');
    }

    public function barangkeluar_create()
    {
        $company = DB::table('m_company')->select('c_id', 'c_name')->get();
        $unit = DB::table('m_unit')->get();

        $mutcat = DB::table('m_mutcat')->select('m_id', 'm_name')->where('m_name', 'like', 'Barang Keluar%')->get();


        return view('inventory/barangkeluar/create')->with(compact('unit', 'company', 'mutcat'));
    }

    public function barangkeluar_edit()
    {
        return view('inventory/barangkeluar/edit');
    }

    // DISTRIBUSI BARANG

    public function distribusibarang_index()
    {
        return view('inventory/distribusibarang/index');
    }

    public function distribusibarang_create()
    {
        return view('inventory/distribusibarang/distribusi/create');
    }

    public function distribusibarang_edit()
    {
        return view('inventory/distribusibarang/distribusi/edit');
    }

    // MANAJEMEN STOK

    public function manajemenstok_index()
    {
        return view('inventory/manajemenstok/index');
    }

    public function manajemenstok_create()
    {
        return view('inventory/manajemenstok/create');
    }

    public function manajemenstok_edit()
    {
        return view('inventory/manajemenstok/edit');
    }

    public function opname_stock()
    {
        return view('inventory/manajemenstok/opname/index');
    }

    public function opname_stock_create()
    {
        return view('inventory/manajemenstok/opname/opnamestock/create');
    }

    public function history_opname()
    {
        return view('inventory/manajemenstok/adjustment/index');
    }

    public function pengelolaanmms_index()
    {
        $companies = Company::get();
        return view('inventory.manajemenstok.pengelolaanmms.index')->with(compact('companies'));
    }

    public function dataStock()
    {
        $datas = Stock::join('m_company as comp', 'd_stock.s_comp', '=', 'comp.c_id')
            ->join('m_company as position', 'd_stock.s_position', '=', 'position.c_id')
            ->join('m_item', 'd_stock.s_item', '=', 'm_item.i_id')
            ->select('d_stock.s_id as id', 'comp.c_name as pemilik', 'position.c_name as posisi', 'm_item.i_name as item',
                'd_stock.s_qty as qty');

        return Datatables::of($datas)
            ->addColumn('pemilik', function ($datas) {
                return strtoupper($datas->pemilik);
            })
            ->addColumn('posisi', function ($datas) {
                return strtoupper($datas->posisi);
            })
            ->addColumn('item', function ($datas) {
                return strtoupper($datas->item);
            })
            ->addColumn('qty', function ($datas) {
                return '<div class="text-center"> ' . number_format($datas->qty, 0, ",", ".") . ' </div>';
            })
            ->addColumn('action', function ($datas) {
                return '<div class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\'' . Crypt::encrypt($datas->id) . '\')"><i class="fa fa-folder"></i></button>
                                <button class="btn btn-warning btn-edit" type="button" title="Edit" onclick="edit(\'' . Crypt::encrypt($datas->id) . '\')"><i class="fa fa-pencil"></i></button>
                            </div>
                        </div>';
            })
            ->rawColumns(['pemilik', 'posisi', 'item', 'qty', 'action'])
            ->make(true);
    }

    public function detailStock($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json(['status' => "Failed", 'message' => $e]);
        }

        $data = Stock::join('m_company as comp', 'd_stock.s_comp', '=', 'comp.c_id')
            ->join('m_company as position', 'd_stock.s_position', '=', 'position.c_id')
            ->join('m_item', 'd_stock.s_item', '=', 'm_item.i_id')
            ->where('d_stock.s_id', '=', $id)
            ->select('comp.c_name as pemilik', 'position.c_name as posisi', 'm_item.i_name as item',
                'd_stock.s_qty as qty', 'd_stock.s_qtymin as qtymin', 'd_stock.s_qtymax as qtymax',
                'd_stock.s_qtysafetystart as rangemin', 'd_stock.s_qtysafetyend as rangemax')
            ->first();
        $codes = DB::table('d_stockdt')->where('sd_stock', '=', $id)->select('sd_code as code', DB::raw('format(sd_qty, 0) as qty'))->get();
        return Response::json([
            'status' => "Success",
            'message' => $data,
            'codes' => $codes
        ]);

    }

    public function searchStock(Request $request)
    {
        $datas = Stock::join('m_company as comp', 'd_stock.s_comp', '=', 'comp.c_id')
            ->join('m_company as position', 'd_stock.s_position', '=', 'position.c_id')
            ->join('m_item', 'd_stock.s_item', '=', 'm_item.i_id')
            ->select('d_stock.s_id as id', 'comp.c_name as pemilik', 'position.c_name as posisi', 'm_item.i_name as item',
                'd_stock.s_qty as qty');

        if ($request->pemilik != "") {
            $datas->where('d_stock.s_comp', '=', $request->pemilik);
        }
        if ($request->posisi != "") {
            $datas->where('d_stock.s_position', '=', $request->posisi);
        }
        if ($request->item != "") {
            $datas->where('d_stock.s_item', '=', $request->item);
        }

        return Datatables::of($datas)
            ->addColumn('pemilik', function ($datas) {
                return strtoupper($datas->pemilik);
            })
            ->addColumn('posisi', function ($datas) {
                return strtoupper($datas->posisi);
            })
            ->addColumn('item', function ($datas) {
                return strtoupper($datas->item);
            })
            ->addColumn('qty', function ($datas) {
                return '<div class="text-center">' . $datas->qty . '</div>';
            })
            ->addColumn('action', function ($datas) {
                return '<div class="btn-group btn-group-sm">
                            <button class="btn btn-primary btn-detail" type="button" title="Detail" onclick="detail(\'' . Crypt::encrypt($datas->id) . '\')"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-warning btn-edit" type="button" title="Edit" onclick="edit(\'' . Crypt::encrypt($datas->id) . '\')"><i class="fa fa-pencil"></i></button>
                        </div>';
            })
            ->rawColumns(['pemilik', 'posisi', 'item', 'qty', 'action'])
            ->make(true);
    }

    public function pengelolaanmms_create()
    {
        $companies = Company::get();
        return view('inventory.manajemenstok.pengelolaanmms.create')->with(compact('companies'));
    }

    public function cariBarang(Request $request)
    {
        $cari = $request->term;

        $nama = DB::table('m_item')
            ->join('d_itemsupplier', 'is_item', '=', 'i_id')
            ->where(function ($q) use ($cari) {
                $q->orWhere('i_name', 'like', '%' . $cari . '%');
                $q->orWhere('i_code', 'like', '%' . $cari . '%');
            })
            ->groupBy('i_id')
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function addPengelolaanms(Request $request)
    {
        DB::beginTransaction();
        try {
            $check = Stock::where('s_comp', $request->pemilik)
                ->where('s_position', $request->posisi)
                ->where('s_item', $request->idBarang)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')->count();

            if ($check > 0) {
                $values = [
                    's_qtymin' => $request->minStock,
                    's_qtymax' => $request->maxStock,
                    's_qtysafetystart' => $request->firstRange,
                    's_qtysafetyend' => $request->secondRange
                ];

                Stock::where('s_comp', $request->pemilik)
                    ->where('s_position', $request->posisi)
                    ->where('s_item', $request->idBarang)
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE')
                    ->update($values);
            } else {
                $values = [
                    's_id' => (Stock::max('s_id')) ? Stock::max('s_id') + 1 : 1,
                    's_comp' => $request->pemilik,
                    's_position' => $request->posisi,
                    's_item' => $request->idBarang,
                    's_qty' => 0,
                    's_status' => "ON DESTINATION",
                    's_condition' => "FINE",
                    's_qtymin' => $request->minStock,
                    's_qtymax' => $request->maxStock,
                    's_qtysafetystart' => $request->firstRange,
                    's_qtysafetyend' => $request->secondRange
                ];
                Stock::insert($values);
            }
            DB::commit();
            return Response::json([
                'status' => "Success",
                'message' => "Data berhasil disimpan"
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json([
                'status' => "Failed",
                'message' => $e
            ]);
        }
    }

    public function pengelolaanmms_edit(Request $request, $id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        if ($request->isMethod('get')) {
            $data = Stock::join('m_company as comp', 'd_stock.s_comp', '=', 'comp.c_id')
                ->join('m_company as position', 'd_stock.s_position', '=', 'position.c_id')
                ->join('m_item', 'd_stock.s_item', '=', 'm_item.i_id')
                ->where('d_stock.s_id', '=', $id)
                ->select('d_stock.s_comp as pemilik', 'd_stock.s_position as posisi', 'd_stock.s_item as idItem', 'm_item.i_name as item',
                    'd_stock.s_qty as qty', 'd_stock.s_qtymin as qtymin', 'd_stock.s_qtymax as qtymax',
                    'd_stock.s_qtysafetystart as rangemin', 'd_stock.s_qtysafetyend as rangemax')
                ->first();

            $companies = Company::get();
            $idx = Crypt::encrypt($id);
            return view('inventory.manajemenstok.pengelolaanmms.edit')->with(compact('data', 'companies', 'idx'));
        } else if ($request->isMethod('post')) {
            DB::beginTransaction();
            try {
                if ($request->idBarang == "") {
                    $iditem = $request->idItem;
                } else {
                    $iditem = $request->idBarang;
                }

                $values = [
                    's_comp' => $request->pemilik,
                    's_position' => $request->posisi,
                    's_item' => $iditem,
                    's_qtymin' => $request->qtymin,
                    's_qtymax' => $request->qtymax,
                    's_qtysafetystart' => $request->rangemin,
                    's_qtysafetyend' => $request->rangemax
                ];

                Stock::where('s_id', $id)->update($values);
                DB::commit();
                return Response::json([
                    'status' => "Success",
                    'message' => "Data berhasil diperbarui"
                ]);
            } catch (Exception $e) {
                DB::rollback();
                return Response::json([
                    'status' => "Failed",
                    'message' => $e
                ]);
            }
        }
    }

    public function analisaTO()
    {
        return view('inventory.manajemenstok.analisastockto.index');
    }

    public function analisaTO_list()
    {
        $data = DB::table('d_salescomp')
            ->join('d_salescompdt', 'scd_sales', 'sc_id')
            ->join('m_item', 'scd_item', 'm_item.i_id')
            ->join('d_stock_mutation', 'sc_nota', 'sm_nota')
            ->join('m_mutcat', 'sm_mutcat', 'm_mutcat.m_id')
            ->where('sc_paidoff', '=', 'Y')
            ->where('m_mutcat.m_status', '=', 'K')
            ->groupBy('scd_item');
        // dd($data->sm_hpp);

        return DataTables::of($data)
            // ->addIndexColumn()
            // ->addColumn('ratarata', function($data) {
            //     for ($i=0; $i < count($data->sm_hpp) ; $i++) { 

            //     }
            //     return strtoupper($data->pemilik);
            // })
            // ->rawColumns(['ratarata'])
            ->make(true);
    }

}
