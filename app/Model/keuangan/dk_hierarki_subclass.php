<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\keuangan\dk_hierarki_subclass
 *
 * @property int $hs_id
 * @property string $hs_nama
 * @property int $hs_level_1
 * @property string|null $hs_status
 * @property string|null $hs_flag
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Model\Keuangan\dk_hierarki_dua[] $level2
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereHsFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereHsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereHsLevel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereHsNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereHsStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\keuangan\dk_hierarki_subclass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class dk_hierarki_subclass extends Model
{
    protected $table = 'dk_hierarki_subclass';
    protected $primaryKey = 'hs_id';

    public function level2(){
    	return $this->hasmany('App\Model\keuangan\dk_hierarki_dua', 'hd_subclass', 'hs_id');
    }
}
