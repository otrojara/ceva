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
    protected $trabajadoresData;
    /**
     * Create a new job instance.
     */
    public function __construct($trabajadoresData)
    {
        $this->trabajadoresData = $trabajadoresData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->realizarConsulta();

        if ($response === false) {
            Log::error('Error en la solicitud cURL: ' . curl_error($response));
            return;
        }

        $responseJson = json_decode($response);

        // Manejar errores de decodificación JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error al decodificar JSON: ' . json_last_error_msg());
            return;
        }

        // Verificar si la solicitud fue exitosa
        if (!isset($responseJson->Users[0]->PlannedInterval)) {
            return;
        }

        $this->procesarAsistencias($responseJson);
    }
    protected function realizarConsulta()
    {
        $xcurl = curl_init();

        curl_setopt_array($xcurl, [
            CURLOPT_URL => "https://customerapi.geovictoria.com/api/v1/AttendanceBook?=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->generarPayload(),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJTQmVSUHBRcy0wMl9JSm9sbml0dldPaVB0LWhXSmN4RzBUYWNneVc2MExLVkdON1VnU1dDaDk2d0xSYkd6dmpPSXNkUGNKX1hEam1oc2pYNnVUT0gyMkRyeXVwdVdCT1JMUVBQRkRxVGh0MldWRmJiT0JJbHRzOUdXTnNRQ0pnTCIsImlhdCI6MTY5MDIxODA0NywibmJmIjoxNjkwMjE4MDQ3LCJleHAiOjI1MzQwMjMwMDgwMCwiaXNzIjoiR2VvVmljdG9yaWEgLSBDdXN0b21lciBBUEkifQ.SHKl51SDsjKS_zkQklSWeOntywxeYgkIc7qsbV_1yV0"
            ],
        ]);

        return curl_exec($xcurl);
    }

    protected function generarPayload()
    {
        $FECHAHOY = Carbon::now()->format('Ymd') . '000000';
        $FECHAMAN = Carbon::now()->subDays(7)->format('Ymd') . '000000';

        return json_encode([
            "StartDate" => $FECHAMAN,
            "EndDate" => $FECHAHOY,
            "UserIds" => $this->trabajadoresData->rut
        ]);
    }

    protected function procesarAsistencias($response)
    {
        if (empty($response->Users[0]->PlannedInterval)) {
            return;
        }

        foreach ($response->Users[0]->PlannedInterval as $asistencia) {
            $this->procesarAsistencia($asistencia);
        }
    }

    protected function procesarAsistencia($asistencia)
    {$entrada_fecha = NULL;
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

        if( key($asistencia->AssignedExtraTime) == null){
            $ext = Null;
        }else{

            $ext = $asistencia->AssignedExtraTime;
            $ext = $ext->{key($asistencia->AssignedExtraTime)};
        }


        $ultimoTurno = GeoAsistencia::where('rut', $this->trabajadoresData->rut)
            ->where('shiftdisplay', '!=', 'Break')
            ->orderByDesc('date')
            ->first();

        if ($ultimoTurno) {
            $shiftdisplay = $ultimoTurno->shiftdisplay;
            // Realizar acciones con $shiftdisplay
        } else {
            $shiftdisplay =$asistencia->Shifts[0]->ShiftDisplay;
        }


        GeoAsistencia::create([
            'rut' => $this->trabajadoresData->rut,
            'date' => Carbon::parse($asistencia->Date)->format('Y-m-d'),
            'entrada_fecha' =>$entrada_fecha ,
            'entrada_origin' => $entrada_origin,
            'entrada_groupdescription' => $entrada_groupdescription,
            'salida_fecha' => $salida_fecha,
            'salida_origin' => $salida_origin,
            'salida_groupdescription' => $salida_groupdescription,
            'type' => $asistencia->Shifts[0]->Type,
            'shiftdisplay' => $shiftdisplay,
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
