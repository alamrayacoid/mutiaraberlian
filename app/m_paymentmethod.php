<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
