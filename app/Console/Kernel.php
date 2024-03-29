<?php

namespace App\Console;

use App\Http\Controllers\Api\ApiGeoController;
use App\Jobs\ProcessTrabajadoresJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
         //$schedule->command('moneda:create')->everyMinute();
       // $schedule->command('queue:work')->everyMinute();
        $schedule->command('proceso:trabajadores')->dailyAt('21:37');
        $schedule->command('proceso:asistenciabn')->dailyAt('1:00');
        $schedule->command('proceso:repgeoasistencia')->dailyAt('1:10');
        $schedule->command('proceso:errores')->dailyAt('1:30');
        //$schedule->call([ApiGeoController::class, 'processTrabajadores'])->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
