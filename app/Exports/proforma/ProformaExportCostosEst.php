<?php

namespace App\Exports\proforma;

use App\Models\ProformaCostosEst;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProformaExportCostosEst implements FromCollection,WithHeadings
{
    public function headings(): array
    {
        return [
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

    public function collection() 
    {

       return  ProformaCostosEst::select('clave',
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
       'total')->get();

    }
}
