<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_item
 *
 * @property int $i_id
 * @property string $i_code
 * @property int|null $i_type m_itemtype
 * @property string|null $i_codegroup
 * @property string|null $i_name
 * @property int|null $i_unit1 id unit (satuan)
 * @property int|null $i_unit2
 * @property int|null $i_unit3
 * @property float|null $i_unitcompare1 konversi satuan dengan unit1 sebagai acuan
 * @property float|null $i_unitcompare2
 * @property float|null $i_unitcompare3
 * @property float|null $i_price1
 * @property float|null $i_price2
 * @property float|null $i_price3
 * @property string|null $i_detail deskripsi item
 * @property string|null $i_isactive
 * @property string|null $i_image
 * @property \Illuminate\Support\Carbon $i_created_at
 * @property \Illuminate\Support\Carbon $i_update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereICode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereICodegroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereICreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIPrice1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIPrice2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIPrice3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnit1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnit2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnit3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnitcompare1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnitcompare2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUnitcompare3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item whereIUpdateAt($value)
 * @mixin \Eloquent
 */
class m_item extends Model
{
    protected $table = 'm_item';
    protected $primaryKey  = 'i_id';

    const CREATED_AT = 'i_created_at';
    const UPDATED_AT = 'i_update_at';

    public function getUnit1()
    {
        return $this->belongsTo('App\m_unit', 'i_unit1', 'u_id');
    }
    public function getUnit2()
    {
        return $this->belongsTo('App\m_unit', 'i_unit2', 'u_id');
    }
    public function getUnit3()
    {
        return $this->belongsTo('App\m_unit', 'i_unit3', 'u_id');
    }
    // get-relation with table d_stock
    public function getStock()
    {
        return $this->hasMany('App\d_stock', 's_item', 'i_id');
    }
}
