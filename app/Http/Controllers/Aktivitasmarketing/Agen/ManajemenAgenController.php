<?php

namespace App\Http\Controllers\Aktivitasmarketing\Agen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use Auth;
use App\m_item;
use DataTables;
use DB;
use Carbon\Carbon;
use CodeGenerator;
use Currency;
use Response;
use Validator;

class ManajemenAgenController extends Controller
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
            'area_prov' => 'required',
            'telp' => 'required|numeric'
        ],
        [
            'area_prov.required' => 'Area Provinsi masih kosong !',
            'telp.required' => 'No Telp masih kosong !',
            'telp.numeric' => 'No Telp hanya berupa angka, tidak boleh mengandung huruf !'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        } else {
            return '1';
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinsi = DB::table('m_wil_provinsi')
      		->select('m_wil_provinsi.*')
      		->get();

        return view('marketing/agen/index', compact('provinsi'));
    }

    // Start: Kelola Data Inventory Agen ----------------
    public function getAgen($city)
    {
        $agen = DB::table('m_agen')
        ->join('m_company', 'a_code', 'c_user')
        ->select('a_code', 'a_name', 'c_id')
        ->where('a_kabupaten', '=', $city)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $agen
        ]);
    }
    public function filterData($id)
    {
        $data = DB::table('d_stock')
        ->leftJoin('m_company as comp', 's_position', 'comp.c_id')
        ->leftJoin('m_company as agen', 's_comp', 'agen.c_id')
        ->leftJoin('m_item', 's_item', 'i_id')
        ->where('s_comp', '=', $id)
        ->select('agen.c_name as agen', 'comp.c_name as comp', 'i_name', 's_condition', 's_qty')
        ->get();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('kondisi', function ($data) {
            if ($data->s_condition == "FINE") {
                return "Normal";
            } else {
                return "Rusak";
            }
        })
        ->addColumn('qty', function ($data) {
            return "<div class='text-center'>$data->s_qty</div>";
        })
        ->rawColumns(['kondisi', 'qty'])
        ->make(true);
    }
    // End: Kelola Data Inventory Agen ----------------


    // Start: Kelola Penjualan Langsung -----------------
    public function createKPL()
    {
        return view('marketing/agen/kelolapenjualan/create');
    }
    // function to retrieve items using autocomple.js
    public function findItem(Request $request)
    {
        $term = $request->term;
        $items = m_item::where(function ($q) use ($term){
                $q->orWhere('i_name', 'like', '%'.$term.'%');
                $q->orWhere('i_code', 'like', '%'.$term.'%');
            })
            ->with('getUnit1')
            ->with('getUnit2')
            ->with('getUnit3')
            ->get();

        if (count($items) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($items as $item) {
                $results[] = ['id' => $item->i_id, 'label' => $item->i_code . ' - ' .strtoupper($item->i_name), 'data' => $item];
            }
        }
        return response()->json($results);
    }
    // End: Kelola Penjualan Langsung -----------------

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        DB::beginTransaction();
        try {
            // start insert data

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
