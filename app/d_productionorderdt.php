<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_productionorderdt extends Model
{
    protected $table = 'd_productionorderdt';
    protected $primaryKey  = 'pod_productionorder';
    public $timestamps = false;

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'pod_item', 'i_id');
    }
    public function getUnit()
    {
      return $this->belongsTo('App\m_unit', 'pod_unit', 'u_id');
    }
}
