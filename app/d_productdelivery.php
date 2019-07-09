<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_productdelivery extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_productdelivery';
    protected $primaryKey = 'pd_id';
    public $timestamps = false;

    public function getExpedition()
    {
        return $this->belongsTo('App\m_expedition', 'pd_expedition', 'e_id');
    }
    public function getExpeditionType()
    {
        return $this->belongsTo('App\m_expeditiondt', ['pd_expedition', 'pd_product'], ['ed_expedition', 'ed_detailid']);
    }
}
