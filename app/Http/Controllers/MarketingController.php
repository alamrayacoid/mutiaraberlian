<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function marketing()
    {
    	return view('marketing/manajemenmarketing/index');
    }
    
    public function year_promotion_create()
    {
        return view('marketing/manajemenmarketing/tahunan/create');
    }

    public function year_promotion_edit()
    {
        return view('marketing/manajemenmarketing/tahunan/edit');
    }

    public function month_promotion_create()
    {
        return view('marketing/manajemenmarketing/bulanan/create');
    }

    public function month_promotion_edit()
    {
        return view('marketing/manajemenmarketing/bulanan/edit');
    }

    public function targetrealisasi()
    {
        return view('marketing/targetrealisasipenjualan/index');
    }

    public function targetrealisasi_create()
    {
        return view('marketing/targetrealisasipenjualan/targetrealisasi/create');
    }
    
    public function status_target()
    {
        return view('marketing/targetrealisasipenjualan/targetrealisasi/status');
    }

    public function penjualan()
    {
    	return view('marketing/penjualanpusat/index');
    }

    public function returnpenjualanagen_create()
    {
        return view('marketing/penjualanpusat/returnpenjualan/create');
    }

    public function konsinyasipusat()
    {
    	return view('marketing/konsinyasipusat/index');
    }

    public function create_penempatanproduk()
    {
        return view('marketing/konsinyasipusat/penempatanproduk/create');
    }

    public function edit_penempatanproduk()
    {
        return view('marketing/konsinyasipusat/penempatanproduk/edit');
    }

    public function marketing_area()
    {
    	return view('marketing/marketingarea/index');
    }

    public function create_orderproduk()
    {
        return view('marketing/marketingarea/orderproduk/create');
    }

    public function edit_orderproduk()
    {
        return view('marketing/marketingarea/orderproduk/edit');
    }

    public function create_keloladataorder()
    {
        return view('marketing/marketingarea/keloladataorder/create');
    }

    public function edit_keloladataorder()
    {
        return view('marketing/marketingarea/keloladataorder/edit');
    }

    public function create_datacanvassing()
    {
        return view('marketing/marketingarea/datacanvassing/create');
    }

    public function edit_datacanvassing()
    {
        return view('marketing/marketingarea/datacanvassing/edit');
    }

    public function create_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/create');
    }

    public function edit_datakonsinyasi()
    {
        return view('marketing/marketingarea/datakonsinyasi/edit');
    }
    
    public function agen()
    {
    	return view('marketing/agen/index');
    }

    public function create_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/create');
    }

    public function edit_orderprodukagenpusat()
    {
        return view('marketing/agen/orderproduk/edit');
    }
}
