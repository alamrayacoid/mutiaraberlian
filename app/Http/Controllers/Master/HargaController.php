<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Response;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Contracts\Encryption\DecryptException;

class HargaController extends Controller
{
    public function dataharga()
    {
        return view('masterdatautama.harga.index');
    }

    public function getGolongan()
    {
        $datas = DB::table('m_priceclass')->orderBy('pc_name', 'asc');
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<center><div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" title="Edit"
                                                    type="button" onclick="editGolongan(\''.Crypt::encrypt($datas->pc_id).'\', \''.$datas->pc_name.'\')"><i class="fa fa-pencil" style="color: #ffffff"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                    title="Hapus" onclick="hapusGolongan(\''.Crypt::encrypt($datas->pc_id).'\')"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-primary" title="add"
                                                    type="button" onclick="addGolonganHarga(\''.Crypt::encrypt($datas->pc_id).'\', \''.$datas->pc_name.'\')"><i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div></center>';

            })
            ->rawColumns(['detail', 'action'])
            ->make(true);
    }

    public function addGolongan(Request $request)
    {
        DB::beginTransaction();
        try{
            $values = [
                'pc_id' => (DB::table('m_priceclass')->max('pc_id')) ? (DB::table('m_priceclass')->max('pc_id'))+1 : 1,
                'pc_name' => $request->nama,
                'pc_insert' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'pc_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ];
            DB::table('m_priceclass')->insert($values);
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function editGolongan(Request $request)
    {
        try{
            $id = Crypt::decrypt($request->idGolongan);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try{
            DB::table('m_priceclass')->where('pc_id', $id)->update([
                'pc_name' => $request->namaGolongan,
                'pc_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]);
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function deleteGolongan($id)
    {
        try{
            $id = Crypt::decrypt($id);
        }catch (DecryptException $e){
            return response()->json(['status'=>"Failed"]);
        }

        DB::beginTransaction();
        try{
            DB::table('m_priceclass')->where('pc_id', $id)->delete();
            DB::commit();
            return response()->json(['status'=>"Success"]);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status'=>"Failed"]);
        }
    }

    public function cariBarang(Request $request)
    {
        $cari = $request->term;
        $results = [];
        $kode = [];
        if (isset($request->kode)) {
            $kode = $request->kode;
            if (($key = array_search(null, $kode)) !== false) {
                unset($kode[$key]);
            }
            $temp = [];
            foreach ($kode as $code){
                array_push($temp, $code);
            }
            $kode = $temp;
        }

        if (count($kode) > 0) {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari, $kode){
//                    $q->whereNotIn('i_code', $kode);
                    $q->where('i_code', 'like', '%'.$cari.'%');
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                })
                ->whereNotIn('i_code', $kode)->get();
        } else {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari){
                    $q->where('i_code', 'like', '%'.$cari.'%');
                    $q->orWhere('i_name', 'like', '%'.$cari.'%');
                })->get();
        }

        if (count($nama) < 1) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' .strtoupper($query->i_name), 'data' => $query];
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

    public function create_golonganharga()
    {
        return view('masterdatautama.harga.golongan.create');
    }

    public function edit_golonganharga()
    {
        return view('masterdatautama.harga.golongan.edit');
    }
}
