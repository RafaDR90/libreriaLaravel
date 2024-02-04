@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Lista de Libros') }}</div>

                    <div class="card-body">
                        @foreach($libros as $libro)
                            <div class="libro {{ $libro->prestado!='no' ? 'text-muted' : '' }} d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>{{ $libro->titulo }}</h3>
                                    <p>{{ $libro->descripcion }}</p>
                                    <span class="{{ $libro->prestado!='no' ? 'text-warning' : 'text-success' }}">
                                    {{ $libro->prestado!='no' ? 'Alquilado' : 'Disponible' }}
                                </span>
                                </div>
                                @if ($libro->prestado=='no')
                                    <a href="{{ route('reservaLibro', ['id' => $libro->id]) }}" class="btn btn-primary">Reservar</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

