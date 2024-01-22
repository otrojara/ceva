<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTrabajadoresJob;
use Illuminate\Console\Command;

class ProcessTrabajadores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proceso:trabajadores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga trabajadores GeoVictoria';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fecha = now()->format('Y-m-d');

        // Llama a la tarea en la cola
        ProcessTrabajadoresJob::dispatch($this->getTrabajadoresData(), $fecha);

        return 0;
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
