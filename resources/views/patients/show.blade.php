@extends('layouts.app')

@section('content')
<div class="container-fluid patients-index pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="fw-bold text-center">Información del Paciente</h1>
                <h4><span class="fw-bold">RUT:</span> {{ $patient->rut }}</h3>
                <h4><span class="fw-bold">Nombre:</span> {{ $patient->name }}</h4>
                <h4><span class="fw-bold">Fecha de Nacimiento:</span> {{ $patient->birth_date }}</h4>
                <h4><span class="fw-bold">Sexo:</span> {{ $patient->gender }}</h4>
                <h4><span class="fw-bold">Dirección:</span> {{ $patient->adress }}</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <a class="btn btn-primary mt-3" href="{{ route('patients.index') }}">Volver a la lista</a>
        </div>
    </div>
</div>
@endsection
