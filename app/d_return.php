<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_return
 *
 * @property int $r_id
 * @property string|null $r_nota RT-001/26/06/2019
 * @property string|null $r_reff nota pembelian
 * @property string|null $r_date
 * @property string|null $r_member c_id -> m_company
 * @property int|null $r_item
 * @property int|null $r_qty satuan terkecil
 * @property string|null $r_code kode produksi
 * @property string|null $r_type ganti barang, uang, potong nota;
 * @property string|null $r_note
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRReff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_return whereRType($value)
 * @mixin \Eloquent
 */
class d_return extends Model
{
    protected $table = 'd_return';
    public $timestamps = false;

    public function getReturnDt()
    {
        return $this->hasMany('App\d_returndt', 'rd_return', 'r_id');
    }
    public function getMember()
    {
        return $this->belongsTo('App\m_company', 'r_member', 'c_id');
    }
    public function getComp()
    {
        return $this->belongsTo('App\m_company', 'r_comp', 'c_id');
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'r_item', 'i_id');
    }

}
