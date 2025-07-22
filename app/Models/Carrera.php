<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $primaryKey = 'id_carrera';
    public $timestamps = false;

    protected $fillable = ['id_ucampus', 'nombre', 'estado'];

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_carrera');
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'id_carrera');
    }
}
