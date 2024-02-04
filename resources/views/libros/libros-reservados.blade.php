@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Lista de libros reservados') }}</div>

                    <div class="card-body">
                        @foreach($libros as $libro)
                            <div class="libro {{ $libro->prestado!='no' ? 'text-muted' : '' }} d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>{{ $libro->titulo }}</h3>
                                    <p>{{ $libro->descripcion }}</p>
                                    <span class="{{ $libro->prestado!='no' ? 'text-success' : 'text-success' }}">
                                    {{ $libro->prestado=='reservado' ? 'Listo para recoger' : 'En posesión' }}
                                </span>
                                </div>
                                @if ($libro->prestado=='reservado')
                                    <a href="{{ route('cancelaRecogida', ['id' => $libro->id]) }}" class="btn btn-primary">Cancelar recogida</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
