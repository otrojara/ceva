<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaMoDetalle extends Model
{
    use HasFactory;

    public $table = "proforma_mo_detalle";

    protected $fillable = [
        'rut',
        'nombre',
        'inicio_contrato',
        'periodo',
        'cod_bu',
        'empresa',
        'cargo',
        'tipo_turno',
        'total',
        'categoria',
        'observaciones',
        'comentarios',
        'costo_empresa_30',
        'costo_total',
        'provision_beneficios',
        'costo_total_productivo',
        'dias_administrativos',
        'dias_cumple',
        'total_dias_productivos',
        'dias_totales',
        'costo_dias_adm_cum',
        'costo_total_final'
    ];
}
