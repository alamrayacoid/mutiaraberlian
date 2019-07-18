<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_presence
 *
 * @property int $p_id
 * @property int $p_detailid
 * @property string|null $p_date
 * @property string|null $p_employee e_id
 * @property string|null $p_entry
 * @property string|null $p_out
 * @property string|null $p_status hadir, izin, tidak masuk, cuti
 * @property string|null $p_note keterangan ex: sakit, bepergian
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePEntry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_presence wherePStatus($value)
 * @mixin \Eloquent
 */
class d_presence extends Model
{
    protected $table = 'd_presence';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('p_id', '=', $this->getAttribute('p_id'))
        ->where('p_detailid', '=', $this->getAttribute('p_detailid'));
        return $query;
    }

    public function getEmployee()
    {
        return $this->belongsTo('App\m_employee', 'p_employee', 'e_id');
    }

}
