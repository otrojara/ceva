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
         $schedule->command('proceso:trabajadores')->dailyAt('13:55');
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
