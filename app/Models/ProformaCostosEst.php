<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaCostosEst extends Model
{
    use HasFactory;

    public $table = "proforma_costos_est";

    protected $fillable = [
        'clave',
        'bu',
        'region',
        'site',
        'dotacion',
        'cargo',
        'turno',
        'tipo_turno',
        'costo_total',
        'fee',
        'neto',
        'gasto_fijo',
        'gasto_variable',
        'total'
    ];
}
