<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RRHHLibro extends Model
{
    use HasFactory;
    public $table = "rrhh_libro";

    protected $fillable = [
        'rut',
        'nombre',
        'mes_proceso',
        'fecha_ingreso',
        'fecha_retiro',
        'sexo',
        'fecha_nacimiento',
        'codigo_c_costo',
        'centro_costo',
        'tipos_contrato',
        'dias_trabajados',
        'dias_de_ausentismo',
        'total_haberes_afectos',
        'total_haberes_exentos',
        'total_descuentos_personales',
        'total_aportes_legales',
        'liquido_del_mes',
        'subase',
        'atraso',
        'dessal',
        'gratif',
        'bnoass',
        'hex060',
        'difsue',
        'bonoch',
        'hex075',
        'comfes',
        'bonree',
        'boninv',
        'boexac',
        'bonant',
        'btutor',
        'bonmaq',
        'boescl',
        'boesup',
        'bonimp',
        'bomatr',
        'boncom',
        'difmov',
        'movili',
        'colaci',
        'difcol',
        'colsid',
        'sbgiro',
        'adimov',
        'asacun',
        'asfama',
        'lsanna',
        'mutual',
        'segcee',
        'segcei',
        'sisafp',
        'total_haberes_imponibles',
        'total_haberes_no_imponibles',	
        'total_haberes_costo_libro',	
        'aportes_patronales',	
        'costo_libro',	
        'bono_gestion',	
        'total_finiquitos',	
        'total_vacaciones',
        'prov_aguinaldo',
        'prov_beneficios_eventos',
        'sistemas_cpeople',
        'prima_seguro_salud',
        'sistemas_cas',
        'alto_check',
        'global_insurance',
        'infocontrol',
        'sistemas_payroll_adp',
        'dias_administrativos',
        'dia_cumpleanos',
        'tarjeta_de_cafe',
        'sala_cuna',
        'total_beneficios',
        'sueldo_base',
        'total_haberes',
        'costo_libro_rem',
        'prov_y_benefios',
        'costo_empresa'
    ];

    
    



}
