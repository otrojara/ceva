<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessTrabajadoresJob;
use App\Models\GeoAsistencia;
use App\Models\GeoCargo;
use App\Models\GeoGrupo;
use App\Models\GeoTrabajadores;
use GuzzleHttp\Client;
use App\Jobs\ProcessTrabajador;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ApiGeoController extends Controller
{
    public function processTrabajadores()
    {
        $fecha = now()->format('Y-m-d');

        // Llama a la tarea en la cola
        ProcessTrabajadoresJob::dispatch($this->getTrabajadoresData(), $fecha);


        return response()->json(['message' => 'Trabajadores en cola para procesar.']);
    }

    private function getTrabajadoresData()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://apiv3.geovictoria.com',
        ]);

        $response = $client->request('POST', '/api/User/List', [
            'headers' => [
                'Authorization' => 'OAuth oauth_consumer_key="9605c3", oauth_nonce="LyZVsjq8zukAuF7uMqvY56GpHCsqDtaW", oauth_signature="GiPzZKzAdJyTT3fUua7dggQX%2Bzg%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1690183730", oauth_version="1.0"',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

}


