@extends('layouts.app')

@section('content')
<div class="container-fluid records-index pt-5">
    <div class="container">
        <h1 class="mb-4 fw-bold text-center">Agregar Historial Médico</h1>
        <div class="border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('records.store') }}" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <label for="patient_rut" class="form-label">RUT Paciente</label>
                    <input type="text" class="form-control mb-2" id="searchPatient" name="patient_rut" placeholder="Buscar por RUT o nombre..." autocomplete="off" required value="{{ old('patient_rut') }}">
                    <input type="hidden" id="patient_id" name="patient_id" value="{{ old('patient_id') }}">
                    <ul class="list-group position-absolute w-100" id="autocompleteList" style="z-index:1000; max-height:200px; overflow-y:auto;"></ul>
                </div>
@push('scripts')
<script>
// --- Pacientes ---
const patients = [
    @foreach($patients as $patient)
        {
            id: "{{ $patient->id }}",
            rut: "{{ $patient->rut }}",
            nombre: "{{ $patient->nombre }} {{ $patient->apellido_paterno }} {{ $patient->apellido_materno }}"
        },
    @endforeach
];
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

// --- Diagnósticos ---
const diagnostics = [
    @foreach($diagnostics as $diagnostic)
        {
            id: "{{ $diagnostic->id }}",
            description: "{{ $diagnostic->description }}",
            patient_rut: "{{ $diagnostic->patient ? $diagnostic->patient->rut : '' }}"
        },
    @endforeach
];
const diagnosticInput = document.getElementById('searchDiagnostic');
const diagnosticList = document.getElementById('autocompleteDiagnosticList');
const hiddenDiagnosticId = document.getElementById('diagnostic_id');
diagnosticInput.addEventListener('input', function() {
    const value = diagnosticInput.value.toLowerCase();
    diagnosticList.innerHTML = '';
    // Extraer RUT del paciente, ya sea que se haya escrito manualmente o seleccionado del autocompletado
    const patientValue = input.value.trim();
    let patientRut = '';
    const rutMatch = patientValue.match(/(\d{1,2}\.\d{3}\.\d{3}-[\dkK])/);
    if (rutMatch) {
        patientRut = rutMatch[1];
    } else {
        patientRut = patientValue;
    }
    // Normalizar RUT para comparación (sin puntos, guion, minúsculas)
    const normalizedPatientRut = patientRut.replace(/\.|-/g, '').toLowerCase();
    let filteredDiagnostics;
    if (!normalizedPatientRut) {
        filteredDiagnostics = diagnostics.filter(d => d.description.toLowerCase().includes(value));
    } else {
        filteredDiagnostics = diagnostics.filter(d => {
            const normalizedDiagnosticRut = (d.patient_rut || '').replace(/\.|-/g, '').toLowerCase();
            return normalizedDiagnosticRut === normalizedPatientRut && d.description.toLowerCase().includes(value);
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
            item.textContent = d.description;
            item.onclick = function() {
                diagnosticInput.value = d.description;
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
    const patientValue = input.value.trim();
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
        return normalizedDiagnosticRut === normalizedPatientRut && d.description.toLowerCase() === value;
    });
    if (found) {
        hiddenDiagnosticId.value = found.id;
    } else {
        hiddenDiagnosticId.value = '';
    }
});

// --- Validación al enviar ---
document.querySelector('form').addEventListener('submit', function(e) {
    if (!hiddenId.value) {
        e.preventDefault();
        input.focus();
        alert('Debes seleccionar un paciente de la lista para continuar.');
        return;
    }
    if (!hiddenDiagnosticId.value) {
        e.preventDefault();
        diagnosticInput.focus();
        alert('Debes seleccionar un diagnóstico válido para continuar.');
        return;
    }
});
</script>
@endpush
                <div class="mb-3 position-relative">
                    <label for="searchDiagnostic" class="form-label">Diagnóstico</label>
                    <input type="text" class="form-control mb-2" id="searchDiagnostic" name="searchDiagnostic" placeholder="Buscar diagnóstico..." autocomplete="off" required>
                    <input type="hidden" id="diagnostic_id" name="diagnostic_id">
                    <ul class="list-group position-absolute w-100" id="autocompleteDiagnosticList" style="z-index:1000; max-height:200px; overflow-y:auto;"></ul>
                </div>
                <div class="mb-3">
                    <label for="tratamientos" class="form-label">Tratamientos</label>
                    <textarea class="form-control" id="tratamientos" name="tratamientos" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>

    const list = document.getElementById('autocompleteList');

    const diagnostics = [
        @foreach($diagnostics as $diagnostic)
            {
                id: "{{ $diagnostic->id }}",
                descripcion: "{{ $diagnostic->description }}",
                patient_rut: "{{ $diagnostic->patient ? $diagnostic->patient->rut : '' }}"
            },
        @endforeach
    ];
    const diagnosticInput = document.getElementById('searchDiagnostic');
    const diagnosticList = document.getElementById('autocompleteDiagnosticList');
    const hiddenDiagnosticId = document.getElementById('diagnostic_id');
    diagnosticInput.addEventListener('input', function() {
        const value = diagnosticInput.value.toLowerCase();
        diagnosticList.innerHTML = '';
        // Extraer RUT del paciente, ya sea que se haya escrito manualmente o seleccionado del autocompletado
        const patientValue = input.value.trim();
        let patientRut = '';
        const rutMatch = patientValue.match(/(\d{1,2}\.\d{3}\.\d{3}-[\dkK])/);
        if (rutMatch) {
            patientRut = rutMatch[1];
        } else {
            patientRut = patientValue;
        }
        // Normalizar RUT para comparación (sin puntos, guion, minúsculas)
        const normalizedPatientRut = patientRut.replace(/\.|-/g, '').toLowerCase();
        console.log('Filtro RUT:', normalizedPatientRut);
        let filteredDiagnostics;
        if (!normalizedPatientRut) {
            // Si no hay paciente seleccionado, mostrar todos los diagnósticos que coincidan con el texto
            filteredDiagnostics = diagnostics.filter(d => d.description.toLowerCase().includes(value));
        } else {
            filteredDiagnostics = diagnostics.filter(d => {
                const normalizedDiagnosticRut = (d.patient_rut || '').replace(/\.|-/g, '').toLowerCase();
                return normalizedDiagnosticRut === normalizedPatientRut && d.description.toLowerCase().includes(value);
            });
        }
        if (filteredDiagnostics.length === 0) {
            const item = document.createElement('li');
            item.className = 'list-group-item list-group-item-danger';
            item.textContent = 'No hay diagnósticos para este paciente.';
            diagnosticList.appendChild(item);
        } else {
            filteredDiagnostics.forEach(d => {
                const item = document.createElement('li');
                item.className = 'list-group-item list-group-item-action';
                item.textContent = d.description;
                item.onclick = function() {
                    diagnosticInput.value = d.description;
                    hiddenDiagnosticId.value = d.id;
                    diagnosticList.innerHTML = '';
                    diagnosticList.style.display = 'none';
                };
                diagnosticList.appendChild(item);
            });
        }
        diagnosticList.style.display = filteredDiagnostics.length ? 'block' : 'none';
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!hiddenId.value) {
            e.preventDefault();
            input.focus();
            alert('Debes seleccionar un paciente de la lista para continuar.');
        }
    });
</script>
@endpush
        </div>
    </div>
</div>
@endsection
