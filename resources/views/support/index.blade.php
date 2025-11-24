@extends('layouts.app')

@section('content')
<div class="container-fluid pt-5 support-index">
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
    <div class="container">
        <div class="row mb-3">
            <div class="col-10">
                <h1 class="fw-bold">Tickets de Soporte</h1>
            </div>
            <div class="col-2 text-end">
                <a href="{{ route('support.create') }}" class="btn btn-primary">Nuevo Ticket</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($tickets->isEmpty())
                    <div class="alert alert-info text-center">
                        No hay tickets de soporte registrados.
                    </div>
                @else
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                    <th>Usuario</th>
                                @endif
                                <th>Asunto</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                        <td>{{ $ticket->user ? $ticket->user->name : 'Usuario eliminado' }}</td>
                                    @endif
                                    <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $ticket->priority_badge }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $ticket->status_badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at ? $ticket->created_at->format('d-m-Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('support.show', $ticket) }}" class="btn btn-primary btn-sm">Ver</a>
                                        @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                            <a href="{{ route('support.edit', $ticket) }}" class="btn btn-secondary btn-sm">Editar</a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ticket->id }}">Eliminar</button>
                                        @elseif($ticket->user_id === Auth::id())
                                            <a href="{{ route('support.edit', $ticket) }}" class="btn btn-secondary btn-sm">Editar</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modales de confirmación de eliminación -->
@foreach($tickets as $ticket)
    <div class="modal fade" id="deleteModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $ticket->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel{{ $ticket->id }}">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el ticket #{{ $ticket->id }} - "{{ Str::limit($ticket->subject, 50) }}"? Esta acción no se puede deshacer.
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
@endforeach
@endsection
