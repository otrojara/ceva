<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use App\Models\FlujoAprobacion;
use App\Models\FlujoAprobacionBusinessUnit;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class FlujoAprobacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flujoaprobaciones = FlujoAprobacion::all();

        
        $bus = FlujoAprobacionBusinessUnit::join('business_units','flujo_aprobacion_business_unit.business_unit_id','=','business_units.id')
        ->get();

       
        
        return view('admin.flujo.index',compact('flujoaprobaciones','bus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $info = FlujoAprobacionBusinessUnit::select('business_unit_id')->get();
        $BusinessUnits = BusinessUnit::whereNotIn('id', $info)
        ->get();

         //dd($BusinessUnits);
        // $BusinessUnits = BusinessUnit::all();

        
        return view('admin.flujo.create',compact('BusinessUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required']
        ]);

        $flujo = FlujoAprobacion::create([
            'nombre' => $request->nombre,
        ]);

        $BusinessUnits = $request->input('BusinessUnits', []);
        foreach ($BusinessUnits as $BusinessUnit) 
        {

            $request->validate([
                'business_unit_id' => ['unique']
            ]);
            
            FlujoAprobacionBusinessUnit::create([
                'flujo_aprobacion_id' => $flujo->id,
                'business_unit_id' => $BusinessUnit
            ]);  
        }


        return redirect()->route('admin.flujo.index')->with('info','El Flujo se creó satifactoriamente');
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
    public function edit(FlujoAprobacion $FlujoAprobacion)
    {

        //dd($FlujoAprobacion->id);

        //$BusinessUnits = BusinessUnit::all();
        $info = FlujoAprobacionBusinessUnit::select('business_unit_id')->where('flujo_aprobacion_id',$FlujoAprobacion->id)->get();
        $info1 = FlujoAprobacionBusinessUnit::select('business_unit_id')->get();

        
 

        // $a = FlujoAprobacionBusinessUnit::join('business_units','flujo_aprobacion_business_unit.business_unit_id','=','business_units.id')
        // ->where('flujo_aprobacion_business_unit.flujo_aprobacion_id',$FlujoAprobacion->id)
        // ->get(['business_units.*', DB::raw("'1' AS 'checked'")]);



        // $b = FlujoAprobacionBusinessUnit::join('business_units','flujo_aprobacion_business_unit.business_unit_id','=','business_units.id')
        // ->whereNotIn('flujo_aprobacion_business_unit.flujo_aprobacion_id',$FlujoAprobacion->id)
        // ->get(['business_units.*', DB::raw("'0' AS 'checked'")]);

       
        $a = BusinessUnit::whereIn('id', $info)
        ->get(['business_units.*', DB::raw("'1' AS 'checked'")]);

        

        $b = BusinessUnit::whereNotIn('id', $info1)
        ->get(['business_units.*', DB::raw("'0' AS 'checked'")]);
        
       

       $BusinessUnits = $a->merge($b);


        
        
       

        



//         $a = Model::where('code', '=', $code)
// ->where('col_a', '=' , 1);

// $b = Model::where('code', '=', $code)->where('col_b', '=' , 1)
// ->union($a)
// ->get();
      
        return view('admin.flujo.edit',compact('FlujoAprobacion','BusinessUnits'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FlujoAprobacion $FlujoAprobacion)
    {
        FlujoAprobacion::where('id', $FlujoAprobacion->id)->update([
            'nombre' => $request->nombre
        ]);

     

        FlujoAprobacionBusinessUnit::where('flujo_aprobacion_id',$FlujoAprobacion->id)->delete();
        $BusinessUnits = $request->input('BusinessUnits', []);
        foreach ($BusinessUnits as $BusinessUnit) 
        {
            FlujoAprobacionBusinessUnit::create([
                'flujo_aprobacion_id' => $FlujoAprobacion->id,
                'business_unit_id' => $BusinessUnit
            ]);  
        }

        return redirect()->route('admin.flujo.index')->with('info','El Flujo se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlujoAprobacion $FlujoAprobacion)
    {
        $FlujoAprobacion->delete();
        return redirect()->route('admin.flujo.index')->with('info','El flujo se eliminó con éxito');
    }
}
