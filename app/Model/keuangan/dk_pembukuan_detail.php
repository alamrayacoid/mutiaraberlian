<?php

namespace App\Model\keuangan;

use Illuminate\Database\Eloquent\Model;

class dk_pembukuan_detail extends Model
{
    protected $table = 'dk_pembukuan_detail';
    protected $primaryKey = ['pd_pembukuan', 'pd_nomor'];
    public $incrementing = false;
}
