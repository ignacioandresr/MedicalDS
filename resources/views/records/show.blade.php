@extends('layouts.app')

@section('content')
<div class="container-fluid records-index pt-5">
    <h1 class="fw-bold text-center mb-4">Historial Médico</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                    <h6><span class="fw-bold">Paciente RUT:</span> {{ optional($record->patient)->rut ?? '' }}</h6>
                <h6><span class="fw-bold">Nombre:</span> {{ $record->patient->name ?? '' }}</h6>
                <h6><span class="fw-bold">Apellido Paterno:</span> {{ $record->patient->apellido_paterno ?? '' }}</h6>
                <h6><span class="fw-bold">Apellido Materno:</span> {{ $record->patient->apellido_materno ?? '' }}</h6>
                <h6><span class="fw-bold">Fecha de Nacimiento:</span> {{ $record->patient->birth_date ?? '' }}</h6>
                <h6><span class="fw-bold">Género:</span> {{ $record->patient->gender ?? '' }}</h6>
                <h6><span class="fw-bold">Dirección:</span> {{ $record->patient->adress ?? '' }}</h6>
                    <h6><span class="fw-bold">Diagnóstico:</span> {{ optional($record->diagnostic)->description ?? 'Sin diagnóstico' }}</h6>
                <h6><span class="fw-bold">Vacunas:</span>
                    @if($record->enfermedades && $record->enfermedades->count())
                        {{ $record->enfermedades->pluck('name')->join(', ') }}
                    @else
                        Sin Vacuna
                    @endif
                </h6>
                <h6><span class="fw-bold">Alergias:</span>
                    @if($record->alergias && $record->alergias->count())
                        {{ $record->alergias->pluck('name')->join(', ') }}
                    @else
                        Sin Alergia
                    @endif
                </h6>
                <h6><span class="fw-bold">Cirugías:</span>
                    @if($record->cirugias && $record->cirugias->count())
                        {{ $record->cirugias->pluck('name')->join(', ') }}
                    @else
                        Sin Cirugía
                    @endif
                </h6>
                <h6><span class="fw-bold">Medicamentos:</span>
                    @if(!empty($record->medicamentos))
                        {{ $record->medicamentos }}
                    @else
                        Sin Medicamento
                    @endif
                </h6>
                <h6><span class="fw-bold">Antecedentes de Salud:</span> {{ !empty($record->antecedentes_salud) ? $record->antecedentes_salud : 'Sin antecedentes' }}</h6>
                <h6><span class="fw-bold">Fecha:</span> {{ $record->fecha }}</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12 justify-content-center text-center">
                <a class="btn btn-primary mt-3" href="{{ route('records.index') }}">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
@endsection
