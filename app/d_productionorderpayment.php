<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_productionorderpayment extends Model
{
    protected $table = 'd_productionorderpayment';
    protected $primaryKey  = 'pop_productionorder';
    public $timestamps = false;
}
