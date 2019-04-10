<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_member extends Model
{
    protected $table = 'm_member';
    protected $primaryKey  = 'm_id';
    const CREATED_AT = 'm_insert';
    const UPDATED_AT = 'm_update';

}
