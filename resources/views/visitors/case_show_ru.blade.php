@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card" style="background-color: rgba(255,255,255,0.85);">
            <div class="card-header">{{ $case->title_ru ?: $case->title ?: $case->title_es }}</div>
            <div class="card-body">
                <h5>Описание</h5>
                <p>{{ $case->description_ru ?: $case->description ?: $case->description_es }}</p>

                <h5>Шаги</h5>
                <p>{!! nl2br(e($case->steps_ru ?: $case->steps ?: $case->steps_es)) !!}</p>

                <form method="POST" action="{{ route('visitor.case.attempt', $case) }}">
                    @csrf

                    @php
                        // try to find options in different locale fields if present
                        $options_raw = $case->options_ru ?: $case->options ?: $case->options_es;
                        $options = [];
                        if ($options_raw) {
                            $decoded = @json_decode($options_raw, true);
                            if (is_array($decoded)) {
                                $options = $decoded;
                            } else {
                                // split by newlines, pipe or semicolon
                                $parts = preg_split('/\r\n|\r|\n|\||;/', $options_raw);
                                $parts = array_map('trim', $parts);
                                $parts = array_filter($parts, function($v) { return $v !== ''; });
                                $options = $parts;
                            }
                        }
                    @endphp

                    @if(count($options))
                        <div class="mb-3">
                            <label class="form-label">Выберите ответ</label>
                            @foreach($options as $idx => $opt)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" id="opt{{ $idx }}" value="{{ $opt }}" required>
                                    <label class="form-check-label" for="opt{{ $idx }}">{{ $opt }}</label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label">Ваш ответ</label>
                            <input name="answer" class="form-control" required>
                        </div>
                    @endif

                    <button class="btn btn-primary">Отправить</button>
                </form>

                @if(session('attempt_result'))
                    @if(session('attempt_result') === 'correct')
                        <div class="alert alert-success mt-3">Правильно!</div>
                    @else
                        <div class="alert alert-danger mt-3">Неправильно. Попробуйте еще.</div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection
