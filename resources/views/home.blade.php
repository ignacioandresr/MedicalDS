@extends('layouts.app')

@push('styles')
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        background-image: url("{{ asset('imagenes/principio.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 100vh; /* ensures background fills the viewport */
        background-color: #c4e1f2; /* soft fallback while image loads */
    }
    .semi-transparent {
        background-color: rgba(196, 225, 242, 0.8);
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto">
            <p class="text-center welcome-message"  style="text-shadow: 2px 2px 4px #000000;">Página principal MedicalDS</p>
        </div>
    </div>
    <div class="row">
        <div class="col-6 justify-content-center text-center">
            <a class="btn btn-primary" href="/patients">Lista de pacientes</a>
        </div>
        <div class="col-6 justify-content-center text-center">
        @role('admin')
            <a class="btn btn-primary" href="{{ route('clinical_cases.index') }}">Casos Clínicos</a>
        @endrole
            
        </div>
    </div>
    <div class="row py-5 justify-content-center text-center" >
        <div class="col-6 border border-rounded semi-transparent mb-3">
            <a class="btn btn-primary mt-3" href="{{ route('symptoms.index') }}">Síntomas</a>
            <div class="p-3">
                <h5>Últimos 3 síntomas</h5>
                @if(isset($latestSymptoms) && $latestSymptoms->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestSymptoms as $s)
                            <li class="py-1">
                                <a href="{{ route('symptoms.show', $s) }}" class="text-decoration-none text-body">
                                    <strong>{{ $s->name ?? $s->descripcion ?? ($s->title ?? 'Síntoma') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ optional($s->created_at)->format('d/m/Y H:i') }}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
        <div class="col-6 border border-rounded border-3 semi-transparent mb-3">
            <a class="btn btn-primary mt-3" href="/diagnostics">Diagnósticos</a>
            <div class="p-3">
                <h5>Últimos 3 diagnósticos</h5>
                @if(isset($latestDiagnostics) && $latestDiagnostics->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestDiagnostics as $d)
                            <li class="py-1">
                                <a href="{{ route('diagnostics.show', $d) }}" class="text-decoration-none text-body">
                                    <strong>{{ $d->description ?? ($d->descripcion ?? ($d->name ?? 'Diagnóstico')) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
        <div class="col-6 border border-rounded border-3 semi-transparent">
            <a class="btn btn-primary mt-3" href="/records">Historial Médico</a>
            <div class="p-3">
                <h5>Últimos 3 historiales</h5>
                @if(isset($latestRecords) && $latestRecords->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestRecords as $r)
                            <li class="py-1">
                                <a href="{{ route('records.show', $r) }}" class="text-decoration-none text-body">
                                    <strong>{{ $r->antecedentes_salud ?? ($r->descripcion ?? ($r->title ?? 'Registro')) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ optional($r->created_at)->format('d/m/Y H:i') }}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
        <div class="col-6 border border-rounded border-3 semi-transparent">
            <a class="btn btn-primary mt-3" href="{{ route('appointments.index') }}">Citas</a>
            <div class="p-3">
                <h5>Últimas 3 citas</h5>
                @if(isset($latestAppointments) && $latestAppointments->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestAppointments as $a)
                            <li class="py-1">
                                <a href="{{ route('appointments.show', $a) }}" class="text-decoration-none text-body">
                                    <strong>{{ $a->patient ? ($a->patient->name) : 'Paciente' }} - {{ $a->date }} {{ $a->time ?? '' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ optional($a->created_at)->format('d/m/Y H:i') }}</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay citas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
