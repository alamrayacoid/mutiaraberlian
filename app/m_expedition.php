<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class m_expedition extends Model
{
    protected $table       = 'm_expedition';
    protected $primaryKey  = 'e_id';

    public function getExpeditionType()
    {
        return $this->hasMany('App\m_expeditiondt', 'ed_expedition', 'e_id');
    }
}
