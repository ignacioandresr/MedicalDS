@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>{{ __('messages.ingresar_symptom') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('symptoms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="patient_id" class="form-label">{{ __('messages.patient') }}</label>
            <select name="patient_id" id="patient_id" class="form-control" required>
                <option value="">{{ __('messages.select_patient') }}</option>
                @foreach($patients as $p)
                    <option value="{{ $p->id }}">{{ $p->rut }} - {{ $p->name }} {{ $p->apellido_paterno }} {{ $p->apellido_materno }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('messages.patient_description') }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('messages.professional_analysis') }}</label>
            <textarea name="description" id="description" class="form-control" rows="6">{{ old('description') }}</textarea>
        </div>

        <div id="ai_suggestions" class="mb-3"></div>

        <div class="mb-3">
            <label for="diagnostic_id" class="form-label">{{ __('messages.associate_diagnostic') }}</label>
            <select name="diagnostic_id" id="diagnostic_id" class="form-control">
                <option value="">{{ __('messages.none') }}</option>
                @foreach($diagnostics as $diag)
                    <option value="{{ $diag->id }}">#{{ $diag->id }} - {{ $diag->patient->name }} ({{ $diag->date }})</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="general_diagnostic_id" id="general_diagnostic_id" value="">

        <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Debounce helper
    function debounce(fn, ms){ let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn.apply(this,args), ms); }; }

    const desc = document.getElementById('description');
    const patientSelect = document.getElementById('patient_id');
    const suggestionsDiv = document.getElementById('ai_suggestions');
    const suggestUrl = "{{ route('symptoms.suggest') }}";

    async function fetchAISuggestions() {
        const text = desc.value.trim();
        if (!text) { suggestionsDiv.innerHTML = ''; return; }
        const payload = { text };
        const patientId = patientSelect ? patientSelect.value : null;
        if (patientId) payload.patient_id = patientId;

        try {
            const res = await fetch(suggestUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            renderAISuggestions(json.data || []);
        } catch (err) {
            suggestionsDiv.innerHTML = '<div class="alert alert-danger">Error obteniendo sugerencias.</div>';
        }
    }

    function renderAISuggestions(items) {
        if (!items.length) {
            suggestionsDiv.innerHTML = '<div class="alert alert-secondary">{{ addslashes(__('messages.no_suggestions')) }}</div>';
            return;
        }
        let html = '<div class="card"><div class="card-body"><h5>{{ addslashes(__('messages.diagnostics_suggested')) }}</h5><ul class="list-group">';
        items.forEach(it => {
            const conf = it.confidence ? (' - ' + it.confidence) : '';
            const reason = it.reason ? ('<br/><small class="text-muted">' + it.reason + '</small>') : '';
            const gdId = it.general_diagnostic_id || '';
            let actionButtons = '';
                if (gdId) {
                actionButtons += `<a class="btn btn-sm btn-outline-secondary me-1" href="/general-diagnostics/${gdId}" target="_blank" rel="noopener">{{ addslashes(__('messages.view_gd')) }}</a>`;
            }
            actionButtons += `<button type="button" class="btn btn-sm btn-outline-primary" data-id="${it.diagnostic_id || ''}" data-general="${gdId}" data-title="${escapeHtml(it.title)}">{{ addslashes(__('messages.use')) }}</button>`;

            html += `<li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${escapeHtml(it.title)}</strong>${conf}${reason}
                        </div>
                        <div>${actionButtons}</div>
                    </li>`;
        });
        html += '</ul></div></div>';
        suggestionsDiv.innerHTML = html;

        suggestionsDiv.querySelectorAll('button[data-title]').forEach(btn => {
            btn.addEventListener('click', function() {
                const title = this.getAttribute('data-title');
                const diagId = this.getAttribute('data-id');
                const gdId = this.getAttribute('data-general');
                // llenar descripción
                document.getElementById('description').value = title;
                // set hidden general diagnostic id
                const gdField = document.getElementById('general_diagnostic_id');
                if (gdField) gdField.value = gdId || '';
                // si viene diagnostic_id, seleccionarlo en el select; si no, dejar vacío
                const diagSelect = document.getElementById('diagnostic_id');
                if (diagSelect) {
                    if (diagId) {
                        diagSelect.value = diagId;
                    } else {
                        diagSelect.value = '';
                    }
                }
                // enfocar textarea para confirmar
                document.getElementById('description').focus();
            });
        });
    }

    function escapeHtml(s){ return s ? s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;') : ''; }

    desc.addEventListener('input', debounce(fetchAISuggestions, 700));
</script>
@endpush
