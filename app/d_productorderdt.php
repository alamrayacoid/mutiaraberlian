<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_productorderdt
 *
 * @property int $pod_productorder
 * @property int $pod_detailid
 * @property int|null $pod_item
 * @property int|null $pod_qty
 * @property int|null $pod_unit
 * @property float|null $pod_price
 * @property float $pod_discvalue
 * @property float $pod_discpersen
 * @property float|null $pod_totalprice
 * @property string $pod_isapproved
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodDiscpersen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodDiscvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodIsapproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodProductorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodTotalprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorderdt wherePodUnit($value)
 * @mixin \Eloquent
 */
class d_productorderdt extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_productorderdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('pod_productorder', '=', $this->getAttribute('pod_productorder'))
        ->where('pod_detailid', '=', $this->getAttribute('pod_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'pod_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'pod_unit', 'u_id');
    }
    public function getProdCode()
    {
        return $this->hasMany('App\d_productordercode', ['poc_productorder', 'poc_item'], ['pod_productorder', 'pod_item']);
    }

}
