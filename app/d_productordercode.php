<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_productordercode extends Model
{
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