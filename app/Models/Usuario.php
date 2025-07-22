<?php
namespace App\Models;
use App\Models\DatoBanco;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    //protected $fillable = ['rut', 'nombres', 'paterno', 'materno', 'email', 'sexo_registral', 'genero', 'cuenta_pasaporte', 'estado'];
    protected $fillable = [
        'rut',
        'nombres',
        'paterno',
        'materno',
        'nombre_social',
        'email', 
        'telefono',
        'sexo_registral',
        'genero',
        'cuenta_pasaporte',
        'estado',
    ];

    public function getAuthIdentifierName()
    {
        return 'rut';
    }

    public function getAuthPassword()
    {
        return 'password'; // solo si usas autenticación con password local
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'rut');
    }

    public function datosBancarios()
    {
        return $this->hasMany(DatoBanco::class, 'rut');
    }
    public function datosBanco()
    {
        return $this->hasMany(\App\Models\DatoBanco::class, 'rut', 'rut')->latest(); // Asegura que venga el más reciente primero
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'rut');
    }

    public function perfiles()
    {
        return $this->hasMany(Permiso::class, 'rut', 'rut');
    }
    
    public function permisoActivo()
    {
        return $this->hasOne(\App\Models\Permiso::class, 'rut', 'rut')->where('estado', 1);
    }

    public function getPerfilAttribute()
    {
        return $this->permisoActivo ? $this->permisoActivo->tipo : null;
    }


    //$usuario = Usuario::with('perfiles')->where('rut', $rut)->first();
}
