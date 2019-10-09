<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_productionordercode
 *
 * @property int $poc_productionorder
 * @property int $poc_detailid
 * @property int|null $poc_item
 * @property string|null $poc_productioncode
 * @property int|null $poc_qty
 * @property int|null $poc_unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocProductioncode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocProductionorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionordercode wherePocUnit($value)
 * @mixin \Eloquent
 */
class d_productionordercode extends Model
{
    protected $table = 'd_productionordercode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('poc_productionorder', '=', $this->getAttribute('poc_productionorder'))
        ->where('poc_detailid', '=', $this->getAttribute('poc_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'poc_item', 'i_id');
    }

    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'poc_unit', 'u_id');
    }

    public function getProductionOrder()
    {
        return $this->belongsTo('App\d_productionorder', 'poc_productionorder', 'po_id');
    }



}
