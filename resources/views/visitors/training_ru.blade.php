@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
                <div class="col-md-8">
                <div class="card" style="background-color: rgba(255,255,255,0.85);">
                    <div class="card-header">Тренировка: Клинические случаи</div>
                    <div class="card-body" style="background-color: transparent;">
                        <p>Добро пожаловать в тренировочный режим. Здесь вы сможете работать с учебными клиническими случаями без доступа к реальным медицинским данным.</p>
                        <ul>
                            <li><a href="#">Случай 1: Диагностика инфекции</a></li>
                            <li><a href="#">Случай 2: Хроническое заболевание</a></li>
                        </ul>
                        <a href="{{ route('visitor.home.ru') }}" class="btn btn-secondary">Вернуться</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
