<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RemoveCurrency extends Controller
{
    public static function rupiah($angka)
    {
        $angka = implode("", explode('Rp. ', $angka));
        $angka = implode("", explode('.', $angka));
        return $angka;
    }
}
