@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-visitor" style="background-color: rgba(255, 255, 255, 0.8);">
                <div class="card-header text-center fw-bold card-visitor-header" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Detalle Cita</div>

                <div class="card-body">
                    <p><strong>Paciente:</strong> {{ $appointment->patient ? $appointment->patient->name : '-' }}</p>
                    <p><strong>Fecha:</strong> {{ $appointment->date ? $appointment->date->format('Y-m-d') : '-' }}</p>
                    <p><strong>Hora:</strong> {{ $appointment->time }}</p>
                    <p><strong>Estado:</strong> {{ $appointment->status_label }}</p>
                    <p><strong>Responsable:</strong> {{ $appointment->user ? $appointment->user->name : '-' }}</p>
                    <p><strong>Notas:</strong></p>
                    <p>{{ $appointment->notes }}</p>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('prescriptions.index', ['appointment_id'=>$appointment->id]) }}" class="btn btn-primary">Recetas</a>
                        <a href="{{ route('prescriptions.create', ['appointment_id'=>$appointment->id]) }}" class="btn btn-success">Nueva Receta</a>
                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-secondary">Editar</a>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection