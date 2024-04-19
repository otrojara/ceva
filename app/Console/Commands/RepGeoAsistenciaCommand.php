<?php

namespace App\Console\Commands;


use App\Jobs\GeoAsistenciaJob;
use App\Models\GeoTrabajadores;
use App\Models\RepGeoAsistencia;
use Illuminate\Console\Command;
use App\Jobs\RepGeoAsistenciaJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\GeoAsistencia;

class RepGeoAsistenciaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:reuno';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera reporte de asistecia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');
        // Obtener todos los ruts para la fecha dada
        $turno = GeoTrabajadores::where('fecha', $fecha)->pluck('rut');

        // Obtener los resultados en una sola consulta
        $results = DB::table('geo_asistencia as aa')
            ->select('aa.rut', DB::raw('MAX(aa.date) as max_date'))
            ->whereNotNull('aa.ShiftDisplay')
            ->whereNotIn('aa.ShiftDisplay', ['BREAK', 'Sin Turno'])
            ->whereIn('aa.rut', $turno)
            ->groupBy('aa.rut')
            ->get();

        // Actualizar los registros en GeoTrabajadores
        foreach ($results as $result) {
            $rut = $result->rut;
            $maxDate = $result->max_date;

            $shiftdisplay = DB::table('geo_asistencia')
                ->where('rut', $rut)
                ->where('date', $maxDate)
                ->value('shiftdisplay');

            if (!empty($shiftdisplay)) {
                GeoTrabajadores::where('rut', $rut)
                    ->where('fecha', $fecha)
                    ->update(['turno' => $shiftdisplay]);
            }
        }

        RepGeoAsistenciaJob::dispatch();
        $this->info('Proceso de reporte de asistencia ejecutado exitosamente.');
    }
}
