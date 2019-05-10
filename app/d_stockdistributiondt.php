<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stockdistributiondt extends Model
{
    protected $table = 'd_stockdistributiondt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sdd_stockdistribution', '=', $this->getAttribute('sdd_stockdistribution'))
        ->where('sdd_detailid', '=', $this->getAttribute('sdd_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'sdd_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'sdd_unit', 'u_id');
    }
}
