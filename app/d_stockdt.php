<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stockdt extends Model
{
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
