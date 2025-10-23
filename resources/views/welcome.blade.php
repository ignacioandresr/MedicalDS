@extends('layouts.app')

@push('styles')
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        background-image: url("{{ asset('imagenes/inicio.jpg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        min-height: 100vh;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row text-center pt-3">
        <p class="col-12 fw-bold fs-1 text-primary" style="text-shadow: 2px 2px 4px #000000;">Bienvenido a MedicalDS</p>
        <p class="col-12 fs-2 text-primary" style="text-shadow: 2px 2px 4px #000000;">Software de gestión médica</p>
    </div>
</div>
@endsection