<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\d_canvassing
 *
 * @property int $c_id
 * @property string|null $c_user ambil dari u_id username
 * @property string|null $c_date
 * @property string|null $c_name
 * @property string|null $c_tlp
 * @property string|null $c_email
 * @property string|null $c_address
 * @property string|null $c_note
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCTlp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\d_canvassing whereCUser($value)
 * @mixin \Eloquent
 */
class d_canvassing extends Model
{
    protected $table = 'd_canvassing';
    protected $primaryKey = 'c_id';
    public $timestamps = false;

}
