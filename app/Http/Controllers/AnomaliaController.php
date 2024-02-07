<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anomalia;
use App\Models\User as ModelsUser;
use App\Models\Libro;
class AnomaliaController extends Controller
{
    public function muestraAnomalias(){
        //comprueba que estoy logueado
        if (!auth()->check()){
            return redirect()->route('login');
        }
        if (auth()->user()->rol!='admin'){
            return redirect()->route('welcome');
        }
        $anomalias= Anomalia::all();
        foreach ($anomalias as $anomalia){
            //meto en $anomalia el campo email
            $email= ModelsUser::find($anomalia->usuario_id)->email;
            $titulo= Libro::find($anomalia->libro_id)->titulo;
            $anomalia->email=$email;
            $anomalia->titulo=$titulo;
        }
        return view('anomalias/vistaAnomalias',['anomalias'=>$anomalias]);
    }
}
