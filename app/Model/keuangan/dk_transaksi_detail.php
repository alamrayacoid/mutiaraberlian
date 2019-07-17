<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_transaksi_detail
 *
 * @property int $trdt_transaksi
 * @property int $trdt_nomor
 * @property int $trdt_akun
 * @property float $trdt_value
 * @property string $trdt_dk D = debet | K = kredit
 * @property string|null $trdt_cashflow
 * @property string|null $trdt_keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtCashflow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtDk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtTransaksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereTrdtValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_transaksi_detail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_transaksi_detail extends Model
{
    protected $table = 'dk_transaksi_detail';
    protected $primaryKey = ['trdt_transaksi', 'trdt_nomor'];
    public $incrementing = false;
}
