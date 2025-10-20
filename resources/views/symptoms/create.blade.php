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
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="diagnostic_id" class="form-label">Asociar a Diagnóstico (opcional)</label>
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
