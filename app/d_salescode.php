<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salescode
 *
 * @property int $sc_sales
 * @property int $sc_item
 * @property int $sc_detailid
 * @property string|null $sc_code
 * @property int|null $sc_qty
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode whereScCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode whereScDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode whereScItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode whereScQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescode whereScSales($value)
 * @mixin \Eloquent
 */
class d_salescode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_salescode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sc_sales', '=', $this->getAttribute('sc_sales'))
        ->where('sc_item', '=', $this->getAttribute('sc_item'))
        ->where('sc_detailid', '=', $this->getAttribute('sc_detailid'));
        return $query;
    }

    public function getSales()
    {
        return $this->belongsTo('App\d_sales', 'sc_sales', 's_id');
    }
}
