@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Diagnóstico General</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $item->description }}</h5>
            <p class="card-text"><strong>Fecha:</strong> {{ $item->date ? $item->date->format('Y-m-d') : '' }}</p>
            @if($item->symptoms->count())
                <p><strong>Síntomas:</strong> {{ $item->symptoms->pluck('name')->join(', ') }}</p>
            @endif
            <a href="{{ route('general-diagnostics.edit', $item) }}" class="btn btn-primary">Editar</a>
            @role('admin')
                <form action="{{ route('general-diagnostics.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este diagnóstico general?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Eliminar</button>
                </form>
            @endrole
            <a href="{{ route('general-diagnostics.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection
