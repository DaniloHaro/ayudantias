<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoBanco extends Model
{
    protected $table = 'datos_banco';
    public $timestamps = true;

    protected $fillable = ['tipo_cuenta_id', 'rut', 'num_cuenta', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rut');
    }

    public function tipoCuenta()
    {
        return $this->belongsTo(TipoCuenta::class);
    }
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

}
