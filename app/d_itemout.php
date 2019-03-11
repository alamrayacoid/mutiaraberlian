<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_itemout extends Model
{
    protected $table = 'd_itemout';
    protected $primaryKey  = 'io_id';
    public $timestamps = false;

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'io_item', 'i_id');
    }
    public function getUnit()
    {
      return $this->belongsTo('App\m_unit', 'io_unit', 'u_id');
    }
    public function getMutcat()
    {
      return $this->belongsTo('App\m_mutcat', 'io_mutcat', 'm_id');
    }
    public function getMutationDetail()
    {
      return $this->hasMany('App\d_stock_mutation', 'sm_nota', 'io_nota');
    }
}
