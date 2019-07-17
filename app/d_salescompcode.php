<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salescompcode
 *
 * @property int $ssc_salescomp
 * @property int $ssc_item
 * @property int $ssc_detailid
 * @property string|null $ssc_code
 * @property int|null $ssc_qty
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode whereSscCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode whereSscDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode whereSscItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode whereSscQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescompcode whereSscSalescomp($value)
 * @mixin \Eloquent
 */
class d_salescompcode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_salescompcode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('ssc_salescomp', '=', $this->getAttribute('ssc_salescomp'))
        ->where('ssc_item', '=', $this->getAttribute('ssc_item'))
        ->where('ssc_detailid', '=', $this->getAttribute('ssc_detailid'));
        return $query;
    }

    public function getSalesCompById()
    {
        return $this->belongsTo('App\d_salescomp', 'ssc_salescomp', 'sc_id');
    }
    public function getSalesCompDt()
    {
        return $this->belongsTo('App\d_salescompdt', ['ssc_salescomp', 'ssc_item'], ['scd_sales', 'scd_item']);
    }
}
