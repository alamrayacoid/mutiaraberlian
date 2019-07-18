<?php

namespace App\Model\Keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Keuangan\dk_hierarki_satu
 *
 * @property int $hs_id
 * @property string|null $hs_nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Keuangan\dk_hierarki_dua[] $level2
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\keuangan\dk_hierarki_subclass[] $subclass
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu whereHsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu whereHsNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\Keuangan\dk_hierarki_satu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_hierarki_satu extends Model
{
    protected $table = 'dk_hierarki_satu';
    protected $primaryKey = 'hs_id';

    public function level2(){
    	return $this->hasMany('App\Model\keuangan\dk_hierarki_dua', 'hd_level_1', 'hs_id');
    }

    public function subclass(){
    	return $this->hasmany('App\Model\keuangan\dk_hierarki_subclass', 'hs_level_1', 'hs_id');
    }
}
