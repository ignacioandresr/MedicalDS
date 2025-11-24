@extends('layouts.app')

@section('content')
<div class="container-fluid support-index pt-5">
    <h1 class="text-center fw-bold mb-4">Editar Ticket de Soporte</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('support.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Asunto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                               id="subject" name="subject" value="{{ old('subject', $ticket->subject) }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioridad <span class="text-danger">*</span></label>
                        <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            <option value="baja" {{ old('priority', $ticket->priority) == 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('priority', $ticket->priority) == 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('priority', $ticket->priority) == 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('priority', $ticket->priority) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                        <div class="mb-3">
                            <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="abierto" {{ old('status', $ticket->status) == 'abierto' ? 'selected' : '' }}>Abierto</option>
                                <option value="en_progreso" {{ old('status', $ticket->status) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                <option value="resuelto" {{ old('status', $ticket->status) == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                <option value="cerrado" {{ old('status', $ticket->status) == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" required>{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                        <div class="mb-3">
                            <label for="admin_response" class="form-label">Respuesta del Administrador</label>
                            <textarea class="form-control @error('admin_response') is-invalid @enderror" 
                                      id="admin_response" name="admin_response" rows="5" 
                                      placeholder="Escriba su respuesta aquí...">{{ old('admin_response', $ticket->admin_response) }}</textarea>
                            @error('admin_response')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Esta respuesta será visible para el usuario que creó el ticket.
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('support.show', $ticket) }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
