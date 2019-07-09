<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_wil_kota extends Model
{
    protected $table = 'm_wil_kota';
    protected $primaryKey  = 'wc_id';
    public $timestamps = false;

    public function getProvince()
    {
        return $this->belongsTo('App\m_wil_provinsi', 'wc_provinsi', 'wp_id');
    }
}
