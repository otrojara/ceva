<?php

namespace App\Exports\proforma;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProformaExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {

         $sheets = [
            'MO DETALLE' => new ProformaExportMODetalle(),
            'LIBRO' => new ProformaExportLibro(),
            'COSTOS EST' => new ProformaExportCostosEst()
        ];

        return $sheets;
    }
}



