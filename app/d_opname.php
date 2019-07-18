<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_opname
 *
 * @property int $o_id
 * @property string $o_comp
 * @property string $o_position
 * @property string $o_date
 * @property string $o_nota OPNAME-001/27/03/2019
 * @property int|null $o_item
 * @property int|null $o_qtyreal
 * @property int|null $o_unitreal
 * @property int|null $o_qtysystem
 * @property int|null $o_unitsystem
 * @property string $o_status
 * @property string $o_insert
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereODate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereONota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOQtyreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOQtysystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOUnitreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_opname whereOUnitsystem($value)
 * @mixin \Eloquent
 */
class d_opname extends Model
{
    protected $table = 'd_opname';
    protected $primaryKey  = 'o_id';
    public $timestamps = false;

}
