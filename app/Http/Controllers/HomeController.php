<?php

namespace App\Http\Controllers;

use App\Entity\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\User as ModelsUser;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['welcome']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application welcome.
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function welcome()
    {
        $libros = Libro::where('eliminado', false)->get();
        $categorias = [];
        foreach ($libros as $libro) {
            //compruebo que en categorias esta la categoria y si no esta la meto
            if (!in_array($libro->categoria, $categorias)) {
                $categorias[] = $libro->categoria;
            }
        }
        //comprueba que viene por get
        if (request()->isMethod('post')) {
            //cojo la categoria de post
            $categoria = request()->input('categoria');
            //obtengo libros por categoria
            if($categoria != 'todos'){
                $libros = Libro::where('categoria', $categoria)->where('eliminado', false)->get();
            }
        }

        return view('welcome',['libros'=>$libros,'categorias'=>$categorias]);
    }

    /**
     * Muestra el edicion de perfil del usuario, si viene por post lo edita
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function editarPerfil()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        //Comprueba si viene por get o por post
        if (request()->isMethod('get')) {
            //obtiene datos del usuario
            $usuario = auth()->user();
            return view('user/editarPerfil', ['usuario' => $usuario]);
        } else {
            //obtiene informacion de post
            $datos = request()->input('datos');

            //valido datos
            $validator = validator($datos, [
                'nombre' => 'required|string',
                'password' => 'nullable|string|min:8',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorString = implode('<br>', $errors);
                return redirect()->route('editarPerfil')->with('error', $errorString);            }

            // Actualizar nombre y contraseña si se proporcionan
            $user = ModelsUser::find(auth()->user()->id);
            $user->name = $datos['nombre'];
            if (!empty($datos['password'])) {
                $user->password = bcrypt($datos['password']);
            }
            $user->save();

            return redirect()->route('editarPerfil')->with('exito', 'Perfil actualizado correctamente');
        }
    }
}
