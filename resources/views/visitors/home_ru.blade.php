@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/home_ru.css') }}">
@endpush

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
                <div class="card text-center" style="background-color: rgba(255,255,255,0.85);">
                <div class="card-header">{{ __('messages.visitor.training.header') }}</div>
                <div class="card-body">
                    <p>{{ __('messages.visitor.training.welcome') }}</p>
                    <a href="{{ route('visitor.training.ru') }}" class="btn btn-primary">{{ __('messages.visitor.training.start_btn') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
