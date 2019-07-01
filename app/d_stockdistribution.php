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
    public function getMutationByNota()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_nota', 'sd_nota');
    }
    public function getProductDelivery()
    {
        return $this->hasOne('App\d_productdelivery', 'pd_nota', 'sd_nota');
    }

    // // function to delete cascade with distribution-detail ??
    // public static function boot() {
    //     parent::boot();
    //
    //     static::deleting(function($stockdist) {
    //         // //remove related rows region and city
    //         // $country->region->each(function($region) {
    //         //     $region->city()->delete();
    //         // });
    //         $stockdist->getDistributionDt()->delete();//
    //         return true;
    //     });
    // }

}
