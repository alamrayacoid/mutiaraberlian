<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_productdelivery
 *
 * @property int $pd_id
 * @property string|null $pd_date
 * @property string|null $pd_nota nota transaksi
 * @property int|null $pd_expedition m_expedition
 * @property int|null $pd_product m_expeditiondt
 * @property string|null $pd_resi
 * @property string|null $pd_couriername
 * @property string|null $pd_couriertelp
 * @property float|null $pd_price tarif ekspedisi
 * @property string $pd_paidoff sudah dibayar?
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdCouriername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdCouriertelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdExpedition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdPaidoff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productdelivery wherePdResi($value)
 * @mixin \Eloquent
 */
class d_productdelivery extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;
    
    protected $table = 'd_productdelivery';
    protected $primaryKey = 'pd_id';
    public $timestamps = false;

    public function getExpedition()
    {
        return $this->belongsTo('App\m_expedition', 'pd_expedition', 'e_id');
    }
    public function getExpeditionType()
    {
        return $this->belongsTo('App\m_expeditiondt', ['pd_expedition', 'pd_product'], ['ed_expedition', 'ed_detailid']);
    }
}
