<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_regulationaction extends Model
{
    protected $table = 'd_regulationaction';
    protected $primaryKey = 'ra_id';

    public function getEmployee()
    {
        return $this->belongsTo('App\m_employee', 'ra_employee', 'e_id');
    }
    public function getRegActDetail()
    {
        return $this->hasMany('App\d_regulationactiondt', 'rad_regulationaction', 'ra_id');
    }
}
