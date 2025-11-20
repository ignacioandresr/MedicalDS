@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Nuevo Diagnóstico General</h1>

    <form action="{{ route('general-diagnostics.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <input type="text" name="description" id="description" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Fecha</label>
            <input type="date" name="date" id="date" class="form-control">
        </div>
        <div class="mb-3">
            <label for="symptoms" class="form-label">Síntomas relacionados</label>
            <select name="symptoms[]" id="symptoms" class="form-control" multiple>
                @foreach($symptoms as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Guardar</button>
        <a class="btn btn-secondary" href="{{ route('general-diagnostics.index') }}">Cancelar</a>
    </form>
</div>
@endsection
