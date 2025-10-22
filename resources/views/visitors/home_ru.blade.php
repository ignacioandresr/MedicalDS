@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/home_ru.css') }}">
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
                <div class="card text-center" style="background-color: rgba(255,255,255,0.85);">
                <div class="card-header">Тренировка</div>
                <div class="card-body">
                    <p>Добро пожаловать в тренировочный режим. Нажмите кнопку ниже, чтобы начать тренировку по клиническим случаям.</p>
                    <a href="{{ route('visitor.training.ru') }}" class="btn btn-primary">Начать тренировку</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
