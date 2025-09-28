@extends('layouts.app')

@section('content')
<div class="container-fluid diagnostics-index pt-5">
    <h1 class="fw-bold text-center mb-4">Información del Diagnóstico N° {{ $diagnostic->id }}</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                <h6><span class="fw-bold">RUT:</span> {{ $diagnostic->patient->rut }}</h6>
                <h6><span class="fw-bold">Nombre:</span> {{ $diagnostic->patient->name }}</h6>
                <h6><span class="fw-bold">Apellido Paterno:</span> {{ $diagnostic->patient->apellido_paterno }}</h6>
                <h6><span class="fw-bold">Apellido Materno:</span> {{ $diagnostic->patient->apellido_materno }}</h6>
                <h6><span class="fw-bold">Fecha de Nacimiento:</span> {{ $diagnostic->patient->birth_date }}</h6>
                <h6><span class="fw-bold">Sexo:</span> {{ $diagnostic->patient->gender }}</h6>
                <h6><span class="fw-bold">Descripción:</span> {{ $diagnostic->description }}</h6>
                <h6><span class="fw-bold">Fecha:</span> {{ $diagnostic->date }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12 justify-content-center text-center">
                <a class="btn btn-primary mt-3" href="{{ route('diagnostics.index') }}">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
