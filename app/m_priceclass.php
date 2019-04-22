<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_priceclass extends Model
{
    protected $table = 'm_priceclass';
    protected $primaryKey  = 'pc_id';
    const CREATED_AT = 'pc_insert';
    const UPDATED_AT = 'pc_update';

    public function getPriceClassDt()
    {
        return $this->hasMany('App\m_priceclassdt', 'pcd_classprice', 'pc_id');
    }

}
