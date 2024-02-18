<?php

use App\Exports\proforma\ProformaExport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BuController;
use App\Http\Controllers\Areas\RecursosHumanos\AsistenciaController;
use App\Http\Controllers\Api\MonedaController;
use App\Http\Controllers\Api\ApiGeoController;
use App\Models\GeoTrabajadores;
use App\Models\GeoAsistencia;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\FlujoAprobacionController;
use App\Http\Controllers\Admin\FlujoAprobacionDetController;
use App\Http\Controllers\Areas\RecursosHumanos\CargaLibroController;
use App\Http\Controllers\Areas\RecursosHumanos\ProformaController;
use App\Mail\AlertaGeoVictoria;
use App\Models\BusinessUnit;
use App\Models\BusinessUnitUser;
use App\Models\GeoAsistenciaAtrasos;
use App\Models\GeoCargo;
use App\Models\GeoGrupo;
use App\Models\GeoTurno;
use App\Models\ProformaMoDetalle;
use App\Models\SisCalendario;
use App\Models\RepGeoAsistencia;
use App\Models\RepGeoErrores;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Jobs\ConsumeApiGeoVictoria;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [HomeController::class ,'index'])->name('home');

Route::get('/procesar', [ApiGeoController::class, 'processTrabajadores']);




Route::get('/repgeoasistencia', function () {
    $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');

    RepGeoAsistencia::truncate();


    $results = DB::select(
        "SELECT cal.fecha AS calfecha, cal.dia, tr.*
    FROM sis_calendario AS cal, geo_trabajadores AS tr
    WHERE tr.fecha = ? AND cal.fecha BETWEEN '2024-01-01' AND '2024-01-07'",
        [$fecha]
    );

dd($results);

    foreach ($results as $r) {
        RepGeoAsistencia::create([
            'fecha' => $r->calfecha,
            'dia' => $r->dia,
            'rut' => $r->rut,
            'empresa' => $r->empresa,
            'nombre' => $r->nombres.' '.$r->apellidos,
            'art22' => $r->ART22,
            'cod_cargo' => 'falta ',
            'cargo' => $r->cargo,
            'categoria' => $r->categoria,
            'estado' => $r->enabled,
            'grupo' => $r->grupo,
            'bu' => $r->bu,
            'cod_bu' => $r->cod_bu,
            'inicio_contrato' => $r->inicio_contrato,
            'termino_contrato' => $r->fin_contrato
        ]);
    }


});


Route::get('/reperrores', function () {

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
            ->from('Geo_Trabajadores')
            ->whereNull('cod_cargo')
            ->where('enabled', 1)
            ->where('fecha', $fecha);
    })
        ->where('fecha', $fecha)
        ->update(['sin_cargo' => 1]);


    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
            ->whereNull('turno')
            ->where('enabled', 1)
            ->where('fecha', $fecha)
            ->whereNull('art22');
    })
        ->where('fecha', $fecha)
        ->update(['sin_turno' => 1]);



    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
            ->where('inicio_contrato', '--')
            ->where('enabled', 1)
            ->where('fecha', $fecha);
    })
        ->where('fecha', $fecha)
        ->update(['sin_inicio_contrato' => 1]);

    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
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

    });

Route::get('/proforma', function () {

    DB::statement("CALL proforma();");
     dd('LISTO');

    });


Route::get('/errores', function () {

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
            ->from('Geo_Trabajadores')
            ->whereNull('cod_cargo')
            ->where('enabled', 1)
            ->where('fecha', $fecha);
    })
        ->where('fecha', $fecha)
        ->update(['sin_cargo' => 1]);


    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
            ->whereNull('turno')
            ->where('enabled', 1)
            ->where('fecha', $fecha)
            ->whereNull('art22');
    })
        ->where('fecha', $fecha)
        ->update(['sin_turno' => 1]);



    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
            ->where('inicio_contrato', '--')
            ->where('enabled', 1)
            ->where('fecha', $fecha);
    })
        ->where('fecha', $fecha)
        ->update(['sin_inicio_contrato' => 1]);

    RepGeoErrores::whereIn('rut', function ($query) use ($fecha) {
        $query->select('rut')
            ->from('Geo_Trabajadores')
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



    dd('listo');


});

Route::get('/prueba', function () {

    // cargar octubre, revisar 198876356

    $results = DB::select("SELECT cal.fecha, cal.dia, tr.*, asi.*
FROM (
    SELECT DISTINCT fecha,dia
    FROM sis_calendario
    WHERE fecha >= '2024-01-01'
) AS cal
CROSS JOIN geo_trabajadores AS tr
LEFT JOIN geo_asistencia AS asi ON asi.rut = tr.rut AND asi.date = cal.fecha
WHERE tr.fecha = '2024-02-05'");

    dd($results);


    $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');

    RepGeoAsistencia::truncate();



    ini_set('memory_limit', '512M');
    $results = DB::select("select cal.fecha as calfecha,cal.dia,tr.*
    FROM sis_calendario as cal ,geo_trabajadores as tr
    WHERE tr.fecha='2024-01-09' and cal.fecha BETWEEN '2024-01-01' and '2024-01-07' ");
    //WHERE tr.fecha='2023-11-21' and cal.fecha BETWEEN '2023-10-01' and '2023-10-31'");
    //    WHERE tr.fecha='2023-11-30' and cal.fecha >= '2023-11-01' ");
//AND tr.rut in ('159810658','267685142','133527745','167868215','205801162','155069856','118595637','109026468','188507646','192855020','171286476','266328427','151887902','198876356','199117556','194798296','197676272','212308676','192340756','126396570')
// AND tr.rut in ('206488778','262163695','260072153','265735800','159810658','267685142','133527745','167868215','205801162','155069856','118595637','109026468','188507646','192855020','171286476','266328427','151887902','198876356','199117556','194798296','197676272','212308676','192340756','126396570')
    foreach ($results as $r) {
        RepGeoAsistencia::create([
            'fecha' => $r->calfecha,
            'dia' => $r->dia,
            'rut' => $r->rut,
            'empresa' => $r->empresa,
            'nombre' => $r->nombres.' '.$r->apellidos,
            'art22' => $r->art22,
            'cod_cargo' => $r->cod_cargo,
            'cargo' => $r->cargo,
            'categoria' => $r->categoria,
            'estado' => $r->enabled,
            'grupo' => $r->grupo,
            'bu' => $r->bu,
            'cod_bu' => $r->cod_bu,
            'inicio_contrato' => $r->inicio_contrato,
            'termino_contrato' => $r->fin_contrato
        ]);
    }




    $info = DB::select("select tra.rut AS rutra,tra.*,asi.*
    FROM rep_geoasistencia as tra
    LEFT JOIN geo_asistencia as asi ON asi.rut=tra.rut AND asi.date=tra.fecha
    ");






    RepGeoAsistencia::truncate();

    foreach ($info as $rep) {




       // echo $file_extension;


        RepGeoAsistencia::create([

            'fecha' => $rep->fecha,
            'dia' => $rep->dia,
            'rut' => $rep->rutra,
            'nombre' => $rep->nombre,
            'estado' => $rep->estado,
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
            'termino_contrato' => $rep->termino_contrato,
            'ingreso' => $rep->entrada_fecha,
            'ingreso_origen' => $rep->entrada_origin,
            'ingreso_grupo' => $rep->entrada_groupdescription,
            'salida' => $rep->salida_fecha,
            'salida_origen' => $rep->salida_origin,
            'salida_grupo' => $rep->salida_groupdescription,
            'atraso' => $rep->delay,
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


// 9 min






    //UPDATE rep_geoasistencia SET no_presente = 1 WHERE presente = '0' AND fecha <= '2023-11-06';



    //UPDATE rep_geoasistencia SET presente = '1' WHERE fecha > '2023-11-06';

#UPDATE rep_geoasistencia SET presente = 0 WHERE  ausente = 'True'
#UPDATE rep_geoasistencia SET presente = 0 WHERE  permiso IN ('Licencia Médica Estándar','Vacaciones')
#UPDATE rep_geoasistencia SET presente = 0 WHERE rut='151887902' and fecha > '2023-10-26' AND fecha <= '2023-11-07'


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

   // dd($licencias);

// 5 min
   // dd($licencias);

   DB::statement("CALL RepGeoAsistencia();");

//    dd('listo');

//    /////////////////////LISTO

//     $turfut = RepGeoAsistencia::where('fecha',$fecha)->where('turno','!=','break')->orWhere('turno','!=',NULL)->get();

//     foreach ($turfut as $tf) {



//               //  dd($turno);
//                 RepGeoAsistencia::where('rut',$tf->rut)
//                 ->whereNull('turno')
//                 ->where('fecha','>',$fecha)
//                 ->update(['turno' => $tf->turno]);

//     }




    ///////////////////

    // $infot = RepGeoAsistencia::where('turno','break')
    // ->orWhere('turno',NULL)
    // //->whereIN('rut', array('192855020'))
    // ->orderBy('fecha', 'ASC')->get();

    // foreach ($infot as $inf) {

    //     $turno = null;

    //     $turno = GeoAsistencia::select('rut','date','shiftdisplay')
    //             ->where('rut','=',$inf->rut)
    //             ->whereNotIn('shiftdisplay', array('break'))
    //             ->where('date','<',$inf->fecha)
    //             ->orderBy('date', 'DESC')
    //             ->first();

    //         if (empty($turno->shiftdisplay)) {

    //         } else {
    //             RepGeoAsistencia::where('fecha','=',$inf->fecha)
    //             ->where('rut',$inf->rut)
    //             ->update(['turno' => $turno->shiftdisplay]);
    //         }

    // }


    // ///////////////////////

    // $tipoturno = GeoTurno::get();


    // foreach ($tipoturno as $tp) {

    //             RepGeoAsistencia::where('turno',$tp->horario)
    //             ->update(['tipo_turno' => $tp->turno]);

    // }






    DB::statement("update rep_geoasistencia SET tipo_turno = 'PART TIME' WHERE GRUPO LIKE '%PART TIME%'");
    DB::statement("update rep_geoasistencia SET tipo_turno = 'ADM' WHERE ART22 = 'SI'");

    //RepGeoAsistencia::where('fecha','>',$fecha)->update(['presente' => 1]);
    RepGeoAsistencia::where('ausente','True')->update(['presente' => 0]);
    RepGeoAsistencia::where('ausente',null)->where('fecha','<=',$fecha)->update(['presente' => 0]);
    RepGeoAsistencia::where('art22','SI')->update(['presente' => 1]);

    RepGeoAsistencia::whereIN('permiso',array('Licencia Médica Estándar','Vacaciones'))->update(['presente' => 0]);

   // GeoAsistencia::where('date','>=', $fecha)->delete();

   // RepGeoAsistencia::table('countries')->where('name','LIKE','%'.$term.'%')->delete();



   DB::statement("update rep_geoasistencia SET ATRASO = (CAST(SUBSTRING(ATRASO, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(ATRASO, 4, 5) AS UNSIGNED)");
   DB::statement("update rep_geoasistencia SET horas_extras = (CAST(SUBSTRING(horas_extras, 1, 2) AS UNSIGNED))*60+CAST(SUBSTRING(horas_extras, 4, 5) AS UNSIGNED)");
   DB::statement("update rep_geoasistencia SET ATRASO = 0 where  atraso <= 15");

   //EN MINUTOS Y HORAS ATRASOS Y HHEE
   //DB::statement("update rep_geoasistencia SET horas_extras = 0 WHERE atraso <= 15");

   DB::statement("update rep_geoasistencia SET licencia = 1 WHERE permiso = 'Licencia Médica Estándar'");
   DB::statement("update rep_geoasistencia SET vacaciones = 1 WHERE permiso = 'Vacaciones'");

   DB::statement("update rep_geoasistencia SET cumpleanio=1 WHERE permiso = 'CUMPLEAÑOS'");
   DB::statement("update rep_geoasistencia SET administrativo=1 WHERE permiso = 'P. Administrativo'");

   DB::statement("update rep_geoasistencia SET libre = 1 WHERE ausente = 'False' and trabajado = 'False' ");
   DB::statement("update rep_geoasistencia SET permiso_con_goce = 1 WHERE permiso IN (SELECT nombre FROM geo_permisos WHERE goce_sueldo = 'Si') ");

   DB::statement("delete FROM  rep_geoasistencia WHERE termino_contrato < fecha AND ingreso IS NULL AND salida IS NULL AND termino_contrato NOT IN ('--')");
   DB::statement("delete from rep_geoasistencia WHERE inicio_contrato > fecha AND ingreso IS NULL AND salida IS NULL");

   RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('presente',0)->delete();
   RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('trabajado','False')->delete();
   RepGeoAsistencia::where('grupo', 'like', '%PART TIME%')->where('fecha','>',$fecha)->delete();

    RepGeoAsistencia::where('presente',0)->update(['no_presente' => 1]);


    DB::statement("CALL proforma();");


    //dd('listo');


    //dd($lic);


});




Route::get('/api3', function () {



            $xcurl = curl_init();

            curl_setopt_array($xcurl, [
                CURLOPT_URL => "https://customerapi.geovictoria.com/api/v1/AttendanceBook?=",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => " {\"StartDate\": \"20230913000000\",\n\"EndDate\": \"20230923000000\",\n\"UserIds\": \"185060861\"}",
                CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJTQmVSUHBRcy0wMl9JSm9sbml0dldPaVB0LWhXSmN4RzBUYWNneVc2MExLVkdON1VnU1dDaDk2d0xSYkd6dmpPSXNkUGNKX1hEam1oc2pYNnVUT0gyMkRyeXVwdVdCT1JMUVBQRkRxVGh0MldWRmJiT0JJbHRzOUdXTnNRQ0pnTCIsImlhdCI6MTY5MDIxODA0NywibmJmIjoxNjkwMjE4MDQ3LCJleHAiOjI1MzQwMjMwMDgwMCwiaXNzIjoiR2VvVmljdG9yaWEgLSBDdXN0b21lciBBUEkifQ.SHKl51SDsjKS_zkQklSWeOntywxeYgkIc7qsbV_1yV0"
                ],
            ]);

            $xresponse = curl_exec($xcurl);
            $err = curl_error($xcurl);

            $trab = json_decode($xresponse);






        if(empty($trab->Users[0]->PlannedInterval)) {


        }else{

            foreach($trab->Users[0]->PlannedInterval as $asistencia)
            {



                if (empty($asistencia->Date)) {
                    $fecha = '1990-01-01';
                }else{
                    $fecha = Carbon::parse($asistencia->Date)->format('Y-m-d') ;
                }



                foreach($asistencia->Punches as $a)
                {
                    if (empty($a->GroupDescription)) {
                        $grupo = '--';
                    }else{
                        $grupo = $a->GroupDescription;
                    }








                    GeoAsistencia::create([
                        'rut' => '185060861',
                        'date' => Carbon::parse($a->Date)->format('Y-m-d H:i:s'),
                        'type' => $a->Type,
                        'origin' => $a->Origin,
                        'groupdescription' => $grupo,
                        'shiftpunchtype' => $a->ShiftPunchType,
                        'fecha' => $fecha
                    ]);

                }

                GeoAsistenciaAtrasos::create([
                    'rut' => '185060861',
                    'fecha' => Carbon::parse($asistencia->Date)->format('Y-m-d'),
                    'delay' => $asistencia->Delay,
                    'workedhours' => $asistencia->WorkedHours,
                    'absent' => $asistencia->Absent,
                    'holiday' => $asistencia->Holiday,
                    'worked' => $asistencia->Worked,
                    'nonworkedhours' => $asistencia->NonWorkedHours
                ]);




            }
        }




});

Route::get('/pruebapi', function () {
    $client = new Client([
        'base_uri' => 'https://customerapi.geovictoria.com/api/v1/',
    ]);

    $response = $client->request('POST', 'AttendanceBook', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJTQmVSUHBRcy0wMl9JSm9sbml0dldPaVB0LWhXSmN4RzBUYWNneVc2MExLVkdON1VnU1dDaDk2d0xSYkd6dmpPSXNkUGNKX1hEam1oc2pYNnVUT0gyMkRyeXVwdVdCT1JMUVBQRkRxVGh0MldWRmJiT0JJbHRzOUdXTnNRQ0pnTCIsImlhdCI6MTY5MDIxODA0NywibmJmIjoxNjkwMjE4MDQ3LCJleHAiOjI1MzQwMjMwMDgwMCwiaXNzIjoiR2VvVmljdG9yaWEgLSBDdXN0b21lciBBUEkifQ.SHKl51SDsjKS_zkQklSWeOntywxeYgkIc7qsbV_1yV0',
        ],
        'json' => [
            'StartDate' => '20240101000000',
            'EndDate' => '20240114000000',
            'UserIds' => '167868215',
        ],
    ]);

    $trab = json_decode($response->getBody()->getContents());


    if(empty($trab->Users[0]->PlannedInterval)) {

    }else{

        foreach($trab->Users[0]->PlannedInterval as $asistencia)
        {

            $entrada_fecha = NULL;
            $entrada_origin = NULL;
            $entrada_groupdescription = NULL;
            $salida_fecha = NULL;
            $salida_origin = NULL;
            $salida_groupdescription = NULL;
            $to_description = NULL;
            $to_star = NULL;
            $to_ends = NULL;
            $to_timeofftypedescription = NULL;

            if(empty($asistencia->Punches)) {

            }
            else{

                foreach($asistencia->Punches as $a){

                    if (empty($a->GroupDescription)) {
                        $grupo = '--';
                    }else{
                        $grupo = $a->GroupDescription;
                    }



                    if($a->ShiftPunchType == "Entrada") {
                        $entrada_fecha = Carbon::parse($a->Date)->format('Y-m-d H:i:s') ;
                        $entrada_origin = $a->Origin;
                        $entrada_groupdescription = $grupo;

                    }
                    elseif($a->ShiftPunchType == "Salida"){
                        $salida_fecha = Carbon::parse($a->Date)->format('Y-m-d H:i:s') ;
                        $salida_origin = $a->Origin;
                        $salida_groupdescription = $grupo;

                    }

                }

            }

            if(empty($asistencia->TimeOffs)) {

            }
            else{

                if(!empty($asistencia->TimeOffs[0]->Description)) {
                    $to_description = $asistencia->TimeOffs[0]->Description;
                }

                $to_star =  Carbon::parse($asistencia->TimeOffs[0]->Starts)->format('Y-m-d H:i:s');
                $to_ends = Carbon::parse($asistencia->TimeOffs[0]->Ends)->format('Y-m-d H:i:s') ;
                $to_timeofftypedescription = $asistencia->TimeOffs[0]->TimeOffTypeDescription;

            }


            // dd($asistencia->AssignedExtraTime);

            //$ext = $asistencia->AssignedExtraTime;
            if( key($asistencia->AssignedExtraTime) == null){
                $ext = Null;
            }else{


                // dd($asistencia->AssignedExtraTime);
                $ext = $asistencia->AssignedExtraTime;
                $ext = $ext->{key($asistencia->AssignedExtraTime)};
            }

            // if(empty($asistencia->AssignedExtraTime)){
            //     $ext = Null;
            // }else{
            //     $ext = $asistencia->AssignedExtraTime;
            //     //dd($asistencia->AssignedExtraTime);
            //     $ext = $ext->{key($asistencia->AssignedExtraTime)};
            // }


            //dd( $ext);
            //dd($ext);


            GeoAsistencia::create([
                'rut' => $trab->Users[0]->Identifier,
                'date' => Carbon::parse($asistencia->Date)->format('Y-m-d'),
                'entrada_fecha' =>$entrada_fecha ,
                'entrada_origin' => $entrada_origin,
                'entrada_groupdescription' => $entrada_groupdescription,
                'salida_fecha' => $salida_fecha,
                'salida_origin' => $salida_origin,
                'salida_groupdescription' => $salida_groupdescription,
                'type' => $asistencia->Shifts[0]->Type,
                'shiftdisplay' => $asistencia->Shifts[0]->ShiftDisplay,
                'delay' => $asistencia->Delay,
                'assignedextratime' => $ext,
                'workedhours' => $asistencia->WorkedHours,
                'absent' => $asistencia->Absent,
                'holiday' => $asistencia->Holiday,
                'worked' => $asistencia->Worked,
                'nonworkedhours' => $asistencia->NonWorkedHours,
                'to_description' => $to_description,
                'to_star' => $to_star,
                'to_ends' => $to_ends,
                'to_timeofftypedescription' => $to_timeofftypedescription

            ]);


        }
    }
});

Route::get('/api2', function () {

    set_time_limit(1800);
    //$fecha = Carbon::parse(Carbon::now()->subDays(2))->format('Y-m-d');
    $fecha = '2024-01-18';
    GeoAsistencia::where('date','>=', '2024-01-18')->delete();

    $FECHAINI = '2024-01-18';
    $FECHAINI = str_replace("-","",$FECHAINI);
    $FECHAINI = $FECHAINI . '000000';

    $FECHAMAN = Carbon::now();
    $FECHAMAN = $FECHAMAN->toDateString();
    $FECHAMAN = str_replace("-","",$FECHAMAN);
    $FECHAMAN = $FECHAMAN . '000000';

    $trabajadores = GeoTrabajadores::select('rut','fecha')
    //->where('fecha','2023-11-10')
    ->where('fecha',Carbon::parse(Carbon::now())->format('Y-m-d'))
    //->whereIN('rut', array('206488778','262163695','260072153','265735800','159810658','267685142','133527745','167868215','205801162','155069856','118595637','109026468','188507646','192855020','171286476','266328427','151887902','198876356','199117556','194798296','197676272','212308676','192340756','126396570'))
    ->get();




   // dd($FECHAMAN);

   //dd($trabajadores);

    foreach($trabajadores as $trabajador)
    {

        //dd($trabajador);

            $xcurl = curl_init();

            curl_setopt_array($xcurl, [
                CURLOPT_URL => "https://customerapi.geovictoria.com/api/v1/AttendanceBook?=",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => " {\"StartDate\": \"20240101000000\",\n\"EndDate\": \"20240114000000\",\n\"UserIds\": \"$trabajador->rut\"}",
               //CURLOPT_POSTFIELDS => " {\"StartDate\": \"20230909000000\",\n\"EndDate\": \"20230909000000\",\n\"UserIds\": \"271862156\"}",
                CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJTQmVSUHBRcy0wMl9JSm9sbml0dldPaVB0LWhXSmN4RzBUYWNneVc2MExLVkdON1VnU1dDaDk2d0xSYkd6dmpPSXNkUGNKX1hEam1oc2pYNnVUT0gyMkRyeXVwdVdCT1JMUVBQRkRxVGh0MldWRmJiT0JJbHRzOUdXTnNRQ0pnTCIsImlhdCI6MTY5MDIxODA0NywibmJmIjoxNjkwMjE4MDQ3LCJleHAiOjI1MzQwMjMwMDgwMCwiaXNzIjoiR2VvVmljdG9yaWEgLSBDdXN0b21lciBBUEkifQ.SHKl51SDsjKS_zkQklSWeOntywxeYgkIc7qsbV_1yV0"
                ],
            ]);

            $xresponse = curl_exec($xcurl);
            $err = curl_error($xcurl);

            $trab = json_decode($xresponse);




           //dd($trab);


        if(empty($trab->Users[0]->PlannedInterval)) {

        }else{

            foreach($trab->Users[0]->PlannedInterval as $asistencia)
            {







                $entrada_fecha = NULL;
                $entrada_origin = NULL;
                $entrada_groupdescription = NULL;
                $salida_fecha = NULL;
                $salida_origin = NULL;
                $salida_groupdescription = NULL;
                $to_description = NULL;
                $to_star = NULL;
                $to_ends = NULL;
                $to_timeofftypedescription = NULL;

                    if(empty($asistencia->Punches)) {

                    }
                    else{

                        foreach($asistencia->Punches as $a){

                            if (empty($a->GroupDescription)) {
                                $grupo = '--';
                            }else{
                                $grupo = $a->GroupDescription;
                            }



                            if($a->ShiftPunchType == "Entrada") {
                                $entrada_fecha = Carbon::parse($a->Date)->format('Y-m-d H:i:s') ;
                                $entrada_origin = $a->Origin;
                                $entrada_groupdescription = $grupo;

                            }
                            elseif($a->ShiftPunchType == "Salida"){
                                $salida_fecha = Carbon::parse($a->Date)->format('Y-m-d H:i:s') ;
                                $salida_origin = $a->Origin;
                                $salida_groupdescription = $grupo;

                            }

                        }

                    }

                    if(empty($asistencia->TimeOffs)) {

                    }
                    else{

                        if(!empty($asistencia->TimeOffs[0]->Description)) {
                            $to_description = $asistencia->TimeOffs[0]->Description;
                        }

                        $to_star =  Carbon::parse($asistencia->TimeOffs[0]->Starts)->format('Y-m-d H:i:s');
                        $to_ends = Carbon::parse($asistencia->TimeOffs[0]->Ends)->format('Y-m-d H:i:s') ;
                        $to_timeofftypedescription = $asistencia->TimeOffs[0]->TimeOffTypeDescription;

                    }


                   // dd($asistencia->AssignedExtraTime);

                    //$ext = $asistencia->AssignedExtraTime;
                    if( key($asistencia->AssignedExtraTime) == null){
                        $ext = Null;
                    }else{


                       // dd($asistencia->AssignedExtraTime);
                        $ext = $asistencia->AssignedExtraTime;
                        $ext = $ext->{key($asistencia->AssignedExtraTime)};
                    }

                    // if(empty($asistencia->AssignedExtraTime)){
                    //     $ext = Null;
                    // }else{
                    //     $ext = $asistencia->AssignedExtraTime;
                    //     //dd($asistencia->AssignedExtraTime);
                    //     $ext = $ext->{key($asistencia->AssignedExtraTime)};
                    // }


                    //dd( $ext);
                    //dd($ext);


                    GeoAsistencia::create([
                        'rut' => $trabajador->rut,
                        'date' => Carbon::parse($asistencia->Date)->format('Y-m-d'),
                        'entrada_fecha' =>$entrada_fecha ,
                        'entrada_origin' => $entrada_origin,
                        'entrada_groupdescription' => $entrada_groupdescription,
                        'salida_fecha' => $salida_fecha,
                        'salida_origin' => $salida_origin,
                        'salida_groupdescription' => $salida_groupdescription,
                        'type' => $asistencia->Shifts[0]->Type,
                        'shiftdisplay' => $asistencia->Shifts[0]->ShiftDisplay,
                        'delay' => $asistencia->Delay,
                        'assignedextratime' => $ext,
                        'workedhours' => $asistencia->WorkedHours,
                        'absent' => $asistencia->Absent,
                        'holiday' => $asistencia->Holiday,
                        'worked' => $asistencia->Worked,
                        'nonworkedhours' => $asistencia->NonWorkedHours,
                        'to_description' => $to_description,
                        'to_star' => $to_star,
                        'to_ends' => $to_ends,
                        'to_timeofftypedescription' => $to_timeofftypedescription

                    ]);


            }
        }
    }


    $turno = GeoTrabajadores::select('rut')->where('fecha', $fecha)->get();

    foreach ($turno as $tu ) {

    $results = DB::select("select shiftdisplay FROM geo_asistencia as aa
    WHERE ShiftDisplay IS NOT NULL AND ShiftDisplay NOT IN ('BREAK','Sin Turno')
    AND date < '2023-09-27' and aa.rut='$tu->rut' ORDER BY DATE DESC limit 1");


    if(empty($results[0]->shiftdisplay)) {


    }else{
        GeoTrabajadores::where('rut',$tu->rut)
         ->where('fecha',$fecha)
         ->update(['turno' => $results[0]->shiftdisplay]);
    }

    }



});







/// API 1 OK, ANDA RAPIDO
Route::get('/api', function () {

    $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');

    GeoTrabajadores::select('rut')->where('fecha', $fecha)->delete();

    $dateHOY = Carbon::now();
    $FECHAINI =  $dateHOY->toDateString();
    $FECHAINI = str_replace("-","",$FECHAINI);
    $FECHAINI = $FECHAINI . '000000';

    $dateINI = Carbon::now();
    $FECHAMAN = $dateINI->addDays(1);
    $FECHAMAN =  $FECHAMAN->toDateString();
    $FECHAMAN = str_replace("-","",$FECHAMAN);
    $FECHAMAN = $FECHAMAN . '000000';

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://apiv3.geovictoria.com/api/User/List",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => [
        'Authorization: OAuth oauth_consumer_key="9605c3", oauth_nonce="LyZVsjq8zukAuF7uMqvY56GpHCsqDtaW", oauth_signature="GiPzZKzAdJyTT3fUua7dggQX%2Bzg%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1690183730", oauth_version="1.0"'
            ]   ,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $info = json_decode($response);


    foreach($info as $RES)
    {
        $contrato = substr($RES->ContractDate, 0,4).'-'.substr($RES->ContractDate, 4,2).'-'.substr($RES->ContractDate, 6,2);
        $fincontrato = substr($RES->endContractDate, 0,4).'-'.substr($RES->endContractDate, 4,2).'-'.substr($RES->endContractDate, 6,2);


        $art22 = NULL;
        if ($RES->Custom2 == 'Art.22') {
            $art22 = 'SI';
        }

        $tmp = explode(' ', $RES->GroupDescription);
        $empresa = end($tmp);



        $xcurl = curl_init();

            GeoTrabajadores::create([
                'nombres' => $RES->Name,
                'apellidos' => $RES->LastName,
                'rut' => $RES->Identifier,
                'fecha' => $fecha,
                'email' => $RES->Email,
                'cod_cargo' => $RES->Custom1,
                'grupo' => $RES->GroupDescription,
                'ART22' => $art22,
                'inicio_contrato' => $contrato,
                'fin_contrato' => $fincontrato,
                'empresa' => $empresa,
                'enabled' => $RES->Enabled
            ]);
    }


   //dd('listo');

    //GeoTrabajadores::select('rut')->where('fecha', $fecha)->delete();

   // GeoTrabajadores::delete();

    //$grupos = GeoGrupo::select('nivel_tres')->get();

    $grupos = GeoGrupo::select('geo_grupos.nivel_tres')
    ->join('business_units', 'geo_grupos.nivel_dos', '=', 'business_units.grupo_geovictoria')
    ->get();


    GeoTrabajadores::select('rut')
    ->where('fecha', $fecha)
    ->whereNotIn('grupo', $grupos)
    ->delete();





    /////////////////////////////////////////////
    ///
    ///
    ///
    dd('listo');
    $trab = GeoTrabajadores::select('rut','grupo')->where('fecha', $fecha)->get();

    foreach ($trab as $t) {

        $grupo = GeoGrupo::select('nivel_tres')->get();

        $bu = GeoGrupo::select('business_units.nombre','business_units.codigo')
                ->join('business_units', 'geo_grupos.nivel_dos', '=', 'business_units.grupo_geovictoria')
                ->where('geo_grupos.nivel_tres', $t->grupo)
                ->first();

        //dd($t->grupo);

        GeoTrabajadores::where('rut', $t->rut)->update(['bu' => $bu->nombre,'cod_bu' => $bu->codigo ]);
    }


////////////

    $trabc = GeoTrabajadores::select('rut','cod_cargo')->where('fecha', $fecha)->whereNotNull('cod_cargo')->get();

    foreach ($trabc as $tc) {

                $cargo = GeoCargo::select('cargo','categoria')
                ->where('id', $tc->cod_cargo)
                ->first();

        //dd($t->grupo);

        GeoTrabajadores::where('rut', $tc->rut)->update(['cargo' => $cargo->cargo,'categoria' => $cargo->categoria ]);
    }

    DB::statement("delete FROM geo_trabajadores WHERE RUT = '88241193'");





});



Route::resource('roles', RoleController::class)->names('admin.roles');


Route::resource('users', UserController::class)->names('admin.users');
Route::resource('cargalibro', ProformaController::class)->names('cargadatos.libro');
// Route::resource('users', UserController::class)->middleware('can:1')->names('admin.users');


Route::resource('bu', BuController::class)->names('admin.bu');
Route::resource('FlujoAprobacion', FlujoAprobacionController::class)->names('admin.flujo');
Route::resource('FlujoAprobacionDet', FlujoAprobacionDetController::class)->names('admin.flujodet');
Route::resource('Asistencia', AsistenciaController::class)->names('areas.recursoshumanos.asistencia');

Route::get('FlujoAprobacionDet/{FlujoAprobacion}/flujo', [FlujoAprobacionDetController::class, 'detindex'])->name('flujo');


Route::get('asistencia/export/', [AsistenciaController::class, 'export'])->name('repAsistencia');
Route::get('asistencia/repSemanalMeli/', [AsistenciaController::class, 'exportSemanalMeli'])->name('repSemanalMeli');
Route::get('/pdf', [AsistenciaController::class, 'imprimir'])->name('PDFAsistencia');
Route::get('/pdfanalista', [AsistenciaController::class, 'analistaPDF'])->name('analistaPDF');




// MODULO: CARATULA INFORME DE COSTO
Route::post('/ImportLibro', [ProformaController::class, 'cargalibro'])->name('ImportLibro');

//-------------------------------
// MODULO: CARATULA INFORME DE COSTO
Route::get('rrhh/export/', [ProformaController::class, 'export'])->name('repProforma');

//-------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
