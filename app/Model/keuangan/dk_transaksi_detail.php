<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_transaksi_detail extends Model
{
    protected $table = 'dk_transaksi_detail';
    protected $primaryKey = ['trdt_transaksi', 'trdt_nomor'];
    public $incrementing = false;
}
