<?php

namespace App\Model\Keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_hierarki_dua extends Model
{
    protected $table = "dk_hierarki_dua";
    protected $primarKey= 'hd_id';

    public function akun(){
    	return $this->hasMany('App\Model\keuangan\dk_akun', 'ak_kelompok', 'hd_id');
    }
}
