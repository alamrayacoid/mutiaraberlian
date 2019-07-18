<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_jurnal_detail
 *
 * @property int $jrdt_jurnal
 * @property int $jrdt_nomor
 * @property int $jrdt_akun
 * @property float $jrdt_value
 * @property string $jrdt_dk D = debet | K = kredit
 * @property string|null $jrdt_cashflow
 * @property string|null $jrdt_keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtCashflow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtDk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtJurnal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereJrdtValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_jurnal_detail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_jurnal_detail extends Model
{
    protected $table = 'dk_jurnal_detail';
    protected $primaryKey = ['jrdt_transaksi', 'jrdt_nomor'];
    public $incrementing = false;
}
