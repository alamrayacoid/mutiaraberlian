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
use Currency;
use Auth;

class HargaController extends Controller
{
    public function dataharga()
    {
        return view('masterdatautama.harga.index');
    }

    public function getDataNeddApprove()
    {
        $datas = DB::table('d_priceclassauthdt')
            ->join('m_priceclass', 'pc_id', '=', 'pcad_classprice')
            ->join('m_item', function ($item) {
                $item->on('d_priceclassauthdt.pcad_item', '=', 'm_item.i_id');
            })
            ->join('m_unit', function ($unit) {
                $unit->on('d_priceclassauthdt.pcad_unit', '=', 'm_unit.u_id');
            })
            ->get();
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('item', function ($datas) {
                return $datas->i_name;
            })
            ->addColumn('jenis', function ($datas) {
                return $datas->pcad_type == "R" ? "Range" : "Unit";
            })
            ->addColumn('range', function ($datas) {
                $end = ($datas->pcad_rangeqtyend == "0") ? "~" : $datas->pcad_rangeqtyend;
                return $datas->pcad_rangeqtystart . '-' . $end;
            })
            ->addColumn('satuan', function ($datas) {
                return $datas->u_name;
            })
            ->addColumn('harga', function ($datas) {
                return '<span class="text-right">' . Currency::addRupiah($datas->pcad_price) . '</span>';
            })
            ->addColumn('jenis_pembayaran', function ($datas) {
                return $datas->pcad_payment == "K" ? "Konsinyasi" : "Cash";
            })
            ->rawColumns(['item', 'jenis', 'range', 'satuan', 'harga', 'jenis_pembayaran'])
            ->make(true);
    }

    public function getGolongan()
    {
        $datas = DB::table('m_priceclass')->orderBy('pc_name', 'asc');
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<center><div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" title="Edit"
                                                    type="button" onclick="editGolongan(\'' . Crypt::encrypt($datas->pc_id) . '\', \'' . $datas->pc_name . '\')"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                    title="Hapus" onclick="hapusGolongan(\'' . Crypt::encrypt($datas->pc_id) . '\')"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-primary" title="add"
                                                    type="button" onclick="addGolonganHarga(\'' . Crypt::encrypt($datas->pc_id) . '\', \'' . $datas->pc_name . '\')"><i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div></center>';

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getGolonganHPA()
    {
        $datas = DB::table('d_salesprice')->orderBy('sp_name', 'asc');
        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('action', function ($datas) {
                return '<center><div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" title="Edit"
                                                    type="button" onclick="editGolonganHPA(\'' . Crypt::encrypt($datas->sp_id) . '\', \'' . $datas->sp_name . '\')"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                    title="Hapus" onclick="hapusGolonganHPA(\'' . Crypt::encrypt($datas->sp_id) . '\')"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-primary" title="add"
                                                    type="button" onclick="addGolonganHargaHPA(\'' . Crypt::encrypt($datas->sp_id) . '\', \'' . $datas->sp_name . '\')"><i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div></center>';

            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getGolonganHarga($id)
    {
        $datas = DB::table('d_priceclassauthdt')
            ->join('m_item', function ($item) {
                $item->on('d_priceclassauthdt.pcad_item', '=', 'm_item.i_id');
            })
            ->join('m_unit', function ($unit) {
                $unit->on('d_priceclassauthdt.pcad_unit', '=', 'm_unit.u_id');
            })
            ->select('m_item.*', 'm_unit.*', 'pcad_price as pcd_price', 'pcad_classprice as pcd_classprice', 'pcad_unit as pcd_unit', 'pcad_detailid as pcd_detailid', 'pcad_item as pcd_item', 'pcad_user as pcd_user', 'pcad_rangeqtyend as pcd_rangeqtyend', 'pcad_rangeqtystart as pcd_rangeqtystart', 'pcad_payment as pcd_payment', 'pcad_type as pcd_type', DB::raw('"N" as status'))
            ->where('d_priceclassauthdt.pcad_classprice', '=', Crypt::decrypt($id));

        $datax = DB::table('m_priceclassdt')
            ->join('m_item', function ($item) {
                $item->on('m_priceclassdt.pcd_item', '=', 'm_item.i_id');
            })
            ->join('m_unit', function ($unit) {
                $unit->on('m_priceclassdt.pcd_unit', '=', 'm_unit.u_id');
            })
            ->select('m_item.*', 'm_unit.*', 'pcd_price', 'pcd_unit', 'pcd_classprice', 'pcd_detailid', 'pcd_item', 'pcd_user as pcd_user', 'pcd_rangeqtyend as pcd_rangeqtyend', 'pcd_rangeqtystart as pcd_rangeqtystart', 'pcd_payment as pcd_payment', 'pcd_type as pcd_type', DB::raw('"Y" as status'))
            ->where('m_priceclassdt.pcd_classprice', '=', Crypt::decrypt($id));

        if ($datas->count() > 0 && $datax->count() > 0) {
            $data = $datas->union($datax)->get();
        } else if ($datas->count() > 0 && $datax->count() == 0) {
            $data = $datas->get();
        } else if ($datas->count() == 0 && $datax->count() > 0) {
            $data = $datax->get();
        } else if ($datas->count() == 0 && $datax->count() == 0) {
            $data = [];
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('item', function ($data) {
                return $data->i_name;
            })
            ->addColumn('jenis', function ($data) {
                return $data->pcd_type == "R" ? "Range" : "Unit";
            })
            ->addColumn('range', function ($data) {
                $end = ($data->pcd_rangeqtyend == "0") ? "~" : $data->pcd_rangeqtyend;
                return $data->pcd_rangeqtystart . '-' . $end;
            })
            ->addColumn('satuan', function ($data) {
                return $data->u_name;
            })
            ->addColumn('harga', function ($data) {
                return '<span class="text-right">' . Currency::addRupiah($data->pcd_price) . '</span>';
            })
            ->addColumn('jenis_pembayaran', function ($data) {
                return $data->pcd_payment == "K" ? "Konsinyasi" : "Cash";
            })
            ->addColumn('action', function ($data) {
                return '<center><div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" title="Edit"
                                                    type="button" onclick="editGolonganHarga(\'' . Crypt::encrypt($data->pcd_classprice) . '\', \'' . Crypt::encrypt($data->pcd_detailid) . '\', \'' . $data->pcd_item . '\', \'' . Currency::addRupiah($data->pcd_price) . '\', \'' . $data->pcd_unit . '\', \'' . $data->pcd_type . '\', \'' . $data->pcd_rangeqtystart . '\', \'' . $data->pcd_rangeqtyend . '\', \'' . $data->status . '\')"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                    title="Hapus" onclick="hapusGolonganHarga(\'' . Crypt::encrypt($data->pcd_classprice) . '\', \'' . Crypt::encrypt($data->pcd_detailid) . '\', \'' . $data->status . '\')"><i class="fa fa-trash"></i></button>
                                        </div></center>';

            })
            ->addColumn('status', function ($data) {
                if ($data->status == 'Y') {
                    return '<span class="btn btn-sm btn-success btn-khusus">Disetujui</span>';
                } else {
                    return '<span class="btn btn-sm btn-danger btn-khusus">Pending</span>';
                }
            })
            ->rawColumns(['item', 'jenis', 'range', 'satuan', 'harga', 'jenis_pembayaran', 'action', 'status'])
            ->make(true);
    }

    public function getGolonganHargaHPA($id)
    {
        $datas = DB::table('d_priceclassauthdt')
            ->join('m_item', function ($item) {
                $item->on('d_priceclassauthdt.pcad_item', '=', 'm_item.i_id');
            })
            ->join('m_unit', function ($unit) {
                $unit->on('d_priceclassauthdt.pcad_unit', '=', 'm_unit.u_id');
            })
            ->select('m_item.*', 'm_unit.*', 'pcad_price as pcd_price', 'pcad_classprice as pcd_classprice', 'pcad_unit as pcd_unit', 'pcad_detailid as pcd_detailid', 'pcad_item as pcd_item', 'pcad_user as pcd_user', 'pcad_rangeqtyend as pcd_rangeqtyend', 'pcad_rangeqtystart as pcd_rangeqtystart', 'pcad_payment as pcd_payment', 'pcad_type as pcd_type', DB::raw('"N" as status'))
            ->where('d_priceclassauthdt.pcad_classprice', '=', Crypt::decrypt($id));

        $datax = DB::table('m_priceclassdt')
            ->join('m_item', function ($item) {
                $item->on('m_priceclassdt.pcd_item', '=', 'm_item.i_id');
            })
            ->join('m_unit', function ($unit) {
                $unit->on('m_priceclassdt.pcd_unit', '=', 'm_unit.u_id');
            })
            ->select('m_item.*', 'm_unit.*', 'pcd_price', 'pcd_unit', 'pcd_classprice', 'pcd_detailid', 'pcd_item', 'pcd_user as pcd_user', 'pcd_rangeqtyend as pcd_rangeqtyend', 'pcd_rangeqtystart as pcd_rangeqtystart', 'pcd_payment as pcd_payment', 'pcd_type as pcd_type', DB::raw('"Y" as status'))
            ->where('m_priceclassdt.pcd_classprice', '=', Crypt::decrypt($id));

        if ($datas->count() > 0 && $datax->count() > 0) {
            $data = $datas->union($datax)->get();
        } else if ($datas->count() > 0 && $datax->count() == 0) {
            $data = $datas->get();
        } else if ($datas->count() == 0 && $datax->count() > 0) {
            $data = $datax->get();
        } else if ($datas->count() == 0 && $datax->count() == 0) {
            $data = [];
        }

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('item', function ($data) {
                return $data->i_name;
            })
            ->addColumn('jenis', function ($data) {
                return $data->pcd_type == "R" ? "Range" : "Unit";
            })
            ->addColumn('range', function ($data) {
                $end = ($data->pcd_rangeqtyend == "0") ? "~" : $data->pcd_rangeqtyend;
                return $data->pcd_rangeqtystart . '-' . $end;
            })
            ->addColumn('satuan', function ($data) {
                return $data->u_name;
            })
            ->addColumn('harga', function ($data) {
                return '<span class="text-right">' . Currency::addRupiah($data->pcd_price) . '</span>';
            })
            ->addColumn('jenis_pembayaran', function ($data) {
                return $data->pcd_payment == "K" ? "Konsinyasi" : "Cash";
            })
            ->addColumn('action', function ($data) {
                return '<center><div class="btn-group btn-group-sm">
                                            <button class="btn btn-warning" title="Edit"
                                                    type="button" onclick="editGolonganHarga(\'' . Crypt::encrypt($data->pcd_classprice) . '\', \'' . Crypt::encrypt($data->pcd_detailid) . '\', \'' . $data->pcd_item . '\', \'' . Currency::addRupiah($data->pcd_price) . '\', \'' . $data->pcd_unit . '\', \'' . $data->pcd_type . '\', \'' . $data->pcd_rangeqtystart . '\', \'' . $data->pcd_rangeqtyend . '\', \'' . $data->status . '\')"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-danger" type="button"
                                                    title="Hapus" onclick="hapusGolonganHarga(\'' . Crypt::encrypt($data->pcd_classprice) . '\', \'' . Crypt::encrypt($data->pcd_detailid) . '\', \'' . $data->status . '\')"><i class="fa fa-trash"></i></button>
                                        </div></center>';

            })
            ->addColumn('status', function ($data) {
                if ($data->status == 'Y') {
                    return '<span class="btn btn-sm btn-success btn-khusus">Disetujui</span>';
                } else {
                    return '<span class="btn btn-sm btn-danger btn-khusus">Pending</span>';
                }
            })
            ->rawColumns(['item', 'jenis', 'range', 'satuan', 'harga', 'jenis_pembayaran', 'action', 'status'])
            ->make(true);
    }

    public function addGolongan(Request $request)
    {
        DB::beginTransaction();
        try {
            $values = [
                'pc_id' => (DB::table('m_priceclass')->max('pc_id')) ? (DB::table('m_priceclass')->max('pc_id')) + 1 : 1,
                'pc_name' => strtoupper($request->nama),
                'pc_insert' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'pc_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ];
            DB::table('m_priceclass')->insert($values);
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function addGolonganHPA(Request $request)
    {
        DB::beginTransaction();
        try {
            $values = [
                'sp_id' => (DB::table('d_salesprice')->max('sp_id')) ? (DB::table('d_salesprice')->max('sp_id')) + 1 : 1,
                'sp_name' => strtoupper($request->namaHPA),
                'sp_insert' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'sp_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ];
            DB::table('d_salesprice')->insert($values);
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function editGolongan(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->idGolongan);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            DB::table('m_priceclass')->where('pc_id', $id)->update([
                'pc_name' => $request->namaGolongan,
                'pc_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]);
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function editGolonganHPA(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->idGolonganHPA);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            DB::table('d_salesprice')->where('sp_id', $id)->update([
                'sp_name' => strtoupper($request->namaGolonganHPA),
                'sp_update' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]);
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function deleteGolongan($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            DB::table('m_priceclass')->where('pc_id', $id)->delete();
            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
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
            foreach ($kode as $code) {
                array_push($temp, $code);
            }
            $kode = $temp;
        }

        if (count($kode) > 0) {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari, $kode) {
//                    $q->whereNotIn('i_code', $kode);
                    $q->where('i_code', 'like', '%' . $cari . '%');
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                })
                ->whereNotIn('i_code', $kode)->get();
        } else {
            $nama = DB::table('m_item')
                ->where(function ($q) use ($cari) {
                    $q->where('i_code', 'like', '%' . $cari . '%');
                    $q->orWhere('i_name', 'like', '%' . $cari . '%');
                })->get();
        }

        if (count($nama) < 1) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($nama as $query) {
                $results[] = ['id' => $query->i_id, 'label' => $query->i_code . ' - ' . strtoupper($query->i_name), 'data' => $query];
            }
        }
        return Response::json($results);
    }

    public function getSatuan($id)
    {
        $data = DB::table('m_item')
            ->select('m_item.*', 'a.u_id as id1', 'a.u_name as unit1', 'b.u_id as id2', 'b.u_name as unit2', 'c.u_id as id3', 'c.u_name as unit3')
            ->where('m_item.i_id', '=', $id)
            ->join('m_unit as a', function ($x) {
                $x->on('m_item.i_unit1', '=', 'a.u_id');
            })
            ->leftjoin('m_unit as b', function ($y) {
                $y->on('m_item.i_unit2', '=', 'b.u_id');
            })
            ->leftjoin('m_unit as c', function ($z) {
                $z->on('m_item.i_unit3', '=', 'c.u_id');
            })
            ->first();
        return Response::json($data);
    }

    public function addGolonganHarga(Request $request)
    {
        try {
            $idGol = Crypt::decrypt($request->idGol);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }
        DB::beginTransaction();
        try {
            if ($request->jenisharga == "U") {

                $check = DB::table('d_priceclassauthdt')
                    ->where('pcad_classprice', '=', $idGol)
                    ->where('pcad_item', '=', $request->idBarang)
                    ->where('pcad_unit', '=', $request->satuanBarang)
                    ->where('pcad_type', '=', $request->jenisharga)
                    ->where('pcad_payment', '=', $request->jenis_pembayaran)
                    ->get();

                $check2 = DB::table('m_priceclassdt')
                    ->where('pcd_classprice', '=', $idGol)
                    ->where('pcd_item', '=', $request->idBarang)
                    ->where('pcd_unit', '=', $request->satuanBarang)
                    ->where('pcd_type', '=', $request->jenisharga)
                    ->where('pcd_payment', '=', $request->jenis_pembayaran)
                    ->get();

                if (count($check) > 0 || count($check2) > 0) {
                    return response()->json(['status' => "Unit Ada"]);
                } else {
                    $checkGol1 = DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->count();
                    $checkGol2 = DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->count();

                    if ($checkGol1 > 0 && $checkGol2 > 0) {
                        $tmp_detail1 = DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid');
                        $tmp_detail2 = DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid');

                        if ($tmp_detail1 > $tmp_detail2) {
                            $detailid = (DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid')) + 1;
                        } else if ($tmp_detail2 > $tmp_detail1) {
                            $detailid = (DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid')) + 1;
                        }

                    } else if ($checkGol1 > 0 && $checkGol2 == 0) {
                        $detailid = (DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid')) + 1;
                    } else if ($checkGol1 == 0 && $checkGol2 > 0) {
                        $detailid = (DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid')) + 1;
                    } else if ($checkGol1 == 0 &&  $checkGol2 == 0) {
                        $detailid = 1;
                    }

                    $values = [
                        'pcad_classprice' => $idGol,
                        'pcad_detailid' => $detailid,
                        'pcad_item' => $request->idBarang,
                        'pcad_unit' => $request->satuanBarang,
                        'pcad_type' => $request->jenisharga,
                        'pcad_payment' => $request->jenis_pembayaran,
                        'pcad_rangeqtystart' => 1,
                        'pcad_rangeqtyend' => 1,
                        'pcad_price' => Currency::removeRupiah($request->harga),
                        'pcad_user' => Auth::user()->u_id
                    ];
                    DB::table('d_priceclassauthdt')->insert($values);
                    DB::commit();
                    return response()->json(['status' => "Success"]);
                }
            } else {
                $check = DB::table('d_priceclassauthdt')
                    ->where('pcad_classprice', '=', $idGol)
                    ->where('pcad_item', '=', $request->idBarang)
                    ->where('pcad_unit', '=', $request->satuanrange)
                    ->where('pcad_type', '=', $request->jenisharga)
                    ->where('pcad_payment', '=', $request->jenis_pembayaranrange)
                    ->get();

                $check2 = DB::table('m_priceclassdt')
                    ->where('pcd_classprice', '=', $idGol)
                    ->where('pcd_item', '=', $request->idBarang)
                    ->where('pcd_unit', '=', $request->satuanBarang)
                    ->where('pcd_type', '=', $request->jenisharga)
                    ->where('pcd_payment', '=', $request->jenis_pembayaranrange)
                    ->get();

                $sts = '';
                foreach ($check as $key => $val) {
                    if (in_array($request->rangestart, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend))) {
                        $sts = 'Not Null';
                        return response()->json(['status' => "Range Ada"]);
                        break;
                    } else {
                        $sts = 'Null';
                        continue;
                    }
                }

                foreach ($check2 as $key => $val) {
                    if (in_array($request->rangestart, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend))) {
                        $sts = 'Not Null';
                        return response()->json(['status' => "Range Ada"]);
                        break;
                    } else {
                        $sts = 'Null';
                        continue;
                    }
                }

                if ($sts = "Null") {
                    $checkGol1 = DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->count();
                    $checkGol2 = DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->count();

                    if ($checkGol1 > 0 && $checkGol2 > 0) {
                        $tmp_detail1 = DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid');
                        $tmp_detail2 = DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid');

                        if ($tmp_detail1 > $tmp_detail2) {
                            $detailid = (DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid')) + 1;
                        } else if ($tmp_detail2 > $tmp_detail1) {
                            $detailid = (DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid')) + 1;
                        }

                    } else if ($checkGol1 > 0 && $checkGol2 == 0) {
                        $detailid = (DB::table('m_priceclassdt')->where('pcd_classprice', '=', $idGol)->max('pcd_detailid')) + 1;
                    } else if ($checkGol1 == 0 && $checkGol2 > 0) {
                        $detailid = (DB::table('d_priceclassauthdt')->where('pcad_classprice', '=', $idGol)->max('pcad_detailid')) + 1;
                    }

                    $values = [
                        'pcad_classprice' => $idGol,
                        'pcad_detailid' => $detailid,
                        'pcad_item' => $request->idBarang,
                        'pcad_unit' => $request->satuanrange,
                        'pcad_type' => $request->jenisharga,
                        'pcad_payment' => $request->jenis_pembayaranrange,
                        'pcad_rangeqtystart' => $request->rangestart,
                        'pcad_rangeqtyend' => ($request->rangeend == "~") ? 0 : $request->rangeend,
                        'pcad_price' => Currency::removeRupiah($request->hargarange),
                        'pcad_user' => Auth::user()->u_id
                    ];
                    DB::table('d_priceclassauthdt')->insert($values);
                    DB::commit();
                    return response()->json(['status' => "Success"]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function deleteGolonganHarga($id, $detail, $status)
    {
        try {
            $id = Crypt::decrypt($id);
            $detail = Crypt::decrypt($detail);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            if ($status == "N") {
                DB::table('d_priceclassauthdt')
                    ->where('pcad_classprice', '=', $id)
                    ->where('pcad_detailid', '=', $detail)
                    ->delete();
            } else if ($status == "Y") {
                DB::table('m_priceclassdt')
                    ->where('pcd_classprice', '=', $id)
                    ->where('pcd_detailid', '=', $detail)
                    ->delete();
            }

            DB::commit();
            return response()->json(['status' => "Success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function editGolonganHargaUnit(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->golId);
            $detail = Crypt::decrypt($request->golDetail);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            if ($request->status == "N") {
                DB::table('d_priceclassauthdt')
                    ->where('pcad_classprice', '=', $id)
                    ->where('pcad_detailid', '=', $detail)
                    ->update([
                        'pcad_unit' => $request->satuanBarangUnitEdit,
                        'pcad_price' => Currency::removeRupiah($request->editharga),
                    ]);

                DB::commit();
                return response()->json(['status' => "Success"]);
            } else if ($request->status == "Y") {
                $price = DB::table('m_priceclassdt')
                    ->where('pcd_classprice', '=', $id)
                    ->where('pcd_detailid', '=', $detail);

                if ($price->count() > 0) {
                    //insert in d_priceclassauthdt
                    $val = [
                        'pcad_classprice'   => $price->first()->pcd_classprice,
                        'pcad_detailid'     => $price->first()->pcd_detailid,
                        'pcad_item'         => $price->first()->pcd_item,
                        'pcad_unit'         => $request->satuanBarangUnitEdit,
                        'pcad_type'         => $price->first()->pcd_type,
                        'pcad_payment'      => $price->first()->pcd_payment,
                        'pcad_rangeqtystart'=> $price->first()->pcd_rangeqtystart,
                        'pcad_rangeqtyend'  => $price->first()->pcd_rangeqtyend,
                        'pcad_price'        => Currency::removeRupiah($request->editharga),
                        'pcad_user'         => $price->first()->pcd_user
                    ];
                    DB::table('d_priceclassauthdt')->insert($val);

                    //delete in m_priceclassdt
                    $price->delete();
                    DB::commit();
                    return response()->json(['status' => "Success"]);
                } else {
                    DB::rollBack();
                    return response()->json(['status' => "Failed"]);
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed"]);
        }
    }

    public function editGolonganHargaRange(Request $request)
    {
        $sts = '';
        try {
            $id = Crypt::decrypt($request->golIdRange);
            $detail = Crypt::decrypt($request->golDetailRange);
        } catch (DecryptException $e) {
            return response()->json(['status' => "Failed"]);
        }

        DB::beginTransaction();
        try {
            $check = DB::table('d_priceclassauthdt')
                ->where('pcad_classprice', '=', $id)
                ->where('pcad_item', '=', $request->golItemRange)
                ->where('pcad_unit', '=', $request->satuanBarangRangeEdit)
                ->where('pcad_type', '=', "R")
                ->get();

            $check2 = DB::table('m_priceclassdt')
                ->where('pcd_classprice', '=', $id)
                ->where('pcd_item', '=', $request->golItemRange)
                ->where('pcd_unit', '=', $request->satuanBarangRangeEdit)
                ->where('pcd_type', '=', "R")
                ->get();



            if (count($check) > 0) {
                if ($request->rangestartedit == $request->rangestartawal && $request->rangeendedit == $request->rangestartakhir) {
                    $sts = 'Null';
                } else {
                    foreach ($check as $key => $val) {
                        if ($val->pcad_classprice != $id && $val->pcad_detailid != $detail) {
                            if (in_array($request->rangestartedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend))) {
                                $sts = 'Not Null';
                                return response()->json(['status' => "Range Ada"]);
                                break;
                            } else if (in_array($request->rangeendedit, range($val->pcad_rangeqtystart, $val->pcad_rangeqtyend))) {
                                $sts = 'Not Null';
                                return response()->json(['status' => "Range Ada"]);
                                break;
                            } else {
                                $sts = 'Null';
                                continue;
                            }
                        }
                    }
                }
            } else if (count($check2) > 0) {
                if ($request->rangestartedit == $request->rangestartawal && $request->rangeendedit == $request->rangestartakhir) {
                    $sts = 'Null';
                } else {
                    foreach ($check2 as $key => $val) {
                        if ($val->pcd_classprice != $id && $val->pcd_detailid != $detail) {
                            if (in_array($request->rangestartedit, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend))) {
                                $sts = 'Not Null';
                                return response()->json(['status' => "Range Ada"]);
                                break;
                            } else if (in_array($request->rangeendedit, range($val->pcd_rangeqtystart, $val->pcd_rangeqtyend))) {
                                $sts = 'Not Null';
                                return response()->json(['status' => "Range Ada"]);
                                break;
                            } else {
                                $sts = 'Null';
                                continue;
                            }
                        }
                    }
                }
            }

            if ($sts = "Null") {
                if ($request->statusRange == "N") {
                    DB::table('d_priceclassauthdt')
                        ->where('pcad_classprice', '=', $id)
                        ->where('pcad_detailid', '=', $detail)
                        ->update([
                            'pcad_unit' => $request->satuanBarangRangeEdit,
                            'pcad_price' => Currency::removeRupiah($request->edithargarange),
                            'pcad_rangeqtystart' => $request->rangestartedit,
                            'pcad_rangeqtyend' => $request->rangeendedit
                        ]);
                    DB::commit();
                    return response()->json(['status' => "Success"]);
                } else if ($request->statusRange == "Y") {
                    $price = DB::table('m_priceclassdt')
                        ->where('pcd_classprice', '=', $id)
                        ->where('pcd_detailid', '=', $detail);

                    if ($price->count() > 0) {
                        //insert in d_priceclassauthdt
                        DB::table('d_priceclassauthdt')->insert([
                            'pcad_classprice'   => $price->first()->pcd_classprice,
                            'pcad_detailid'     => $price->first()->pcd_detailid,
                            'pcad_item'         => $price->first()->pcd_item,
                            'pcad_unit'         => $request->satuanBarangRangeEdit,
                            'pcad_type'         => $price->first()->pcd_type,
                            'pcad_payment'      => $price->first()->pcd_payment,
                            'pcad_rangeqtystart'=> $request->rangestartedit,
                            'pcad_rangeqtyend'  => $request->rangeendedit,
                            'pcad_price'        => Currency::removeRupiah($request->edithargarange),
                            'pcad_user'         => $price->first()->pcd_user
                        ]);

                        //delete in m_priceclassdt
                        $price->delete();

                        DB::commit();
                        return response()->json(['status' => "Success"]);
                    } else {
                        DB::rollBack();
                        return response()->json(['status' => "Failed"]);
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => "Failed", "msg" => $e]);
        }
    }

    public function create_golonganharga($id)
    {
        if (in_array($id, range(1, 7))) {
            echo 'Number ' . $id . ' is in range 1-7';
        } else {
            echo 'Number ' . $id . ' not in range 1-7';
        }
    }

    public function edit_golonganharga()
    {
        return view('masterdatautama.harga.golongan.edit');
    }
}
