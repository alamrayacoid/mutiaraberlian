<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salesdt
 *
 * @property int $sd_sales
 * @property int $sd_detailid
 * @property string|null $sd_comp
 * @property int|null $sd_item
 * @property int|null $sd_qty
 * @property int|null $sd_unit
 * @property float|null $sd_value
 * @property int|null $sd_discpersen
 * @property float|null $sd_discvalue
 * @property float|null $sd_totalnet
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdDiscpersen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdDiscvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdTotalnet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesdt whereSdValue($value)
 * @mixin \Eloquent
 */
class d_salesdt extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_salesdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sd_sales', '=', $this->getAttribute('sd_sales'))
        ->where('sd_detailid', '=', $this->getAttribute('sd_detailid'));
        return $query;
    }

    public function getSales()
    {
        return $this->belongsTo('App\d_sales', 'sd_sales', 's_id');
    }
    // get production-code
    public function getProdCode()
    {
        return $this->hasMany('App\d_salescode', ['sc_sales', 'sc_item'], ['sd_sales', 'sd_item']);
    }
    public function getItemOwner()
    {
        return $this->belongsTo('App\m_company', 'sd_comp', 'c_id');
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'sd_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'sd_unit', 'u_id');
    }
}
