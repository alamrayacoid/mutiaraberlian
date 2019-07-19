<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_jabatan
 *
 * @property int $j_id
 * @property string|null $j_name
 * @property string|null $j_web
 * @property string|null $j_mobile
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan whereJId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan whereJMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan whereJName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_jabatan whereJWeb($value)
 * @mixin \Eloquent
 */
class m_jabatan extends Model
{
    protected $table = 'm_jabatan';
    protected $primaryKey  = 'j_id';
    public $timestamps = false;
}
