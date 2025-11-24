@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3">Editar Receta #{{ $prescription->id }}</h1>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('prescriptions.update',$prescription) }}">
                        @csrf @method('PUT')
                        <p><strong>Paciente:</strong> {{ $prescription->patient?->name }}</p>
                        @if($prescription->appointment)
                            <p><strong>Cita:</strong> <a href="{{ route('appointments.show',$prescription->appointment) }}">#{{ $prescription->appointment->id }}</a></p>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input name="title" class="form-control" value="{{ old('title',$prescription->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenido</label>
                            <textarea name="content" class="form-control" rows="5" required>{{ old('content',$prescription->content) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Indicaciones</label>
                            <textarea name="indications" class="form-control" rows="3">{{ old('indications',$prescription->indications) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('prescriptions.show',$prescription) }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection