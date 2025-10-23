@extends('layouts.app')

@push('styles')
    <link href="{{ mix('css/home_ru.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="visitor-welcome">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card text-center p-4">
                        <h1 class="display-5 fw-bold">Добро пожаловать, марсианин!</h1>
                        <p class="lead">Вы находитесь в интерфейсе Марсианина — здесь вы можете тренироваться и просматривать клинические случаи.</p>
                        <a href="{{ route('visitor.training.ru') }}" class="btn btn-martian btn-lg mt-3">Начать тренировку</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
