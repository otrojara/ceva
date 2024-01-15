<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\GeoTrabajadores;

class HomeController extends Controller
{
    public function index(){

        $usuarios = User::count();
        $BusinessUnits = BusinessUnit::count();
        $GeoTrabajadores = GeoTrabajadores::count();    
        return view('home',compact('usuarios','BusinessUnits','GeoTrabajadores'));
    }
}
