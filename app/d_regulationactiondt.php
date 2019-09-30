<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_regulationactiondt extends Model
{
    protected $table = 'd_regulationactiondt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('rad_regulationaction', '=', $this->getAttribute('rad_regulationaction'))
        ->where('rad_detailid', '=', $this->getAttribute('rad_detailid'));
        return $query;
    }

    public function getRegAct()
    {
        return $this->belongsTo('App\d_regulationaction', 'rad_regulationaction', 'ra_id');
    }
    public function getRegulation()
    {
        return $this->belongsTo('App\m_regulations', 'rad_regulation', 'r_id');
    }
}
