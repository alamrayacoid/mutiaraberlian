<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_item extends Model
{
    protected $table = 'm_item';
    protected $primaryKey  = 'i_id';

    const CREATED_AT = 'i_created_at';
    const UPDATED_AT = 'i_update_at';
}
