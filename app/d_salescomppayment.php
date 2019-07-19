<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_salescomppayment
 *
 * @property int $scp_salescomp
 * @property int $scp_detailid
 * @property string|null $scp_date
 * @property float|null $scp_pay
 * @property int|null $scp_payment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment whereScpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment whereScpDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment whereScpPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment whereScpPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salescomppayment whereScpSalescomp($value)
 * @mixin \Eloquent
 */
class d_salescomppayment extends Model
{
    protected $table = 'd_salescomppayment';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('scp_salescomp', '=', $this->getAttribute('scp_salescomp'))
        ->where('scp_detailid', '=', $this->getAttribute('scp_detailid'));
        return $query;
    }

    public function getSalesComp()
    {
        return $this->belongsTo('App\d_salescomp', 'scp_salescomp', 'sc_id');
    }
}
