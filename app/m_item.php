<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_item extends Model
{
    protected $table = 'm_item';
    protected $primaryKey  = 'i_id';

    const CREATED_AT = 'i_created_at';
    const UPDATED_AT = 'i_update_at';

    public function getUnit1()
    {
        return $this->belongsTo('App\m_unit', 'i_unit1', 'u_id');
    }
    public function getUnit2()
    {
        return $this->belongsTo('App\m_unit', 'i_unit2', 'u_id');
    }
    public function getUnit3()
    {
        return $this->belongsTo('App\m_unit', 'i_unit3', 'u_id');
    }
    // get-relation with table d_stock
    // public function getStock()
    // {
    //     return $this->hasMany('App\d_stock', 's_item', 'i_id');
    // }
}
