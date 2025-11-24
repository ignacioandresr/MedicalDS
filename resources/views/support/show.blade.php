@extends('layouts.app')

@section('content')
<div class="container-fluid support-index pt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <h1 class="fw-bold text-center mb-4">Detalles del Ticket de Soporte</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row mb-3">
            <div class="col-md-6">
                <h6><span class="fw-bold">ID:</span> #{{ $ticket->id }}</h6>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ $ticket->status_badge }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h6><span class="fw-bold">Usuario:</span> {{ $ticket->user ? $ticket->user->name : 'Usuario eliminado' }}</h6>
            </div>
            <div class="col-md-6">
                <h6><span class="fw-bold">Prioridad:</span> 
                    <span class="badge {{ $ticket->priority_badge }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </h6>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <h6><span class="fw-bold">Asunto:</span></h6>
                <p class="ps-3">{{ $ticket->subject }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <h6><span class="fw-bold">Descripción:</span></h6>
                <div class="ps-3 border-start border-3 border-secondary">
                    <p class="ms-2" style="white-space: pre-wrap;">{{ $ticket->description }}</p>
                </div>
            </div>
        </div>

        @if($ticket->admin_response)
            <div class="row mb-3">
                <div class="col-12">
                    <h6><span class="fw-bold text-success">Respuesta del Administrador:</span></h6>
                    <div class="ps-3 border-start border-3 border-success bg-light p-3 rounded">
                        <p class="ms-2 mb-0" style="white-space: pre-wrap;">{{ $ticket->admin_response }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <h6><span class="fw-bold">Fecha de creación:</span> {{ $ticket->created_at ? $ticket->created_at->format('d-m-Y H:i') : 'N/A' }}</h6>
            </div>
            @if($ticket->resolved_at)
                <div class="col-md-6">
                    <h6><span class="fw-bold">Fecha de resolución:</span> {{ $ticket->resolved_at->format('d-m-Y H:i') }}</h6>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-12 d-flex gap-2 justify-content-center">
                <a class="btn btn-primary" href="{{ route('support.index') }}">Volver a la lista</a>
                @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                    <a class="btn btn-secondary" href="{{ route('support.edit', $ticket) }}">Editar</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Eliminar</button>
                @elseif($ticket->user_id === Auth::id())
                    <a class="btn btn-secondary" href="{{ route('support.edit', $ticket) }}">Editar</a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este ticket de soporte? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('support.destroy', $ticket) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
