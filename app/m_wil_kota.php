<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_wil_kota
 *
 * @property int $wc_id
 * @property string $wc_provinsi
 * @property string $wc_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota whereWcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota whereWcName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_wil_kota whereWcProvinsi($value)
 * @mixin \Eloquent
 */
class m_wil_kota extends Model
{
    protected $table = 'm_wil_kota';
    protected $primaryKey  = 'wc_id';
    public $timestamps = false;

    public function getProvince()
    {
        return $this->belongsTo('App\m_wil_provinsi', 'wc_provinsi', 'wp_id');
    }
}
