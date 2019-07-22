<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_sales
 *
 * @property int $s_id
 * @property string|null $s_comp
 * @property string|null $s_member kode agen
 * @property string|null $s_type CASH | KONSINYASI
 * @property string|null $s_date
 * @property string|null $s_nota PC-001/27/12/2019 Cash | PK-001/27/12/2019 Konsinyasi
 * @property float|null $s_total
 * @property int|null $s_user auth user
 * @property \Illuminate\Support\Carbon $s_insert
 * @property \Illuminate\Support\Carbon $s_update
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSInsert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_sales whereSUser($value)
 * @mixin \Eloquent
 */
class d_sales extends Model
{
    protected $table = 'd_sales';
    protected $primaryKey = 's_id';
    const CREATED_AT = 's_insert';
    const UPDATED_AT = 's_update';

    public function getSalesDt()
    {
      return $this->hasMany('App\d_salesdt', 'sd_sales', 's_id');
    }
    public function getMember()
    {
        return $this->belongsTo('App\m_member', 's_member', 'm_code');
    }
    public function getUser()
    {
        return $this->belongsTo('App\d_username', 's_comp', 'u_company');
    }
    // get sales-web
    public function getSalesWeb()
    {
        return $this->belongsTo('App\d_salesweb', 's_nota', 'sw_reff');
    }
}
