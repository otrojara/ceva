<?php

namespace App\Console\Commands;

use App\Models\GeoAsistencia;
use App\Models\GeoTrabajadores;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\GeoAsistenciaJob;
use Illuminate\Support\Facades\DB;

class GeoAsistenciaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:xpdos';

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
        $fecha = Carbon::now()->subDays(7)->format('Y-m-d');
        GeoAsistencia::where('date', '>=', $fecha)->delete();

        $trabajadores = GeoTrabajadores::where('fecha', Carbon::parse(now())->toDateString())
         //   ->whereIN('rut', array('273731407','187804221','171286476','124273692','159810658','267834903','133527745','167868215','205801162','155069856','118595637','109026468','188507646','192855020','171286476','266328427','151887902','198876356','199117556','194798296','197676272','212308676','192340756','126396570'))
            ->get();

        foreach ($trabajadores as $trabajador) {
            GeoAsistenciaJob::dispatch($trabajador);
        }



    }
}
