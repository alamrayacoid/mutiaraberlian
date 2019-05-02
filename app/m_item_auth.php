<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class m_item_auth extends Model
{
    protected $table = 'm_item_auth';
    protected $primaryKey = 'ia_id';
    const CREATED_AT = 'ia_created_at';
    const UPDATED_AT = 'ia_update_at';

    // protected function setKeysForSaveQuery(Builder $query)
    // {
    //     $query
    //     ->where('ia_id', '=', $this->getAttribute('ia_id'))
    //     ->where('ia_code', '=', $this->getAttribute('sd_detailiia_code'));
    //     return $query;
    // }
    public function getItem()
    {
        return $this->belongsTo('App\m_item', 'ia_id', 'i_id');
    }
    public function getItemType()
    {
        return $this->belongsTo('App\m_itemtype', 'ia_type', 'it_id');
    }
}
