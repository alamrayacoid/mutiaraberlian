<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_wil_provinsi extends Model
{
    protected $table = 'm_wil_provinsi';
    protected $primaryKey  = 'wp_id';
    public $timestamps = false;

    public function getCities()
    {
        return $this->hasMany('App\m_wil_kota', 'wc_provinsi', 'wp_id');
    }
}
