<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salescompcode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_salescompcode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('ssc_salescomp', '=', $this->getAttribute('ssc_salescomp'))
        ->where('ssc_item', '=', $this->getAttribute('ssc_item'))
        ->where('ssc_detailid', '=', $this->getAttribute('ssc_detailid'));
        return $query;
    }

    public function getSalesCompById()
    {
        return $this->belongsTo('App\d_salescomp', 'ssc_salescomp', 'sc_id');
    }
}
