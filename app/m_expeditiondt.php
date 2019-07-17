<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\m_expeditiondt
 *
 * @property int $ed_expedition
 * @property int $ed_detailid
 * @property string|null $ed_product
 * @property string $ed_isactive
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt whereEdDetailid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt whereEdExpedition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt whereEdIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_expeditiondt whereEdProduct($value)
 * @mixin \Eloquent
 */
class m_expeditiondt extends Model
{
    // use third-party library to create relationship multi-column
    use \Awobaz\Compoships\Compoships;
    
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
