@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Editar Síntoma</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('symptoms.update', $symptom) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="patient_id" class="form-label">Paciente</label>
            <select name="patient_id" id="patient_id" class="form-control" required>
                <option value="">-- Seleccione Paciente --</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}" @if(old('patient_id', $symptom->patient_id) == $p->id) selected @endif>{{ $p->rut }} - {{ $p->name }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Nombre (opcional)</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $symptom->name) }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $symptom->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="diagnostic_id" class="form-label">Asociar a Diagnóstico (opcional)</label>
            <select name="diagnostic_id" id="diagnostic_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($diagnostics as $diag)
                    <option value="{{ $diag->id }}" @if(in_array($diag->id, $attached ?? [])) selected @endif>#{{ $diag->id }} - {{ $diag->patient->name }} ({{ $diag->date }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Actualizar</button>
    </form>
</div>
@endsection
