@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: rgba(255,255,255,0.85)">
                <div class="card-header fw-bold">Receta #{{ $prescription->id }}</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <p><strong>Título:</strong> {{ $prescription->title }}</p>
                    <p><strong>Paciente:</strong> {{ $prescription->patient?->name }}</p>
                    @if($prescription->appointment)
                        <p><strong>Cita:</strong> <a href="{{ route('appointments.show',$prescription->appointment) }}">#{{ $prescription->appointment->id }} ({{ $prescription->appointment->date?->format('Y-m-d') }})</a></p>
                    @endif
                    <p><strong>Contenido:</strong></p>
                    <div class="mb-3">{!! nl2br(e($prescription->content)) !!}</div>
                    @if($prescription->indications)
                        <p><strong>Indicaciones:</strong></p>
                        <div class="mb-3">{!! nl2br(e($prescription->indications)) !!}</div>
                    @endif
                    <p class="text-muted">Creada: {{ $prescription->created_at->format('Y-m-d H:i') }} por {{ $prescription->user?->name }}</p>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('prescriptions.edit',$prescription) }}" class="btn btn-secondary">Editar</a>
                        <a href="{{ route('prescriptions.index', $prescription->appointment?['appointment_id'=>$prescription->appointment->id]:[]) }}" class="btn btn-outline-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection