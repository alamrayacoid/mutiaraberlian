<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_expedition
 *
 * @property int|null $e_id
 * @property string|null $e_name
 * @property string $e_isactive
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition whereEId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition whereEIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expedition whereEName($value)
 * @mixin \Eloquent
 */
class m_expedition extends Model
{
    protected $table       = 'm_expedition';
    protected $primaryKey  = 'e_id';

    public function getExpeditionType()
    {
        return $this->hasMany('App\m_expeditiondt', 'ed_expedition', 'e_id');
    }
}
