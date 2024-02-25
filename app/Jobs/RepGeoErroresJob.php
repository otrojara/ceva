<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\RepGeoErrores;
use App\Models\GeoTrabajadores;
use App\Models\GeoAsistencia;

class RepGeoErroresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');

        RepGeoErrores::where('fecha','=', $fecha)->delete();

        $resultado = GeoTrabajadores::select('rut','nombres','apellidos','bu')->where('fecha',$fecha)->get();


        foreach ($resultado as $r) {
            RepGeoErrores::create([
                'nombre' => $r->nombres.' '.$r->apellidos,
                'rut' => $r->rut,
                'bu' => $r->bu,
                'fecha' => $fecha
            ]);
        }


        RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
            $query->select('rut')
                ->from('geo_trabajadores')
                ->whereNull('cod_cargo')
                ->where('enabled', 1)
                ->where('fecha', $fecha);
        })
            ->where('fecha', $fecha)
            ->update(['sin_cargo' => 1]);


        RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
            $query->select('rut')
                ->from('geo_trabajadores')
                ->whereNull('turno')
                ->where('enabled', 1)
                ->where('fecha', $fecha)
                ->whereNull('art22');
        })
            ->where('fecha', $fecha)
            ->update(['sin_turno' => 1]);



        RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
            $query->select('rut')
                ->from('geo_trabajadores')
                ->where('inicio_contrato', '--')
                ->where('enabled', 1)
                ->where('fecha', $fecha);
        })
            ->where('fecha', $fecha)
            ->update(['sin_inicio_contrato' => 1]);

        RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
            $query->select('rut')
                ->from('geo_trabajadores')
                ->where('fin_contrato', '--')
                ->where('enabled', 0)
                ->where('fecha', $fecha)
                ->where('inicio_contrato', '>=', '2023-12-01');
        })
            ->where('fecha', $fecha)
            ->update(['sin_fin_contrato' => 1]);

        $s = GeoAsistencia::select('rut')
            ->whereNotNull('entrada_fecha')
            ->whereDate('geo_asistencia.date', Carbon::now()->subDays(1)->format('Y-m-d'))
            ->get();

        RepGeoErrores::whereIn('rut', $s->pluck('rut'))
            ->where('fecha', Carbon::now()->subDays(1)->format('Y-m-d'))
            ->update(['sin_salida' => 1]);



        RepGeoErrores::where('fecha','=', $fecha)
            ->where('sin_cargo', NULL)
            ->where('sin_turno',NULL)
            ->where('sin_inicio_contrato',NULL)
            ->where('sin_fin_contrato',NULL)
            ->where('sin_salida',NULL)
            ->delete();


    }
}
