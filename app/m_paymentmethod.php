<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_paymentmethod
 *
 * @property int $pm_id
 * @property string|null $pm_name
 * @property int|null $pm_akun
 * @property string|null $pm_note
 * @property string $pm_isactive
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod wherePmAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod wherePmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod wherePmIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod wherePmName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_paymentmethod wherePmNote($value)
 * @mixin \Eloquent
 */
class m_paymentmethod extends Model
{
    protected $table = 'm_paymentmethod';
    protected $primaryKey  = 'pm_id';
    public $timestamps = false;

    public function getAkun()
    {
        return $this->belongsTo('App\Model\keuangan\dk_akun', 'pm_akun', 'ak_id');
    }
}
