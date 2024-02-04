@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Gestion de reservas') }}</div>
                    <div class="card-body">
                        @foreach($libros as $libro)
                            <!--Obtiene $id del cliente que tiene el libro-->

                            <div class="libro {{ $libro->prestado!='no' ? 'text-muted' : '' }} d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>{{ $libro->titulo }}</h3>
                                    <p>Usuario: {{ $libro->usuario_id }}</p>
                                    <span class="{{ $libro->prestado!='no' ? 'text-success' : 'text-success' }}">
                                        {{ $libro->prestado=='reservado' ? 'Listo para recoger' : 'En posesión del cliente' }}
                                    </span>
                                </div>
                                @if ($libro->prestado=='reservado')
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('cancelaRecogida', ['id' => $libro->id]) }}" class="btn btn-primary mb-2">Cancelar recogida</a>
                                        <a href="{{ route('confirmarEntrega', ['id' => $libro->id]) }}" class="btn btn-primary">Confirmar entrega</a>
                                    </div>
                                @else
                                    <a href="{{ route('confirmarRecogida', ['id' => $libro->id]) }}" class="btn btn-primary">Confirmar Recogida</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
