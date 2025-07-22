<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table = 'proceso';
    public $timestamps = false;

    protected $fillable = ['nombre', 'estado'];

    public function etapas()
    {
        return $this->hasMany(EtapaProceso::class, 'proceso_id');
    }
}
