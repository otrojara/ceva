<?php

namespace App\Http\Controllers\Areas\RecursosHumanos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeoTrabajadores;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AsistenciaExport;
use App\Exports\RepSemanalMeliExport;
use App\Mail\AlertaGeoVictoria;
use App\Models\BusinessUnit;
use App\Models\BusinessUnitUser;
use App\Models\GeoAsistencia;
use App\Models\RepGeoErrores;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');
        $GeoTrabajadores = GeoTrabajadores::where('fecha',$fecha)->count();

        $sinCargos = GeoTrabajadores::where('fecha',$fecha)
        ->where('enabled',1)
        ->where('cargo',NULL)
        ->get();

        $sinInicioContrato = GeoTrabajadores::where('fecha',$fecha)
        ->where('enabled',1)
        ->where('inicio_contrato','--')
        ->get();

        $cargos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_cargo', 1)->orderBy('bu', 'DESC')->get();
        $cargoscount = count($cargos);

        $turnos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_turno', 1)->orderBy('bu', 'DESC')->get();
        $turnoscount = count($turnos);

        $icontrato = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_inicio_contrato', 1)->orderBy('bu', 'DESC')->get();
        $icontratocount = count($icontrato) ;



        return view('areas.recursoshumanos.asistencia.index',compact('GeoTrabajadores','sinCargos','sinInicioContrato','cargoscount','turnoscount','icontratocount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export()
    {
        return Excel::download(new AsistenciaExport, 'users.xlsx');
    }

    public function exportSemanalMeli()
    {
        return Excel::download(new RepSemanalMeliExport, 'Semanal.xlsx');
    }

    public function imprimir(){


        // $cargos = GeoTrabajadores::where('cargo', NULL)->WHERE('enabled',1)->where('fecha',Carbon::parse(Carbon::now())->format('Y-m-d'))->get();
        // $cargoscount = $cargos->count();
        //$fecha = Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d');

        $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');


        $errores = RepGeoErrores::select()->where('fecha',$fecha)->orderBy('bu', 'DESC')->get();


        $cargos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_cargo', 1)->orderBy('bu', 'DESC')->get();
        $cargoscount = count($cargos);

        $turnos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_turno', 1)->orderBy('bu', 'DESC')->get();
        $turnoscount = count($turnos);

        $icontrato = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_inicio_contrato', 1)->orderBy('bu', 'DESC')->get();
        $icontratocount = count($icontrato) ;

        $fcontrato = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_fin_contrato', 1)->orderBy('bu', 'DESC')->get();
        $fcontratocount = count($fcontrato);


        $salida = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_salida', 1)->orderBy('bu', 'DESC')->get();
        $salidacount = count($salida);

        //dd($salida);
        //$salidacount = count($salida);

        $pdf = Pdf::loadView('pdf.egeovictoria',[
        'errores' => $errores,
        'cargoscount' => $cargoscount,
        'icontratocount' => $icontratocount,
        'fcontratocount' => $fcontratocount,
        'turnoscount' => $turnoscount,
        'salidacount' => $salidacount,
        'fecha' => $fecha
        ]);
        //return $pdf->download('invoice.pdf');


        //$data["email"] = "djaramontenegro@gmail.com;djaramontenegro@ourlook.com";
        $data["pdf"] = $pdf;

        //usuarios = model_has_roles::select('email')->


        $users = User::whereHas("roles", function($q){ $q->where("name", "Administrador"); })->get();

        //Mail::to('djaramontenegro@gmail.com')->send(new AlertaGeoVictoria($data));

       // Codigo, correcto, descomentar cuando se utilice la plataforma
        foreach ($users  as $us) {
            Mail::to($us->email)->send(new AlertaGeoVictoria($data));
        }

       // dd('Mail sent successfully');

        return redirect()->route('areas.recursoshumanos.asistencia.index');

    //     Mail::to('djaramontenegro@gmail.com')
    //     ->send(new AlertaGeoVictoria)
    //     ->attachData($pdf->output(),"errores.pdf");

    // return "Mensaje Enviado";

   }


   public function analistaPDF(){


    // $cargos = GeoTrabajadores::where('cargo', NULL)->WHERE('enabled',1)->where('fecha',Carbon::parse(Carbon::now())->format('Y-m-d'))->get();
    // $cargoscount = $cargos->count();
    //$fecha = Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d');



        $users = User::whereHas("roles", function($q){ $q->where("name", "Analista"); })->get();

        foreach ($users as $u) {

            $bus = BusinessUnitUser::select('business_unit_id')->where('user_id',$u->id)->get();

            $buNAME = BusinessUnit::select('nombre')->whereIN('id',$bus)->get();

            $fecha = Carbon::parse(Carbon::now())->format('Y-m-d');


            $errores = RepGeoErrores::select()->where('fecha',$fecha)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();


            $cargos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_cargo', 1)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();
            $cargoscount = count($cargos);



            $turnos = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_turno', 1)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();
            $turnoscount = count($turnos);

            $icontrato = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_inicio_contrato', 1)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();
            $icontratocount = count($icontrato) ;

            $fcontrato = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_fin_contrato', 1)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();
            $fcontratocount = count($fcontrato);


            $salida = RepGeoErrores::select('rut','nombre','bu')->where('fecha',$fecha)->where('sin_salida', 1)->whereIN('bu',$buNAME)->orderBy('bu', 'DESC')->get();
            $salidacount = count($salida);

            //dd($buNAME);

            $pdf = Pdf::loadView('pdf.egeovictoria',[
                'errores' => $errores,
                'cargoscount' => $cargoscount,
                'icontratocount' => $icontratocount,
                'fcontratocount' => $fcontratocount,
                'turnoscount' => $turnoscount,
                'salidacount' => $salidacount,
                'fecha' => $fecha
                ]);
                //return $pdf->download('invoice.pdf');


                //$data["email"] = "djaramontenegro@gmail.com";
                $data["pdf"] = $pdf;

                //usuarios = model_has_roles::select('email')->


                //$users = User::whereHas("roles", function($q){ $q->where("name", "Administrador"); })->get();


                    Mail::to($u->email)->send(new AlertaGeoVictoria($data));



        }






      //  dd('Mail sent successfully');

      return redirect()->route('areas.recursoshumanos.asistencia.index');


    //dd($salida);
    //$salidacount = count($salida);



//     Mail::to('djaramontenegro@gmail.com')
//     ->send(new AlertaGeoVictoria)
//     ->attachData($pdf->output(),"errores.pdf");

// return "Mensaje Enviado";

}

}
