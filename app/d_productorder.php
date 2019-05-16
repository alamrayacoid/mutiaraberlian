<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_productorder extends Model
{
    protected $table = 'd_productorder';
    protected $primaryKey = 'po_id';
    public $timestamps = false;

    public function getPODt()
    {
        return $this->hasMany('App\d_productorderdt', 'pod_productorder', 'po_id');
    }

    public function getAgent()
    {
        return $this->belongsTo('App\m_company', 'po_agen', 'c_id');
    }
}
