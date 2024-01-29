<?php

namespace App\Console\Commands;

use App\Models\GeoAsistencia;
use App\Models\GeoTrabajadores;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\GeoAsistenciaJob;

class GeoAsistenciaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:asistencia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserta asistencia de trabajadores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fecha = '2024-01-29';
        GeoAsistencia::where('date', '>=', '2024-01-29')->delete();

        $FECHAINI = '2024-01-29';
        $FECHAINI = str_replace("-", "", $FECHAINI);
        $FECHAINI = $FECHAINI . '000000';

        $trabajadores = GeoTrabajadores::where('fecha', Carbon::parse(now())->toDateString())->get();

        foreach ($trabajadores as $trabajador) {
            GeoAsistenciaJob::dispatch($trabajador);
        }

//        $fecha = '2024-01-29';
//        GeoAsistencia::where('date','>=', '2024-01-29')->delete();
//
//        $FECHAINI = '2024-01-29';
//        $FECHAINI = str_replace("-","",$FECHAINI);
//        $FECHAINI = $FECHAINI . '000000';
//
//        $FECHAMAN = Carbon::now();
//        $FECHAMAN = $FECHAMAN->toDateString();
//        $FECHAMAN = str_replace("-","",$FECHAMAN);
//        $FECHAMAN = $FECHAMAN . '000000';
//
//        $trabajadores = GeoTrabajadores::select('rut', 'fecha')
//            ->where('fecha', Carbon::parse(Carbon::now())->format('Y-m-d'))
//            ->get();
//
//        $chunkedTrabajadores = array_chunk($trabajadores->toArray(), 10); // Dividir en bloques de 100 trabajadores
//
//        foreach ($chunkedTrabajadores as $chunk) {
//            foreach ($chunk as $trabajador) {
//
//
//                //dd($trabajador);
//
//                GeoAsistenciaJob::dispatch($trabajador);
//
//
//
//            }
//        }
//
//        $this->info('Proceso de asistencia ejecutado exitosamente.');
    }
}
