<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_itemreceipt extends Model
{
    protected $table = 'd_itemreceipt';
    protected $primaryKey  = 'ir_id';

    const CREATED_AT = 'ir_insert';
    const UPDATED_AT = 'ir_update';

    public function getIRDetail()
    {
        return $this->hasMany('App\d_itemreceiptdt', 'ird_itemreceipt', 'ir_id');
    }

}
