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
            <a class="btn btn-primary mt-3" href="{{ route('symptoms.create') }}">Ingresar Síntomas</a>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn btn-primary mt-3" href="/diagnostics">Diagnósticos</a>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn btn-primary mt-3" href="/records">Historial médico</a>
            <p style="padding-top:200px;"></p>
        </div>
    </div>
</div>
@endsection
