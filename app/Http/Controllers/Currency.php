<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Currency extends Controller
{
    public static function removeRupiah($angka)
    {
        $angka = implode("", explode('Rp. ', $angka));
        $angka = implode("", explode('.', $angka));
        return $angka;
    }

    public static function addRupiah($angka){

        $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
        return $hasil_rupiah;

    }

    public static function addCurrency($angka){

        $hasil_rupiah = number_format($angka,0,',','.');
        return $hasil_rupiah;

    }
}
