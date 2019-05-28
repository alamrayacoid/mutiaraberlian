<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stockmutationdt extends Model
{
    protected $table = 'd_stockmutationdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('smd_stock', '=', $this->getAttribute('smd_stock'))
        ->where('smd_stockmutation', '=', $this->getAttribute('smd_stockmutation'))
        ->where('smd_detailid', '=', $this->getAttribute('smd_detailid'));
        return $query;
    }
}
