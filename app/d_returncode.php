<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_returncode extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;

    protected $table = 'd_returncode';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('d_return', '=', $this->getAttribute('d_return'))
        ->where('d_returndt', '=', $this->getAttribute('d_returndt'))
        ->where('d_detailid', '=', $this->getAttribute('d_detailid'));
        return $query;
    }

    public function getReturn()
    {
        return $this->belongsTo('App\d_return', 'd_return', 'r_id');
    }
    public function getReturnDt()
    {
        return $this->belongsTo('App\d_returndt', ['d_return', 'd_returndt'], ['rd_return', 'rd_detailid']);
    }
}
