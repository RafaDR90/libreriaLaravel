@extends('layouts.app')

@section('content')
    <!--muestra libros-->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Gestion de Libros') }} &nbsp&nbsp <a href="{{ route('addLibro') }}" class="btn btn-dark mb-2">Añadir libro</a>

                        @foreach($libros as $libro)
                            <div class="card">
                                <p class="card-header">ID: {{ $libro->id }}</p>
                                <div class="card-body">
                                    <p class="card-text"><strong>Titulo:</strong> {{ $libro->titulo }}</p>
                                    <p class="card-text"><strong> Estado:</strong> {{ $libro->estado }}</p>
                                    @if($libro->eliminado==1)
                                        <p class="card-text text-danger">Eliminado</p>
                                    @endif
                                </div>
                                <div class="card-footer d-flex gap-2">
                                    <a href="{{ route('formEditarLibro',['id' => $libro->id]) }}" class="btn btn-dark">Editar</a>
                                    <a href="{{ route('eliminarLibro', ['id' => $libro->id]) }}" class="btn btn-{{ $libro->eliminado == 1 ? 'success' : 'danger' }}">
                                        {{ $libro->eliminado == 1 ? 'Reponer' : 'Eliminar' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
