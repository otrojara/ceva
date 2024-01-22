<?php

namespace App\Jobs;

use App\Models\GeoCargo;
use App\Models\GeoGrupo;
use App\Models\GeoTrabajadores;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessTrabajadoresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $trabajadoresData;
    protected $fecha;

    public function __construct($trabajadoresData, $fecha)
    {
        $this->trabajadoresData = $trabajadoresData;
        $this->fecha = $fecha;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $batchSize = 100;
        $chunks = array_chunk($this->trabajadoresData, $batchSize);

        foreach ($chunks as $chunk) {
            $mappedTrabajadores = $this->mapTrabajadores($chunk, $this->fecha);
            GeoTrabajadores::insert($mappedTrabajadores);
        }


        DB::table('geo_trabajadores')
            ->where('fecha', $this->fecha)
            ->whereNotIn('grupo', function ($query) {
                $query->select('geo_grupos.nivel_tres')
                    ->from('geo_grupos')
                    ->join('business_units', 'geo_grupos.nivel_dos', '=', 'business_units.grupo_geovictoria');
            })
            ->delete();



        GeoTrabajadores::select('rut', 'grupo')
            ->where('fecha', $this->fecha)
            ->get()
            ->each(function ($t) {
                $bu = GeoGrupo::select('business_units.nombre', 'business_units.codigo')
                    ->join('business_units', 'geo_grupos.nivel_dos', '=', 'business_units.grupo_geovictoria')
                    ->where('geo_grupos.nivel_tres', $t->grupo)
                    ->first();

                GeoTrabajadores::where('rut', $t->rut)->update([
                    'bu' => $bu->nombre,
                    'cod_bu' => $bu->codigo
                ]);
            });
        $trabajadoresConCargo = GeoTrabajadores::select('rut', 'cod_cargo')
            ->where('fecha', $this->fecha)
            ->whereNotNull('cod_cargo')
            ->get();

        foreach ($trabajadoresConCargo as $trabajador) {
            $cargo = GeoCargo::select('cargo', 'categoria')
                ->where('id', $trabajador->cod_cargo)
                ->first();

            if ($cargo) {
                GeoTrabajadores::where('rut', $trabajador->rut)->update([
                    'cargo' => $cargo->cargo,
                    'categoria' => $cargo->categoria,
                ]);
            }
        }

    }
    private function mapTrabajadores(array $trabajadores, $fecha)
    {
        $mappedTrabajadores = [];

        foreach ($trabajadores as $RES) {
            $contrato = Carbon::parse($RES['ContractDate'])->format('Y-m-d');
            $fincontrato = Carbon::parse($RES['endContractDate'])->format('Y-m-d');
            $art22 = $RES['Custom2'] == 'Art.22' ? 'SI' : null;

            $tmp = explode(' ', $RES['GroupDescription']);
            $empresa = end($tmp);

            $mappedTrabajadores[] = [
                'nombres' => $RES['Name'],
                'apellidos' => $RES['LastName'],
                'rut' => $RES['Identifier'],
                'fecha' => $fecha,
                'email' => $RES['Email'],
                'cod_cargo' => $RES['Custom1'],
                'grupo' => $RES['GroupDescription'],
                'ART22' => $art22,
                'inicio_contrato' => $contrato,
                'fin_contrato' => $fincontrato,
                'empresa' => $empresa,
                'enabled' => $RES['Enabled']
            ];
        }

        return $mappedTrabajadores;
    }


}
