<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\m_priceclassdt
 *
 * @property int $pcd_classprice
 * @property int $pcd_detailid
 * @property int|null $pcd_item
 * @property int|null $pcd_unit
 * @property string $pcd_type R: RANGE | U: UNIT
 * @property string $pcd_payment Cash | Konsinyasi
 * @property int|null $pcd_rangeqtystart rentang harga awal
 * @property int|null $pcd_rangeqtyend rentang harga akhir
 * @property float|null $pcd_price
 * @property int|null $pcd_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdClassprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdRangeqtyend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdRangeqtystart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclassdt wherePcdUser($value)
 * @mixin \Eloquent
 */
class m_priceclassdt extends Model
{
    protected $table = 'm_priceclassdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('pcd_classprice', '=', $this->getAttribute('pcd_classprice'))
        ->where('pcd_detailid', '=', $this->getAttribute('pcd_detailid'));
        return $query;
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'pcd_item', 'i_id');
    }
}
