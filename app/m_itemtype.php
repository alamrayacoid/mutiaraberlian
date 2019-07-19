<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_itemtype
 *
 * @property int $it_id
 * @property string|null $it_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_itemtype newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_itemtype newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_itemtype query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_itemtype whereItId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_itemtype whereItName($value)
 * @mixin \Eloquent
 */
class m_itemtype extends Model
{
    protected $table = 'm_itemtype';
    protected $primaryKey  = 'it_id';
    public $timestamps = false;

}
