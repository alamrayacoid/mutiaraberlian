<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_itemout
 *
 * @property int $io_id
 * @property string|null $io_date
 * @property string|null $io_nota
 * @property int|null $io_item
 * @property int|null $io_qty
 * @property int|null $io_unit
 * @property int|null $io_mutcat
 * @property string|null $io_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoMutcat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemout whereIoUser($value)
 * @mixin \Eloquent
 */
class d_itemout extends Model
{
    protected $table = 'd_itemout';
    protected $primaryKey  = 'io_id';
    public $timestamps = false;

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'io_item', 'i_id');
    }
    public function getUnit()
    {
      return $this->belongsTo('App\m_unit', 'io_unit', 'u_id');
    }
    public function getMutcat()
    {
      return $this->belongsTo('App\m_mutcat', 'io_mutcat', 'm_id');
    }
    public function getMutationDetail()
    {
      return $this->hasMany('App\d_stock_mutation', 'sm_nota', 'io_nota');
    }
}
