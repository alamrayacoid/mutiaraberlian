<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\m_company as Company;
use App\d_stock as Stock;
use Mockery\Exception;
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
        return view('inventory.manajemenstok.pengelolaanmms.index');
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
            ->where(function ($q) use ($cari){
                $q->orWhere('i_name', 'like', '%'.$cari.'%');
                $q->orWhere('i_code', 'like', '%'.$cari.'%');
            })
            ->get();

        if (count($nama) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function addPengelolaanms(Request $request)
    {
        DB::beginTransaction();
        try{
            $check = Stock::where('s_comp', $request->pemilik)
                ->where('s_position', $request->posisi)
                ->where('s_item', $request->idBarang)
                ->where('s_status', 'ON DESTINATION')
                ->where('s_condition', 'FINE')->count();

            if ($check > 0) {
                $values = [
                    's_qtymin'          => $request->minStock,
                    's_qtymax'          => $request->maxStock,
                    's_qtysafetystart'  => $request->firstRange,
                    's_qtysafetyend'    => $request->secondRange
                ];

                Stock::where('s_comp', $request->pemilik)
                    ->where('s_position', $request->posisi)
                    ->where('s_item', $request->idBarang)
                    ->where('s_status', 'ON DESTINATION')
                    ->where('s_condition', 'FINE')
                    ->update($values);
            } else {
                $values = [
                    's_id'              => (Stock::max('s_id')) ? Stock::max('s_id') + 1 : 1,
                    's_comp'            => $request->pemilik,
                    's_position'        => $request->posisi,
                    's_item'            => $request->idBarang,
                    's_qty'             => 0,
                    's_status'          => "ON DESTINATION",
                    's_condition'       => "FINE",
                    's_qtymin'          => $request->minStock,
                    's_qtymax'          => $request->maxStock,
                    's_qtysafetystart'  => $request->firstRange,
                    's_qtysafetyend'    => $request->secondRange
                ];
                Stock::insert($values);
            }
            DB::commit();
            return Response::json([
                'status' => "Success",
                'message'=> "Data berhasil disimpan"
            ]);
        }catch (Exception $e){
            DB::rollback();
            return Response::json([
                'status' => "Failed",
                'message'=> $e
            ]);
        }
    }
    
    public function pengelolaanmms_edit()
    {
        return view('inventory.manajemenstok.pengelolaanmms.edit');
    }

}
