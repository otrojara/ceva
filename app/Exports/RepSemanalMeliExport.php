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

class RepSemanalMeliExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {

         $sheets = [
            new RepSemanaMeliHoja1(),
            new RepSemanaMeliHoja2(),
            new RepSemanaMeliHoja3()
        ];

        return $sheets;
    }
}



