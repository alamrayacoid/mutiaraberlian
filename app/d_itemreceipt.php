<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_itemreceipt
 *
 * @property int $ir_id
 * @property string|null $ir_notapo
 * @property \Illuminate\Support\Carbon $ir_insert
 * @property \Illuminate\Support\Carbon $ir_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt whereIrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt whereIrInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt whereIrNotapo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceipt whereIrUpdate($value)
 * @mixin \Eloquent
 */
class d_itemreceipt extends Model
{
    protected $table = 'd_itemreceipt';
    protected $primaryKey  = 'ir_id';

    const CREATED_AT = 'ir_insert';
    const UPDATED_AT = 'ir_update';

    public function getIRDetail()
    {
        return $this->hasMany('App\d_itemreceiptdt', 'ird_itemreceipt', 'ir_id');
    }
    public function getProductionOrder()
    {
        return $this->belongsTo('App\d_productionorder', 'ir_notapo', 'po_nota');
    }

}
