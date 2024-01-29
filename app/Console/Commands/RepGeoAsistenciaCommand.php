<?php

namespace App\Console\Commands;


use App\Jobs\GeoAsistenciaJob;
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
    protected $signature = 'proceso:repgeoasistencia';

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

        RepGeoAsistenciaJob::dispatch();
        $this->info('Proceso de reporte de asistencia ejecutado exitosamente.');
    }
}
