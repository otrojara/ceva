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


        $cargos = GeoTrabajadores::select('rut','nombres','apellidos','bu')
            ->where('cod_cargo', NULL)->WHERE('enabled',1)->where('fecha',$fecha)->orderBy('bu', 'DESC')->get();
        $cargoscount = count($cargos);

        foreach ($cargos as $c) {
            RepGeoErrores::where('rut',$c->rut)
                ->where('fecha',$fecha)
                ->update(['sin_cargo' => 1]);
        }

        $turnos = GeoTrabajadores::select('rut','nombres','apellidos','bu')
            ->where('turno', NULL)->WHERE('enabled',1)->where('fecha',$fecha)->where('art22','!=','SI')->orderBy('bu', 'DESC')->get();
        $turnoscount = count($turnos);

        foreach ($turnos as $t) {
            RepGeoErrores::where('rut',$t->rut)
                ->where('fecha',$fecha)
                ->update(['sin_turno' => 1]);
        }

        $icontrato = GeoTrabajadores::select('rut','nombres','apellidos','bu')
            ->where('inicio_contrato', '--')->WHERE('enabled',1)->where('fecha',$fecha)->orderBy('bu', 'DESC')->get();
        $icontratocount = count($icontrato) ;

        foreach ($icontrato as $ic) {
            RepGeoErrores::where('rut',$ic->rut)
                ->where('fecha',$fecha)
                ->update(['sin_inicio_contrato' => 1]);
        }

        $fcontrato = GeoTrabajadores::select('rut','nombres','apellidos','bu')
            ->where('fin_contrato', '--')->WHERE('enabled',0)->where('fecha',$fecha)->where('inicio_contrato','>=','2023-12-01')->orderBy('bu', 'DESC')->get();
        $fcontratocount = count($fcontrato);


        foreach ($fcontrato as $fc) {
            RepGeoErrores::where('rut',$fc->rut)
                ->where('fecha',$fecha)
                ->update(['sin_fin_contrato' => 1]);
        }



        $s = GeoAsistencia::select('rut')
            ->whereNotNull('entrada_fecha')
            ->whereNull('salida_fecha')
            ->where('geo_asistencia.date',Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d'))
            ->get();

        $salida = GeoTrabajadores::select('rut','nombres','apellidos','bu')
            ->whereIN('rut', $s)->where('fecha',Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d'))->orderBy('bu', 'DESC')->get();
        $salidacount = count($salida);


        foreach ($salida as $s) {
            RepGeoErrores::where('rut',$s->rut)
                ->where('fecha',$fecha)
                ->update(['sin_salida' => 1]);
        }

        RepGeoErrores::where('fecha','=', $fecha)
            ->where('sin_cargo', NULL)
            ->where('sin_turno',NULL)
            ->where('sin_inicio_contrato',NULL)
            ->where('sin_fin_contrato',NULL)
            ->where('sin_salida',NULL)
            ->delete();

    }
}
