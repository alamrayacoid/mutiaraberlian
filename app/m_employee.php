<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_employee extends Model
{
    protected $table       = 'm_employee';
    protected $primaryKey  = 'e_id';
    public $incrementing   = false;

    public function username()
    {
        return $this->belongsTo(d_username::class, 'e_id', 'u_code');
    }
    // get division
    public function getDivision()
    {
        return $this->belongsTo('App\m_divisi', 'e_department', 'm_id');
    }
    // get company
    public function getCompany()
    {
        return $this->belongsTo('App\m_company', 'e_company', 'c_id');
    }
}
