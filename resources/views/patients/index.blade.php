@extends('layouts.app')

@section('content')
<div class="container-fluid pt-5 patients-index">
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <div class="row mb-3">
            <div class="col-10">
                <h1 class="fw-bold">Lista de Pacientes</h1>
            </div>
            <div class="col-2 text-end">
                <a href="{{ route('patients.create') }}" class="btn btn-primary">Agregar Paciente</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>RUT</th>
                            <th>Nombre</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Género</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->rut }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->birth_date ->format('d-m-Y') }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>{{ $patient->adress }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-info btn-sm">Mostrar</a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

