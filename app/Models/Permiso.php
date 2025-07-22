<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = ['id_carrera', 'rut', 'tipo', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rut');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }
}
