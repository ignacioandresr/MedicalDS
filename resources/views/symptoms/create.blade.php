@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Ingresar Síntoma</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('symptoms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="patient_id" class="form-label">Paciente</label>
            <select name="patient_id" id="patient_id" class="form-control" required>
                <option value="">-- Seleccione Paciente --</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}">{{ $p->rut }} - {{ $p->name }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Descripción por Paciente</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Análisis Profesional</label>
            <textarea name="description" id="description" class="form-control" rows="6">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="diagnostic_id" class="form-label">Asociar a Diagnóstico</label>
            <select name="diagnostic_id" id="diagnostic_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($diagnostics as $diag)
                    <option value="{{ $diag->id }}">#{{ $diag->id }} - {{ $diag->patient->name }} ({{ $diag->date }})</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
</div>
@endsection
