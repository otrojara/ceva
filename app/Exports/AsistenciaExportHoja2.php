<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Models\RepGeoAsistencia;


class AsistenciaExportHoja2 implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
        'FECHA',
        'DIA',
        'RUT',
        'NOMBRE',
        'ESTADO',
        'GRUPO',
        'BU',
        'COD_CARGO',
        'CARGO',
        'CATEGORIA',
        'TURNO',
        'TIPO_TURNO',
        'ART22',
        'INICIO_CONTRATO',
        'TERMINO_CONTRATO',
        'TYPE',
        'AUSENTE',
        'FERIADO',
        'TRABAJADO',
        'INGRESO',
        'INGRESO_ORIGEN',
        'INGRESO_GRUPO',
        'SALIDA',
        'SALIDA_ORIGEN',
        'SALIDA_GRUPO',
        'ATRASO',
        'HORAS_EXTRAS',
        'PERMISO',
        'TO_STAR',
        'TO_END',
        'PRESENTE'
        ];
    }

    public function collection()
    {
       // return RepGeoAsistencia::all();

        return RepGeoAsistencia::select('FECHA',
        'DIA',
        'RUT',
        'NOMBRE',
        'ESTADO',
        'GRUPO',
        'BU',
        'COD_CARGO',
        'CARGO',
        'CATEGORIA',
        'TURNO',
        'TIPO_TURNO',
        'ART22',
        //'INICIO_CONTRATO',
        //'TERMINO_CONTRATO',
        'TYPE',
        'AUSENTE',
        'FERIADO',
        'TRABAJADO',
        'INGRESO',
        'INGRESO_ORIGEN',
        'INGRESO_GRUPO',
        'SALIDA',
        'SALIDA_ORIGEN',
        'SALIDA_GRUPO',
        'ATRASO',
        'HORAS_EXTRAS',
        'PERMISO',
        'TO_STAR',
        'TO_END',
        'PRESENTE'
        )
            ->whereBetween('FECHA', ['2024-02-01', '2023-02-29'])
        ->get();
    }
}
