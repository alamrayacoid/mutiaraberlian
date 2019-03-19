<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_supplier extends Model
{
    protected $table = 'm_supplier';
    protected $primaryKey  = 's_id';

    const CREATED_AT = 's_insert';
    const UPDATED_AT = 's_update';
}