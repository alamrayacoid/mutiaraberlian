<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_stockdistributiondt
 *
 * @property int $sdd_stockdistribution
 * @property int $sdd_detailid
 * @property string|null $sdd_comp pemilik barang
 * @property int|null $sdd_item
 * @property int|null $sdd_qty
 * @property int|null $sdd_unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddStockdistribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stockdistributiondt whereSddUnit($value)
 * @mixin \Eloquent
 */
class d_stockdistributiondt extends Model
{
    // use third-party library to create relationship multi-column
    // used in getProdCode
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_stockdistributiondt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sdd_stockdistribution', '=', $this->getAttribute('sdd_stockdistribution'))
        ->where('sdd_detailid', '=', $this->getAttribute('sdd_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'sdd_item', 'i_id');
    }
    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'sdd_unit', 'u_id');
    }
    public function getProdCode()
    {
        return $this->hasMany('App\d_stockdistributioncode', ['sdc_stockdistribution', 'sdc_stockdistributiondt'], ['sdd_stockdistribution', 'sdd_detailid']);
    }

    // // get mutation
    // public function scopeGetMutationOut($query)
    // {
    //     // join with 'd_stockdistribution'
    //     $query->join('d_stockdistribution', function ($join) {
    //         $join->on('d_stockdistribution.sd_id', 'sdd_stockdistribution');
    //     });
    //     // join with 'd_stock'
    //     $query->join('d_stock', function ($join) {
    //         $join->on('d_stock.s_item', 'sdd_item');
    //         $join->on('d_stock.s_position', 'sd_from');
    //         $join->where('d_stock.s_status', 'ON DESTINATION');
    //         $join->where('d_stock.s_condition', 'FINE');
    //         // $join->whereHas('getMutation');
    //     });
    //     return $query;
    // }
    // public function scopeGetMutationIn($query)
    // {
    //     return $query->join('d_stock_mutation', function ($join) {
    //         $join->on('d_stock_mutation.sm_nota', 'd_stockdistribution.sd_nota');
    //         // $join->where('d_stock_mutation.sm_mutcat', 18);
    //     })
    //     ->get();
    // }
}
