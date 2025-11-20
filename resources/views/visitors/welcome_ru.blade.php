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
                        <h1 class="display-5 fw-bold">{{ __('messages.visitor.welcome.title') }}</h1>
                            <p class="lead">{{ __('messages.visitor.welcome.lead') }}</p>
                            <a href="{{ route('visitor.training.ru') }}" class="btn btn-martian btn-lg mt-3">{{ __('messages.visitor.welcome.start') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
