<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Auth;
use DB;

class d_username extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table       = 'd_username';
    protected $primaryKey  = 'u_id';
    public $incrementing   = false;
    public $remember_token = false;
    protected $hidden = [
        'u_password', 'remember_token',
    ];
    const CREATED_AT       = 'u_created_at';
    const UPDATED_AT       = 'u_updated_at';

    protected $fillable = ['u_id','u_company', 'u_username', 'u_password', 'u_user', 'u_lastlogin', 'u_lastlogout'];

    public function findForPassport($username)
    {
        return $this->where(DB::raw('BINARY u_username'), $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        $login = $this->select('u_password')->first();
        $login = $login->u_password;
        if (sha1(md5('islamjaya') . $password) == $login){
            return true;
        }
        return false;
    }

    public static function getName()
    {
        if (Auth::user()->u_user == 'E'){
            return Auth::user()->employee->e_name;
        } elseif (Auth::user()->u_user == 'A'){
            return Auth::user()->agen->a_name;
        }
    }

    public function agen()
    {
        return $this->hasOne(m_agen::class, 'a_code', 'u_code');
    }

    public function employee()
    {
        return $this->hasOne(m_employee::class, 'e_id', 'u_code');
    }
}
