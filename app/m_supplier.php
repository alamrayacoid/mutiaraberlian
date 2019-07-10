<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_supplier extends Model
{
    protected $table = 'm_supplier';
    protected $primaryKey  = 's_id';

    const CREATED_AT = 's_insert';
    const UPDATED_AT = 's_update';

    public function getPO()
    {
        return $this->hasMany('App\d_productionorder', 'po_supplier', 's_id');
    }
}
