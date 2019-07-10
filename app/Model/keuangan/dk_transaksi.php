<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_transaksi extends Model
{
    protected $table = 'dk_transaksi';
    protected $primaryKey = 'tr_id';

    function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_transaksi_detail', 'trdt_transaksi', 'tr_id');
    }
}
