<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_regulationactiondt extends Model
{
    protected $table = 'd_regulationactiondt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('rad_regulationaction', '=', $this->getAttribute('rad_regulationaction'))
        ->where('rad_detailid', '=', $this->getAttribute('rad_detailid'));
        return $query;
    }
}
