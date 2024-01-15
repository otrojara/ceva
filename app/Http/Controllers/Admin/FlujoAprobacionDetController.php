<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlujoAprobacion;
use App\Models\FlujoAprobacionDetalle;
use App\Models\User;

class FlujoAprobacionDetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
    
    }
    public function detindex(FlujoAprobacion $FlujoAprobacion)
    {


        //dd($FlujoAprobacion);
       // dd($FlujoAprobacion);
        $info = FlujoAprobacionDetalle::select('user_id')
        ->where('flujo_aprobacion_id',$FlujoAprobacion->id)
        ->get();

       // dd($info);

       //dd($FlujoAprobacion);
       $detalles = FlujoAprobacionDetalle::join('users','flujo_aprobacion_detalles.user_id','=','users.id')
       ->where('flujo_aprobacion_detalles.flujo_aprobacion_id',$FlujoAprobacion->id)
       ->get(['flujo_aprobacion_detalles.*', 'users.name']);


    //    $users = User::join('posts', 'users.id', '=', 'posts.user_id')
    //            ->get(['users.*', 'posts.descrption']);



       // SomeModel::select(..)->whereNotIn('book_price', [100,200])->get();
        $users = User::whereNotIn('id', $info)
        ->get()
        ->pluck('name','id');
        return view('admin.flujo.detalle.index',compact('FlujoAprobacion','users','detalles'));
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
        $request->validate([
            'user' => ['required']
        ]);

        $info = FlujoAprobacionDetalle::select('posicion')
        ->where('flujo_aprobacion_id',$request->flujo_id)
        ->orderBy('posicion', 'desc')
        ->first();

        $FlujoAprobacion =  FlujoAprobacion::where('id',$request->flujo_id)->first();

        //dd($FlujoAprobacion);

        if(empty($info->posicion)){
            $posicion = 0;
        }else{
            $posicion = $info->posicion;
        }
        

        $flujo = FlujoAprobacionDetalle::create([
            'posicion' => $posicion+1,
            'user_id' => $request->user,
            'flujo_aprobacion_id' => $request->flujo_id
        ]);

         
        return redirect()->route('flujo',$FlujoAprobacion)->with('info','El Flujo se actualizó satifactoriamente');
       
    
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

        $idf = FlujoAprobacionDetalle::where('id',$id)->first();
        
        FlujoAprobacionDetalle::where('id',$id)->delete();

        $detalles = FlujoAprobacionDetalle::where('flujo_aprobacion_id',$idf->flujo_aprobacion_id)->get();

        $FlujoAprobacion =  FlujoAprobacion::where('id',$idf->flujo_aprobacion_id)->first();

        $posicion = 0;

        //dd($detalles);
        foreach($detalles as $det){

            $posicion = $posicion + 1;
            $det = FlujoAprobacionDetalle::find($det['id']); 
            $det->posicion = $posicion; 
            $det->save(); 
            
        }

        return redirect()->route('flujo',$FlujoAprobacion)->with('info','El Usuario se eliminó con éxito');
    }
}
