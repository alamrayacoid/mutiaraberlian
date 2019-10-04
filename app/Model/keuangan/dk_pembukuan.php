<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_pembukuan extends Model
{
    protected $table = 'dk_pembukuan';
    protected $primaryKey = 'pe_id';

    public function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_pembukuan_detail', 'pd_pembukuan', 'pe_id');
    }
}
