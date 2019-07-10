<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salescomppayment extends Model
{
    protected $table = 'd_salescomppayment';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('scp_salescomp', '=', $this->getAttribute('scp_salescomp'))
        ->where('scp_detailid', '=', $this->getAttribute('scp_detailid'));
        return $query;
    }

    public function getSalesComp()
    {
        return $this->belongsTo('App\d_salescomp', 'scp_salescomp', 'sc_id');
    }
}
