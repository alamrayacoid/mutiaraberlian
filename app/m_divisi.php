<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_divisi
 *
 * @property int $m_id
 * @property string|null $m_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_divisi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_divisi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_divisi query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_divisi whereMId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_divisi whereMName($value)
 * @mixin \Eloquent
 */
class m_divisi extends Model
{
    protected $table       = 'm_divisi';
    protected $primaryKey  = 'm_id';
    public $incrementing   = false;
}
