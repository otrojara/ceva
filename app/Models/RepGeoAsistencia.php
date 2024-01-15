<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepGeoAsistencia extends Model
{
    use HasFactory;

    public $table = "rep_geoasistencia";

    protected $fillable = [
        'fecha',
        'dia',
        'rut',
        'nombre',
        'estado',
        'cod_cargo',
        'grupo',
        'bu',
        'cod_bu',
        'cargo',
        'categoria',
        'empresa',
        'turno',
        'art22',
        'tipo_turno',
        'inicio_contrato',
        'termino_contrato',
        'ingreso',
        'ingreso_origen',
        'ingreso_grupo',
        'salida',
        'salida_origen',
        'salida_grupo',
        'atraso',
        'horas_extras',
        'permiso',
        'type',
        'ausente',
        'feriado',
        'trabajado',
        'to_star',
        'to_end'


    ];


}
