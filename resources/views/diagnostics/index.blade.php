
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1 class="fw-bold">Diagnosticos</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('diagnostics.create') }}" class="btn btn-primary">Diagnostico Adicional</a>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Rut Paciente</th>
                <th>Descripcion</th>
                <th>Fecha</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            @foreach($diagnostics as $diagnostic)
                <tr>
                    <td>{{ $diagnostic->id }}</td>
                    <td>{{ $diagnostic->patient->rut }}</td>
                    <td>{{ $diagnostic->description }}</td>
                    <td>{{ $diagnostic->date->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('diagnostics.show', $diagnostic) }}" class="btn btn-primary btn-sm">Mostrar</a>
                        <a href="{{ route('diagnostics.edit', $diagnostic) }}" class="btn btn-secondary btn-sm">Editar</a>
                        @role('admin')
                        <form action="{{ route('diagnostics.destroy', $diagnostic) }}" method="POST" style="display:inline;">
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
@endsection



@extends('layouts.app')

@section('content')
<div class="container-fluid pt-5 diagnostics-index">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container">
        <div class="row mb-3">
            <div class="col-10">
                <h1 class="fw-bold">Lista de Diagnósticos</h1>
            </div>
            <div class="col-2 text-end">
                <a href="{{ route('diagnostics.create') }}" class="btn btn-primary">Agregar Diagnóstico</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12" >
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>RUT Paciente</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diagnostics as $diagnostic)
                            <tr>
                                <td>{{ $diagnostic->id }}</td>
                                <td>{{ $diagnostic->patient->rut }}</td>
                                <td>{{ $diagnostic->description }}</td>
                                <td>{{ $diagnostic->date->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('diagnostics.show', $diagnostic) }}" class="btn btn-primary btn-sm">Mostrar</a>
                                    <a href="{{ route('diagnostics.edit', $diagnostic) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    <form action="{{ route('diagnostics.destroy', $diagnostic) }}" method="POST" style="display:inline;">
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

