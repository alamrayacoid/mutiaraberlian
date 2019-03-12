<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class d_stock_mutation extends Model
{
  protected $table = 'd_stock_mutation';
  public $timestamps = false;

  protected function setKeysForSaveQuery(Builder $query)
  {
    $query
      ->where('sm_stock', '=', $this->getAttribute('sm_stock'))
      ->where('sm_detailid', '=', $this->getAttribute('sm_detailid'));
    return $query;
  }
}
