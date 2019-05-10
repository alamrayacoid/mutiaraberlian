<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CodeGenerator extends Controller
{
    public static function code($table, $field, $lebar = 0, $awalan)
    {
        //        CodeGenerator::code('m_company', 'c_id', 7, 'MB');
        $code = DB::table($table)->select($field)->orderBy($field, 'desc')->limit(1);
        $countData = $code->count();
        if ($countData == 0) {
            $nomor = 1;
        } else {
            $getData = $code->get();
            $row = array();
            foreach ($getData as $value) {
                $row = array($value->$field);
            }
            $nomor = intval(substr($row[0], strlen($awalan))) + 1;
        }

        if ($lebar > 0) {
            $angka = $awalan . str_pad($nomor, $lebar, "0", STR_PAD_LEFT);
        } else {
            $angka = $awalan . $nomor;
        }

        return $angka;
    }

    public static function codeWithSeparator($table, $field, $mulai = 0, $panjang = 0, $lebar = 0, $awalan, $separator)
    {
        // mulai: index (start with '1') after awalan and separator (date-start) ex: PC-001/01/01/2019 so mulai = 8
        // panjang: date-length (default with 10)
        // lebar: max digits of incrementing number
        // CodeGenerator::codeWithSeparator('m_company', 'c_id', 8, 10, 3, 'MB', '-');
        $code = DB::table($table)->where(DB::raw('substr(' . $field . ', ' . $mulai . ', ' . $panjang . ')'), '=', Carbon::now('Asia/Jakarta')->format('d/m/Y'));

        $countData = $code->count();

        $nomor = $countData + 1;


        if ($lebar > 0) {
            $angka = $awalan . $separator . str_pad($nomor, $lebar, "0", STR_PAD_LEFT) . '/' . Carbon::now('Asia/Jakarta')->format('d/m/Y');
        } else {
            $angka = $awalan . $separator . $nomor . '/' . Carbon::now('Asia/Jakarta')->format('d/m/Y');
        }

        return $angka;
    }
}
