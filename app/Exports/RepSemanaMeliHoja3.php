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

class RepSemanaMeliHoja3 implements FromCollection,WithHeadings
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
            'PERMISO'


        ]; 
    }

    public function collection()
    {
        return  DB::table('rep_geoasistencia')
                ->select( DB::raw("CONCAT(substr(RUT,1,CHARACTER_LENGTH(RUT)-1),'-',substr(RUT,-1)) AS RUT"),'NOMBRE','CARGO', 'TIPO_TURNO','BU', 'FECHA','PERMISO')
                ->whereNotIn('PERMISO', ["ART 22"])
                ->whereNotNull('PERMISO')
                ->get();

           //     SELECT RUT,NOMBRE,FECHA,BU,CARGO,TIPO_TURNO,PERMISO FROM REP_GEOASISTENCIA WHERE permiso IS NOT NULL AND PERMISO NOT IN ('ART 22')

    }
}
