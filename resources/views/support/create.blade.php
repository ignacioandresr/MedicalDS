@extends('layouts.app')

@section('content')
<div class="container-fluid support-index pt-5">
    <div class="container">
        <h1 class="mb-4 fw-bold text-center">Crear Ticket de Soporte</h1>
        <div class="border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
            <form action="{{ route('support.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="subject" class="form-label">Asunto <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                           id="subject" name="subject" value="{{ old('subject') }}" 
                           placeholder="Resumen breve del problema" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                    <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                        <option value="" disabled selected>Seleccione la prioridad</option>
                        <option value="baja" {{ old('priority') == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ old('priority') == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción del problema <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="6" 
                              placeholder="Describa el problema en detalle..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Por favor, proporcione la mayor cantidad de detalles posible para ayudarnos a resolver su problema.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Crear Ticket</button>
                    <a href="{{ route('support.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
