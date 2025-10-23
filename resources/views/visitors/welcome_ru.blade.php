@extends('layouts.app')

@push('styles')
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            background-image: url('/imagenes/alien.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .visitor-welcome {
            display: flex;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }
        .visitor-welcome .card {
            background: rgba(0,0,0,0.45);
            border: none;
            color: #fff;
        }
    </style>
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
