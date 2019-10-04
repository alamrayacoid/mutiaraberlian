<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_returndt extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_returndt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('rd_return', '=', $this->getAttribute('rd_return'))
        ->where('rd_detailid', '=', $this->getAttribute('rd_detailid'));
        return $query;
    }

    public function getReturn()
    {
        return $this->belongsTo('App\d_return', 'rd_return', 'r_id');
    }
    // get production-code
    public function getProdCode()
    {
        return $this->hasMany('App\d_returncode', ['d_return', 'd_returndt'], ['rd_return', 'rd_detailid']);
    }
    // get item detail
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'rd_item', 'i_id');
    }
    // get-relation with table m_unit
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'rd_unit', 'u_id');
    }
    // get-relation with table d_stock
    public function getStock()
    {
        return $this->belongsTo('App\d_stock', 'rd_item', 's_item');
    }
}
