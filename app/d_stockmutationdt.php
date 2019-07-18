<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_stockmutationdt
 *
 * @property int $smd_stock
 * @property int $smd_stockmutation
 * @property int $smd_detailid
 * @property string|null $smd_productioncode
 * @property int|null $smd_qty
 * @property int|null $smd_unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdProductioncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdStockmutation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockmutationdt whereSmdUnit($value)
 * @mixin \Eloquent
 */
class d_stockmutationdt extends Model
{
    protected $table = 'd_stockmutationdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('smd_stock', '=', $this->getAttribute('smd_stock'))
        ->where('smd_stockmutation', '=', $this->getAttribute('smd_stockmutation'))
        ->where('smd_detailid', '=', $this->getAttribute('smd_detailid'));
        return $query;
    }
}
