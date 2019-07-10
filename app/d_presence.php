<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_presence extends Model
{
    protected $table = 'd_presence';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('p_id', '=', $this->getAttribute('p_id'))
        ->where('p_detailid', '=', $this->getAttribute('p_detailid'));
        return $query;
    }

    public function getEmployee()
    {
        return $this->belongsTo('App\m_employee', 'p_employee', 'e_id');
    }

}
