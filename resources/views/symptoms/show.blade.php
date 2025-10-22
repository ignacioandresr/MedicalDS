@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-visitor" style="background-color: rgba(255, 255, 255, 0.8);">
                <div class="card-header text-center fw-bold card-visitor-header" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Detalle del Síntoma</div>

                <div class="card-body">
                    <h4>{{ $symptom->name ?? 'Sin nombre' }}</h4>
                    <p class="text-muted">{{ $symptom->description ?? 'Sin descripción' }}</p>

                    <hr>

                    <p><strong>Paciente:</strong>
                        @if($symptom->patient)
                            {{ $symptom->patient->rut }} - {{ $symptom->patient->name }} {{ $symptom->patient->apellido_paterno }}
                        @else
                            --
                        @endif
                    </p>

                    <p><strong>Diagnósticos asociados:</strong></p>
                    @if($symptom->diagnostics && $symptom->diagnostics->count())
                        <ul>
                            @foreach($symptom->diagnostics as $d)
                                <li>#{{ $d->id }} - {{ $d->description ?? 'Sin descripción' }} ({{ $d->date ?? '' }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No tiene diagnósticos asociados.</p>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('symptoms.edit', $symptom) }}" class="btn btn-secondary">Editar</a>
                        @auth
                        <form action="{{ route('symptoms.destroy', $symptom) }}" method="POST" style="display:inline-block" onsubmit="return confirm('¿Eliminar síntoma? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                        @endauth
                        <a href="{{ route('symptoms.index') }}" class="btn btn-outline-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
