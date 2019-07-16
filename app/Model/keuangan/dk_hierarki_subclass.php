<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_hierarki_subclass extends Model
{
    protected $table = 'dk_hierarki_subclass';
    protected $primaryKey = 'hs_id';

    public function level2(){
    	return $this->hasmany('App\Model\keuangan\dk_hierarki_dua', 'hd_subclass', 'hs_id');
    }
}
