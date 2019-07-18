<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salespricedt
 *
 * @property int $spd_salesprice
 * @property int $spd_detailid
 * @property int|null $spd_item
 * @property int|null $spd_unit
 * @property string $spd_type R: RANGE | U: UNIT
 * @property string $spd_payment Cash | Konsinyasi
 * @property int|null $spd_rangeqtystart rentang harga awal
 * @property int|null $spd_rangeqtyend rentang harga akhir
 * @property float|null $spd_price
 * @property int|null $spd_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdRangeqtyend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdRangeqtystart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdSalesprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salespricedt whereSpdUser($value)
 * @mixin \Eloquent
 */
class d_salespricedt extends Model
{
    protected $table = 'd_salespricedt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('spd_salesprice', '=', $this->getAttribute('spd_salesprice'))
        ->where('spd_detailid', '=', $this->getAttribute('spd_detailid'));
        return $query;
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'spd_item', 'i_id');
    }
}
