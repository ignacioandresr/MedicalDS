@extends('layouts.app')

@section('content')
<div class="container-fluid records-index pt-5">
    <h1 class="text-center fw-bold mb-4">Ingresar Historial Médico</h1>
    <div class="container border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Enfermedades eliminado del formulario (campo vacío sin uso) --}}
                <form action="{{ route('records.update', $record) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 position-relative">
                        <label for="searchPatient" class="form-label">Paciente</label>
                        <input type="text" class="form-control mb-2" id="searchPatient" name="patient_rut" placeholder="Buscar por RUT, nombre o apellidos..." autocomplete="off" required value="{{ isset($record->patient) ? (preg_replace('/[^0-9kK]/', '', $record->patient->rut) ? $record->patient->rut . ' - ' . $record->patient->nombre . ' ' . $record->patient->apellido_paterno . ' ' . $record->patient->apellido_materno : '') : old('patient_rut') }}">
                        <ul class="list-group position-absolute w-100" id="autocompleteList" style="z-index:1000; max-height:200px; overflow-y:auto;"></ul>
                    </div>
                    <input type="hidden" id="patient_id" name="patient_id" value="{{ old('patient_id', $record->patient_id ?? '') }}">
                    <div class="mb-3 position-relative">
                        <label for="searchDiagnostic" class="form-label">Diagnóstico</label>
                        <input type="text" class="form-control mb-2" id="searchDiagnostic" name="searchDiagnostic" placeholder="Buscar diagnóstico por descripción..." autocomplete="off" value="{{ optional($record->diagnostic)->description ?? old('searchDiagnostic') }}">
                        <ul class="list-group position-absolute w-100" id="autocompleteDiagnosticList" style="z-index:1000; max-height:200px; overflow-y:auto;"></ul>
                    </div>
                    <input type="hidden" id="diagnostic_id" name="diagnostic_id" value="{{ old('diagnostic_id', $record->diagnostic_id ?? '') }}">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="no_diagnostic" name="no_diagnostic" {{ empty($record->diagnostic) ? 'checked' : '' }}>
                        <label class="form-check-label" for="no_diagnostic">Sin diagnóstico</label>
                    </div>
@push('scripts')
<script>
window.MDS = window.MDS || {};
if (!window.MDS.recordsAutocompleteInitialized) {
    window.MDS.recordsAutocompleteInitialized = true;

    window.MDS.patients = window.MDS.patients || [
        @foreach($patients as $patient)
            {
                id: "{{ $patient->id }}",
                rut: "{{ $patient->rut }}",
                nombre: "{{ $patient->nombre }} {{ $patient->apellido_paterno }} {{ $patient->apellido_materno }}"
            },
        @endforeach
    ];

    window.MDS.diagnostics = window.MDS.diagnostics || [
        @foreach($diagnostics as $diagnostic)
            {
                id: "{{ $diagnostic->id }}",
                descripcion: "{{ $diagnostic->description }}",
                patient_rut: "{{ $diagnostic->patient ? $diagnostic->patient->rut : '' }}"
            },
        @endforeach
    ];

    document.addEventListener('DOMContentLoaded', function() {
        const patients = window.MDS.patients;
        const diagnostics = window.MDS.diagnostics;
        const input = document.getElementById('searchPatient');
        const list = document.getElementById('autocompleteList');
        const hiddenId = document.getElementById('patient_id');

        function formatRut(rut) {
            rut = rut.replace(/[^0-9kK]/g, '');
            if (rut.length < 2) return rut;
            var cuerpo = rut.slice(0, -1);
            var dv = rut.slice(-1);
            cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return cuerpo + '-' + dv;
        }

        if (input && list && hiddenId) {
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
                    hiddenId.value = '';
                    return;
                }
                const results = patients.filter(p => p.rut.replace(/\./g, '').replace('-', '').toLowerCase().includes(search) || p.nombre.toLowerCase().includes(value.toLowerCase()));
                results.forEach(p => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item list-group-item-action bg-secondary text-dark';
                    item.innerHTML = `<strong>${formatRut(p.rut)}</strong> - ${p.nombre}`;
                    item.onclick = function() {
                        input.value = formatRut(p.rut);
                        hiddenId.value = p.id;
                        list.innerHTML = '';
                        list.style.display = 'none';
                    };
                    list.appendChild(item);
                });
                list.style.display = results.length ? 'block' : 'none';
            });

            input.addEventListener('blur', function() {
                const value = input.value.trim().toLowerCase();
                let found = null;
                const normalizedInputRut = value.replace(/\./g, '').replace('-', '');
                found = patients.find(p => p.rut.replace(/\./g, '').replace('-', '').toLowerCase() === normalizedInputRut);
                if (!found) {
                    found = patients.find(p => p.nombre.toLowerCase().includes(value));
                }
                if (found) {
                    hiddenId.value = found.id;
                } else {
                    hiddenId.value = '';
                }
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !list.contains(e.target)) {
                    list.innerHTML = '';
                    list.style.display = 'none';
                }
            });
        }

        const diagnosticInput = document.getElementById('searchDiagnostic');
        const diagnosticList = document.getElementById('autocompleteDiagnosticList');
        const hiddenDiagnosticId = document.getElementById('diagnostic_id');

        if (diagnosticInput && diagnosticList && hiddenDiagnosticId) {
            diagnosticInput.addEventListener('input', function() {
                const value = diagnosticInput.value.toLowerCase();
                diagnosticList.innerHTML = '';
                const patientValue = input ? input.value.trim() : '';
                let patientRut = '';
                const rutMatch = patientValue.match(/(\d{1,2}\.\d{3}\.\d{3}-[\dkK])/);
                if (rutMatch) {
                    patientRut = rutMatch[1];
                } else {
                    patientRut = patientValue;
                }
                const normalizedPatientRut = patientRut.replace(/\.|-/g, '').toLowerCase();
                let filteredDiagnostics;
                if (!normalizedPatientRut) {
                    filteredDiagnostics = diagnostics.filter(d => d.descripcion.toLowerCase().includes(value));
                } else {
                    filteredDiagnostics = diagnostics.filter(d => {
                        const normalizedDiagnosticRut = (d.patient_rut || '').replace(/\.|-/g, '').toLowerCase();
                        return normalizedDiagnosticRut === normalizedPatientRut && d.descripcion.toLowerCase().includes(value);
                    });
                }
                if (filteredDiagnostics.length === 0) {
                    const item = document.createElement('li');
                    item.className = 'list-group-item list-group-item-danger bg-secondary text-dark';
                    item.textContent = 'No hay diagnósticos para este paciente.';
                    diagnosticList.appendChild(item);
                    hiddenDiagnosticId.value = '';
                } else {
                    filteredDiagnostics.forEach(d => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item list-group-item-action bg-secondary text-dark';
                        item.textContent = d.descripcion;
                        item.onclick = function() {
                            diagnosticInput.value = d.descripcion;
                            hiddenDiagnosticId.value = d.id;
                            diagnosticList.innerHTML = '';
                            diagnosticList.style.display = 'none';
                        };
                        diagnosticList.appendChild(item);
                    });
                }
                diagnosticList.style.display = filteredDiagnostics.length ? 'block' : 'none';
            });

            diagnosticInput.addEventListener('blur', function() {
                const value = diagnosticInput.value.trim().toLowerCase();
                const patientValue = input ? input.value.trim() : '';
                let patientRut = '';
                const rutMatch = patientValue.match(/(\d{1,2}\.\d{3}\.\d{3}-[\dkK])/);
                if (rutMatch) {
                    patientRut = rutMatch[1];
                } else {
                    patientRut = patientValue;
                }
                const normalizedPatientRut = patientRut.replace(/\.|-/g, '').toLowerCase();
                let found = null;
                found = diagnostics.find(d => {
                    const normalizedDiagnosticRut = (d.patient_rut || '').replace(/\.|-/g, '').toLowerCase();
                    return normalizedDiagnosticRut === normalizedPatientRut && d.descripcion.toLowerCase().includes(value);
                });
                if (found) {
                    hiddenDiagnosticId.value = found.id;
                } else {
                    hiddenDiagnosticId.value = '';
                }
            });
        }

        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (hiddenId && !hiddenId.value) {
                    e.preventDefault();
                    if (input) input.focus();
                }
            });
        }
    });
}
</script>
@endpush
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const noDiag = document.getElementById('no_diagnostic');
                        const diagInput = document.getElementById('searchDiagnostic');
                        const hiddenDiag = document.getElementById('diagnostic_id');
                        const diagList = document.getElementById('autocompleteDiagnosticList');

                        function updateNoDiag() {
                            if (!diagInput || !hiddenDiag || !noDiag) return;
                            if (noDiag.checked) {
                                diagInput.disabled = true;
                                diagInput.value = '';
                                hiddenDiag.value = '';
                                if (diagList) { diagList.innerHTML = ''; diagList.style.display = 'none'; }
                            } else {
                                diagInput.disabled = false;
                                diagInput.focus();
                            }
                        }

                        if (diagInput) {
                            diagInput.addEventListener('input', function() {
                                if (diagInput.value.trim().length > 0) {
                                    noDiag.checked = false;
                                    updateNoDiag();
                                }
                            });
                        }

                        if (diagList) {
                            diagList.addEventListener('click', function() {
                                if (noDiag) { noDiag.checked = false; updateNoDiag(); }
                            });
                        }

                        if (noDiag) {
                            noDiag.addEventListener('change', updateNoDiag);
                            updateNoDiag();
                        }
                    });
                    </script>

                    <div class="mb-3">
                        <label for="antecedentes_salud" class="form-label">Antecedesntes de Salud</label>
                        <textarea class="form-control" id="antecedentes_salud" name="antecedentes_salud" rows="3">{{ old('antecedentes_salud', $record->antecedentes_salud ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="enfermedades_text" class="form-label">Vacunas (separadas por coma)</label>
                        <input type="text" class="form-control" id="enfermedades_text" name="enfermedades_text" value="{{ old('enfermedades_text', isset($record->enfermedades) ? $record->enfermedades->pluck('name')->join(', ') : '') }}" placeholder="Ej: BCG, Hepatitis B">
                    </div>
                    <div class="mb-3">
                        <label for="alergias_text" class="form-label">Alergias (separadas por coma)</label>
                        <input type="text" class="form-control" id="alergias_text" name="alergias_text" value="{{ old('alergias_text', isset($record->alergias) ? $record->alergias->pluck('name')->join(', ') : '') }}" placeholder="Ej: Penicilina, Polen">
                    </div>
                    <div class="mb-3">
                        <label for="cirugias_text" class="form-label">Cirugías (separadas por coma)</label>
                        <input type="text" class="form-control" id="cirugias_text" name="cirugias_text" value="{{ old('cirugias_text', isset($record->cirugias) ? $record->cirugias->pluck('name')->join(', ') : '') }}" placeholder="Ej: Apendicectomía">
                    </div>
                    <div class="mb-3">
                        <label for="medicamentos" class="form-label">Medicamentos</label>
                        <textarea class="form-control" id="medicamentos" name="medicamentos" rows="2">{{ old('medicamentos', $record->medicamentos ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="{{ \Carbon\Carbon::parse($record->fecha)->format('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary text-center">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
