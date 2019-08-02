<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\m_employee
 *
 * @property string $e_id EMP00000001
 * @property string $e_company id cabang
 * @property int|null $e_department departemen (divisi; ex: produksi)
 * @property int|null $e_position id position (posisi sebagai apa; ex:staf)
 * @property string $e_nik id pegawai; format bisa tanya di client
 * @property string $e_nip no identitas
 * @property string $e_name
 * @property string|null $e_workingdays
 * @property string $e_address
 * @property string $e_birth
 * @property string $e_gender
 * @property string $e_education pendidikan terakhir
 * @property string $e_email
 * @property string $e_telp
 * @property string $e_religion
 * @property string $e_maritalstatus status pernikahan
 * @property string|null $e_matename nama pasangan
 * @property int|null $e_child
 * @property string|null $e_workingyear tanggal masuk bekerja
 * @property string $e_bank
 * @property string $e_rekening
 * @property string $e_an
 * @property string $e_isactive
 * @property string|null $e_foto
 * @property string $e_created_at
 * @property string $e_updated_at
 * @property-read \App\d_username $username
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEAn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereECompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereECreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEIsactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEMaritalstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEMatename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereENik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereENip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereERekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereETelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEWorkingdays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\m_employee whereEWorkingyear($value)
 * @mixin \Eloquent
 */
class m_employee extends Model
{
    protected $table       = 'm_employee';
    protected $primaryKey  = 'e_id';
    public $incrementing   = false;
    const CREATED_AT = 'e_created_at';
    const UPDATED_AT = 'e_updated_at';

    public function username()
    {
        return $this->belongsTo(d_username::class, 'e_id', 'u_code');
    }
    // get division
    public function getDivision()
    {
        return $this->belongsTo('App\m_divisi', 'e_department', 'm_id');
    }
    // get company
    public function getCompany()
    {
        return $this->belongsTo('App\m_company', 'e_company', 'c_id');
    }
    // get presence
    public function getPresence()
    {
        return $this->hasMany('App\d_presence', 'p_employee', 'e_id');
    }
}
