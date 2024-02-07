
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Anomalias') }}</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Libro</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Indicaciones</th>
                                    <th scope="col">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($anomalias as $anomalia)
                                    <tr>
                                        <td>{{$anomalia->libro->titulo}}</td>
                                        <td>{{$anomalia->email}}</td>
                                        <td>{{$anomalia->descripcion}}</td>
                                        <td>{{$anomalia->created_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

