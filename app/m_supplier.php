<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_supplier
 *
 * @property int $s_id
 * @property string|null $s_company
 * @property string|null $s_name
 * @property string|null $s_npwp
 * @property string|null $s_address
 * @property string|null $s_phone
 * @property string|null $s_phone1
 * @property string|null $s_phone2
 * @property string|null $s_rekening
 * @property string|null $s_atasnama
 * @property string|null $s_bank
 * @property string|null $s_fax
 * @property string|null $s_note
 * @property int|null $s_top termin of payment (tgl jatuh tempo bayar hutang)
 * @property int|null $s_deposit tanggal jatuh tempo pengiriman barang
 * @property float $s_limit limit hutang supplier
 * @property float $s_hutang hutang supplier
 * @property string $s_isactive
 * @property \Illuminate\Support\Carbon $s_insert
 * @property \Illuminate\Support\Carbon $s_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSAtasnama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSDeposit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSHutang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSPhone1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSPhone2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_supplier whereSUpdate($value)
 * @mixin \Eloquent
 */
class m_supplier extends Model
{
    protected $table = 'm_supplier';
    protected $primaryKey  = 's_id';

    const CREATED_AT = 's_insert';
    const UPDATED_AT = 's_update';

    public function getPO()
    {
        return $this->hasMany('App\d_productionorder', 'po_supplier', 's_id');
    }
}
