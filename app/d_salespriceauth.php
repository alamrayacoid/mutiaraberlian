<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salespriceauth extends Model
{
    protected $table = 'd_salespriceauth';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('spa_salesprice', '=', $this->getAttribute('spa_salesprice'))
        ->where('spa_detailid', '=', $this->getAttribute('spa_detailid'));
        return $query;
    }
}
