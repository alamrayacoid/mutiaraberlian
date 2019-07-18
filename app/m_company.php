<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_company
 *
 * @property string $c_id MB00000001
 * @property string|null $c_name
 * @property string|null $c_address
 * @property string|null $c_tlp
 * @property string|null $c_hp
 * @property string $c_type PUSAT | CABANG | AGEN
 * @property string|null $c_user user pemilik
 * @property string|null $c_area
 * @property string $c_isactive
 * @property \Illuminate\Support\Carbon $c_insert
 * @property \Illuminate\Support\Carbon $c_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCTlp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_company whereCUser($value)
 * @mixin \Eloquent
 */
class m_company extends Model
{
    protected $table = 'm_company';
    protected $primaryKey  = 'c_id';
    public $incrementing = false;

    const CREATED_AT = 'c_insert';
    const UPDATED_AT = 'c_update';

    public function getCity()
    {
        return $this->belongsTo('App\m_wil_kota', 'c_area', 'wc_id');
    }
    public function getAgent()
    {
        return $this->belongsTo('App\m_agen', 'c_user', 'a_code');
    }
}
