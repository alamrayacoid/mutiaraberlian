<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_company extends Model
{
    protected $table = 'm_company';
    protected $primaryKey  = 'c_id';
    public $incrementing = false;

    const CREATED_AT = 'c_insert';
    const UPDATED_AT = 'c_update';

    public function getCity()
    {
        return $this->belongsTo('App\m_wil_kota', 'c_area', 'wc_id');
    }
    public function getAgent()
    {
        return $this->belongsTo('App\m_agen', 'c_user', 'a_code');
    }
}
