<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_member
 *
 * @property int $m_id
 * @property string $m_code CUS0000000001 | 13 DIGIT
 * @property string|null $m_name
 * @property string|null $m_tlp
 * @property string|null $m_nik
 * @property string|null $m_address
 * @property string|null $m_province
 * @property string|null $m_city
 * @property string|null $m_agen
 * @property string $m_status
 * @property \Illuminate\Support\Carbon $m_insert
 * @property \Illuminate\Support\Carbon $m_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMAgen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMTlp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_member whereMUpdate($value)
 * @mixin \Eloquent
 */
class m_member extends Model
{
    protected $table = 'm_member';
    protected $primaryKey  = 'm_id';
    const CREATED_AT = 'm_insert';
    const UPDATED_AT = 'm_update';

    public function getAgent()
    {
        return $this->belongsTo('App\m_agen', 'm_agen', 'a_code');
    }

}
