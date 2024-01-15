<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepGeoErrores extends Model
{
    use HasFactory;

    public $table = "rep_geoerrores";


    protected $fillable = [
        'nombre',
        'rut',
        'bu',
        'fecha',
        'sin_Cargo',
        'sin_turno',
        'sin_inicio_contrato',
        'sin_fin_contrato',
        'sin_calida'
    ];
}
