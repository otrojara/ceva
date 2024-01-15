<?php

namespace App\Exports\proforma;

use App\Models\ProformaMoDetalle;
use App\Models\RRHHLibro;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProformaExportMODetalle implements FromCollection,WithHeadings
{
    public function headings(): array
    {
        return [
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

    public function collection() 
    {

       return  ProformaMoDetalle::select('rut',
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
       'costo_total_final')->get();

    }
}
