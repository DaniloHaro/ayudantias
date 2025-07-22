<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    protected $table = 'asignatura';
    protected $primaryKey = 'id_asigantura';
    public $timestamps = false;

    protected $fillable = ['nombre', 'id_carrera', 'rut', 'seccion', 'bloque_1', 'bloque_2', 'bloque_3', 'cupos', 'estado'];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rut');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'id_asigantura');
    }
}
