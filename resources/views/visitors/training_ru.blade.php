@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
                <div class="col-md-8">
                <div class="card" style="background-color: rgba(255,255,255,0.85);">
                    <div class="card-header">Тренировка: Клинические случаи</div>
                                    <div class="card-body" style="background-color: transparent;">
                                        <p>Добро пожаловать в тренировочный режим. Здесь вы сможете работать с учебными клиническими случаями без доступа к реальным медицинским данным.</p>
                                        @if(isset($cases) && $cases->count())
                                            <div class="list-group training-list">
                                                @foreach($cases as $case)
                                                    <a href="{{ route('visitor.case.show', $case) }}" class="list-group-item list-group-item-action visitor-link">{{ $case->title_ru ?: $case->title_es ?: $case->title }}</a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="training-fallback">
                                                <a href="#" class="visitor-link">Случай 1: Диагностика инфекции</a><br>
                                                <a href="#" class="visitor-link">Случай 2: Хроническое заболевание</a>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <a href="{{ route('visitor.home.ru') }}" class="btn btn-martian">Вернуться</a>
                                        </div>
                                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
