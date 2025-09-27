@extends('layouts.app')

@section('content')
<div class="container-fluid patients-index pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h1 class="text-center fw-bold">Editar Paciente</h1>
                <form action="{{ route('patients.update', $patient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT:</label>
                        <input type="text" class="form-control" id="rut" name="rut" value="{{ $patient->rut }}">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $patient->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ \Carbon\Carbon::parse($patient->birth_date)->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Sexo:</label>
                        <input type="text" class="form-control" id="gender" name="gender" value="{{ $patient->gender }}">
                    </div>
                    <div class="mb-3">
                        <label for="adress" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="adress" name="adress" value="{{ $patient->adress }}">
                    </div>
                    <button type="submit" class="btn btn-primary text-center">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
