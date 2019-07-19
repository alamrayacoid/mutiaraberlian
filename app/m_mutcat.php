<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_mutcat
 *
 * @property int $m_id
 * @property string|null $m_name
 * @property string $m_status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat whereMId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat whereMName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_mutcat whereMStatus($value)
 * @mixin \Eloquent
 */
class m_mutcat extends Model
{
    protected $table = 'm_mutcat';
    protected $primaryKey  = 'm_id';
    public $timestamps = false;
}
