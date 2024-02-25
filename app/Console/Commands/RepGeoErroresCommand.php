<?php

namespace App\Console\Commands;

use App\Jobs\RepGeoAsistenciaJob;
use App\Jobs\RepGeoErroresJob;
use Illuminate\Console\Command;

class RepGeoErroresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:bpcuatro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea reporte de errores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RepGeoErroresJob::dispatch();
        $this->info('Proceso de reporte de errores ejecutado exitosamente.');
    }
}
