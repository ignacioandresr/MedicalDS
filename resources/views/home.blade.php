@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-auto">
            <p class="text-center welcome-message">Página princial MedicalDS</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 justify-content-center text-center">
              <a class="btn btn-primary" href="/patients">Agregar Pacientes</a>
        </div>
    </div>
</div>
@endsection
