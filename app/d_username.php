<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_username extends Model
{
    protected $table       = 'd_username';
    protected $primaryKey  = 'u_id';
    public $incrementing   = false;
    const CREATED_AT       = 'u_created_at';
    const UPDATED_AT       = 'u_updated_at';

    protected $fillable = ['u_id','u_company', 'u_username', 'u_password', 'u_user', 'u_lastlogin', 'u_lastlogout', 'created_at', 'updated_at'];

    public function agen()
    {
        return $this->hasMany('');
    }
}
