<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_salescomp extends Model
{
    protected $table = 'd_salescomp';
    protected $primaryKey = 'sc_id';
    const CREATED_AT = 'sc_insert';
    const UPDATED_AT = 'sc_update';

    public function getSalesCompDt()
    {
        return $this->hasMany('App\d_salescompdt', 'scd_sales', 'sc_id');
    }
    // get-agent from username (agent = user who is logged in)
    public function getAgent()
    {
        return $this->belongsTo('App\m_company', 'sc_member', 'c_id');
    }
    // get-stock-mutation based on no-nota
    public function getMutation()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_nota', 'sc_nota');
    }
    // get-stock-mutation based on no-nota as sm_reff
    public function getMutationReff()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_reff', 'sc_nota');
    }
}
