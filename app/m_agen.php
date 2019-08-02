<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_agen
 *
 * @property int $a_id
 * @property string $a_code AG00000001
 * @property string $a_area id kota
 * @property string $a_name
 * @property string|null $a_birthday
 * @property string|null $a_email
 * @property string $a_telp
 * @property string|null $a_address
 * @property string|null $a_sex
 * @property int|null $a_class
 * @property int|null $a_salesprice
 * @property string $a_mma
 * @property string $a_type
 * @property string|null $a_parent
 * @property string|null $a_desa
 * @property string|null $a_kecamatan
 * @property string|null $a_kabupaten
 * @property string|null $a_provinsi
 * @property string|null $a_img
 * @property string $a_isactive
 * @property string|null $a_insert
 * @property string $a_update
 * @property-read \App\d_username $username
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereABirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereACode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereADesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAMma($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereASalesprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereASex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereATelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_agen whereAUpdate($value)
 * @mixin \Eloquent
 */
class m_agen extends Model
{
    protected $table       = 'm_agen';
    protected $primaryKey  = 'a_id';
    const CREATED_AT = 'a_insert';
    const UPDATED_AT = 'a_update';

    public function username()
    {
        return $this->belongsTo('App\d_username', 'u_code', 'a_code');
    }
    public function getArea()
    {
        return $this->belongsTo('App\m_wil_kota', 'a_area', 'wc_id');
    }
    public function getProvince()
    {
        return $this->belongsTo('App\m_wil_provinsi', 'a_provinsi', 'wp_id');
    }
    public function getCity()
    {
        return $this->belongsTo('App\m_wil_kota', 'a_kabupaten', 'wc_id');
    }
    public function getCompany()
    {
        return $this->belongsTo('App\m_company', 'a_code', 'c_user');
    }
}
