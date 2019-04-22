<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salespricedt extends Model
{
    protected $table = 'd_salespricedt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('spd_salesprice', '=', $this->getAttribute('spd_salesprice'))
        ->where('spd_detailid', '=', $this->getAttribute('spd_detailid'));
        return $query;
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'spd_item', 'i_id');
    }
}
