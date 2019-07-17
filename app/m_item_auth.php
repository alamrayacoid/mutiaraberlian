<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\m_item_auth
 *
 * @property int $ia_id
 * @property string $ia_code
 * @property int|null $ia_type m_itemtype
 * @property string|null $ia_codegroup
 * @property string|null $ia_name
 * @property int|null $ia_unit1 id unit (satuan)
 * @property int|null $ia_unit2
 * @property int|null $ia_unit3
 * @property float|null $ia_unitcompare1 konversi satuan dengan unit1 sebagai acuan
 * @property float|null $ia_unitcompare2
 * @property float|null $ia_unitcompare3
 * @property float|null $ia_price1
 * @property float|null $ia_price2
 * @property float|null $ia_price3
 * @property string|null $ia_detail deskripsi item
 * @property string|null $ia_isactive
 * @property string|null $ia_image
 * @property \Illuminate\Support\Carbon $ia_created_at
 * @property \Illuminate\Support\Carbon $ia_update_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaCodegroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaPrice1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaPrice2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaPrice3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnit1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnit2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnit3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnitcompare1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnitcompare2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUnitcompare3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_item_auth whereIaUpdateAt($value)
 * @mixin \Eloquent
 */
class m_item_auth extends Model
{
    protected $table = 'm_item_auth';
    protected $primaryKey = 'ia_id';
    const CREATED_AT = 'ia_created_at';
    const UPDATED_AT = 'ia_update_at';

    // protected function setKeysForSaveQuery(Builder $query)
    // {
    //     $query
    //     ->where('ia_id', '=', $this->getAttribute('ia_id'))
    //     ->where('ia_code', '=', $this->getAttribute('sd_detailiia_code'));
    //     return $query;
    // }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'ia_id', 'i_id');
    }
    public function getItemType()
    {
        return $this->belongsTo('App\m_itemtype', 'ia_type', 'it_id');
    }
}
