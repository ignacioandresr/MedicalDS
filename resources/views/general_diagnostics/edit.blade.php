@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Editar Diagnóstico General</h1>

    <form action="{{ route('general-diagnostics.update', $general_diagnostic) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $general_diagnostic->description) }}" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Fecha</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $general_diagnostic->date ? $general_diagnostic->date->format('Y-m-d') : '') }}">
        </div>
        <div class="mb-3">
            <label for="symptoms" class="form-label">Síntomas relacionados</label>
            <select name="symptoms[]" id="symptoms" class="form-control" multiple>
                @foreach($symptoms as $s)
                    <option value="{{ $s->id }}" @if(in_array($s->id, $attached)) selected @endif>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a class="btn btn-secondary" href="{{ route('general-diagnostics.index') }}">Cancelar</a>
    </form>
</div>
@endsection
