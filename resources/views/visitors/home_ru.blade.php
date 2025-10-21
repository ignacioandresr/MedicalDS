@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/home_ru.css') }}">
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-auto">
            <p class="text-center welcome-message"  style="text-shadow: 2px 2px 4px #000000;">Главная страница МедицинскийDS</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 justify-content-center text-center">
            <a class="btn btn-martian" href="/patients">Список пациентов</a>
        </div>
    </div>
    <div class="row py-5 justify-content-center text-center" >
        <div class="col-4 border border-rounded" style="background-color: #C4E1F2;">
            <a class="btn btn-martian mt-3" href="{{ route('symptoms.index') }}">Симптомы</a>
            <div class="p-3">
                <h5>Последние 3 симптома</h5>
                @if(isset($latestSymptoms) && $latestSymptoms->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestSymptoms as $s)
                            <li class="py-1">
                                <strong>{{ $s->name ?? $s->descripcion ?? ($s->title ?? 'Симптом') }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($s->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Нет записей</p>
                @endif
            </div>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn mt-3 btn-martian" href="/diagnostics">Диагнозы</a>
            <div class="p-3">
                <h5>Последние 3 диагноза</h5>
                @if(isset($latestDiagnostics) && $latestDiagnostics->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestDiagnostics as $d)
                            <li class="py-1">
                                <strong>{{ $d->description ?? ($d->descripcion ?? ($d->name ?? 'Диагноз')) }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($d->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Нет записей</p>
                @endif
            </div>
        </div>
        <div class="col-4 border border-rounded border-3" style="background-color: #C4E1F2;">
            <a class="btn mt-3 btn-martian" href="/records">Медицинская история</a>
            <div class="p-3">
                <h5>Последние 3 истории</h5>
                @if(isset($latestRecords) && $latestRecords->count())
                    <ul class="list-unstyled text-start">
                        @foreach($latestRecords as $r)
                            <li class="py-1">
                                <strong>{{ $r->tratamientos ?? ($r->descripcion ?? ($r->title ?? 'Запись')) }}</strong>
                                <br>
                                <small class="text-muted">{{ optional($r->created_at)->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Нет записей</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
