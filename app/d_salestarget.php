<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_salestarget extends Model
{
    protected $table = 'd_salestarget';
    protected $primaryKey  = 'st_id';
    const CREATED_AT = 'st_insert';
    const UPDATED_AT = 'st_update';

    public function getSalesTargetDt()
    {
        return $this->hasMany('App\d_salestargetdt', 'std_salestarget', 'st_id');
    }
    public function getCompany()
    {
        return $this->belongsTo('App\m_company', 'st_comp', 'c_id');
    }
}
