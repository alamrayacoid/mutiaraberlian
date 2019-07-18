<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_productionorderpayment
 *
 * @property int $pop_productionorder
 * @property int $pop_termin termin ke-
 * @property string|null $pop_date tanggal pelunasan
 * @property string|null $pop_datetop batas maksimal termin
 * @property float|null $pop_value tagihan
 * @property float $pop_pay bayar
 * @property string $pop_status status lunas / tidak
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopDatetop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopProductionorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopTermin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productionorderpayment wherePopValue($value)
 * @mixin \Eloquent
 */
class d_productionorderpayment extends Model
{
    protected $table = 'd_productionorderpayment';
    protected $primaryKey  = 'pop_productionorder';
    public $timestamps = false;

    public function getPO()
    {
        return $this->belongsTo('App\d_productionorder', 'pop_productionorder', 'po_id');
    }
}
