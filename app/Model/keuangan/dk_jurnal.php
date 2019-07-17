<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_jurnal
 *
 * @property int $jr_id
 * @property string $jr_type MK: mutasi kas, TK: transaksi kas, TM: transaksi memorial
 * @property string $jr_comp
 * @property string $jr_ref
 * @property string $jr_nota_ref
 * @property string $jr_tanggal_trans
 * @property string $jr_keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\keuangan\dk_jurnal_detail[] $detail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrNotaRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrTanggalTrans($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereJrType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_jurnal extends Model
{
    protected $table = 'dk_jurnal';
    protected $primaryKey = 'jr_id';

    function detail(){
    	return $this->hasMany('App\Model\keuangan\dk_jurnal_detail', 'jrdt_jurnal', 'jr_id');
    }
}
