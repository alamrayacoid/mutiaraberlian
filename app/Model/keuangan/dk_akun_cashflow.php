<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_akun_cashflow extends Model
{
    protected $table = 'dk_akun_cashflow';
    protected $primaryKey = 'ac_id';

    public function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_akun_cashflow', 'ac_type', 'ac_type');
    }
}
