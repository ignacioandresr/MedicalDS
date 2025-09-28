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
            <p class="text-center welcome-message"  style="text-shadow: 2px 2px 4px #000000;">Página princial MedicalDS</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 justify-content-center text-center">
              <a class="btn btn-primary" href="/patients">Agregar Pacientes</a>
        </div>
    </div>
</div>
@endsection
