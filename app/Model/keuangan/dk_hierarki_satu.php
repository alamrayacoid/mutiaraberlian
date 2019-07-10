<?php

namespace App\Model\Keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_hierarki_satu extends Model
{
    protected $table = 'dk_hierarki_satu';
    protected $primaryKey = 'hs_id';

    public function level2(){
    	return $this->hasMany('App\Model\keuangan\dk_hierarki_dua', 'hd_level_1', 'hs_id');
    }

    public function subclass(){
    	return $this->hasmany('App\Model\keuangan\dk_hierarki_subclass', 'hs_level_1', 'hs_id');
    }
}
