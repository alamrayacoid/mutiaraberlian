<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_unit
 *
 * @property int $u_id
 * @property string $u_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_unit whereUId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_unit whereUName($value)
 * @mixin \Eloquent
 */
class m_unit extends Model
{
  protected $table = 'm_unit';
  protected $primaryKey  = 'u_id';
  public $timestamps = false;
}
