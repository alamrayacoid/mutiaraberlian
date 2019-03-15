<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class d_productionorder extends Model
{
  protected $table = 'd_productionorder';
  protected $primaryKey  = 'po_id';

  const CREATED_AT = 'po_insert';
  const UPDATED_AT = 'po_update';

  public function getSupplier()
  {
    return $this->belongsTo('App\m_supplier', 'po_supplier', 's_id');
  }

  public function getPOPayment()
  {
    return $this->hasMany('App\d_productionorderpayment', 'pop_productionorder', 'po_id');
  }

  public function getPODt()
  {
    return $this->hasMany('App\d_productionorderdt', 'pod_productionorder', 'po_id');
  }

}
