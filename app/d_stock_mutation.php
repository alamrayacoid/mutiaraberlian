<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_stock_mutation
 *
 * @property int $sm_stock
 * @property int $sm_detailid
 * @property string|null $sm_date
 * @property int|null $sm_mutcat
 * @property int|null $sm_qty
 * @property int|null $sm_use
 * @property int|null $sm_residue
 * @property float|null $sm_hpp
 * @property float|null $sm_sell
 * @property string|null $sm_nota
 * @property string|null $sm_reff
 * @property string|null $sm_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmHpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmMutcat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmReff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmResidue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmSell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_stock_mutation whereSmUser($value)
 * @mixin \Eloquent
 */
class d_stock_mutation extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_stock_mutation';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('sm_stock', '=', $this->getAttribute('sm_stock'))
        ->where('sm_detailid', '=', $this->getAttribute('sm_detailid'));
        return $query;
    }

    public function getMutationDt()
    {
        return $this->hasMany('App\d_stockmutationdt', ['smd_stock', 'smd_stockmutation'], ['sm_stock', 'sm_detailid']);
    }

    // relation with table d_stock
    public function getStock()
    {
        return $this->belongsTo('App\d_stock', 'sm_stock', 's_id');
    }
}
