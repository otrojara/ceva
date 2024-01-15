<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Moneda;

class MonedaController extends Controller
{
    
    public function index()
    {
        $url = env('URL_SERVER_API','https://api.sbif.cl');
        $response = Http::get($url.'/api-sbifv3/recursos_api/uf?apikey=40f8ba9c2c1fcb12e308b8ddaaece139a26c9494&formato=json');
        $data = $response->json();
    
        return ($data);
       // dd($data);
    
        // foreach ($data as $client) {
        //    dd($client[0]['Valor']);
        // }
    
        // dd($data);
    }

}
