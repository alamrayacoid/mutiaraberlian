<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_stock extends Model
{
    protected $table = 'd_stock';
    protected $primaryKey  = 's_id';
    const CREATED_AT = 's_created_at';
    const UPDATED_AT = 's_updated_at';

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 's_item', 'i_id');
    }
    // get-relation with table d_stock_mutation
    public function getMutation()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_stock', 's_id');
    }
    // get stock-detail
    public function getStockDt()
    {
        return $this->hasMany('App\d_stockdt', 'sd_stock', 's_id');
    }
}
