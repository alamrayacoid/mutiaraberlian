<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_productordercode
 *
 * @property int $poc_productorder
 * @property int $poc_item
 * @property int $poc_detailid
 * @property string|null $poc_code
 * @property int|null $poc_qty
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode wherePocCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode wherePocDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode wherePocItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode wherePocProductorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productordercode wherePocQty($value)
 * @mixin \Eloquent
 */
class d_productordercode extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_productordercode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('poc_productorder', '=', $this->getAttribute('poc_productorder'))
        ->where('poc_item', '=', $this->getAttribute('poc_item'))
        ->where('poc_detailid', '=', $this->getAttribute('poc_detailid'));
        return $query;
    }
}
