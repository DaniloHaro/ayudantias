<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapaProceso extends Model
{
    protected $table = 'etapa_proceso';
    public $timestamps = false;

    protected $fillable = ['proceso_id', 'etapa_proceso', 'fecha_inicio', 'fecha_fin', 'descripcion', 'estado','tipo'];

    public function proceso()
    {
        return $this->belongsTo(Proceso::class, 'proceso_id');
    }
}
