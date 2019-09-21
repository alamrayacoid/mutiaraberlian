<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Auth;
use DB;

/**
 * App\d_username
 *
 * @property int $u_id
 * @property string $u_company
 * @property string $u_username
 * @property string|null $u_password
 * @property int|null $u_level
 * @property string|null $u_user A: Agen | E: Employee
 * @property string|null $u_code kode Employee/Agen
 * @property string|null $u_lastlogin
 * @property string|null $u_lastlogout
 * @property \Illuminate\Support\Carbon $u_created_at
 * @property string $u_update_at
 * @property-read \App\m_agen $agen
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \App\m_employee $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereULastlogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereULastlogout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereULevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_username whereUUsername($value)
 * @mixin \Eloquent
 */
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
            if (!is_null(Auth::user()->employee)) {
                return Auth::user()->employee->e_name;
            }
        } elseif (Auth::user()->u_user == 'A'){
            if (!is_null(Auth::user()->agen)) {
                return Auth::user()->agen->a_name;
            }
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

    public function getCompany()
    {
        return $this->belongsTo('App\m_company', 'u_company', 'c_id');
    }
}
