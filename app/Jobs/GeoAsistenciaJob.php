<?php

namespace App\Jobs;

use App\Models\GeoAsistencia;
use App\Models\GeoTrabajadores;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeoAsistenciaJob implements ShouldQueue
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

        set_time_limit(1800);
        //$fecha = Carbon::parse(Carbon::now()->subDays(2))->format('Y-m-d');
        $fecha = '2024-01-29';
        GeoAsistencia::where('date','>=', '2024-01-18')->delete();

        $FECHAINI = '2024-01-29';
        $FECHAINI = str_replace("-","",$FECHAINI);
        $FECHAINI = $FECHAINI . '000000';

        $FECHAMAN = Carbon::now();
        $FECHAMAN = $FECHAMAN->toDateString();
        $FECHAMAN = str_replace("-","",$FECHAMAN);
        $FECHAMAN = $FECHAMAN . '000000';

        $trabajadores = GeoTrabajadores::select('rut','fecha')
            //->where('fecha','2023-11-10')
            ->where('fecha',Carbon::parse(Carbon::now())->format('Y-m-d'))
            ->whereIN('rut', array('206488778','262163695','260072153','265735800','159810658','267685142','133527745','167868215','205801162','155069856','118595637','109026468','188507646','192855020','171286476','266328427','151887902','198876356','199117556','194798296','197676272','212308676','192340756','126396570'))
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
                CURLOPT_POSTFIELDS => " {\"StartDate\": \"20240101000000\",\n\"EndDate\": \"20240129000000\",\n\"UserIds\": \"$trabajador->rut\"}",
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

    }
}
