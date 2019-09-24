<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salestargetdt extends Model
{
    protected $table = 'd_salestargetdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('std_salestarget', '=', $this->getAttribute('std_salestarget'))
        ->where('std_detailid', '=', $this->getAttribute('std_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'std_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'std_unit', 'u_id');
    }
}
