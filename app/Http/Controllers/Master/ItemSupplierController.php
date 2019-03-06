<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Validator;
use carbon\Carbon;
use Yajra\DataTables\DataTables;
use Response;
use Crypt;

class ItemSupplierController extends Controller
{
    public function auto_item(Request $request){

        $getItemSelected = DB::table('d_itemsupplier')->where('is_supplier', $request->idSupp)->select('is_item')->get();

        $is_item = array();
        for($i = 0; $i < count($getItemSelected); $i++){
            array_push($is_item, $getItemSelected[$i]->is_item);
        }

        $cari = $request->term;
        $item = DB::table('m_item')
            ->select('i_id', 'i_name', 'i_code')
            ->whereNotIn('i_id', $is_item)
            ->whereRaw("i_name like '%" . $cari . "%'")
            ->orWhereRaw("i_code like '%" . $cari . "%'")
            ->get();

        if (count($item) == 0) {
            $hasilItem[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($item as $query) {
                if($query->i_code == null){
                    $hasilItem[] = [
                        'id' => $query->i_id,
                        'label' => $query->i_name
                    ];
                }else{
                    $hasilItem[] = [
                        'id' => $query->i_id,
                        'label' => $query->i_code.' - '.$query->i_name
                    ];
                }
            }
        }
        return Response::json($hasilItem);
    }

    public function get_itemDT(Request $request)
    {
        $getItemDT = DB::table('d_itemsupplier')
            ->join('m_item', 'i_id', '=', 'is_item')
            ->join('m_supplier', 's_id', '=', 'is_supplier')
            ->where('is_supplier', $request->idSupp)
            ->select('is_item', 'i_name', 'is_supplier', 's_company')->get();

        return DataTables::of($getItemDT)
            ->addIndexColumn()
            ->addColumn('aksi', function ($getItemDT) {
                $hapus = '<button class="btn btn-danger hint--bottom-left hint--error" rel="tooltip" data-placement="top" aria-label="Hapus Data" onclick="hapus(\'' . Crypt::encrypt($getItemDT->is_item) . '\',\''. Crypt::encrypt($getItemDT->is_supplier) .'\')"><i class="fa fa-close"></i></button>';
                return '<div class="btn-group btn-group-sm">' . $hapus . '</div>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function hapus($itemId, $suppId){
        DB::beginTransaction();
        try {
            $itemId = Crypt::decrypt($itemId);
            $suppId = Crypt::decrypt($suppId);
            DB::table('d_itemsupplier')->where('is_item', $itemId)->where('is_supplier', $suppId)->delete();

            DB::commit();
            return json_encode([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode([
                'status' => 'gagal',
                'msg' => $e
            ]);
        }
        
    }

    public function tambah(Request $request){
        DB::beginTransaction();
        try {
            DB::table('d_itemsupplier')->insert([
                'is_item' => $request->idItem,
                'is_supplier' => $request->idSupp
            ]);

            DB::commit();
            return json_encode([
                'status' => 'sukses'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode([
                'status' => 'gagal',
                'msg' => $e
            ]);
        }
    }
}
