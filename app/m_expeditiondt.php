<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class m_expeditiondt extends Model
{
    protected $table = 'm_expeditiondt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('ed_expedition', '=', $this->getAttribute('ed_expedition'))
        ->where('ed_detailid', '=', $this->getAttribute('ed_detailid'));
        return $query;
    }

}
