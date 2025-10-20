@extends('layouts.app')

@push('styles')
<style>
    body {
        background-image: url("{{ asset('imagenes/principio.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
    }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-auto">
            <p class="text-center welcome-message"  style="text-shadow: 2px 2px 4px #000000;">Página principal MedicalDS</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 justify-content-center text-center">
            <a class="btn btn-primary" href="/patients">Lista de pacientes</a>
        </div>
    </div>
    <div class="row py-5 justify-content-center text-center" >
        <div class="col-4 border border-rounded" style="background-color: #C4E1F2;">
            <a class="btn btn-primary mt-3" href="{{ route('symptoms.index') }}">Síntomas</a>
            <div class="p-3">
                <h5>Últimos 3 síntomas</h5>
                @if(isset($latestSymptoms) && $latestSymptoms->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestSymptoms as $s)
                            <li class="py-1">
                                <strong>{{ $s->name ?? $s->descripcion ?? ($s->title ?? 'Síntoma') }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($s->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn btn-primary mt-3" href="/diagnostics">Diagnósticos</a>
            <div class="p-3">
                <h5>Últimos 3 diagnósticos</h5>
                @if(isset($latestDiagnostics) && $latestDiagnostics->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestDiagnostics as $d)
                            <li class="py-1">
                                <strong>{{ $d->name ?? $d->descripcion ?? ($d->title ?? 'Diagnóstico') }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn btn-primary mt-3" href="/records">Historial Médico</a>
            <div class="p-3">
                <h5>Últimos 3 historiales</h5>
                @if(isset($latestRecords) && $latestRecords->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestRecords as $r)
                            <li class="py-1">
                                <strong>{{ $r->title ?? $r->descripcion ?? ($r->note ?? 'Registro') }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($r->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No hay registros</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
