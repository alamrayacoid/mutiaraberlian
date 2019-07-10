<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class m_priceclassdt extends Model
{
    protected $table = 'm_priceclassdt';
    public $timestamps = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
        ->where('pcd_classprice', '=', $this->getAttribute('pcd_classprice'))
        ->where('pcd_detailid', '=', $this->getAttribute('pcd_detailid'));
        return $query;
    }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'pcd_item', 'i_id');
    }
}
