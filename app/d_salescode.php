<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salescode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_salescode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sc_sales', '=', $this->getAttribute('sc_sales'))
        ->where('sc_item', '=', $this->getAttribute('sc_item'))
        ->where('sc_detailid', '=', $this->getAttribute('sc_detailid'));
        return $query;
    }
}
