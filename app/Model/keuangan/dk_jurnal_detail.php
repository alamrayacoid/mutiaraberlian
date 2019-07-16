<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_jurnal_detail extends Model
{
    protected $table = 'dk_jurnal_detail';
    protected $primaryKey = ['jrdt_transaksi', 'jrdt_nomor'];
    public $incrementing = false;
}
