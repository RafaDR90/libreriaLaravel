@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Formulario de Anomalias') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('confirmarRecogida',['id' => $libroId]) }}" class="needs-validation" novalidate>
                            @csrf
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="datos[anomalia]" id="anomalias">
                                    <label class="form-check-label" for="anomalias">Anomalías</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="indicaciones">Indicar las anomalías</label>
                                <input type="text" class="form-control" name="datos[indicaciones]" id="indicaciones">
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado actual</label>
                                <select class="form-select" name="datos[estado]" id="estado">
                                    <option value="bueno">Buen estado</option>
                                    <option value="malo">Mal estado</option>
                                    <option value="critico">Muy mal estado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
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
