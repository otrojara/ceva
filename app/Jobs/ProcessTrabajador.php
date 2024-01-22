<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\GeoAsistencia;



class ProcessTrabajador implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $trabajador;

    public function __construct($trabajador)
    {
        $this->trabajador = $trabajador;
    }

    public function handle()
    {
        try {
            // Tu lógica de manejo de trabajador aquí
            GeoAsistencia::create([
                'rut' => '1234',
                'date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            ]);
            Log::info('Trabajo encolado correctamente', ['trabajador' => '1234']);
        } catch (\Exception $e) {
            Log::error('Error en el manejo del trabajo: ' . $e->getMessage());
        }
    }
}
