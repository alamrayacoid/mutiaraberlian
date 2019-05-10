<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_stockdistribution extends Model
{
    protected $table = 'd_stockdistribution';
    protected $primaryKey  = 'sd_id';
    public $timestamps = false;

    public function getOrigin()
    {
        return $this->belongsTo('App\m_company','sd_from' , 'c_id');
    }
    public function getDestination()
    {
        return $this->belongsTo('App\m_company','sd_destination' , 'c_id');
    }
    public function getDistributionDt()
    {
        return $this->hasMany('App\d_stockdistributiondt', 'sdd_stockdistribution', 'sd_id');
    }

}
