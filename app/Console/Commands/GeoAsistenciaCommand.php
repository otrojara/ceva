<?php

namespace App\Console\Commands;

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
        GeoAsistenciaJob::dispatch();
        $this->info('Proceso de asistencia ejecutado exitosamente.');
    }
}
