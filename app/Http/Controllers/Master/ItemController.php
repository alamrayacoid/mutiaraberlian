<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Session;
use Validator;
use carbon\Carbon;
use Yajra\DataTables\DataTables;
use Intervention\Image\ImageManagerStatic as Image;

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
        'dataproduk_name.required' => 'Nama produk masih kosong, silahkan isi terlebih dahulu !',
        'dataproduk_code.required' => 'Code produk masih kosong, silahkan isi terlebih dahulu !',
        'dataproduk_type.required' => 'Type produk masih kosong, silahkan isi terlebih dahulu !',
      ];
      $validator = Validator::make($request->all(), [
        'dataproduk_name' => 'required',
        'dataproduk_code' => 'required',
        'dataproduk_type' => 'required',
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
      $datas = DB::table('m_item')->orderBy('i_name', 'asc')->where('i_isactive', 'Y')->join('m_itemtype', 'i_type', '=', 'it_id')->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
          return '<center><div class="btn-group btn-group-sm">
          <button class="btn btn-info btn-xs detail" onclick="DetailDataproduk('.$datas->i_id.')" rel="tooltip" data-placement="top"><i class="fa fa-folder"></i></button>
          <button class="btn btn-warning" onclick="EditDataproduk('.$datas->i_id.')" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
          <button class="btn btn-danger" onclick="DeleteDataproduk('.$datas->i_id.')" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
          </div></center>';
        })
        ->rawColumns(['detail', 'action'])
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
      $jenis = DB::table('m_itemtype')
                  ->get();

      $satuan = DB::table('m_unit')
                  ->get();

      return view('masterdatautama.produk.create', compact('jenis', 'satuan'));
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

        $file = $request->file('file');
            if ($file != null) {

                $file_name = 'produk'. $id  .'.' . $file->getClientOriginalExtension();

                if (!is_dir(storage_path('uploads/produk/original/'))) {
                    mkdir(storage_path('uploads/produk/original/'), 0777, true);
                }

                $original_path = storage_path('uploads/produk/original/');
                // return $original_path;
                Image::make($file)
                      ->resize(261,null,function ($constraint) {
                        $constraint->aspectRatio();
                         })
                      ->save($original_path . $file_name);
            }

        DB::table('m_item')
          ->insert([
            'i_id' => $id,
            'i_code' => $request->dataproduk_code,
            'i_type' => $request->dataproduk_type,
            'i_codegroup' => null,
            'i_name' => $request->dataproduk_name,
            'i_unit1' => $request->dataproduk_satuanutama,
            'i_unit2' => $request->dataproduk_satuanalt1,
            'i_unit3' => $request->dataproduk_satuanalt2,
            'i_unitcompare1' => $request->dataproduk_isisatuanutama,
            'i_unitcompare2' => $request->dataproduk_isisatuanalt1,
            'i_unitcompare3' => $request->dataproduk_isisatuanalt2,
            'i_detail' => $request->dataproduk_ket,
            'i_isactive' => "Y",
            'i_image' => $file_name,
            'i_created_at' => Carbon::now(),
            'i_update_at' => Carbon::now(),
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

      $jenis = DB::table('m_itemtype')
                  ->get();

      $satuan = DB::table('m_unit')
                  ->get();

      return view('masterdatautama.produk.edit', compact('data', 'jenis', 'satuan'));
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

      $gambar = DB::table('m_item')->where('i_id','=',$id)->first();
      $file = $request->file('file');
          if ($file != null) {
                  // dd(base_path('assets\barang\\'.$gambar[0]->i_image));
              if($gambar->i_image != '')
              {
                  if(file_exists(storage_path('uploads/produk/original/').$gambar->i_image)  )
                  {
                      $storage2 = unlink(storage_path('uploads/produk/original/').$gambar->i_image);
                  }

              }

              $file_name = 'produk'. $id  .'.' . $file->getClientOriginalExtension();

              if (!is_dir(storage_path('uploads/produk/original/'))) {
                  mkdir(storage_path('uploads/produk/original/'), 0777, true);
              }

              $original_path = storage_path('uploads/produk/original/');
              // return $original_path;
              Image::make($file)
                    ->resize(261,null,function ($constraint) {
                      $constraint->aspectRatio();
                       })
                    ->save($original_path . $file_name);
          } else {
            $file_name = $gambar->i_image;
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
            // 'i_unit1' => $request->dataproduk_satuanutama,
            'i_unit2' => $request->dataproduk_satuanalt1,
            'i_unit3' => $request->dataproduk_satuanalt2,
            // 'i_unitcompare1' => $request->dataproduk_isisatuanutama,
            'i_unitcompare2' => $request->dataproduk_isisatuanalt1,
            'i_unitcompare3' => $request->dataproduk_isisatuanalt2,
            'i_detail' => $request->dataproduk_ket,
            'i_image' => $file_name,
            'i_isactive' => "Y",
            'i_created_at' => Carbon::now(),
            'i_update_at' => Carbon::now(),
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

    public function simpanjenis(Request $request){
        $messages = [
          'jenis.required' => 'Jenis masih kosong, silahkan isi terlebih dahulu !',
        ];
        $validator = Validator::make($request->all(), [
          'jenis' => 'required',
        ], $messages);
        if($validator->fails())
        {
          return response()->json([
            'status' => 'invalid',
            'message' => $validator->errors()->first()
          ]);
        }
        else
        {
          DB::beginTransaction();
          try {

            $id = DB::table('m_itemtype')->max('it_id')+1;
            DB::table('m_itemtype')
                  ->insert([
                    'it_id' => $id,
                    'it_name' => $request->jenis
                  ]);

            DB::commit();
            return response()->json([
              'status' => 'berhasil'
            ]);
          } catch (Exception $e) {
            DB::rollback();
            return response()->json([
              'status' => 'gagal'
            ]);
          }
        }
    }

    public function tablejenis(){
      $datas = DB::table('m_itemtype')->orderBy('it_name', 'asc')->get();
      return Datatables::of($datas)
        ->addIndexColumn()
        ->addColumn('action', function($datas) {
          return '<center><div class="btn-group btn-group-sm">
          <button class="btn btn-warning" onclick="editjenis('.$datas->it_id.', this)" rel="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
          <button class="btn btn-danger" onclick="deletejenis('.$datas->it_id.')" data-id="" rel="tooltip" data-placement="top" data-original-title="Hapus"><i class="fa fa-trash-o"></i></button>
          </div></center>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function hapusjenis(Request $request){
      DB::beginTransaction();
      try {

        $cek = DB::table('m_item')->where('i_type', $request->id)->count();

        if ($cek > 0) {
          return response()->json([
            'status' => 'digunakan'
          ]);
        } else {
          DB::table('m_itemtype')->where('it_id', $request->id)->delete();
          DB::commit();
          return response()->json([
            'status' => 'berhasil'
          ]);
        }

      } catch (Exception $e) {
        DB::rollback();
        return response()->json([
          'status' => 'gagal'
        ]);
      }
    }

    public function updatejenis(Request $request){
      $messages = [
        'jenis.required' => 'Jenis masih kosong, silahkan isi terlebih dahulu !',
      ];
      $validator = Validator::make($request->all(), [
        'jenis' => 'required',
      ], $messages);
      if($validator->fails())
      {
        return response()->json([
          'status' => 'invalid',
          'message' => $validator->errors()->first()
        ]);
      } else {
        DB::beginTransaction();
        try {

          DB::table('m_itemtype')->where('it_id', $request->id)
          ->update([
            'it_name' => $request->jenis
          ]);

          DB::commit();
          return response()->json([
            'status' => 'berhasil'
          ]);
        } catch (Exception $e) {
          DB::rollback();
          return response()->json([
            'status' => 'gagal'
          ]);
        }
      }
    }

    public function detail(Request $request){
      $data['dataproduk'] = DB::table('m_item')
      ->where('i_id', $request->id)
      ->first();

      $jenis = DB::table('m_itemtype')
                  ->get();

      $satuan = DB::table('m_unit')
                  ->get();

      return view('masterdatautama.produk.detail', compact('data', 'jenis', 'satuan'));
    }
}
