@extends('layouts.app')

@push('styles')
    <link href="{{ mix('css/home.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row text-center pt-3">
        <p class="col-12 fw-bold fs-1 text-primary" style="text-shadow: 2px 2px 4px #000000;">Bienvenido a MedicalDS</p>
        <p class="col-12 fs-2 text-primary" style="text-shadow: 2px 2px 4px #000000;">Software de gestión médica</p>
    </div>
</div>
@endsection