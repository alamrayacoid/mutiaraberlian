<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_stockdistribution
 *
 * @property int $sd_id
 * @property string|null $sd_from distribusi dari company
 * @property string|null $sd_destination distribusi ke company
 * @property string|null $sd_date
 * @property string|null $sd_nota DC-001/29/04/2019 CASH | DK-001/29/04/2019 Konsinyasi
 * @property string $sd_status
 * @property int|null $sd_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistribution whereSdUser($value)
 * @mixin \Eloquent
 */
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
