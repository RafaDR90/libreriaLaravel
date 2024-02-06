@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Gestion de Libros') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ isset($libro) ? route('formEditarLibro', ['id' => $libro->id]) : route('addLibro') }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group">
                                <label for="titulo">Titulo</label>
                                <input type="text" class="form-control" name="datos[titulo]" id="titulo" @if(isset($libro)) value="{{ $libro->titulo }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="autor">Autor</label>
                                <input type="text" class="form-control" name="datos[autor]" id="autor" @if(isset($libro)) value="{{ $libro->autor }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripcion</label>
                                <input type="text" class="form-control" name="datos[descripcion]" id="editorial" @if(isset($libro)) value="{{ $libro->descripcion }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <input type="text" class="form-control" name="datos[categoria]" id="categoria" @if(isset($libro)) value="{{ $libro->categoria }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="lanzamiento">Lanzamiento</label>
                                <input type="text" class="form-control" name="datos[lanzamiento]" id="lanzamiento" @if(isset($libro)) value="{{ $libro->lanzamiento }}" @else placeholder="yyyy-mm-dd" @endif>
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="form-select" name="datos[estado]" id="estado">
                                    <option value="bueno" @if(isset($libro) && $libro->estado == 'bueno') selected @endif>Buen estado</option>
                                    <option value="malo" @if(isset($libro) && $libro->estado == 'malo') selected @endif>Mal estado</option>
                                    <option value="critico" @if(isset($libro) && $libro->estado == 'critico') selected @endif>Muy mal estado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-dark mt-3">
                                    {{ __('Enviar') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
