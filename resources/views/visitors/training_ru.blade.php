@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
                <div class="col-md-8">
                <div class="card" style="background-color: rgba(255,255,255,0.85);">
                    <div class="card-header">{{ __('messages.visitor.training.title') }}</div>
                                    <div class="card-body" style="background-color: transparent;">
                                        <p>{{ __('messages.visitor.training.intro') }}</p>
                                        @if(isset($cases) && $cases->count())
                                            <div class="list-group training-list">
                                                @foreach($cases as $case)
                                                    <a href="{{ route('visitor.case.show', $case) }}" class="list-group-item list-group-item-action">
                                                        {{ $case->title_ru ?: $case->title_es ?: $case->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="training-fallback">
                                                <a href="#" class="visitor-link">{{ __('messages.visitor.training.no_cases_case1') }}</a><br>
                                                <a href="#" class="visitor-link">{{ __('messages.visitor.training.no_cases_case2') }}</a>
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            <a href="{{ route('visitor.home.ru') }}" class="btn btn-martian">{{ __('messages.visitor.training.back_btn') }}</a>
                                        </div>
                                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
