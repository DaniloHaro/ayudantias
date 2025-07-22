<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitud';
    protected $primaryKey = 'id_solicitud';
    public $timestamps = true;

    protected $fillable = [
        'id_carrera_estudiante',
        'id_asigantura',
        'datos_banco_id',
        'archivo_path',
        'n_matricula',
        'pers_dig',
        'fecha_dig',
        'estado',
    ];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'id_asigantura');
    }
    public function carreraEstudiante()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_estudiante');
    }
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_estudiante');
    }
    public function datosBanco()
    {
        return $this->belongsTo(DatoBanco::class, 'datos_banco_id');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'pers_dig', 'rut');
    }
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'pers_dig', 'rut');
    }
    public function historial()
    {
        return $this->hasMany(HistorialSolicitud::class, 'id_solicitud');
    }
    public function estudiante() {
        return $this->belongsTo(Usuario::class, 'pers_dig', 'rut');
    }
}
