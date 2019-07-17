<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_akun
 *
 * @property int $ak_id
 * @property string $ak_nomor
 * @property string $ak_tahun
 * @property string $ak_comp
 * @property string $ak_nama
 * @property string $ak_sub_id
 * @property int $ak_kelompok
 * @property string|null $ak_posisi D = Debet | K = Kredit
 * @property string|null $ak_opening_date
 * @property float $ak_opening Saldo Awal Pembukaan
 * @property string $ak_setara_kas
 * @property string|null $ak_status
 * @property string $ak_isactive 1 = aktif | 0 = non aktif
 * @property int|null $ak_akun_utama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkAkunUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkOpening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkOpeningDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkPosisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkSetaraKas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkSubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereAkTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_akun extends Model
{
    protected $table = 'dk_akun';
    protected $primaryKey = 'ak_id';
}
