@extends('layouts.app')

@section('content')
<div class="container-fluid patients-index pt-5">
    <h1 class="fw-bold text-center mb-4">Información del Paciente</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                <h6><span class="fw-bold">RUT:</span> {{ $patient->rut }}</h6>
                <h6><span class="fw-bold">Nombre:</span> {{ $patient->name }}</h6>
                <h6><span class="fw-bold">Apellido Paterno::</span> {{ $patient->apellido_paterno }}</h6>
                <h6><span class="fw-bold">Apellido Materno:</span> {{ $patient->apellido_materno }}</h6>
                <h6><span class="fw-bold">Fecha de Nacimiento:</span> {{ $patient->birth_date ->format('d-m-Y') }}</h6>
                <h6><span class="fw-bold">Genero:</span> {{ $patient->gender }}</h6>
                <h6><span class="fw-bold">Dirección:</span> {{ $patient->adress }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12 justify-content-center text-center">
                <a class="btn btn-primary mt-3" href="{{ route('patients.index') }}">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
