<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_salesprice
 *
 * @property int $sp_id
 * @property string|null $sp_name
 * @property \Illuminate\Support\Carbon|null $sp_insert
 * @property \Illuminate\Support\Carbon|null $sp_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice whereSpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice whereSpInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice whereSpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_salesprice whereSpUpdate($value)
 * @mixin \Eloquent
 */
class d_salesprice extends Model
{
    protected $table = 'd_salesprice';
    protected $primaryKey  = 'sp_id';
    const CREATED_AT = 'sp_insert';
    const UPDATED_AT = 'sp_update';

    public function getSalesPriceDt()
    {
        return $this->hasMany('App\d_salespricedt', 'spd_salesprice', 'sp_id');
    }
}
