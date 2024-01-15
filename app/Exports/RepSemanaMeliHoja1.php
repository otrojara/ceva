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

class RepSemanaMeliHoja1 implements FromCollection,WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'FECHA',
            'PRESENTE',
            'LIBRE',
            'LICENCIA',
            'CUMPLEANIO',
            'ADMINISTRATIVO',
            'VACACIONES',
            'PERMISO_CON_GOCE',
            'NO_PRESENTE'


        ]; 
    }

    public function collection()
    {
        return  DB::table('rep_geoasistencia')
        ->select( 'FECHA',
        DB::raw("SUM(PRESENTE) AS PRESENTE"),
        DB::raw("SUM(LIBRE) AS LIBRE"),
        DB::raw("SUM(LICENCIA) AS LICENCIA"),
        DB::raw("SUM(CUMPLEANIO) AS CUMPLEANIO"),
        DB::raw("SUM(ADMINISTRATIVO) AS ADMINISTRATIVO"),
        DB::raw("SUM(VACACIONES) AS VACACIONES"),
        DB::raw("SUM(PERMISO_CON_GOCE) AS PERMISO_CON_GOCE"),
        DB::raw("SUM(NO_PRESENTE) AS NO_PRESENTE")
        )
        ->groupBy('FECHA')
        ->orderBy('FECHA', 'asc')
        ->get();

    }
}
