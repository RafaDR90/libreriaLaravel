<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Anomalia;

class LibroController extends Controller
{
    public function reservarLibro($id)
    {
        //comprueba si esta logueado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    //valida la id
        $id=(int)$id;
        $validator = validator(['id' => $id], [
            'id' => 'required|integer|exists:libros,id',
        ]);
        if ($validator->fails()) {
            return redirect()->route('/');
        }
        //modifica el libro en la base de datos
        $libro = Libro::find($id);
        $libro->prestado = 'reservado';
        $libro->save();
        //añado el libro id y el user id a la tabla reservas
        $prestamo = new Prestamo();
        $prestamo->usuario_id = auth()->user()->id;
        $prestamo->libro_id = $id;
        $prestamo->fecha_salida = date('Y-m-d');
        $prestamo->fecha_entrada =null;
        $prestamo->save();
        return redirect()->route('welcome');
    }

    public function libros_reservados()
    {
        //comprueba si esta logueado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        //obtiene los id de los libros reservados con la id de usuario
        $idLibros = Prestamo::where('usuario_id', auth()->user()->id)
            ->whereNull('fecha_entrada')
            ->get();
        //obtiene los libros con los id
        $libros = [];
        foreach ($idLibros as $idLibro) {
            $libros[] = Libro::find($idLibro->libro_id);
        }
        return view('libros/libros-reservados', ['libros' => $libros]);
    }

    public function cancelaRecogida($libroId)
    {
        //comprueba si esta logueado
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        //valida la id
        $libroId=(int)$libroId;
        $validator = validator(['libroId' => $libroId], [
            'libroId' => 'required|integer|exists:libros,id',
        ]);
        if ($validator->fails()) {
            return redirect()->route('/');
        }
        //modifica el libro en la base de datos
        $libro = Libro::find($libroId);
        $libro->prestado = 'no';
        $libro->save();
        //modifica la fecha de entrada en la tabla reservas
        $prestamo = Prestamo::where('usuario_id', auth()->user()->id)
            ->where('libro_id', $libroId)
            ->whereNull('fecha_entrada')
            ->first();
        $prestamo->fecha_entrada = date('Y-m-d');
        $prestamo->save();
        return redirect()->route('libros-reservados');
    }

    public function gestionReservas()
    {
        //obtiene todos los id de libros de la tabla reservas que no tengan fecha de entrada
        $idLibros = Prestamo::whereNull('fecha_entrada')->get();
        //obtiene los libros con los id
        $libros = [];
        foreach ($idLibros as $idLibro) {
            $libros[] = Libro::find($idLibro->libro_id);
            //añade el id de usuario a cada libro
            $libros[count($libros) - 1]->usuario_id = $idLibro->usuario_id;
        }
        return view('libros/gestion-reservas', ['libros' => $libros]);
    }

    public function confirmarEntrega($libroId)
    {
        //comprueba si esta logueado y su rol es admin
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        //valida la id
        $libroId=(int)$libroId;
        $validator = validator(['libroId' => $libroId], [
            'libroId' => 'required|integer|exists:libros,id',
        ]);
        if ($validator->fails()) {
            return redirect()->route('/');
        }
        //modifica el libro en la base de datos
        $libro = Libro::find($libroId);
        $libro->prestado = 'entregado';
        $libro->save();
        return redirect()->route('gestionReservas');
    }

    public function confirmarRecogida($libroId)
    {
        //comprueba si esta logueado y su rol es admin
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        $request = request();
        if ($request->isMethod('get')) {
            return view('libros/form-anomalias', ['libroId' => $libroId]);
        }else {
            if ($request->input('datos.anomalia')!=null){
                //validamos datos.estado
                $validator = validator($request->all(), [
                    'datos.estado' => 'required|in:bueno,malo,critico',
                ]);
                if ($validator->fails()) {
                    return redirect()->route('/');
                }
                //cambiamos el estado del libro en la bd
                $libro = Libro::find($libroId);
                $libro->estado = $request->input('datos.estado');
                $libro->save();
                //validamos datos.anomalia
                $validator = validator($request->all(), [
                    'datos.anomalia' => 'required|string',
                ]);
                if ($validator->fails()) {
                    return redirect()->route('/');
                }
                //buscamos la id del cliente que tiene el libro
                $prestamo = Prestamo::where('libro_id', $libroId)
                    ->whereNull('fecha_entrada')
                    ->first();
                $usuarioId = $prestamo->usuario_id;
                //guardamos la anomalia en la tabla anomalias de la bd con user_id y libro_id y descripcion
                $anomalia = new Anomalia();
                $anomalia->usuario_id = $usuarioId;
                $anomalia->libro_id = $libroId;
                $anomalia->descripcion = $request->input('datos.indicaciones');
                $anomalia->save();
            }
            //valida la id
            $libroId = (int)$libroId;
            $validator = validator(['libroId' => $libroId], [
                'libroId' => 'required|integer|exists:libros,id',
            ]);
            if ($validator->fails()) {
                return redirect()->route('/');
            }
            //modifica el libro en la base de datos
            $libro = Libro::find($libroId);
            $libro->prestado = 'no';
            $libro->save();
            //modifica la fecha de entrada en la tabla reservas
            $prestamo = Prestamo::where('libro_id', $libroId)
                ->whereNull('fecha_entrada')
                ->first();
            $prestamo->fecha_entrada = date('Y-m-d');
            $prestamo->save();
            return redirect()->route('gestionReservas');
        }
    }
}
