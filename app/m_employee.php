<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_employee extends Model
{
    protected $table       = 'm_employee';
    protected $primaryKey  = 'e_id';
    public $incrementing   = false;

    public function username()
    {
        return $this->belongsTo(d_username::class, 'e_id', 'u_code');
    }
}
