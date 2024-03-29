<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\AksesUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use Validator;
use carbon\Carbon;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    /**
     * Validate request before execute command.
     *
     * @param  \Illuminate\Http\Request $request
     * @return 'error message' or '1'
     */
    public function validate_req(Request $request)
    {
        // start: validate data before execute
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'name' => 'required',
            'npwp' => 'sometimes|nullable|numeric',
            'phone' => 'required|numeric',
            'phone1' => 'sometimes|nullable|numeric',
            'phone2' => 'sometimes|nullable|numeric',
            'rekening' => 'required|numeric',
            'atasnama' => 'required_with:rekening',
            'bank' => 'required_with:rekening',
            'fax' => 'sometimes|nullable|numeric',
            'top' => 'sometimes|nullable|numeric|max:127',
            'deposit' => 'sometimes|nullable|numeric|max:127'
        ],
            [
                'company.required' => 'Nama perusahaan masih kosong !',
                'name.required' => 'Nama suplier masih kosong !',
                'npwp.numeric' => 'Nomor npwp hanya boleh berisi angka !',
                'phone.required' => 'Nomor telp masih kosong !',
                'phone.numeric' => 'Nomor telp hanya boleh berisi angka !',
                'phone1.numeric' => 'Nomor telp hanya boleh berisi angka !',
                'phone2.numeric' => 'Nomor telp hanya boleh berisi angka !',
                'rekening.required' => 'Nomor rekening masih kosong !',
                'rekening.numeric' => 'Nomor rekening hanya boleh berisi angka !',
                'atasnama.required_with' => 'Atasnama (rekening) masih kosong !',
                'bank.required_with' => 'Nama bank masih kosong !',
                'fax.numeric' => 'Nomor fax hanya boleh berisi angka !',
                'top.max' => 'TOP maksimal 127 hari !',
                'deposit.max' => 'Deposit maksimal 127 hari !'
            ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    /**
     * Remove maskMoney (suffix, prefix) and return floatval.
     *
     * @param string $str
     * @param string $prefix
     * @param string $suffix
     * @return float $limit or $hutang
     */
    public function removeMask($str, $prefix, $suffix)
    {
        $strFormat = str_replace($prefix, '', $str);
        $strFormat = str_replace($suffix, '', $strFormat);
        $strFormat = str_replace('.', '', $strFormat);
        $floatformat = floatval($strFormat);
        return $floatformat;
    }

    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList(Request $request)
    {
        $status = $request->status;

        $datas = DB::table('m_supplier');
        if ($status != '') {
            $datas = $datas->where('s_isactive', $status);
        }
        $datas = $datas->orderBy('s_company', 'asc')
            ->get();


        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('limit', function ($datas) {
                return '<td>
                <span class="float-left">Rp </span>
                <span class="float-right">' . number_format($datas->s_limit, 2, ',', '.') . '</span>
                </td>';
            })
            ->addColumn('hutang', function ($datas) {
                return '<td>
                <span class="float-left">Rp </span>
                <span class="float-right">' . number_format($datas->s_hutang, 2, ',', '.') . '</span>
                </td>';
            })
            ->addColumn('phone', function ($datas) {
                return '<td>' .
                    (($datas->s_phone == null) ? '-' : $datas->s_phone) . '' .
                    (($datas->s_phone1 == null) ? '' : ' / ' . $datas->s_phone1) . '' .
                    (($datas->s_phone2 == null) ? '' : ' / ' . $datas->s_phone2) . '</td>';
            })
            ->addColumn('action', function ($datas) {
                if ($datas->s_isactive == 'Y') {
                    return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-warning hint--top hint--warning" onclick="EditSupplier(' . $datas->s_id . ')" rel="tooltip" data-placement="top" aria-label="Edit Data"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger hint--top hint--error" onclick="DisableSupplier(' . $datas->s_id . ')" rel="tooltip" data-placement="top" aria-label="Nonaktifkan Data"><i class="fa fa-times-circle"></i></button>
                    </div>';
                } elseif ($datas->s_isactive == 'N') {
                    return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-success btn-enable hint--top hint--error" onclick="EnableSupplier(' . $datas->s_id . ')" rel="tooltip" data-placement="top" aria-label="Aktifkan Data"><i class="fa fa-check-circle"></i></button>
                    </div>';
                }
            })
            ->rawColumns(['limit', 'hutang', 'phone', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!AksesUser::checkAkses(5, 'read')){
            abort(401);
        }
        $getSupp = DB::table('m_supplier')->select('s_id', 's_company')->get();

        return view('masterdatautama.suplier.index')->with(compact('getSupp'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!AksesUser::checkAkses(5, 'create')){
            abort(401);
        }
        return view('masterdatautama.suplier.datasuplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!AksesUser::checkAkses(5, 'create')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // Remove masked value before validate it
        // also, merged $request with new data
        $request->merge(['top' => (int)$this->removeMask($request->top, '', ' Hari')]);
        $request->merge(['deposit' => (int)$this->removeMask($request->deposit, '', ' Hari')]);
        $request->merge(['limit' => $this->removeMask($request->limit, 'Rp. ', '')]);
        $request->merge(['hutang' => $this->removeMask($request->hutang, 'Rp. ', '')]);
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
            $id = DB::table('m_supplier')->max('s_id') + 1;
            DB::table('m_supplier')
                ->insert([
                    's_id' => $id,
                    's_company' => $request->company,
                    's_name' => $request->name,
                    's_npwp' => $request->npwp,
                    's_address' => $request->address,
                    's_phone' => $request->phone,
                    's_phone1' => $request->phone1,
                    's_phone2' => $request->phone2,
                    's_rekening' => $request->rekening,
                    's_atasnama' => $request->atasnama,
                    's_bank' => $request->bank,
                    's_fax' => $request->fax,
                    's_note' => $request->note,
                    's_top' => $request->top,
                    's_deposit' => $request->deposit,
                    's_limit' => $request->limit,
                    's_hutang' => $request->hutang,
                    's_isactive' => 'Y',
                    's_insert' => Carbon::now(),
                    's_update' => Carbon::now()
                ]);

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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!AksesUser::checkAkses(5, 'update')){
            abort(401);
        }
        $data['supplier'] = DB::table('m_supplier')
            ->where('s_id', $id)
            ->first();
        $data['supplier']->s_limit = (int)$data['supplier']->s_limit;
        $data['supplier']->s_hutang = (int)$data['supplier']->s_hutang;
        // dd($data['supplier']);
        return view('masterdatautama.suplier.datasuplier.edit', compact('data'));
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
        if (!AksesUser::checkAkses(5, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // Remove masked value before validate it
        // also, merged $request with new data
        $request->merge(['top' => (int)$this->removeMask($request->top, '', ' Hari')]);
        $request->merge(['deposit' => (int)$this->removeMask($request->deposit, '', ' Hari')]);
        $request->merge(['limit' => $this->removeMask($request->limit, 'Rp. ', '')]);
        $request->merge(['hutang' => $this->removeMask($request->hutang, 'Rp. ', '')]);
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
            DB::table('m_supplier')
                ->where('s_id', $id)
                ->update([
                    's_company' => $request->company,
                    's_name' => $request->name,
                    's_npwp' => $request->npwp,
                    's_address' => $request->address,
                    's_phone' => $request->phone,
                    's_phone1' => $request->phone1,
                    's_phone2' => $request->phone2,
                    's_rekening' => $request->rekening,
                    's_atasnama' => $request->atasnama,
                    's_bank' => $request->bank,
                    's_fax' => $request->fax,
                    's_note' => $request->note,
                    's_top' => $request->top,
                    's_deposit' => $request->deposit,
                    's_limit' => $request->limit,
                    's_hutang' => $request->hutang,
                    // 's_insert' => Carbon::now(),
                    's_update' => Carbon::now()
                ]);

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
     * Enable the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        if (!AksesUser::checkAkses(5, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // start: execute delete data
        DB::beginTransaction();
        try {
            DB::table('m_supplier')
                ->where('s_id', $id)
                ->update([
                    's_isactive' => 'Y'
                ]);

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
     * Disable the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        if (!AksesUser::checkAkses(5, 'update')){
            return response()->json([
                'status' => 'gagal',
                'message' => 'anda tidak memiliki akses'
            ]);
        }
        // start: execute delete data
        DB::beginTransaction();
        try {
            DB::table('m_supplier')
                ->where('s_id', $id)
                ->update([
                    's_isactive' => 'N'
                ]);

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
}
