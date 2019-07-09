<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_salesprice extends Model
{
    protected $table = 'd_salesprice';
    protected $primaryKey  = 'sp_id';
    const CREATED_AT = 'sp_insert';
    const UPDATED_AT = 'sp_update';

    public function getSalesPriceDt()
    {
        return $this->hasMany('App\d_salespricedt', 'spd_salesprice', 'sp_id');
    }
}
