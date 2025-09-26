@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Información del Paciente</h1>
    <p>RUT: {{ $patient->rut }}</p>
    <p>Nombre: {{ $patient->name }}</p>
    <p>Fecha de Nacimiento: {{ $patient->birth_date }}</p>
    <p>Sexo: {{ $patient->gender }}</p>
    <p>Dirección: {{ $patient->adress }}</p>


    <a href="{{ route('patients.index') }}">Volver a la lista</a>


</div>
@endsection
