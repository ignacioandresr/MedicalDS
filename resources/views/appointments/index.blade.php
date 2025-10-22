@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="fw-bold">Citas</h1>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary">Agregar Cita</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card"></div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>
                                    <td>{{ $a->patient ? $a->patient->name : '-' }}</td>
                                    <td>{{ $a->date ? $a->date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $a->time }}</td>
                                    <td>{{ $a->status_label }}</td>
                                    <td>
                                        <a href="{{ route('appointments.show', $a) }}" class="btn btn-info btn-sm">Mostrar</a>
                                        <a href="{{ route('appointments.edit', $a) }}" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="{{ route('appointments.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar cita?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Eliminar</button>
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