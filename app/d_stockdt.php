<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_stockdt
 *
 * @property int $sd_stock
 * @property int $sd_detailid
 * @property string|null $sd_code
 * @property int|null $sd_qty
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt whereSdCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt whereSdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt whereSdQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdt whereSdStock($value)
 * @mixin \Eloquent
 */
class d_stockdt extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_stockdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sd_stock', '=', $this->getAttribute('sd_stock'))
        ->where('sd_detailid', '=', $this->getAttribute('sd_detailid'))
        ->where('sd_code', '=', $this->getAttribute('sd_code'));
        return $query;
    }
}
