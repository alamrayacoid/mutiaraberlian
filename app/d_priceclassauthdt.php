<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_priceclassauthdt extends Model
{
    protected $table = 'd_priceclassauthdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('pcad_classprice', '=', $this->getAttribute('pcad_classprice'))
        ->where('pcad_detailid', '=', $this->getAttribute('pcad_detailid'));
        return $query;
    }

}
