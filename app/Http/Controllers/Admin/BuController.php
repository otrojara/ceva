<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\SisComuna;
use App\Models\SisRegion;


class BuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bus= BusinessUnit::all();
        return view('admin.bu.index',compact('bus'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comunas = SisComuna::pluck('nombre','id')->toArray();
      //  $comunas = SisComuna::select('nombre','id')->orderBy('nombre','DESC')->get();
       // $regions = SisRegion::pluck('nombre','id')->toArray();

       // dd($comunas);

        return view('admin.bu.create',compact('comunas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required']
        ]);

        BusinessUnit::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'grupo_geovictoria' => $request->grupo_geovictoria,
            'comuna_id' => $request->comuna_id,
            'zona' => $request->zona,
            'gerente_operacion' => $request->gerente_operacion,
            'contract_id' => $request->contract_id,
        ]);

        return redirect()->route('admin.bu.index')->with('info','El BU se creo satifactoriamente');
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
    public function edit(BusinessUnit $bu)
    {

        $comunas = SisComuna::pluck('nombre','id')->toArray();
        $regions = SisRegion::pluck('nombre','id')->toArray();

        $idcomuna = $bu->id;

        return view('admin.bu.edit',compact('bu','comunas','regions','idcomuna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessUnit $bu)
    {
        $request->validate([
            'nombre' => ['required']
        ]);

            BusinessUnit::where('id', $bu->id)->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'grupo_geovictoria' => $request->grupo_geovictoria,
            'comuna_id' => $request->comuna_id,
            'zona' => $request->zona,
            'gerente_operacion' => $request->gerente_operacion,
            'contract_id' => $request->contract_id
        ]);




        $comunas = SisComuna::pluck('nombre','id')->toArray();
        $regions = SisRegion::pluck('nombre','id')->toArray();

        $idcomuna = $bu->comuna_id;


        //return view('admin.bu.index',compact('bu','comunas','regions','idcomuna'));
        return redirect()->route('admin.bu.index')->with('info','La BU se editó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessUnit $bu)
    {
        $bu->delete();
        return redirect()->route('admin.bu.index')->with('info','La BU se eliminó con éxito');
    }
}
