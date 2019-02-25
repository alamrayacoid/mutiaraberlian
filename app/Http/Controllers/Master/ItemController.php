<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Session;
use Validator;
use carbon\Carbon;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    /**
     * Validate request before execute command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
      $messages = [
        'dataproduk_name.required' => 'Nama produk masih kosong, silahkan isi terlebih dahulu !'
      ];
      $validator = Validator::make($request->all(), [
        'dataproduk_name' => 'required'
      ], $messages);
      if($validator->fails())
      {
        return $validator->errors()->first();
      }
      else
      {
        return '1';
      }
    }

    /**
     * Return new code for item.
     *
     * @return String (code item)
     */
    public function getCode()
    {
      $det = DB::table('m_item')->select('i_code')->first();
      if (empty($det)) {
        $id = 1;
      } else {
        $biggest = 0;
        $baseCodes = DB::table('m_item')->select('i_code')->get();
        foreach ($baseCodes as $baseCode) {
          $temp = explode('/', $baseCode->i_code, 3);
          if($temp[2] > $biggest) {
            $biggest = (int)$temp[2];
          }
        }
        $id = $biggest + 1;
      }
      $code = 'IP/' . Session::get('code_comp') . '/' . $id;

      return $code;
    }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList()
    {
      $datas = DB::table('m_item')->orderBy('i_name', 'asc')->where('i_isactive', 'Y')->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('type', function($datas) {
          if ($datas->i_type == 'BB') {
            return '<td>Bahan Baku</td>';
          } elseif ($datas->i_type == 'BP') {
            return '<td>Barang Produksi</td>';
          } elseif ($datas->i_type == 'BJ') {
            return '<td>Barang Jualan</td>';
          } else {
            return '<td>Lain-lain</td>';
          }
        })
        ->addColumn('action', function($datas) {
          return '<div class="btn-group btn-group-sm">
          <button class="btn btn-warning" onclick="EditDataproduk('.$datas->i_id.')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
          <button class="btn btn-danger" onclick="DeleteDataproduk('.$datas->i_id.')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
          </div>';
        })
        ->rawColumns(['type', 'action'])
        ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return view('masterdatautama.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $code = $this->getCode();
      return view('masterdatautama.produk.create', compact('code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // validate request
      $isValidRequest = $this->validate_req($request);
      if ($isValidRequest != '1') {
        $errors = $isValidRequest;
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // start: execute insert data
      DB::beginTransaction();
      try {
        $id = DB::table('m_item')->max('i_id') + 1;

        DB::table('m_item')
          ->insert([
            'i_id' => $id,
            'i_code' => $request->dataproduk_code,
            'i_type' => $request->dataproduk_type,
            'i_codegroup' => null,
            'i_name' => $request->dataproduk_name,
            'i_min_stock' => $request->dataproduk_minstock,
            'i_unit1' => $request->dataproduk_satuanutama,
            'i_unit2' => $request->dataproduk_satuanalt1,
            'i_unit3' => $request->dataproduk_satuanalt2,
            'i_unitcompare1' => $request->dataproduk_isisatuanutama,
            'i_unitcompare2' => $request->dataproduk_isisatuanalt1,
            'i_unitcompare3' => $request->dataproduk_isisatuanalt2,
            'i_price' => null,
            'i_det' => $request->dataproduk_ket,
            'i_isactive' => "Y",
            'i_created_at' => Carbon::now(),
            'i_update_at' => Carbon::now(),
            'i_insert_by' => Session::get('code_comp'),
            'i_updated_by' => Session::get('code_comp')
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data['dataproduk'] = DB::table('m_item')
      ->where('i_id', $id)
      ->first();
      return view('masterdatautama.produk.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // validate request
      $isValidRequest = $this->validate_req($request);
      if ($isValidRequest != '1') {
        $errors = $isValidRequest;
        return response()->json([
          'status' => 'invalid',
          'message' => $errors
        ]);
      }
      // start: execute update data
      DB::beginTransaction();
      try {
        DB::table('m_item')
          ->where('i_id', $id)
          ->update([
            'i_type' => $request->dataproduk_type,
            'i_codegroup' => null,
            'i_name' => $request->dataproduk_name,
            'i_min_stock' => $request->dataproduk_minstock,
            // 'i_unit1' => $request->dataproduk_satuanutama,
            'i_unit2' => $request->dataproduk_satuanalt1,
            'i_unit3' => $request->dataproduk_satuanalt2,
            // 'i_unitcompare1' => $request->dataproduk_isisatuanutama,
            'i_unitcompare2' => $request->dataproduk_isisatuanalt1,
            'i_unitcompare3' => $request->dataproduk_isisatuanalt2,
            'i_price' => null,
            'i_det' => $request->dataproduk_ket,
            'i_isactive' => "Y",
            'i_created_at' => Carbon::now(),
            'i_update_at' => Carbon::now(),
            'i_insert_by' => Session::get('code_comp'),
            'i_updated_by' => Session::get('code_comp')
          ]);
        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // start: execute update data (delete)
      DB::beginTransaction();
      try {
        DB::table('m_item')
          ->where('i_id', $id)
          ->update([
            'i_isactive' => "N",
            'i_update_at' => Carbon::now(),
            'i_updated_by' => Session::get('code_comp')
          ]);

        DB::commit();
        return response()->json([
          'status' => 'berhasil'
        ]);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal',
          'message' => $e
        ]);
      }
    }
}
