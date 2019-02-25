<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function order_produksi()
    {
    	return view('produksi/orderproduksi/index');
    }
    public function create_produksi()
    {
        return view('produksi/orderproduksi/create');
    }
    public function edit_produksi()
    {
        return view('produksi/orderproduksi/edit');
    }
    public function penerimaan_barang()
    {
    	return view('produksi/penerimaanbarang/index');
    }
    public function create_penerimaan_barang()
    {
        return view('produksi/penerimaanbarang/create');
    }    
    public function pembayaran()
    {
    	return view('produksi/pembayaran/index');
    }
    public function return_produksi()
    {
    	return view('produksi/returnproduksi/index');
    }
    public function create_return_produksi()
    {
        return view('produksi/returnproduksi/create');
    }
}
