<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_agen extends Model
{
    protected $table       = 'm_agen';
    protected $primaryKey  = 'a_id';

    public function username()
    {
        return $this->belongsTo('App/d_username', 'u_code', 'a_code');
    }
    public function getArea()
    {
        return $this->belongsTo('App\m_wil_kota', 'a_area', 'wc_id');
    }
    public function getProvince()
    {
        return $this->belongsTo('App\m_wil_provinsi', 'a_provinsi', 'wp_id');
    }
    public function getCity()
    {
        return $this->belongsTo('App\m_wil_kota', 'a_kabupaten', 'wc_id');
    }
}
