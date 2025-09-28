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

    function formatRut(rut) {
        rut = rut.replace(/[^0-9kK]/g, '');
        if (rut.length < 2) return rut;
        var cuerpo = rut.slice(0, -1);
        var dv = rut.slice(-1);
        // Puntos cada 3 dígitos desde la derecha
        cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return cuerpo + '-' + dv;
    }

    input.addEventListener('input', function() {
        let value = input.value;
        // Si el input es solo números y k/K, formatea como RUT
        if (/^[0-9kK]+$/.test(value.replace(/\./g, '').replace('-', ''))) {
            let raw = value.replace(/[^0-9kK]/g, '');
            input.value = formatRut(raw);
            value = input.value;
        }
        // Para buscar, elimina puntos y guion del input
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

    // Oculta la lista si se hace clic fuera
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.innerHTML = '';
            list.style.display = 'none';
        }
    });

    // Antes de enviar el formulario, limpia el RUT
    document.querySelector('form').addEventListener('submit', function(e) {
        input.value = input.value.replace(/[^0-9kK]/g, '');
    });
</script>
@endpush
            </form>
        </div>
    </div>
</div>
@endsection
</div>
