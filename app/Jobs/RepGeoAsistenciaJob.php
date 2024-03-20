<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\RepGeoAsistencia;
use App\Models\GeoAsistencia;
use App\Models\GeoTrabajadores;

class RepGeoAsistenciaJob implements ShouldQueue
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

        // RepGeoAsistencia::truncate();

        $fechaAnt = Carbon::now()->subDays(14)->format('Y-m-d');
       // $fechaAnt = '2024-02-01';

        RepGeoAsistencia::where('fecha', '>=', $fechaAnt)->delete();
        //RepGeoAsistencia::truncate();

        ini_set('memory_limit', '512M');
//        $results = DB::select("select cal.fecha as calfecha,cal.dia,tr.*
//        FROM sis_calendario as cal ,geo_trabajadores as tr
//         WHERE tr.fecha='2024-02-12' and cal.fecha BETWEEN '2024-01-01' and '2024-01-31' ");


        $results = DB::select("SELECT cal.fecha as calfecha, cal.dia, tr.*, asi.*
            FROM (
                SELECT DISTINCT fecha,dia
                FROM sis_calendario
                WHERE fecha BETWEEN ? AND ?
            ) AS cal
            CROSS JOIN geo_trabajadores AS tr
            LEFT JOIN geo_asistencia AS asi ON asi.rut = tr.rut AND asi.date = cal.fecha
            WHERE tr.fecha = ? ",[$fechaAnt,$fecha,$fecha]);




    //    dd($results);
//
//        foreach ($results as $r) {
//            RepGeoAsistencia::create([
//                'fecha' => $r->calfecha,
//                'dia' => $r->dia,
//                'rut' => $r->rut,
//                'empresa' => $r->empresa,
//                'nombre' => $r->nombres.' '.$r->apellidos,
//                'art22' => $r->art22,
//                'cod_cargo' => 'falta',
//                'cargo' => $r->cargo,
//                'categoria' => $r->categoria,
//                'estado' => $r->enabled,
//                'grupo' => $r->grupo,
//                'bu' => $r->bu,
//                'cod_bu' => $r->cod_bu,
//                'inicio_contrato' => $r->inicio_contrato,
//                'termino_contrato' => $r->fin_contrato
//            ]);
//        }




//        $info = RepGeoasistencia::select('rep_geoasistencia.rut AS rutra', 'rep_geoasistencia.*', 'geo_asistencia.*')
//            ->leftJoin('geo_asistencia', function ($join) {
//                $join->on('geo_asistencia.rut', '=', 'rep_geoasistencia.rut')
//                    ->on('geo_asistencia.date', '=', 'rep_geoasistencia.fecha');
//            })
//            ->get();

//        $info = RepGeoasistencia::select('rep_geoasistencia.rut AS rutra', 'rep_geoasistencia.*', 'geo_asistencia.*')
//            ->leftJoin('geo_asistencia', function ($join) {
//                $join->on('geo_asistencia.rut', '=', 'rep_geoasistencia.rut')
//                    ->on('geo_asistencia.date', '=', DB::raw('(SELECT MAX(date) FROM geo_asistencia WHERE rut = rep_geoasistencia.rut)'));
//            })
//            ->get();


//        $info = DB::select("select tra.rut AS rutra,tra.*,asi.*
//    FROM rep_geoasistencia as tra
//    LEFT JOIN geo_asistencia as asi ON asi.rut=tra.rut AND asi.date=tra.fecha
//    ");

       // RepGeoAsistencia::truncate();

        foreach ($results as $rep) {

            RepGeoAsistencia::create([



                'fecha' => $rep->calfecha,
                'dia' => $rep->dia,
                'rut' => $rep->rut,
                'nombre' => $rep->nombres.' '.$rep->apellidos,
                'estado' => $rep->enabled,
                'empresa' => $rep->empresa,
                'bu' => $rep->bu,
                'cod_bu' => $rep->cod_bu,
                'grupo' => $rep->grupo,
                'cod_cargo'  => $rep->cod_cargo,
                'cargo' => $rep->cargo,
                'categoria' => $rep->categoria,
                'turno' => $rep->shiftdisplay,
                'art22' => $rep->art22,
                'inicio_contrato' => $rep->inicio_contrato,
                'termino_contrato' => $rep->fin_contrato,
                'ingreso' => $rep->entrada_fecha,
                'ingreso_origen' => $rep->entrada_origin,
                'ingreso_grupo' => $rep->entrada_groupdescription,
                'salida' => $rep->salida_fecha,
                'salida_origen' => $rep->salida_origin,
                'salida_grupo' => $rep->salida_groupdescription,
                'atraso' => $rep->delay !== null ? $rep->delay : '00:00',
                'horas_extras' => $rep->assignedextratime,
                'permiso' => $rep->to_timeofftypedescription,
                'type' => $rep->type,
                'ausente' => $rep->absent,
                'feriado' => $rep->holiday,
                'trabajado' => $rep->worked,
                'to_star' => $rep->to_star,
                'to_end'  => $rep->to_ends
            ]);
        }


        $licencias = GeoAsistencia::select('rut','to_ends','to_star')
            ->where('date', $fecha)
            ->where('to_timeofftypedescription', 'Licencia Médica Estándar')
            ->where('to_ends','>=',$fecha)
            ->get();



        foreach ($licencias as $l) {



            RepGeoAsistencia::where('rut',$l->rut)
                ->where('fecha','>=',Carbon::parse($l->to_star)->format('Y-m-d'))
                ->where('fecha','<=',Carbon::parse($l->to_ends)->format('Y-m-d'))
                ->update(['presente' => 0,
                    'permiso' => 'Licencia Médica Estándar',
                    'to_star' => $l->to_star,
                    'to_end' => $l->to_ends
                ]);

        }

        //DB::statement("UPDATE rep_geoasistencia SET tipo_turno = (SELECT geo_turnos.turno FROM geo_turnos WHERE geo_turnos.horario = rep_geoasistencia.turno)");
        DB::table('rep_geoasistencia')
            ->update([
                'tipo_turno' => DB::raw('(SELECT geo_turnos.turno FROM geo_turnos WHERE geo_turnos.horario = rep_geoasistencia.turno LIMIT 1)')
            ]);

        DB::statement("update rep_geoasistencia SET tipo_turno = 'PART TIME' WHERE GRUPO LIKE '%PART TIME%'");
        DB::statement("update rep_geoasistencia SET tipo_turno = 'ADM' WHERE art22 = 'SI'");



//
//        DB::statement("CALL RepGeoAsistencia();");
//
//
//
//
        DB::statement("update rep_geoasistencia SET tipo_turno = 'PART TIME' WHERE GRUPO LIKE '%PART TIME%'");
        DB::statement("update rep_geoasistencia SET tipo_turno = 'ADM' WHERE ART22 = 'SI'");
//
//
        DB::statement("update rep_geoasistencia SET presente = 1");

        RepGeoAsistencia::where('ausente','True')->update(['presente' => 0]);
        RepGeoAsistencia::where('ausente',null)->where('fecha','<=',$fecha)->update(['presente' => 0]);
        RepGeoAsistencia::where('art22','SI')->update(['presente' => 1]);

        RepGeoAsistencia::whereIN('permiso',array('Licencia Médica Estándar','Vacaciones'))->update(['presente' => 0]);
//
//
//
//

        //este tambine tiene error
        // DB::statement("update rep_geoasistencia SET ATRASO = (CAST(SUBSTRING(ATRASO, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(ATRASO, 4, 5) AS UNSIGNED)");

        DB::statement("
    UPDATE rep_geoasistencia
    SET ATRASO = (CAST(SUBSTRING(ATRASO, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(ATRASO, 4, 5) AS UNSIGNED)
    WHERE fecha >= ?
    ", [$fechaAnt]);



        DB::statement("update rep_geoasistencia SET horas_extras = (CAST(SUBSTRING(horas_extras, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(horas_extras, 4, 5) AS UNSIGNED) WHERE fecha >= ?", [$fechaAnt]);
        DB::statement("update rep_geoasistencia SET ATRASO = 0 where  atraso <= 15 ");
//
//
//
        DB::statement("update rep_geoasistencia SET licencia = 1 WHERE permiso = 'Licencia Médica Estándar'");
        DB::statement("update rep_geoasistencia SET vacaciones = 1 WHERE permiso = 'Vacaciones'");

        DB::statement("update rep_geoasistencia SET cumpleanio=1 WHERE permiso = 'CUMPLEAÑOS'");
        DB::statement("update rep_geoasistencia SET administrativo=1 WHERE permiso = 'P. Administrativo'");

        DB::statement("update rep_geoasistencia SET libre = 1 WHERE ausente = 'False' and trabajado = 'False' ");
        //DB::statement("update rep_geoasistencia SET presente = 0 WHERE libre = 1 ");
        DB::statement("update rep_geoasistencia SET permiso_con_goce = 1 WHERE permiso IN (SELECT nombre FROM geo_permisos WHERE goce_sueldo = 'Si') ");

        //Este manda error
         DB::statement("delete FROM  rep_geoasistencia WHERE termino_contrato < fecha AND ingreso IS NULL AND salida IS NULL AND termino_contrato NOT IN ('--')");

        DB::statement("delete from rep_geoasistencia WHERE inicio_contrato > fecha AND ingreso IS NULL AND salida IS NULL");

        RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('presente',0)->delete();
        RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('trabajado','False')->delete();
        RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('fecha','>',$fecha)->delete();

        RepGeoAsistencia::where('presente',0)->update(['no_presente' => 1]);

        //        RepGeoAsistencia::where('presente',null)->update(['presente' => 0]);
        RepGeoAsistencia::where('no_presente',null)->update(['no_presente' => 0]);
        RepGeoAsistencia::where('libre',null)->update(['libre' => 0]);
//        RepGeoAsistencia::where('licencia',null)->update(['licencia' => 0]);
//        RepGeoAsistencia::where('vacaciones',null)->update(['vacaciones' => 0]);
//        RepGeoAsistencia::where('cumpleanio',null)->update(['cumpleanio' => 0]);
//        RepGeoAsistencia::where('administrativo',null)->update(['administrativo' => 0]);
//        RepGeoAsistencia::where('permiso_con_goce',null)->update(['permiso_con_goce' => 0]);

//
//        DB::statement("CALL proforma();");

    }
}
