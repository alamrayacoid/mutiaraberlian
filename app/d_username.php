<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class d_username extends Authenticatable
{
    protected $table       = 'd_username';
    protected $primaryKey  = 'u_id';
    public $incrementing   = false;
    public $remember_token = false;
    const CREATED_AT       = 'u_created_at';
    const UPDATED_AT       = 'u_updated_at';

    protected $fillable = ['u_id','u_company', 'u_username', 'u_password', 'u_user', 'u_lastlogin', 'u_lastlogout'];

    public function agen()
    {
        return $this->hasOne('App/m_agen', 'a_code', 'u_code');
    }

    public function employee()
    {
        return $this->hasOne(m_employee::class, 'e_id', 'u_code');
    }
}
