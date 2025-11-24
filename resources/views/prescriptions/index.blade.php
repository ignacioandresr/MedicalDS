@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="fw-bold">Recetas Médicas</h1>
                <div>
                    <a href="{{ route('prescriptions.create', request()->only('appointment_id')) }}" class="btn btn-primary">Nueva Receta</a>
                    @if($currentAppointment)
                        <a href="{{ route('appointments.show',$currentAppointment) }}" class="btn btn-outline-secondary">Volver a Cita</a>
                    @endif
                </div>
            </div>
            @if($currentAppointment)
                <div class="alert alert-info mb-3">
                    Cita #{{ $currentAppointment->id }} - Paciente: {{ $currentAppointment->patient?->name }} ({{ $currentAppointment->date?->format('Y-m-d') }})
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Paciente</th>
                                <th>Cita</th>
                                <th>Creada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($prescriptions as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->title }}</td>
                                <td>
                                    @if($p->patient)
                                        {{ $p->patient->name }} {{ $p->patient->apellido_paterno ?? '' }} {{ $p->patient->apellido_materno ?? '' }}
                                    @else
                                        <span class="text-muted">Sin paciente</span>
                                    @endif
                                </td>
                                <td>{{ $p->appointment?->date?->format('Y-m-d') }}</td>
                                <td>{{ $p->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('prescriptions.show',$p) }}" class="btn btn-info btn-sm">Mostrar</a>
                                    <a href="{{ route('prescriptions.edit',$p) }}" class="btn btn-secondary btn-sm">Editar</a>
                                    <form action="{{ route('prescriptions.destroy',$p) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar receta?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Sin recetas</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection