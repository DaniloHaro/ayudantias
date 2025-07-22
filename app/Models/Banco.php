<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'banco';
    public $timestamps = true;

    protected $fillable = ['banco', 'estado'];

    public function tiposCuenta()
    {
        return $this->hasMany(TipoCuenta::class, 'banco_id');
    }
}
