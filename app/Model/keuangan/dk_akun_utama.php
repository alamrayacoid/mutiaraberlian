<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_akun_utama
 *
 * @property int $au_id
 * @property string $au_nomor
 * @property string $au_nama
 * @property string $au_sub_id
 * @property int $au_kelompok
 * @property string|null $au_posisi D = Debet | K = Kredit
 * @property string $au_setara_kas
 * @property string|null $au_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuKelompok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuPosisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuSetaraKas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereAuSubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_akun_utama whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_akun_utama extends Model
{
    protected $table = 'dk_akun_utama';
    protected $primaryKey = 'au_id';
}
