<?php

namespace App\Http\Controllers\Master;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use DB;
use Response;

class EkspedisiController extends Controller
{
    public function index()
    {
        return view('masterdatautama.ekspedisi.index');
    }

    public function getData()
    {
        $data = DB::table('m_expedition')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data){
                if ($data->e_isactive == 'Y'){
                    return '<div class="btn-group btn-group-sm">
                <button class="btn btn-danger btn-process" type="button" onclick="nonaktifEkspedisi(' . $data->e_id . ')" title="Nonaktif"><i class="fa fa-close"></i></button>
                <button class="btn btn-primary btn-process" type="button" onclick="detailEkspedisi(' . $data->e_id . ',\'' .$data->e_name. '\')" title="Detail"><i class="fa fa-arrow-right"></i></button>
                </div>';
                } else {
                    return '<div class="btn-group btn-group-sm">
                <button class="btn btn-success" type="button" onclick="enableEkspedisi(' . $data->e_id . ')" title="Aktifkan"><i class="fa fa-check"></i></button>
                <button class="btn btn-primary btn-process" type="button" onclick="detailEkspedisi(' . $data->e_id . ',\'' .$data->e_name. '\')" title="Detail"><i class="fa fa-arrow-right"></i></button>
                </div>';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function save(Request $request)
    {
        DB::beginTransaction();
        try {
            $name = $request->name;
            $check = DB::table('m_expedition')
                ->where('e_name', '=', $name)
                ->get();
            if (count($check) > 0){
                return Response::json([
                    'status' => 'gagal',
                    'message' => 'Data sudah ada'
                ]);
            }
            $id = DB::table('m_expedition')
                ->max('e_id');
            ++$id;
            DB::table('m_expedition')
                ->insert([
                    'e_id' => $id,
                    'e_name' => strtoupper($name),
                    'e_isactive' => 'Y'
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

    public function getDataProduct(Request $request)
    {
        $id = $request->id;
        $data = DB::table('m_expeditiondt')
            ->where('ed_expedition', '=', $id)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data){
               if ($data->ed_isactive == 'Y'){
                   return '<div class="btn-group text-center btn-group-sm">
                <button class="btn btn-danger btn-process" type="button" onclick="nonaktifProduk(' . $data->ed_expedition . ','.$data->ed_detailid.')" title="Nonaktif"><i class="fa fa-close"></i></button>
                </div>';
               } else {
                   return '<div class="btn-group text-center btn-group-sm">
                <button class="btn btn-success btn-process" type="button" onclick="enableProduk(' . $data->ed_expedition . ','.$data->ed_detailid.')" title="Aktif"><i class="fa fa-check"></i></button>
                </div>';
               }
            })
            ->addColumn('status', function ($data){
                if ($data->ed_isactive == 'Y'){
                    return 'Aktif';
                } else {
                    return 'Tidak Aktif';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function saveProduk(Request $request)
    {
        DB::beginTransaction();
        try {
            $name = $request->name;
            $id = $request->id;
            $check = DB::table('m_expedition')
                ->join('m_expeditiondt', 'ed_expedition', '=', 'e_id')
                ->where('e_id', '=', $id)
                ->where('ed_product', '=', $name)
                ->get();

            if (count($check) > 0){
                return Response::json([
                    'status' => 'gagal',
                    'message' => 'Data sudah ada'
                ]);
            }
            $detail = DB::table('m_expeditiondt')
                ->where('ed_expedition', '=', $id)
                ->max('ed_detailid');
            ++$detail;
            DB::table('m_expeditiondt')
                ->insert([
                    'ed_expedition' => $id,
                    'ed_detailid' => $detail,
                    'ed_product' => strtoupper($name),
                    'ed_isactive' => 'Y'
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

    public function disableEkspedisi(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();
        try {
            DB::table('m_expedition')
                ->where('e_id', '=', $id)
                ->update([
                    'e_isactive' => 'N'
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

    public function disableProduk(Request $request)
    {
        $id = $request->id;
        $detail = $request->detail;

        DB::beginTransaction();
        try {
            DB::table('m_expeditiondt')
                ->where('ed_expedition', '=', $id)
                ->where('ed_detailid', '=', $detail)
                ->update([
                    'ed_isactive' => 'N'
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

    public function enableEkspedisi(Request $request)
    {
        $id = $request->id;

        DB::beginTransaction();
        try {
            DB::table('m_expedition')
                ->where('e_id', '=', $id)
                ->update([
                    'e_isactive' => 'Y'
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

    public function enableProduk(Request $request)
    {
        $id = $request->id;
        $detail = $request->detail;

        DB::beginTransaction();
        try {
            DB::table('m_expeditiondt')
                ->where('ed_expedition', '=', $id)
                ->where('ed_detailid', '=', $detail)
                ->update([
                    'ed_isactive' => 'Y'
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
}
