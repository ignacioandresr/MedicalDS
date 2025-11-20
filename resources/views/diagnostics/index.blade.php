
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
        <div class="row mb-3 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold">Lista de Diagnósticos</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('diagnostics.create') }}" class="btn btn-primary me-2">Agregar Diagnóstico</a>
                <a href="{{ route('general-diagnostics.index') }}" class="btn btn-outline-secondary">Diagnósticos Generales</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
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
                                <td>{{ optional($diagnostic->patient)->rut }}</td>
                                <td>{{ $diagnostic->description }}</td>
                                <td>{{ $diagnostic->date ? $diagnostic->date->format('d-m-Y') : '' }}</td>
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
        </div>
    </div>
</div>
@endsection

