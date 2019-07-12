<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_paymentmethod extends Model
{
    protected $table = 'm_paymentmethod';
    protected $primaryKey  = 'pm_id';
    public $timestamps = false;
}
