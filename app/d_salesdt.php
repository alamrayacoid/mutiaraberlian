<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_salesdt extends Model
{
    protected $table = 'd_salesdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sd_sales', '=', $this->getAttribute('sd_sales'))
        ->where('sd_detailid', '=', $this->getAttribute('sd_detailid'));
        return $query;
    }

    public function getSales()
    {
        return $this->belongsTo('App\d_sales', 'sd_sales', 's_id');
    }
    public function getItemOwner($value='')
    {
        return $this->belongsTo('App\m_company', 'sd_comp', 'c_id');
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'sd_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'sd_unit', 'u_id');
    }
}
