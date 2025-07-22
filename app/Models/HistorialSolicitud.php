<?php
namespace App\Models;
use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\DatoBanco;

use Illuminate\Database\Eloquent\Model;

class HistorialSolicitud extends Model
{
    protected $table = 'historial_solicitud';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = ['id_solicitud', 'etapa', 'estado_solicitud', 'pers_dig', 'fecha_dig', 'estado'];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'id_solicitud');
    }
    
    public function asignatura()
    {
        return $this->hasMany(Asignatura::class, 'id_asigantura');
    }

    public function carrera()
    {
        return $this->hasMany(Carrera::class, 'id_carrera');
    }
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'pers_dig', 'rut');
    }
      public function datosBanco()
    {
        return $this->hasMany(DatoBanco::class, 'rut', 'rut')->latest(); // Asegura que venga el más reciente primero
    }


}
