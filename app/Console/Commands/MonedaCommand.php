<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Moneda;
use Illuminate\Support\Facades\Http;

class MonedaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moneda:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga UF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
        $url = env('URL_SERVER_API','https://api.sbif.cl');
        $response = Http::get($url.'/api-sbifv3/recursos_api/uf?apikey=40f8ba9c2c1fcb12e308b8ddaaece139a26c9494&formato=json');
        $data = $response->json();

        $d = Http::get($url.'/api-sbifv3/recursos_api/dolar?apikey=40f8ba9c2c1fcb12e308b8ddaaece139a26c9494&formato=json');
        $dolar = $d->json();

        $e = Http::get($url.'/api-sbifv3/recursos_api/euro?apikey=40f8ba9c2c1fcb12e308b8ddaaece139a26c9494&formato=json');
        $euro = $e->json();

       
        $string = str($data['UFs'][0]['Valor'])->replaceFirst('.', '');
            
        Moneda::create([
            'fecha' => $euro['Euros'][0]['Fecha'],
            'uf' => str($string)->replaceFirst(',', '.'),
            'dolar' => str($dolar['Dolares'][0]['Valor'])->replaceFirst(',', '.'),
            'euro' => str($euro['Euros'][0]['Valor'])->replaceFirst(',', '.')
        ]);  
 
    }
}
