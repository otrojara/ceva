<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RepSemanaMeliHoja2 implements FromCollection,WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'RUT',
            'NOMBRE',
            'CARGO',
            'TIPO_TURNO',
            'BU',
            'FECHA MARCA',
            'ESTADO',
            'HORAS EXTRAS (EN MINUTOS)'


        ];
    }

    public function collection()
    {
        return  DB::table('rep_geoasistencia')
        ->select( DB::raw("CONCAT(substr(RUT,1,CHARACTER_LENGTH(RUT)-1),'-',substr(RUT,-1)) AS RUT")
            ,'NOMBRE',
            'CARGO',
            'TIPO_TURNO',
            'BU',
            'FECHA',DB::raw("CASE
                WHEN PRESENTE = 1 AND LIBRE = 1 THEN 'Libre'
                WHEN PRESENTE = 0 AND LIBRE = 1 THEN 'Libre'
                WHEN PRESENTE = 1 AND LIBRE = 0 THEN 'Presente'
                WHEN PRESENTE = 0 AND LIBRE = 0 THEN 'Ausente'
                ELSE 'OTRO'
                END AS ESTADO"),'HORAS_EXTRAS')
                ->get();


    }
}
