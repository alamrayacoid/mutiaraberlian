<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_productionorder
 *
 * @property int $po_id
 * @property string|null $po_nota PO-001/24/02/2019
 * @property string|null $po_date
 * @property int|null $po_supplier
 * @property float|null $po_totalnet
 * @property string $po_status
 * @property \Illuminate\Support\Carbon|null $po_insert
 * @property \Illuminate\Support\Carbon|null $po_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoTotalnet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorder wherePoUpdate($value)
 * @mixin \Eloquent
 */
class d_productionorder extends Model
{
    protected $table = 'd_productionorder';
    protected $primaryKey  = 'po_id';

    const CREATED_AT = 'po_insert';
    const UPDATED_AT = 'po_update';

    public function getSupplier()
    {
        return $this->belongsTo('App\m_supplier', 'po_supplier', 's_id');
    }

    public function getPOPayment()
    {
        return $this->hasMany('App\d_productionorderpayment', 'pop_productionorder', 'po_id');
    }

    public function getPODt()
    {
        return $this->hasMany('App\d_productionorderdt', 'pod_productionorder', 'po_id');
    }

    public function getItemReceipt()
    {
        return $this->hasOne('App\d_itemreceipt', 'ir_notapo', 'po_nota');
    }

}
