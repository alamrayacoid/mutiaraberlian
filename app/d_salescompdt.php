<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salescompdt
 *
 * @property int $scd_sales
 * @property int $scd_detailid
 * @property string|null $scd_comp
 * @property int|null $scd_item
 * @property int|null $scd_qty
 * @property int|null $scd_unit
 * @property float|null $scd_value
 * @property int $scd_discpersen
 * @property float $scd_discvalue
 * @property float|null $scd_totalnet
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdDiscpersen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdDiscvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdTotalnet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompdt whereScdValue($value)
 * @mixin \Eloquent
 */
class d_salescompdt extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_salescompdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('scd_sales', '=', $this->getAttribute('scd_sales'))
        ->where('scd_detailid', '=', $this->getAttribute('scd_detailid'));
        return $query;
    }

    public function getSalesComp()
    {
        return $this->belongsTo('App\d_salescomp', 'scd_sales', 'sc_id');
    }
    // get production-code
    public function getProdCode()
    {
        return $this->hasMany('App\d_salescompcode', ['ssc_salescomp', 'ssc_item'], ['scd_sales', 'scd_item']);
    }
    // get item detail
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'scd_item', 'i_id');
    }
    // get-relation with table m_unit
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'scd_unit', 'u_id');
    }
    // get-relation with table d_stock
    public function getStock()
    {
        return $this->belongsTo('App\d_stock', 'scd_item', 's_item');
    }
}
