<?php

namespace App\Http\Controllers\Produksi;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\pushnotifikasiController as pushNotif;

use App\d_productionorder;
use App\d_productionorderpayment;
use App\d_username;
use App\m_paymentmethod;
use App\m_supplier;
use Carbon\Carbon;
use Crypt;
use Currency;
use DB;
use Mockery\Exception;
use Response;
use Yajra\DataTables\DataTables;
use App\Helper\keuangan\jurnal\jurnal;
use Auth;

class PembayaranController extends Controller
{
    /**
     * Return DataTable list for view.
     *
     * @return Yajra/DataTables
     */
    public function getList(Request $request)
    {
        // dd($request->all());
        $datas = d_productionorderpayment::where('pop_productionorder', $request->po_id)
            ->orderBy('pop_productionorder', 'asc')
            ->orderBy('pop_termin', 'asc')
            ->get();

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('estimasi', function ($datas) {
                return date('d-m-Y', strtotime($datas->pop_datetop));
            })
            ->addColumn('nominal', function ($datas) {
                return '<div class="label"> <p class="float-left">Rp </p>
                    <p class="float-right">' . number_format($datas->pop_value, 2, ',', '.') . '</p>
                </div>';
            })
            ->addColumn('terbayar', function ($datas) {
                return '<div class="label"><p class="float-left">Rp </p>
                <p class="float-right">' . number_format($datas->pop_pay, 2, ',', '.') . '</p>
                </div>';
            })
            ->addColumn('date', function ($datas) {
                return date('d-m-Y', strtotime($datas->pop_date));
            })
            ->addColumn('status', function ($datas) {

                if ($datas->pop_status == 'Y') {
                    return '<div class="text-center">LUNAS</div>';
                    // return '<div class="text-center">
                    //           <div class="status-success">
                    //               <p>Lunas</p>
                    //           </div>
                    //          </div>';
                } elseif ($datas->pop_status == 'N') {
                    return '<div class="text-center">BELUM LUNAS</div>';
                    // return '<div class="text-center">
                    //           <div class="status-danger">
                    //               <p>Belum</p>
                    //           </div>
                    //         </div>';
                }
            })
            ->addColumn('action', function ($datas) {
                if ($datas->pop_status == 'N') {
                    return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                              <button class="btn btn-danger hint--top-left hint--info" aria-label="Bayar" onclick="bayar(\'' . Crypt::encrypt($datas->pop_productionorder) . '\', \'' . Crypt::encrypt($datas->pop_termin) . '\')"><i class="fa fa-money"></i>
                                </button>
                              <button class="btn btn-success hint--top-left hint--info" style="margin-left:3px" aria-label="Print Nota" onclick="openNota(\'' . Crypt::encrypt($datas->pop_productionorder) . '\', \'' . $datas->pop_termin . '\')"><i class="fa fa-print "></i>
                                </button>
                            </div>';
                }else {
                  return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                          <button class="btn btn-success hint--top-left hint--info" style="margin-left:3px" aria-label="Print Nota" onclick="openNota(\'' . Crypt::encrypt($datas->pop_productionorder) . '\', \'' . $datas->pop_termin . '\')"><i class="fa fa-print "></i>
                            </button>
                          </div>';
                }


                // return '<div class="text-center"><div class="btn-group btn-group-sm text-center">
                //               <button class="btn btn-info hint--top-left hint--info" aria-label="Detail" onclick="Detail(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-list"></i>
                //               </button>
                //               <button class="btn btn-danger hint--top-left hint--info" aria-label="Detail" onclick="Bayar(\''.Crypt::encrypt($datas->pop_productionorder).'\', \''.Crypt::encrypt($datas->pop_termin).'\')"><i class="fa fa-money"></i>
                //               </button>
                //           </div>';
            })
            ->rawColumns(['estimasi', 'nominal', 'terbayar', 'date', 'status', 'action'])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = d_productionorder::with('getSupplier')
        //   ->with('getPOPayment')
        //     ->selectRaw('sum(pop_pay) as terbayar, po_id, po_nota')
        //   ->orderBy('po_nota', 'asc')
        //   ->get();

        $data = DB::table('d_productionorder')
            ->join('m_supplier', function ($s) {
                $s->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->join('d_productionorderpayment', function ($pop) {
                $pop->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
            })
            ->groupby(['d_productionorder.po_nota', 'd_productionorderpayment.pop_productionorder'])
            ->select('d_productionorder.po_id as id', 'd_productionorder.po_nota as nota', 'm_supplier.s_company as supplier',
                DB::raw('sum(d_productionorderpayment.pop_value) as value'), DB::raw('sum(d_productionorderpayment.pop_pay) as terbayar'))
            ->get();

        return view('produksi/pembayaran/index', compact('data'));
    }

    public function show($id, $termin)
    {
        try {
            $id = Crypt::decrypt($id);
            $termin = Crypt::decrypt($termin);
        } catch (DecryptException $e) {
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }

        $data = d_productionorder::where('po_id', $id)
            ->with('getPODt')
            ->with('getPODt.getItem')
            ->with('getPODt.getUnit')
            ->with(['getPOPayment' => function ($query) use ($termin) {
                $query->where('pop_termin', $termin);
            }])
            ->first();

        return Response::json(['status' => "Success", 'data' => $data]);
    }

    function listBayar(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $termin = Crypt::decrypt($request->termin);
        } catch (DecryptException $e) {
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }

        // $data = d_productionorder::where('po_id', $id)
        //     ->with('getPODt')
        //     ->with('getSupplier')
        //     ->with('getPODt.getItem')
        //     ->with('getPODt.getUnit')
        //     ->with(['getPOPayment' => function($query) use($termin) {
        //         $query->where('pop_termin',$termin);
        //     }])
        //     ->first();

        $data = DB::table('d_productionorder')
            ->join('d_productionorderpayment', function ($x) use ($termin) {
                $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                $x->where('d_productionorderpayment.pop_termin', '=', $termin);
            })
            ->join('m_supplier', function ($y) use ($termin) {
                $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->where('d_productionorder.po_id', '=', $id)
            ->select('d_productionorder.po_id', 'd_productionorder.po_nota', 'd_productionorder.po_date',
                'm_supplier.s_name', 'd_productionorderpayment.pop_termin', 'd_productionorderpayment.pop_value', 'd_productionorderpayment.pop_pay')
            ->first();

        if ($data->pop_pay == null || $data->pop_pay == "") {
            $pay = 0;
        } else {
            $pay = $data->pop_pay;
        }

        $kekurangan = $data->pop_value - $pay;
        // get list akun-payment
        $userCode = Auth::user()->u_company;
        $paymentMethod = m_paymentmethod::where('pm_isactive', 'Y')
            // ->whereHas('getAkun', function ($q) use ($userCode) {
            //     $q->where('ak_comp', $userCode);
            // })
            // ->with('getAkun')
            ->where('pm_comp', $userCode)
            ->get();

        $listPaymentMethod = [];
        $opt = [
            'id' => '-',
            'text' => 'Pilih Akun Kas'
        ];
        array_push($listPaymentMethod, $opt);
        foreach ($paymentMethod as $key => $value) {
            $opt = [
                'id' => $value->pm_akun,
                'text' => $value->pm_name
            ];
            array_push($listPaymentMethod, $opt);
        }

        $data = [
            'poid' => Crypt::encrypt($data->po_id),
            'nota' => $data->po_nota,
            'supplier' => $data->s_name,
            'tanggal_pembelian' => Carbon::parse($data->po_date)->format('d-m-Y'),
            'termin' => $data->pop_termin,
            'tagihan' => number_format($data->pop_value, 0, ',', ''),
            'terbayar' => number_format($pay, 0, ',', ''),
            'kekurangan' => number_format($kekurangan, 0, ',', ''),
            'payment_method' => $listPaymentMethod
        ];

        return Response::json(['status' => "Success", 'data' => $data]);
    }

    public function printNota(Request $request)
    {

        try {
            $id = Crypt::decrypt($request->id);
            $termin = $request->termin;
        } catch (DecryptException $e) {
            return Response::json(['status' => 'Failed', 'message' => $e]);
        }

        $data = DB::table('d_productionorder')
            ->join('d_productionorderpayment', function ($x) use ($termin) {
                $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                $x->where('d_productionorderpayment.pop_termin', '=', $termin);
            })
            ->join('m_supplier', function ($y) use ($termin) {
                $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->where('d_productionorder.po_id', '=', $id)
            ->select('d_productionorder.po_id', 'd_productionorder.po_nota', 'd_productionorder.po_date',
                'm_supplier.s_name', 'd_productionorderpayment.pop_termin', 'd_productionorderpayment.pop_value', 'd_productionorderpayment.pop_pay')
            ->first();


        if ($data->pop_pay == null || $data->pop_pay == "") {
            $pay = 0;
        } else {
            $pay = $data->pop_pay;
        }

        $kekurangan = $data->pop_value - $pay;

        $tglbeli = Carbon::parse($data->po_date)->format('d-m-Y');

        $date = Carbon::now()->format('d-m-Y');

        return view('produksi.pembayaran.nota')->with(compact('data', 'date', 'kekurangan', 'tglbeli'));
    }

    public function bayar(Request $request)
    {
        $bayar = Currency::removeRupiah($request->nilai_bayar);
        $termin = $request->termin;
        try {
            $poid = Crypt::decrypt($request->poid);
        } catch (DecryptException $e) {
            return Response::json(['status' => "Failed", 'message' => $e]);
        }

        $data_po = DB::table('d_productionorder')
            ->join('d_productionorderpayment', function ($x) use ($termin) {
                $x->on('d_productionorder.po_id', '=', 'd_productionorderpayment.pop_productionorder');
                $x->where('d_productionorderpayment.pop_termin', '=', $termin);
            })
            ->join('m_supplier', function ($y) use ($termin) {
                $y->on('d_productionorder.po_supplier', '=', 'm_supplier.s_id');
            })
            ->where('d_productionorder.po_id', '=', $poid)
            ->select('d_productionorderpayment.pop_value as value', 'd_productionorderpayment.pop_pay as pay')
            ->first();

        $value = number_format($data_po->value, 0, ',', '');
        $status = "N";
        if ($bayar > $value || $data_po->pay + $bayar > $value) {
            return Response::json(['status' => "Failed", 'message' => "Nilai pembayaran yang Anda masukkan melebihi tagihan termin ke-" . $termin]);
        } else if ($bayar == $value) {
            $status = "Y";
        } else if ($data_po->pay + $bayar == $value) {
            $status = "Y";
        }

        DB::beginTransaction();
        try {
            $values = [
                'pop_date' => Carbon::now("Asia/Jakarta")->format("Y-m-d"),
                'pop_pay' => $data_po->pay + $bayar,
                'pop_status' => $status
            ];

            DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $poid)
                ->where('pop_termin', '=', $termin)
                ->update($values);

            $check = DB::table('d_productionorderpayment')
                ->where('pop_productionorder', '=', $poid)
                ->where('pop_status', '=', 'N')
                ->get();

            if (count($check) == 0) {
                DB::table('d_productionorder')
                    ->where('po_id', '=', $poid)
                    ->update([
                        'po_status' => "LUNAS"
                    ]);
            }

            // Tambahan Dirga
                $acc_kas = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Pembayaran Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Kas/Setara Kas')
                                        ->first();

                $hutang = DB::table('dk_pembukuan_detail')
                                        ->where('pd_pembukuan', function($query){
                                            $query->select('pe_id')->from('dk_pembukuan')
                                                        ->where('pe_nama', 'Pembayaran Order Produksi')
                                                        ->where('pe_comp', Auth::user()->u_company)->first();
                                        })->where('pd_nama', 'COA Hutang')
                                        ->first();

                $pembukuan = DB::table('dk_pembukuan')
                                    ->where('pe_nama', 'Pembayaran Order Produksi')
                                    ->where('pe_comp', Auth::user()->u_company)->first();

                $details = []; $count = 0;

                if(!$hutang || !$acc_kas || !$pembukuan){
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'beberapa COA yang digunakan untuk transaksi ini belum ditentukan.'
                    ]);
                }

                array_push($details, [
                    "jrdt_nomor"        => 1,
                    "jrdt_akun"         => $acc_kas->pd_acc,
                    "jrdt_value"        => $bayar,
                    "jrdt_dk"           => "K",
                    "jrdt_keterangan"   => $pembukuan->pe_keterangan,
                    "jrdt_cashflow"     => $acc_kas->pd_cashflow
                ]);

                // set cash-account to auto-generate
                if ($request->cashAccount == '-') {
                    array_push($details, [
                        "jrdt_nomor"        => 2,
                        "jrdt_akun"         => $hutang->pd_acc,
                        "jrdt_value"        => $bayar,
                        "jrdt_dk"           => "D",
                        "jrdt_keterangan"   => $pembukuan->pe_keterangan,
                        "jrdt_cashflow"     => null
                    ]);
                }
                // set cash-account to selected paymentMethod
                else {
                    array_push($details, [
                        "jrdt_nomor"        => 2,
                        "jrdt_akun"         => $request->cashAccount,
                        "jrdt_value"        => $bayar,
                        "jrdt_dk"           => "D",
                        "jrdt_keterangan"   => $pembukuan->pe_keterangan,
                        "jrdt_cashflow"     => null
                    ]);
                }


                $nota = $request->nota.'-'.$termin;

                $jurnal = jurnal::jurnalTransaksi($details, date('Y-m-d'), $nota, $pembukuan->pe_keterangan, 'TK', Auth::user()->u_company);

                if ($jurnal['status'] == 'error'){
                    return json_encode($jurnal);
                }

            // selesai dirga

            // pusher -> push notification
            pushNotif::notifikasiup('Notifikasi Pembayaran Produksi');

            DB::commit();
            return Response::json(['status' => "Success", 'message' => "Data berhasil disimpan"]);
        } catch (Exception $e) {
            DB::rollback();
            return Response::json(['status' => "Failed", 'message' => $e]);
        }
    }

    // ======================= History ======================
    public function findNotaHistory(Request $request)
    {
        $term = $request->term;
        $pos = d_productionorder::where('po_nota', 'like', '%' . $term . '%')
            ->with('getPOPayment')
            ->get();

        if (count($pos) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($pos as $po) {
                $results[] = ['id' => $po->po_id, 'label' => $po->po_nota, 'data' => $po];
            }
        }
        return response()->json($results);
    }

    public function findSupplier(Request $request)
    {
        $term = $request->term;
        $suppliers = m_supplier::where(function ($q) use ($term) {
            $q->orWhere('s_name', 'like', '%' . $term . '%');
            $q->orWhere('s_company', 'like', '%' . $term . '%');
            })
            ->has('getPO')
            ->get();

        if (count($suppliers) == 0) {
            $results[] = ['id' => null, 'label' => 'Tidak ditemukan data terkait'];
        } else {
            foreach ($suppliers as $supp) {
                $results[] = ['id' => $supp->s_id, 'label' => $supp->s_company, 'data' => $supp];
            }
        }
        return response()->json($results);
    }

    public function getListHistory(Request $request)
    {
        $nota = $request->nota;

        if ($nota !== null) {
            $datas = d_productionorder::where('po_nota', $nota)
                ->with('getSupplier')
                ->orderBy('po_nota', 'desc')
                ->get();
        } else {
            $datas = d_productionorder::with('getSupplier')
                ->orderBy('po_nota', 'desc')
                ->get();
        }

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('supplier', function ($datas) {
                return $datas->getSupplier->s_name;
            })
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->po_date)->format('d M Y');
            })
            ->addColumn('action', function ($datas) {
                return
                    '<div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-sm btn-primary" onclick="showDetailHistory(\'' . $datas->po_id . '\')"><i class="fa fa-folder"></i></button>
            </div>';
            })
            ->rawColumns(['date', 'supplier', 'action'])
            ->make(true);
    }

    public function showDetailHistory($id)
    {
        $detail = d_productionorderpayment::where('pop_productionorder', $id)
            ->with('getPO.getSupplier')
            ->get();

        return response()->json($detail);
    }

    public function getListNota(Request $request)
    {
        $date_from = null;
        $date_to = null;
        if ($request->date_from !== null) {
            $date_from = Carbon::parse($request->date_from)->format('Y-m-d');
        }
        if ($request->date_to !== null) {
            $date_to = Carbon::parse($request->date_to)->format('Y-m-d');
        }
        $supplierId = $request->supplier;

        if ($date_from !== null) {
            $datas = d_productionorder::whereBetween('po_date', [$date_from, $date_to])
                ->where('po_supplier', $supplierId)
                ->with('getSupplier')
                ->orderBy('po_nota', 'desc')
                ->get();
        } else {
            $datas = d_productionorder::where('po_supplier', $supplierId)
                ->with('getSupplier')
                ->orderBy('po_nota', 'desc')
                ->get();
        }

        return Datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('date', function ($datas) {
                return Carbon::parse($datas->po_date)->format('d M Y');
            })
            ->addColumn('supplier', function ($datas) {
                return $datas->getSupplier->s_name;
            })
            ->addColumn('action', function ($datas) {
                return
                    '<div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-sm btn-primary" onclick="addFilter(\'' . $datas->po_nota . '\')"><i class="fa fa-download"></i></button>
            </div>';
            })
            ->rawColumns(['date', 'supplier', 'action'])
            ->make(true);
    }

    public function getTerminByDate(Request $request)
    {
        $hari = $request->hari;
        $hari = (int)$hari;
        $hari = str_replace(" Hari", "", $hari);
        $sekarang = Carbon::now('Asia/Jakarta');
        $target = $sekarang->addDays($hari)->format("Y-m-d");

        $data = DB::table('d_productionorder')
            ->join('d_productionorderpayment', 'pop_productionorder', '=', 'po_id')
            ->join('m_supplier', 's_id', '=', 'po_supplier')
            ->select('po_id', 'po_nota', 's_company', DB::raw('date_format(pop_datetop, "%d-%m-%Y") as pop_datetop'),
                DB::raw('concat("Rp. ", replace(format(round(pop_value),0), ",", ".")) as pop_value'), 'pop_status',
                DB::raw('concat("Rp. ", replace(format(round(pop_pay),0), ",", ".")) as pop_pay'))
            ->where('pop_datetop', '>=', $target)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                if ($data->pop_status == 'Y'){
                    $detail = '<button class="btn btn-success" type="button" title="Gunakan" onclick="gunakanNota('.$data->po_id.')" readonly disabled><i class="fa fa-arrow-down"></i></button>';
                } else {
                    $detail = '<button class="btn btn-primary" type="button" title="Gunakan" onclick="gunakanNota('.$data->po_id.')"><i class="fa fa-arrow-down"></i></button>';
                }
                return '<div class="btn-group btn-group-sm">'. $detail .'</div>';
            })
            ->make(true);
    }

}
