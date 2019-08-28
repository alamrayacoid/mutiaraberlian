<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_returnproductionorder extends Model
{
    protected $table = 'd_returnproductionorder';
    public $timestamps = false;

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'rpo_item', 'i_id');
    }
    public function getPO()
    {
        return $this->belongsTo('App\d_productionorder', 'rpo_nota', 'po_nota');
    }

}
