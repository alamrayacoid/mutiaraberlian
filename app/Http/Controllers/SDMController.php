<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SDMController extends Controller
{
    public function proses_rekruitmen()
    {
    	return view('sdm/prosesrekruitmen/index');
    }
    public function process()
    {
        return view('sdm/prosesrekruitmen/process');
    }
    public function kinerja()
    {
    	return view('sdm/kinerjasdm/index');
    }
    public function absensi()
    {
    	return view('sdm/absensisdm/index');
    }
    public function penggajian()
    {
    	return view('sdm/penggajian/index');
    }
    public function create_manajemen()
    {
        return view('sdm/penggajian/manajemen/tambah_manajemen');
    }
    public function edit_manajemen()
    {
        return view('sdm/penggajian/manajemen/edit_manajemen');
    }
    public function create_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/tambah_tunjangan');
    }
    public function edit_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/edit_tunjangan');
    }
    public function set_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/set_tunjangan');
    }
    public function edit_set_tunjangan()
    {
        return view('sdm/penggajian/tunjangan/edit_set_tunjangan');
    }
    public function create_produksi()
    {
        return view('sdm/penggajian/produksi/tambah_produksi');
    }
    public function edit_produksi()
    {
        return view('sdm/penggajian/produksi/edit_produksi');
    }
}
