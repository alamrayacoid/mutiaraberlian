<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
