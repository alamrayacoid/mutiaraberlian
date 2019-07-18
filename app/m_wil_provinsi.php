<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_wil_provinsi
 *
 * @property int $wp_id
 * @property string $wp_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_provinsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_provinsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_provinsi query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_provinsi whereWpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_provinsi whereWpName($value)
 * @mixin \Eloquent
 */
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
