<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_productorder
 *
 * @property int $po_id
 * @property string $po_comp
 * @property string $po_agen
 * @property string $po_date
 * @property string $po_nota PRO-001/27/03/2019
 * @property string $po_status
 * @property string|null $po_send po_send sudah diterima apa belum
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoAgen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoComp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoNota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoSend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_productorder wherePoStatus($value)
 * @mixin \Eloquent
 */
class d_productorder extends Model
{
    protected $table = 'd_productorder';
    protected $primaryKey = 'po_id';
    public $timestamps = false;

    public function getPODt()
    {
        return $this->hasMany('App\d_productorderdt', 'pod_productorder', 'po_id');
    }

    public function getAgent()
    {
        return $this->belongsTo('App\m_company', 'po_agen', 'c_id');
    }
    // get 'from' (company)
    public function getOrigin()
    {
        return $this->belongsTo('App\m_company','po_comp' , 'c_id');
    }
    // get 'to' (company)
    public function getDestination()
    {
        return $this->belongsTo('App\m_company','po_agen' , 'c_id');
    }
}
