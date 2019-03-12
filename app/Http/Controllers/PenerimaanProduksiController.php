<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenerimaanProduksiController extends Controller
{
    public function penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/index');
    }
    public function create_penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/create');
    }

    public function getNotaPO()
    {

    }
}
