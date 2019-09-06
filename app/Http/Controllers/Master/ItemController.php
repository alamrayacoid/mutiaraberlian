<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\pushotorisasiController as pushOtorisasi;

use DB;
use Auth;
use App\m_item;
use App\m_item_auth;
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
     * @param  \Illuminate\Http\Request $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
        $messages = [
            'dataproduk_name.required' => 'Nama produk masih kosong, silahkan isi terlebih dahulu !',
            'dataproduk_code.required' => 'Code produk masih kosong, silahkan isi terlebih dahulu !',
            'dataproduk_type.required' => 'Type produk masih kosong, silahkan isi terlebih dahulu !',
            'dataproduk_satuanutama.required' => 'Satuan Utama produk masih kosong, silahkan isi terlebih dahulu !',
        ];
        $validator = Validator::make($request->all(), [
            'dataproduk_name' => 'required',
            'dataproduk_code' => 'required',
            'dataproduk_type' => 'required',
            'dataproduk_satuanutama' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
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
                if ($temp[2] > $biggest) {
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
    public function getList(Request $request)
    {
        $status = $request->status;

        $datas = DB::table('m_item');
        if ($status != '') {
            $datas = $datas->where('i_isactive', $status);
        }
        $datas = $datas->orderBy('i_name', 'asc')
            ->join('m_itemtype', 'i_type', '=', 'it_id')
            ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->setRowClass(function ($datas){
                if ($datas->i_isactive == 'N'){
                    return 'disabled-row';
                }
            })
            ->addColumn('action', function ($datas) {
                if ($datas->i_isactive == 'Y'){
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailDataproduk(' . $datas->i_id . ')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-warning hint--top hint--warning" onclick="EditDataproduk(' . $datas->i_id . ')" rel="tooltip" data-placement="top" aria-label="Edit data"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger hint--top hint--error" onclick="DeleteDataproduk(' . $datas->i_id . ')" rel="tooltip" data-placement="top" data-original-title="Hapus" aria-label="Nonaktifkan"><i class="fa fa-close"></i></button>
                            </div></center>';
                } else {
                    return '<center><div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-xs detail hint--top hint--info" onclick="DetailDataproduk(' . $datas->i_id . ')" rel="tooltip" data-placement="top" aria-label="Detail data"><i class="fa fa-folder"></i></button>
                            <button class="btn btn-primary hint--top hint--error" onclick="ActiveDataproduk(' . $datas->i_id . ')" rel="tooltip" data-placement="top" data-original-title="Aktif" aria-label="Aktifkan"><i class="fa fa-check"></i></button>
                            </div></center>';
                }

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
        if (!AksesUser::checkAkses(3, 'read')){
            abort(401);
        }
        return view('masterdatautama.produk.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!AksesUser::checkAkses(3, 'create')){
            abort(401);
        }
        $jenis = DB::table('m_itemtype')
            ->get();

        $satuan = DB::table('m_unit')
            ->get();

        return view('masterdatautama.produk.create', compact('jenis', 'satuan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        if (!AksesUser::checkAkses(3, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => "anda tidak memiliki akses"
            ]);
        }
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
            // use id based on max-id in m_item and m_item_auth, to prevent duplicate id
            if (DB::table('m_item')->max('i_id') > DB::table('m_item_auth')->max('ia_id')) {
                $id = DB::table('m_item')->max('i_id') + 1;
            } else {
                $id = DB::table('m_item_auth')->max('ia_id') + 1;
            }

            $file = $request->file('file');
            $file_name = null;
            if ($file != null) {
                // make-directory based on item-id
                $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                if (!is_dir($authDirectory)) {
                    mkdir($authDirectory, 0777, true);
                }
                // specify file-name
                $file_name = time() . '.' . $file->getClientOriginalExtension();

                // create image inside auth-directory
                Image::make($file)
                    ->resize(261, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($authDirectory .'\\'. $file_name);
            }

            DB::table('m_item_auth')
                ->insert([
                    'ia_id' => $id,
                    'ia_code' => strtoupper($request->dataproduk_code),
                    'ia_type' => $request->dataproduk_type,
                    'ia_codegroup' => null,
                    'ia_name' => strtoupper($request->dataproduk_name),
                    'ia_unit1' => $request->dataproduk_satuanutama,
                    'ia_unit2' => $request->dataproduk_satuanalt1,
                    'ia_unit3' => $request->dataproduk_satuanalt2,
                    'ia_unitcompare1' => $request->dataproduk_isisatuanutama,
                    'ia_unitcompare2' => $request->dataproduk_isisatuanalt1,
                    'ia_unitcompare3' => $request->dataproduk_isisatuanalt2,
                    'ia_detail' => $request->dataproduk_ket,
                    'ia_isactive' => "Y",
                    'ia_image' => $file_name,
                    'ia_created_at' => Carbon::now(),
                    'ia_update_at' => Carbon::now(),
                ]);

            $link = route('revisi');
            pushOtorisasi::otorisasiup('Otorisasi Revisi Data', 1, $link);

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!AksesUser::checkAkses(3, 'update')){
            abort(401);
        }
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!AksesUser::checkAkses(3, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // validate request
        $isValidRequest = $this->validate_req($request);
        if ($isValidRequest != '1') {
            $errors = $isValidRequest;
            return response()->json([
                'status' => 'invalid',
                'message' => $errors
            ]);
        }

        DB::beginTransaction();
        try {
            $gambar = DB::table('m_item')->where('i_id', '=', $id)->first();
            $file = $request->file('file');
            if ($file != null) {
                // make-directory based on item-id
                $authDirectory = storage_path('uploads\produk\item-auth\\') . $id;
                if (!is_dir($authDirectory)) {
                    mkdir($authDirectory, 0777, true);
                }
                // remove/delete current image
                if ($gambar->i_image != '') {
                    if (file_exists($authDirectory .'\\'. $gambar->i_image)) {
                        unlink($authDirectory .'\\'. $gambar->i_image);
                    }
                }
                // specify file-name
                $file_name = time() . '.' . $file->getClientOriginalExtension();

                // dd($authDirectory, $file_name);
                // create image inside auth-directory
                Image::make($file)
                ->resize(261, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($authDirectory .'\\'. $file_name);
            }
            else {
                $file_name = $gambar->i_image;
            }

            // is there already existing un-autorized edit-item ?
            $cek = DB::table('m_item_auth')->where('ia_id', $id)->count();
            if ($cek == 0) {
                DB::table('m_item_auth')
                ->insert([
                    'ia_id' => $id,
                    'ia_type' => $request->dataproduk_type,
                    'ia_code' => $request->dataproduk_code,
                    'ia_codegroup' => null,
                    'ia_name' => strtoupper($request->dataproduk_name),
                    'ia_detail' => $request->dataproduk_ket,
                    'ia_image' => $file_name,
                    'ia_isactive' => "Y",
                    'ia_update_at' => Carbon::now(),
                ]);
            }
            else {
                // start: execute update data
                DB::table('m_item_auth')
                ->where('ia_id', $id)
                ->update([
                    'ia_type' => $request->dataproduk_type,
                    'ia_code' => $request->dataproduk_code,
                    'ia_codegroup' => null,
                    'ia_name' => strtoupper($request->dataproduk_name),
                    'ia_detail' => $request->dataproduk_ket,
                    'ia_image' => $file_name,
                    'ia_isactive' => "Y",
                    'ia_update_at' => Carbon::now(),
                ]);

                $link = route('revisi');
                pushOtorisasi::otorisasiup('Otorisasi Revisi Data', 1, $link);
            }


            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!AksesUser::checkAkses(3, 'delete')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // start: execute update data (delete)
        DB::beginTransaction();
        try {
            $isAlreadyExist = m_item_auth::where('ia_id', $id)->first();
            if ($isAlreadyExist !== null) {
                DB::table('m_item_auth')
                ->where('ia_id', $id)
                ->update([
                    'ia_isactive' => "N",
                    'ia_update_at' => Carbon::now(),
                ]);
            }
            else {
                $itemCode = m_item::where('i_id', $id)->select('i_code')->first();
                // dd($itemCode);
                DB::table('m_item_auth')
                ->insert([
                    'ia_id' => $id,
                    'ia_code' => $itemCode->i_code,
                    'ia_isactive' => "N",
                    'ia_update_at' => Carbon::now()
                ]);

                $link = route('revisi');
                pushOtorisasi::otorisasiup('Otorisasi Revisi Data', 1, $link);
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function active($id)
    {
        if (!AksesUser::checkAkses(3, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // start: execute update data (delete)
        DB::beginTransaction();
        try {
            DB::table('m_item_auth')
                ->where('ia_id', $id)
                ->update([
                    'ia_isactive' => "Y",
                    'ia_update_at' => Carbon::now(),
                ]);

            $link = route('revisi');
            pushOtorisasi::otorisasiup('Otorisasi Revisi Data', 1, $link);

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

    public function simpanjenis(Request $request)
    {
        if (!AksesUser::checkAkses(3, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        $messages = [
            'jenis.required' => 'Jenis masih kosong, silahkan isi terlebih dahulu !',
        ];
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'invalid',
                'message' => $validator->errors()->first()
            ]);
        } else {
            DB::beginTransaction();
            try {

                $id = DB::table('m_itemtype')->max('it_id') + 1;
                DB::table('m_itemtype')
                    ->insert([
                        'it_id' => $id,
                        'it_name' => strtoupper($request->jenis)
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

    public function tablejenis()
    {
        $datas = DB::table('m_itemtype')->orderBy('it_name', 'asc')->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<center><div class="btn-group btn-group-sm">
                      <button class="btn btn-danger btn-sm" onclick="deletejenis(' . $datas->it_id . ', this)" rel="tooltip" data-placement="top"><i class="fa fa-close"></i></button>
                      </div></center>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function hapusjenis(Request $request)
    {
        if (!AksesUser::checkAkses(3, 'delete')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
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

    public function updatejenis(Request $request)
    {
        if (!AksesUser::checkAkses(3, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        $messages = [
            'jenis.required' => 'Jenis masih kosong, silahkan isi terlebih dahulu !',
        ];
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'invalid',
                'message' => $validator->errors()->first()
            ]);
        } else {
            DB::beginTransaction();
            try {

                DB::table('m_itemtype')->where('it_id', $request->id)
                    ->update([
                        'it_name' => strtoupper($request->jenis)
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

    public function detail(Request $request)
    {
        if (!AksesUser::checkAkses(3, 'read')){
            abort(401);
        }
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
