@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between">{{ __('Lista de Libros') }}
                        <form method="post" action="{{ url('/') }}">
                            @csrf
                            <label for="categorias">Filtrar por Categoria &nbsp;</label>
                            <select name="categoria"> <!-- Agrega el atributo name -->
                                @if(isset($categorias))
                                    <option>Todo</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{$categoria}}">{{$categoria}}</option> <!-- Usa el valor de la categoría como value -->
                                    @endforeach
                                @endif
                            </select>
                            <input type="submit" value="Buscar">
                        </form>
                    </div>

                    <div class="card-body">
                        @foreach($libros as $libro)
                            <div class="libro {{ $libro->prestado!='no' ? 'text-muted' : '' }} d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>{{ $libro->titulo }}</h3>
                                    <p><b>Descripción:</b> {{ $libro->descripcion }}</p>
                                    <p><b>Categoría:</b> {{ $libro->categoria }}</p>
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

