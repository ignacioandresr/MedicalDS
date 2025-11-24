@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3">Nueva Receta</h1>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('prescriptions.store') }}">
                        @csrf
                        @if($appointment)
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <div class="mb-3">
                                <label class="form-label">Cita</label>
                                <div class="form-control">#{{ $appointment->id }} - {{ $appointment->patient?->name }} ({{ $appointment->date?->format('Y-m-d') }})</div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label">Paciente</label>
                                <select name="patient_id" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($patients as $pt)
                                        <option value="{{ $pt->id }}" {{ old('patient_id')==$pt->id?'selected':'' }}>{{ $pt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenido (medicamentos)</label>
                            <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Indicaciones</label>
                            <textarea name="indications" class="form-control" rows="3">{{ old('indications') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('prescriptions.index', $appointment?['appointment_id'=>$appointment->id]:[]) }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection