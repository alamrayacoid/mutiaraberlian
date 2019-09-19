<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_budgeting extends Model
{
    protected $table = 'd_budgeting';
    protected $primaryKey  = 'b_id';

    const CREATED_AT = 'b_insert_at';
    const UPDATED_AT = 'b_update_at';
}
