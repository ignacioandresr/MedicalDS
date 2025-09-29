@extends('layouts.app')

@section('content')
<div class="container-fluid records-index pt-5">
    <h1 class="fw-bold text-center mb-4">Historial Médico</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                <h6><span class="fw-bold">Paciente RUT:</span> {{ $record->patient->rut }}</h6>
                <h6><span class="fw-bold">Nombre:</span> {{ $record->patient->name ?? '' }}</h6>
                <h6><span class="fw-bold">Apellido Paterno:</span> {{ $record->patient->apellido_paterno ?? '' }}</h6>
                <h6><span class="fw-bold">Apellido Materno:</span> {{ $record->patient->apellido_materno ?? '' }}</h6>
                <h6><span class="fw-bold">Fecha de Nacimiento:</span> {{ $record->patient->birth_date ?? '' }}</h6>
                <h6><span class="fw-bold">Género:</span> {{ $record->patient->gender ?? '' }}</h6>
                <h6><span class="fw-bold">Dirección:</span> {{ $record->patient->adress ?? '' }}</h6>
                <h6><span class="fw-bold">Diagnóstico:</span> {{ $record->diagnostic->description}}</h6>
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
