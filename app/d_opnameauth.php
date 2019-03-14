<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_opnameauth extends Model
{
    protected $table = 'd_opnameauth';
    protected $primaryKey  = 'oa_id';
    public $timestamps = false;

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'oa_item', 'i_id');
    }
    public function getUnitReal()
    {
      return $this->belongsTo('App\m_unit', 'oa_unitreal', 'u_id');
    }
    public function getUnitSystem()
    {
      return $this->belongsTo('App\m_unit', 'oa_unitsystem', 'u_id');
    }
    public function getPosition()
    {
      return $this->belongsTo('App\m_company', 'oa_position', 'c_id');
    }
    public function getOwner()
    {
      return $this->belongsTo('App\m_company', 'oa_comp', 'c_id');
    }
}
