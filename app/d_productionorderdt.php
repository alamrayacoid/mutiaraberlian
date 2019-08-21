<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_productionorderdt
 *
 * @property int $pod_productionorder
 * @property int $pod_detailid
 * @property int|null $pod_item
 * @property int|null $pod_qty
 * @property int|null $pod_unit
 * @property float|null $pod_value
 * @property string $pod_received
 * @property float|null $pod_totalnet
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodProductionorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodTotalnet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderdt wherePodValue($value)
 * @mixin \Eloquent
 */
class d_productionorderdt extends Model
{
    protected $table = 'd_productionorderdt';
    protected $primaryKey  = 'pod_productionorder';
    public $timestamps = false;


    public function getProductionOrder()
    {
        return $this->belongsTo('App\d_productionorder', 'pod_productionorder', 'po_id');
    }
    // public function getProdCode()
    // {
    //     return $this->hasMany('App\d_productionordercode', 'poc_productionorder', 'pod_productionorder');
    // }
    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'pod_item', 'i_id');
    }
    public function getUnit()
    {
      return $this->belongsTo('App\m_unit', 'pod_unit', 'u_id');
    }
}
