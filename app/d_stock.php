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

}
