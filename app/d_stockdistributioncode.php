<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stockdistributioncode extends Model
{
    protected $table = 'd_stockdistributioncode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sdc_stockdistribution', '=', $this->getAttribute('sdc_stockdistribution'))
        ->where('sdc_stockdistributiondt', '=', $this->getAttribute('sdc_stockdistributiondt'))
        ->where('sdc_detailid', '=', $this->getAttribute('sdc_detailid'));
        return $query;
    }
}
