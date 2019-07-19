<?php

namespace App\Model\Keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Keuangan\dk_hierarki_dua
 *
 * @property int $hd_id
 * @property string $hd_nomor
 * @property string|null $hd_nama
 * @property int|null $hd_level_1
 * @property int|null $hd_subclass
 * @property string|null $hd_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\keuangan\dk_akun[] $akun
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdLevel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereHdSubclass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_dua whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_hierarki_dua extends Model
{
    protected $table = "dk_hierarki_dua";
    protected $primarKey= 'hd_id';

    public function akun(){
    	return $this->hasMany('App\Model\keuangan\dk_akun', 'ak_kelompok', 'hd_id');
    }
}
