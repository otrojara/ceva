<?php

namespace App\Imports;

use App\Models\RRHHLibro;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CargaLibroImport implements ToModel,WithMultipleSheets,WithHeadingRow
{
    public function sheets(): array
    {
        return [
            'LIBRO' => $this,
        ];
    }

    public function model(array $row)
    {
        return new RRHHLibro([
            'rut' => $row['rut'],
            'nombre' => $row['nombre'], 
            'mes_proceso' => $row['mes_de_proceso'],
            'fecha_ingreso' => $row['fecha_de_ingreso'],
            'fecha_retiro' => $row['fecha_de_retiro'],
            'sexo' => $row['sexo'],
            'codigo_c_costo' => $row['codigo_c_costo'],
            'centro_costo' => $row['centro_costo'],
            'tipos_contrato' => $row['tipos_contrato'],
            'dias_trabajados' => $row['dias_trabajados'],
            'dias_de_ausentismo' => $row['dias_de_ausentismo'],
            'total_haberes_afectos' => $row['total_haberes_afectos'],
            'total_haberes_exentos' => $row['total_haberes_exentos'],
            'total_descuentos_personales' => $row['total_descuentos_personales'],
            'total_aportes_legales' => $row['total_aportes_legales'],
            'liquido_del_mes' => $row['liquido_del_mes'],
            'subase' => $row['subase'],
            'atraso' => $row['atraso'],
            'dessal' => $row['dessal'],
            'gratif' => $row['gratif'],
            'bnoass' => $row['bnoass'],
            'hex060' => $row['hex060'],
            'difsue' => $row['difsue'],
            'bonoch' => $row['bonoch'],
            'hex075' => $row['hex075'],
            'comfes' => $row['comfes'],
            'bonree' => $row['bonree'],
            'boninv' => $row['boninv'],
            'boexac' => $row['boexac'],
            'bonant' => $row['bonant'],
            'btutor' => $row['btutor'],
            'bonmaq' => $row['bonmaq'],
            'boescl' => $row['boescl'],
            'boesup' => $row['boesup'],
            'bonimp' => $row['bonimp'],
            'bomatr' => $row['bomatr'],
            'boncom' => $row['boncom'],
            'difmov' => $row['difmov'],
            'movili' => $row['movili'],
            'colaci' => $row['colaci'],
            'difcol' => $row['difcol'],
            'colsid' => $row['colsid'],
            'sbgiro' => $row['sbgiro'],
            'adimov' => $row['adimov'],
            'asacun' => $row['asacun'],
            'asfama' => $row['asfama'],
            'lsanna' => $row['lsanna'],
            'mutual' => $row['mutual'],
            'segcee' => $row['segcee'],
            'segcei' => $row['segcei'],
            'sisafp' => $row['sisafp'],
            'total_haberes_imponibles' => $row['total_haberes_imponibles'],
            'total_haberes_no_imponibles' => $row['total_haberes_no_imponibles'],	
            'total_haberes_costo_libro' => $row['total_haberes_costo_libro'],	
            'aportes_patronales' => $row['aportes_patronales'],	
            'costo_libro' => $row['costo_libro'],	
            'bono_gestion' => $row['bono_gestion'],	
            'total_finiquitos' => $row['total_finiquitos'],	
            'total_vacaciones' => $row['total_vacaciones'],
            'prov_aguinaldo' => $row['prov_aguinaldo'],
            'prov_beneficios_eventos' => $row['prov_beneficios_eventos'],
            'sistemas_cpeople' => $row['sistemas_cpeople'],
            'prima_seguro_salud' => $row['prima_seguro_salud'],
            'sistemas_cas' => $row['sistemas_cas'],
            'alto_check' => $row['alto_check'],
            'global_insurance' => $row['global_insurance'],
            'infocontrol' => $row['infocontrol'],
            'sistemas_payroll_adp' => $row['sistemas_payroll_adp'],
            'dias_administrativos' => $row['dias_administrativos'],
            'dia_cumpleanos' => $row['dia_cumpleanos'],
            'tarjeta_de_cafe' => $row['tarjeta_de_cafe'],
            'sala_cuna' => $row['sala_cuna'],
            'total_beneficios' => $row['total_beneficios'],
            'sueldo_base' => $row['sueldo_base'],
            'total_haberes' => $row['total_haberes'],
            'costo_libro_rem' => $row['costo_libro_rem'],
            'prov_y_benefios' => $row['prov_y_benefios'],
            'costo_empresa' => $row['costo_empresa']
        ]);
    }

}

