<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_stock
 *
 * @property int $s_id
 * @property string $s_comp pemilik barang
 * @property string $s_position posisi barang
 * @property int $s_item
 * @property int $s_qty
 * @property string $s_status ON GOING | ON DESTINATION
 * @property string $s_condition FINE | BROKEN
 * @property int|null $s_qtymin
 * @property int|null $s_qtymax
 * @property int|null $s_qtysafetystart
 * @property int|null $s_qtysafetyend
 * @property \Illuminate\Support\Carbon $s_created_at
 * @property \Illuminate\Support\Carbon $s_updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSQtymax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSQtymin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSQtysafetyend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSQtysafetystart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock whereSUpdatedAt($value)
 * @mixin \Eloquent
 */
class d_stock extends Model
{
    protected $table = 'd_stock';
    protected $primaryKey  = 's_id';
    const CREATED_AT = 's_created_at';
    const UPDATED_AT = 's_updated_at';

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 's_item', 'i_id');
    }
    // get-relation with table d_stock_mutation
    public function getMutation()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_stock', 's_id');
    }
    // get stock-detail
    public function getStockDt()
    {
        return $this->hasMany('App\d_stockdt', 'sd_stock', 's_id');
    }
}
