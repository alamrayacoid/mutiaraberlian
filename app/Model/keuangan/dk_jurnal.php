<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_jurnal extends Model
{
    protected $table = 'dk_jurnal';
    protected $primaryKey = 'jr_id';

    function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_jurnal_detail', 'jrdt_jurnal', 'jr_id');
    }
}
