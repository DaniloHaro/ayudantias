<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;

    protected $fillable = ['accion', 'pers_dig', 'fecha', 'estado'];
}
