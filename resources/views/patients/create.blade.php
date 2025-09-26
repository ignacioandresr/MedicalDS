@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Agrega un Paciente</h1>
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="rut" class="form-label">RUT</label>
            <input type="text" class="form-control" id="rut" name="rut">
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="mb-3">
            <label for="birth_date" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Sexo</label>
            <input type="text" class="form-control" id="gender" name="gender">
        </div>
        <div class="mb-3">
            <label for="adress" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="adress" name="adress">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

</div>
@endsection
