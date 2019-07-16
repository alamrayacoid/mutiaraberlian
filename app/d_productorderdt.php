<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_productorderdt extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_productorderdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('pod_productorder', '=', $this->getAttribute('pod_productorder'))
        ->where('pod_detailid', '=', $this->getAttribute('pod_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'pod_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'pod_unit', 'u_id');
    }
    public function getProdCode()
    {
        return $this->hasMany('App\d_productordercode', ['poc_productorder', 'poc_item'], ['pod_productorder', 'pod_item']);
    }

}
