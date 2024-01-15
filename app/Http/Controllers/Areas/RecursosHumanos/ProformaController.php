<?php

namespace App\Http\Controllers\Areas\RecursosHumanos;

use App\Exports\proforma\ProformaExport;
use App\Http\Controllers\Controller;
use App\Imports\CargaLibroImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProformaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cargadatos.rrhh.libro.index');
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

    public function cargalibro()
    {
        Excel::import(new CargaLibroImport, request()->file('file'));
        return redirect('/')->with('success', 'All good!');
    }

    public function export() 
    {
        return Excel::download(new ProformaExport, 'proforma.xlsx');
        
    }

 
}
