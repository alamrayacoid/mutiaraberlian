<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SupplierController extends Controller
{
    public function index()
    {
        return view('masterdatautama.suplier.index');
    }

    public function add(Request $request)
    {
        if (!$request->isMethod('post')){
            return view('masterdatautama.suplier.create');
        } else {
            $data = $request->all();
            try{
                $value = [
                    's_company' => $data[''],
                    's_name'    => $data[''],
                    's_npwp'    => $data[''],
                    's_address' => $data[''],
                    's_phone'   => $data[''],
                    's_phone1'  => $data[''],
                    's_phone2'  => $data[''],
                    's_rekening'=> $data[''],
                    's_bank'    => $data[''],
                    's_fax'     => $data[''],
                    's_note'    => $data[''],
                    's_top'     => $data[''],
                    's_deposit' => $data[''],
                    's_limit'   => $data[''],
                    's_hutang'  => $data['']
                ];
                DB::commit();
                return response()->json([
                    'status'  => 'true'
                ]);
            }catch (\Exception $e){
                DB::rollBack();
                return response()->json([
                    'status'  => 'false'
                ]);
            }
        }
    }

    public function edit()
    {
        return view('masterdatautama.suplier.edit');
    }
}
