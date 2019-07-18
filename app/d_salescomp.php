<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_salescomp
 *
 * @property int $sc_id
 * @property string|null $sc_comp
 * @property string|null $sc_member
 * @property string|null $sc_type CASH | KONSINYASI
 * @property string|null $sc_date
 * @property string|null $sc_nota SC-001/27/12/2019 Cash | SK-001/27/12/2019 Konsinyasi
 * @property float|null $sc_total
 * @property string|null $sc_datetop deadline pembayaran
 * @property string $sc_paidoff
 * @property string $sc_paymenttype
 * @property int|null $sc_paymentmethod akun yang digunakan
 * @property int|null $sc_user
 * @property \Illuminate\Support\Carbon $sc_insert
 * @property \Illuminate\Support\Carbon $sc_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScDatetop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScPaidoff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScPaymentmethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScPaymenttype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomp whereScUser($value)
 * @mixin \Eloquent
 */
class d_salescomp extends Model
{
    protected $table = 'd_salescomp';
    protected $primaryKey = 'sc_id';
    const CREATED_AT = 'sc_insert';
    const UPDATED_AT = 'sc_update';

    public function getSalesCompDt()
    {
        return $this->hasMany('App\d_salescompdt', 'scd_sales', 'sc_id');
    }
    // get salescomp-payment
    public function getSalesCompPayment()
    {
        return $this->hasMany('App\d_salescomppayment', 'scp_salescomp', 'sc_id');
    }
    // get sc_member
    public function getAgent()
    {
        return $this->belongsTo('App\m_company', 'sc_member', 'c_id');
    }
    // get sc_comp
    public function getComp()
    {
        return $this->belongsTo('App\m_company', 'sc_comp', 'c_id');
    }
    // get-stock-mutation based on no-nota
    public function getMutation()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_nota', 'sc_nota');
    }
    // get-stock-mutation based on no-nota as sm_reff
    public function getMutationReff()
    {
        return $this->hasMany('App\d_stock_mutation', 'sm_reff', 'sc_nota');
    }
}
