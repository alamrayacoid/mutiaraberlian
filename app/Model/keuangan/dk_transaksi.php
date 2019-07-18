<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_transaksi
 *
 * @property int $tr_id
 * @property string|null $tr_type MK : mutasi kas, TK : transaksi kas, TM : transaksi memorial
 * @property string $tr_comp
 * @property string $tr_nomor
 * @property string $tr_tanggal_trans
 * @property string $tr_keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\keuangan\dk_transaksi_detail[] $detail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrTanggalTrans($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereTrType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_transaksi extends Model
{
    protected $table = 'dk_transaksi';
    protected $primaryKey = 'tr_id';

    function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_transaksi_detail', 'trdt_transaksi', 'tr_id');
    }
}
