<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_stockdistributioncode
 *
 * @property int $sdc_stockdistribution
 * @property int $sdc_stockdistributiondt
 * @property int $sdc_detailid
 * @property string|null $sdc_code
 * @property int|null $sdc_qty
 * @property int|null $sdc_unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcStockdistribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcStockdistributiondt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributioncode whereSdcUnit($value)
 * @mixin \Eloquent
 */
class d_stockdistributioncode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_stockdistributioncode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sdc_stockdistribution', '=', $this->getAttribute('sdc_stockdistribution'))
        ->where('sdc_stockdistributiondt', '=', $this->getAttribute('sdc_stockdistributiondt'))
        ->where('sdc_detailid', '=', $this->getAttribute('sdc_detailid'));
        return $query;
    }
}
