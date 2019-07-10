<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    //Input Transaksi
    public function inputtransaksi_index()
    {
        return view('keuangan/transaksi/index');
    }

    public function create_transaksikas()
    {
        return view('keuangan/inputtransaksi/transaksikas/create');
    }

    public function create_transaksibank()
    {
        return view('keuangan/inputtransaksi/transaksibank/create');
    }
    
    public function create_transaksimemorial()
    {
        return view('keuangan/inputtransaksi/transaksimemorial/create');
    }

    // Laporan Keuangan
    public function laporankeuangan_index()
    {
        return view('keuangan/laporankeuangan/index');
    }

    public function laporankeuangan_jurnal()
    {
        return view('keuangan/laporankeuangan/jurnal/index');
    }

    public function laporankeuangan_bukubesar()
    {
        return view('keuangan/laporankeuangan/bukubesar/index');
    }

    public function laporankeuangan_neraca()
    {
        return view('keuangan/laporankeuangan/neraca/index');
    }

    public function laporankeuangan_labarugi()
    {
        return view('keuangan/laporankeuangan/labarugi/index');
    }

    public function laporankeuangan_aruskas()
    {
        return view('keuangan/laporankeuangan/aruskas/index');
    }

}
