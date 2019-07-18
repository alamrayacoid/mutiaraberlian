<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_priceclass
 *
 * @property int $pc_id
 * @property string|null $pc_name
 * @property \Illuminate\Support\Carbon|null $pc_insert
 * @property \Illuminate\Support\Carbon|null $pc_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass wherePcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass wherePcInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass wherePcName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_priceclass wherePcUpdate($value)
 * @mixin \Eloquent
 */
class m_priceclass extends Model
{
    protected $table = 'm_priceclass';
    protected $primaryKey  = 'pc_id';
    const CREATED_AT = 'pc_insert';
    const UPDATED_AT = 'pc_update';

    public function getPriceClassDt()
    {
        return $this->hasMany('App\m_priceclassdt', 'pcd_classprice', 'pc_id');
    }

}
