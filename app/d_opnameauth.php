<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_opnameauth
 *
 * @property int $oa_id
 * @property string $oa_comp
 * @property string $oa_position
 * @property string $oa_date
 * @property string $oa_nota OPNAME-001/27/03/2019
 * @property int|null $oa_item
 * @property int|null $oa_qtyreal
 * @property int|null $oa_unitreal
 * @property int|null $oa_qtysystem
 * @property int|null $oa_unitsystem
 * @property string $oa_insert
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaQtyreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaQtysystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaUnitreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opnameauth whereOaUnitsystem($value)
 * @mixin \Eloquent
 */
class d_opnameauth extends Model
{
    protected $table = 'd_opnameauth';
    protected $primaryKey  = 'oa_id';
    public $timestamps = false;

    public function getItem()
    {
      return $this->belongsTo('App\m_item', 'oa_item', 'i_id');
    }
    public function getUnitReal()
    {
      return $this->belongsTo('App\m_unit', 'oa_unitreal', 'u_id');
    }
    public function getUnitSystem()
    {
      return $this->belongsTo('App\m_unit', 'oa_unitsystem', 'u_id');
    }
    public function getPosition()
    {
      return $this->belongsTo('App\m_company', 'oa_position', 'c_id');
    }
    public function getOwner()
    {
      return $this->belongsTo('App\m_company', 'oa_comp', 'c_id');
    }
}
