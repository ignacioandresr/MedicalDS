@extends('layouts.app')

@section('content')
    <div class="container">
    <h3>Crear caso clínico</h3>

        <form action="{{ route('clinical_cases.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input name="title_es" class="form-control @error('title_es') is-invalid @enderror" value="{{ old('title_es') }}" required>
                @error('title_es')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                {{-- idioma oculto: la app administra por defecto en español para creación manual --}}
                <input type="hidden" name="language" value="es">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description_es" class="form-control @error('description_es') is-invalid @enderror">{{ old('description_es') }}</textarea>
                @error('description_es')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Pasos</label>
                <textarea name="steps_es" class="form-control @error('steps_es') is-invalid @enderror">{{ old('steps_es') }}</textarea>
                @error('steps_es')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Solución</label>
                <input name="solution_es" class="form-control @error('solution_es') is-invalid @enderror" value="{{ old('solution_es') }}">
                @error('solution_es')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Opciones (una por línea)</label>
                <textarea name="options_es" class="form-control @error('options_es') is-invalid @enderror" placeholder="Escriba cada opción en una línea">{{ old('options_es') }}</textarea>
                @error('options_es')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Índice de opción correcta</label>
                <select name="correct_index" class="form-control @error('correct_index') is-invalid @enderror">
                    <option value="">-- Seleccionar --</option>
                    @for($i=0;$i<10;$i++)
                        <option value="{{ $i }}" {{ old('correct_index') == (string)$i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <small class="text-muted">El índice empieza en 0 (primera opción = 0).</small>
                @error('correct_index')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary">Crear</button>
        </form>
    </div>
@endsection
