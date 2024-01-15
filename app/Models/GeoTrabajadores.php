<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GeoTrabajadores extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'rut',
        'turno',
        'atraso',
        'permiso',
        'email',
        'fecha',
        'grupo',
        'bu',
        'cod_bu',
        'ART22',
        'cod_cargo',
        'cargo',
        'categoria',
        'ingreso',
        'salida',
        'inicio_contrato',
        'fin_contrato',
        'empresa',
        'enabled'
    ];


    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }
}
