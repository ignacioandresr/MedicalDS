@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Paciente</h1>
    <form action="{{ route('patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')
        RUT: <input type="text" name="rut" value="{{ $patient->rut }}"><br>
        Nombre: <input type="text" name="name" value="{{ $patient->name }}"><br>
        Fecha de Nacimiento: <input type="date" name="birth_date" birth_date="{{ $patient->birth_date }}"><br>
        Sexo: <input type="text" name="gender" value="{{ $patient->gender }}"><br>
        Dirección: <input type="text" value="{{ $patient->adress }}"><br>
        <button type="submit">Actualizar</button>
    </form>


</div>
@endsection
