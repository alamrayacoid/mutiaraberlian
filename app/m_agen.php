<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_agen extends Model
{
    protected $table       = 'm_agen';
    protected $primaryKey  = 'a_code';

    public function username()
    {
        return $this->belongsTo('App/d_username', 'u_code', 'a_code');
    }
}
