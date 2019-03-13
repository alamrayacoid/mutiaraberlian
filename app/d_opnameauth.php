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

}
