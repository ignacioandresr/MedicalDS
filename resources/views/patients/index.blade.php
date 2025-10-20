@extends('layouts.app')

@section('content')
<div class="container-fluid pt-5 patients-index">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            <div class="col-12" >
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>RUT</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Sexo</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->rut }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->apellido_paterno }}</td>
                                <td>{{ $patient->apellido_materno }}</td>
                                <td>{{ $patient->birth_date ->format('d-m-Y') }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>{{ $patient->adress }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-primary btn-sm">Mostrar</a>
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    @role('admin')
                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                    @endrole
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
