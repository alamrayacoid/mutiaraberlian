<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\d_itemreceiptdt
 *
 * @property int $ird_itemreceipt
 * @property int $ird_detailid
 * @property string|null $ird_date
 * @property int|null $ird_item
 * @property int|null $ird_qty gunakan satuan terkecil
 * @property int|null $ird_unit
 * @property int|null $ird_user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdItemreceipt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_itemreceiptdt whereIrdUser($value)
 * @mixin \Eloquent
 */
class d_itemreceiptdt extends Model
{
    protected $table = 'd_itemreceiptdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('ird_itemreceipt', '=', $this->getAttribute('ird_itemreceipt'))
        ->where('ird_detailid', '=', $this->getAttribute('ird_detailid'));
        return $query;
    }

    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'ird_item', 'i_id');
    }

    public function getUnit()
    {
        return $this->belongsTo('App\m_unit', 'ird_unit', 'u_id');
    }

}
