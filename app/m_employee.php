<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_employee extends Model
{
    protected $table       = 'm_employee';

    public function username()
    {
        return $this->belongsTo('App/d_username', 'u_code', 'e_id');
    }
}
