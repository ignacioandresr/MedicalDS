@extends('layouts.app')

@section('content')
<div class="container-fluid records-index pt-5">
    <h1 class="fw-bold text-center mb-4">Historial Médico</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                <h6><span class="fw-bold">Paciente:</span> {{ $record->patient->name ?? '' }}</h6>
                <h6><span class="fw-bold">Diagnóstico:</span> {{ $record->diagnostic->description ?? '' }}</h6>
                <h6><span class="fw-bold">Tratamientos:</span> {{ $record->tratamientos }}</h6>
                <h6><span class="fw-bold">Fecha:</span> {{ $record->fecha }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12 justify-content-center text-center">
                <a class="btn btn-primary mt-3" href="{{ route('records.index') }}">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
