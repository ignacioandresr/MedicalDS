@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Agrega un Paciente</h1>
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        RUT: <input type="text" name="rut"><br>
        Nombre: <input type="text" name="name"><br>
        Fecha de Nacimiento: <input type="date" name="birth_date"><br>
        Sexo: <input type="text" name="gender"><br>
        Dirección: <input type="text" name="adress"><br>
        <button type="submit">Guardar</button>
    </form>

</div>
@endsection
