<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stock_mutation extends Model
{
    protected $table = 'd_stock_mutation';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sm_stock', '=', $this->getAttribute('sm_stock'))
        ->where('sm_detailid', '=', $this->getAttribute('sm_detailid'));
        return $query;
    }
    // ????
    // public function getMutaionDt($query)
    // {
    //     return $query->join('d_stockmutationdt', function ($join) {
    //         $join->on('d_stockmutationdt.smd_stock', 'd_stock_mutation.sm_stock');
    //         $join->on('d_stockmutationdt.smd_stockmutation', 'd_stock_mutation.sm_detailid');
    //     });
    // }

    // relation with table d_stock
    public function getStock()
    {
        return $this->belongsTo('App\d_stock', 'sm_stock', 's_id');
    }
}
