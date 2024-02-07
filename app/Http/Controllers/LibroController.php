<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Anomalia;
use App\Models\User as ModelsUser;

class LibroController extends Controller
{
    /**
     * Reserva un libro.
     *
     * @param int $id
     * @return RedirectResponse
     */
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
        $prestamo->fecha_entrada = null;
        $prestamo->save();
        return redirect()->route('welcome');
    }

    /**
     * Muestra los libros reservados por el usuario logueado.
     *
     * @return View|RedirectResponse
     */
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

    /**
     * Cancela la recogida de un libro reservado.
     *
     * @param int $libroId
     * @return RedirectResponse
     */
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

    /**
     * Muestra la vista de gestión de reservas de libros.
     *
     * @return View
     */
    public function gestionReservas()
    {
        //obtiene todos los id de libros de la tabla reservas que no tengan fecha de entrada
        $idLibros = Prestamo::whereNull('fecha_entrada')->get();
        //obtiene los libros con los id
        $libros = [];
        foreach ($idLibros as $idLibro) {
            //obtiene el email del usuario con el id
            $email = ModelsUser::find($idLibro->usuario_id)->email;
            $libros[] = Libro::find($idLibro->libro_id);
            //añade el id de usuario a cada libro
            $libros[count($libros) - 1]->usuario_id = $email;
        }
        return view('libros/gestion-reservas', ['libros' => $libros]);
    }

    /**
     * Confirma la entrega de un libro reservado.
     *
     * @param int $libroId
     * @return RedirectResponse
     */
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

    /**
     * Confirma la recogida de un libro reservado.
     *
     * @param int $libroId
     * @return RedirectResponse|View
     */
    public function confirmarRecogida($libroId)
    {
        //comprueba si esta logueado y su rol es admin
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        $request = request();
        if ($request->isMethod('get')) {
            return view('libros/form-anomalias', ['libroId' => $libroId]);
        } else {
            if ($request->input('datos.anomalia') != null) {
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

    /**
     * Muestra la vista de gestión de libros.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function gestionLibros()
    {
        //comprueba si esta logueado y su rol es admin
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        $libros = Libro::all();
        return view('libros/gestion-libros', ['libros' => $libros]);
    }

    /**
     * Elimina un libro.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function eliminarLibro($id)
    {
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        //valida la id
        $id=(int)$id;
        $validator = validator(['id' => $id], [
            'id' => 'required|integer|exists:libros,id',
        ]);
        if ($validator->fails()) {
            return redirect()->route('/');
        }
        //marco eliminado como true en la bd
        $libro = Libro::find($id);
        if ($libro->eliminado==0) {
            $libro->eliminado = 1;
        }else{
            $libro->eliminado = 0;
        }
        $libro->save();
        return redirect()->route('gestionLibros');
    }

    /**
     * Muestra el formulario para editar un libro.
     *
     * @param int $libroId
     * @return Application|Factory|View|RedirectResponse
     */
    public function formEditarLibro($libroId)
    {
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
        $request = request();
        if ($request->isMethod('get')) {
            //obtengo el libro con la id
            $libro = Libro::find($libroId);
            return view('libros/add-edit-libro', ['libro' => $libro]);
        }else {
            //valida los datos
            $datos = $request->input('datos');

            if (isset($datos['lanzamiento'])) {
                $datos['lanzamiento'] = date('Y-m-d', strtotime($datos['lanzamiento']));
            }

            // Validar los datos
            $validator = validator($datos, [
                'titulo' => 'required|string',
                'autor' => 'required|string',
                'descripcion' => 'required|string',
                'categoria' => 'required|string',
                'lanzamiento' => ['required', 'date', 'date_format:Y-m-d'],
                'estado' => 'required|in:bueno,malo,critico',
            ]);
            if ($validator->fails()) {
                return redirect()->route('/');
            }
            //modifica el libro en la base de datos
            $libro = Libro::find($libroId);
            $libro->titulo = $request->input('datos.titulo');
            $libro->autor = $request->input('datos.autor');
            $libro->descripcion = $request->input('datos.descripcion');
            $libro->categoria = $request->input('datos.categoria');
            $libro->lanzamiento = $request->input('datos.lanzamiento');
            $libro->estado = $request->input('datos.estado');
            $libro->save();
            return redirect()->route('gestionLibros');
        }
    }

    /**
     * Agrega un libro.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function addLibro()
    {
        if (!auth()->check() || auth()->user()->rol != 'admin') {
            return redirect()->route('welcome');
        }
        $request = request();
        if ($request->isMethod('get')) {
            return view('libros/add-edit-libro');
        }else {
            //valida los datos
            $datos = $request->input('datos');

            if (isset($datos['lanzamiento'])) {
                $datos['lanzamiento'] = date('Y-m-d', strtotime($datos['lanzamiento']));
            }

            // Validar los datos
            $validator = validator($datos, [
                'titulo' => 'required|string',
                'autor' => 'required|string',
                'descripcion' => 'required|string',
                'categoria' => 'required|string',
                'lanzamiento' => ['required', 'date', 'date_format:Y-m-d'],
                'estado' => 'required|in:bueno,malo,critico',
            ]);
            if ($validator->fails()) {
                return redirect()->route('/');
            }
            //crea el libro en la base de datos
            $libro = new Libro();
            $libro->titulo = $request->input('datos.titulo');
            $libro->autor = $request->input('datos.autor');
            $libro->descripcion = $request->input('datos.descripcion');
            $libro->categoria = $request->input('datos.categoria');
            $libro->lanzamiento = $request->input('datos.lanzamiento');
            $libro->estado = $request->input('datos.estado');
            $libro->prestado = 'no';
            $libro->eliminado = 0;
            $libro->save();
            return redirect()->route('gestionLibros');
        }
    }
}
