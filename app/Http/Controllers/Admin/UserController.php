<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Malahierba\ChileRut\ChileRut;
use Malahierba\ChileRut\Rules\ValidChileanRut;
use App\Models\BusinessUnit;
use App\Models\BusinessUnitUser;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','id')->toArray();
        $BusinessUnits = BusinessUnit::all();

        return view('admin.users.create',compact('roles','BusinessUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       // dd($request); 

       $request->validate([
        'rut' => ['required', 'string', new ValidChileanRut(new ChileRut)],
        ]);

        $pass = str_replace( ".", "",str_replace( "-", "", $request->rut ) );

        $user = User::create([
            'name' => $request->name,
            'rut' => $request->rut,
            'email' => $request->email,
            'password' => Hash::make($pass)
        ]); 

        $user->assignRole($request->role_id);

        $BusinessUnits = $request->input('BusinessUnits', []);
        foreach ($BusinessUnits as $BusinessUnit) 
        {
            BusinessUnitUser::create([
                'business_unit_id' => $BusinessUnit,
                'user_id' => $user->id
            ]);  
        }


        
   
        return redirect()->route('admin.users.index')->with('info','El usuario se creo satifactoriamente');
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
    public function edit(User $user)
    {

        $userRole = $user->roles[0]->id;
        $roles = Role::pluck('name','id')->toArray();
        // $BusinessUnits = BusinessUnit::select('*')
        // ->leftjoin('business_unit_user', 'business_unit_user.business_unit_id', '=', 'business_units.id')
 
        // ->get();



        $BusinessUnits = DB::select("select business_units.id,business_units.nombre,business_unit_user.user_id
        FROM business_units 
        LEFT JOIN business_unit_user  ON business_unit_user.business_unit_id=business_units.id and business_unit_user.user_id=$user->id
        ");

        //dd($BusinessUnits);

        $BuUser = BusinessUnitUser::select('business_unit_id')->where('user_id',$user->id)->get();
        
  

        return view('admin.users.edit',compact('user','roles','userRole','BusinessUnits','BuUser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'rut' => ['required', 'string', new ValidChileanRut(new ChileRut)],
            ]);
       // dd($role);

        // $role->update([
        //     'name' => 'hola'
        // ]);

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'rut' => $request->rut,
            'email' => $request->email
        ]);

        //dd($request->role_id);
        //$user->permissions()->sync($request->permissions);
        $user->roles()->sync($request->role_id);
        $roles = Role::pluck('name','id')->toArray();
        $userRole = $request->role_id;

        BusinessUnitUser::where('user_id',$user->id)->delete();
        $BusinessUnits = $request->input('BusinessUnits', []);
        foreach ($BusinessUnits as $BusinessUnit) 
        {
            BusinessUnitUser::create([
                'business_unit_id' => $BusinessUnit,
                'user_id' => $user->id
            ]);  
        }

        $users = User::all();

        //return redirect()->route('admin.users.edit',$user);
        return redirect()->route('admin.users.index')->with('info','El Usuario se actualizó con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('info','El Usuario se eliminó con éxito');
    }
}
