@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h1 class="h3 mb-0">{{ __('messages.general_diagnostics') }}</h1>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('general-diagnostics.create') }}" class="btn btn-primary">{{ __('messages.new_general_diagnostic') }}</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input id="gd-search" type="text" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" aria-label="{{ __('messages.search') }}">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">{{ __('messages.showing_x', ['count' => $items->total()]) }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" id="gd-cards">
        @foreach($items as $it)
            <div class="col gd-item" data-search="{{ e(strtolower($it->description . ' ' . $it->symptoms->pluck('name')->join(' '))) }}">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($it->description, 80) }}</h5>
                        <p class="card-text mb-2 text-muted small">
                            <strong>{{ __('messages.date') }}:</strong> {{ $it->date ? $it->date->format('Y-m-d') : '—' }}
                        </p>
                        @if($it->symptoms->count())
                            <p class="mb-2 small"><strong>{{ __('messages.symptoms') }}:</strong> {{ $it->symptoms->pluck('name')->join(', ') }}</p>
                        @endif

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('general-diagnostics.show', $it) }}" class="btn btn-sm btn-outline-primary me-1" title="{{ __('messages.view') }}" aria-label="{{ __('messages.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('general-diagnostics.edit', $it) }}" class="btn btn-sm btn-outline-secondary me-1" title="{{ __('messages.edit') }}" aria-label="{{ __('messages.edit') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @role('admin')
                                    <form action="{{ route('general-diagnostics.destroy', $it) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este diagnóstico general?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="{{ __('messages.delete') }}" aria-label="{{ __('messages.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endrole
                            </div>
                            <small class="text-muted">ID: {{ $it->id }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @php
        $current = $items->currentPage();
        $last = $items->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp

    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="{{ __('messages.pagination_navigation') }}">
            <ul class="pagination gd-pagination mb-0">
                @if($items->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                @if($start > 1)
                    <li class="page-item"><a class="page-link" href="{{ $items->url(1) }}">1</a></li>
                    @if($start > 2)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif
                    <li class="page-item"><a class="page-link" href="{{ $items->url($last) }}">{{ $last }}</a></li>
                @endif

                @if($items->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">&raquo;</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@push('styles')
<style>
    /* Styles scoped to the general-diagnostics compact paginator */
    .gd-pagination .page-link {
        padding: .18rem .36rem;
        font-size: .8rem;
        min-width: 2.2rem;
        text-align: center;
    }
    .gd-pagination .page-item:first-child .page-link,
    .gd-pagination .page-item:last-child .page-link {
        padding-left: .24rem;
        padding-right: .24rem;
    }
    .gd-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
    function debounce(fn, ms){ let t; return function(...args){ clearTimeout(t); t = setTimeout(()=> fn.apply(this,args), ms); }; }

    const input = document.getElementById('gd-search');
    const cards = Array.from(document.querySelectorAll('.gd-item'));

    function filterCards() {
        const q = input.value.trim().toLowerCase();
        if (!q) {
            cards.forEach(c => c.style.display = '');
            return;
        }
        cards.forEach(c => {
            const hay = c.getAttribute('data-search') || '';
            c.style.display = hay.includes(q) ? '' : 'none';
        });
    }

    input.addEventListener('input', debounce(filterCards, 250));
</script>
@endpush

@endsection
