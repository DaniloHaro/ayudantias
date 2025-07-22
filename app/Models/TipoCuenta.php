<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCuenta extends Model
{
    protected $table = 'tipo_cuenta';
    public $timestamps = true;

    protected $fillable = ['tipo_cuenta', 'banco_id', 'estado'];

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function datos()
    {
        return $this->hasMany(DatoBanco::class, 'tipo_cuenta_id');
    }
}
