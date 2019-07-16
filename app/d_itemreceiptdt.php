<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_itemreceiptdt extends Model
{
    protected $table = 'd_itemreceiptdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('ird_itemreceipt', '=', $this->getAttribute('ird_itemreceipt'))
        ->where('ird_detailid', '=', $this->getAttribute('ird_detailid'));
        return $query;
    }


}
