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


class AsistenciaExportHoja1 implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
        'RUT',
        'NOMBRE',
        'CARGO',
        'CATEGORIA',
        'EMPRESA',
        'TIPO_TURNO',
        'BU',
        'CODIGO BU',
        'INICIO_CONTRATO',
        'TERMINO_CONTRATO',
        'DIAS_TRABAJADOS',
        'LIBRE',
        'LICENCIA',
        'VACACIONES',
        'NO_PRESENTE',
        'PERMISO_CON_GOCE',
        'TOTAL',
        'HORAS_EXTRAS (EN MINUTOS)',
        'HORAS_EXTRAS (EN HORAS)',
        'ATRASO (EN MINUTOS)'


        ];
    }

    public function collection()
    {

        return  DB::table('rep_geoasistencia as REP')
                ->select('REP.RUT','REP.NOMBRE','REP.CARGO','REP.CATEGORIA','REP.EMPRESA','REP.TIPO_TURNO','REP.COD_BU','REP.BU',
                    //'REP.INICIO_CONTRATO','REP.TERMINO_CONTRATO',
                DB::raw(" SUM(PRESENTE) AS PRESENTE "),
                DB::raw(" SUM(libre) AS libre "),
                DB::raw(" SUM(licencia) AS licencia "),
                DB::raw(" SUM(vacaciones) AS vacaciones "),
                DB::raw(" SUM(no_presente) AS no_presente "),
                DB::raw(" SUM(permiso_con_goce) AS permiso_con_goce "),
                DB::raw(" SUM(PRESENTE) + SUM(no_presente)  AS TOTAL"),
                DB::raw(" SUM(HORAS_EXTRAS) AS HE "),
                DB::raw(" SUM(HORAS_EXTRAS) / 60 "),
                DB::raw(" SUM(ATRASO) AS ATR "))
                ->whereBetween('REP.FECHA', ['2024-02-01', '2024-02-29'])
                ->groupBy('REP.RUT','REP.NOMBRE','REP.CARGO','REP.CATEGORIA','REP.EMPRESA','REP.TIPO_TURNO','REP.COD_BU','REP.BU'
                   // ,'REP.INICIO_CONTRATO','REP.TERMINO_CONTRATO'
                )
                ->orderByRaw('REP.RUT DESC')
                ->get();
    }
}
