@extends('layouts.app')

@section('content')
<div class="container-fluid diagnostics-index pt-5">
    <div class="container">
        <h1 class="mb-4 fw-bold text-center">Agrega un Diagnóstico</h1>
        <div class="border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
            <form action="{{ route('diagnostics.store') }}" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <label for="patient_rut" class="form-label">RUT Paciente</label>
                    <input type="text" class="form-control mb-2" id="searchPatient" name="patient_rut" placeholder="Buscar por RUT o nombre..." autocomplete="off" required>
                    <ul class="list-group position-absolute w-100" id="autocompleteList" style="z-index:1000; max-height:200px; overflow-y:auto;"></ul>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="date_diagnostic" name="date">
                </div>
                <div class="mb-3">
                    <label for="symptoms" class="form-label">Síntomas (mantener Ctrl para seleccionar varios)</label>
                    <select name="symptoms[]" id="symptoms" class="form-control" multiple>
                        @foreach($symptoms as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} @if($s->description) - {{ $s->description }} @endif</option>
                        @endforeach
                    </select>
                    <div id="suggestions" class="mt-3"></div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const patients = [
        @foreach($patients as $patient)
            {
                rut: "{{ $patient->rut }}",
                nombre: "{{ $patient->name }} {{ $patient->apellido_paterno }} {{ $patient->apellido_materno }}"
            },
        @endforeach
    ];

    const input = document.getElementById('searchPatient');
    const list = document.getElementById('autocompleteList');
    const symptomsSelect = document.getElementById('symptoms');
    const suggestionsDiv = document.getElementById('suggestions');
    const suggestUrl = "{{ route('diagnostics.suggest') }}";

    function formatRut(rut) {
        rut = rut.replace(/[^0-9kK]/g, '');
        if (rut.length < 2) return rut;
        var cuerpo = rut.slice(0, -1);
        var dv = rut.slice(-1);
        cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return cuerpo + '-' + dv;
    }

    input.addEventListener('input', function() {
        let value = input.value;
        if (/^[0-9kK]+$/.test(value.replace(/\./g, '').replace('-', ''))) {
            let raw = value.replace(/[^0-9kK]/g, '');
            input.value = formatRut(raw);
            value = input.value;
        }
        const search = value.replace(/\./g, '').replace('-', '').toLowerCase();
        list.innerHTML = '';
        if (value.length === 0) {
            list.style.display = 'none';
            return;
        }
        const results = patients.filter(p => p.rut.replace(/\./g, '').replace('-', '').toLowerCase().includes(search) || p.nombre.toLowerCase().includes(value.toLowerCase()));
        results.forEach(p => {
            const item = document.createElement('li');
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${formatRut(p.rut)} - ${p.nombre}`;
            item.onclick = function() {
                input.value = formatRut(p.rut);
                list.innerHTML = '';
                list.style.display = 'none';
            };
            list.appendChild(item);
        });
        list.style.display = results.length ? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.innerHTML = '';
            list.style.display = 'none';
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        input.value = input.value.replace(/[^0-9kK]/g, '');
    });

    // Fetch suggestions cuando cambian los síntomas seleccionados
    async function fetchSuggestions() {
        const selected = Array.from(symptomsSelect.selectedOptions).map(o => o.value);
        if (!selected.length) {
            suggestionsDiv.innerHTML = '';
            return;
        }
        const params = new URLSearchParams();
        selected.forEach(id => params.append('symptoms[]', id));
        try {
            const res = await fetch(suggestUrl + '?' + params.toString(), { headers: { 'Accept': 'application/json' }});
            const json = await res.json();
            renderSuggestions(json.data || []);
        } catch (err) {
            suggestionsDiv.innerHTML = '<div class="alert alert-danger">Error al obtener sugerencias.</div>';
        }
    }

    function renderSuggestions(items) {
        if (!items.length) {
            suggestionsDiv.innerHTML = '<div class="alert alert-secondary">No se encontraron diagnósticos para los síntomas seleccionados.</div>';
            return;
        }
        let html = '<div class="card"><div class="card-body"><h5>Sugerencias de diagnóstico</h5><ul class="list-group">';
        items.forEach(i => {
            const matched = i.matched_symptoms.join(', ');
            html += `<li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${i.description}</strong><br/>
                            <small class="text-muted">Coincidencias: ${i.matched_count}/${i.total_symptoms} — ${matched}</small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-desc="${escapeHtml(i.description)}">Usar</button>
                        </div>
                    </li>`;
        });
        html += '</ul></div></div>';
        suggestionsDiv.innerHTML = html;

        // agregar manejador a botones "Usar"
        suggestionsDiv.querySelectorAll('button[data-desc]').forEach(btn => {
            btn.addEventListener('click', function() {
                const desc = this.getAttribute('data-desc');
                document.getElementById('description').value = desc;
            });
        });
    }

    function escapeHtml(text) {
        return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    symptomsSelect.addEventListener('change', fetchSuggestions);
    // También cargar sugerencias si hay preseleccionados (por ejemplo edición futura)
    document.addEventListener('DOMContentLoaded', function() {
        fetchSuggestions();
    });
</script>
@endpush

@endsection
