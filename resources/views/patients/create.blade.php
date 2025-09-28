@extends('layouts.app')

@section('content')
<div class="container-fluid patients-index pt-5">
    <div class="container">
        <h1 class="mb-4 fw-bold text-center">Agrega un Paciente</h1>
        <div class="border border-2 rounded p-4" style="background-color: rgba(255, 255, 255, 0.8);">
            <form action="{{ route('patients.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="rut" class="form-label">RUT</label>
                    <input type="text" class="form-control" id="rut" name="rut" placeholder="12.345.678-9">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="birth_date" name="birth_date">
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Género</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="" disabled selected>Seleccione una opción</option>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="No especifica">No especifica</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="adress" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="adress" name="adress" placeholder="#1234, Calle, Ciudad">
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('rut').addEventListener('input', function (e) {
    var rut = e.target.value.replace(/\./g, '').replace('-', '');
    if (rut.length > 0) {
        rut = rut.substring(0, rut.length - 1) + '-' + rut.substring(rut.length - 1);
    }
    if (rut.length > 4) {
        rut = rut.substring(0, rut.length - 5) + '.' + rut.substring(rut.length - 5);
    }
    if (rut.length > 8) {
        rut = rut.substring(0, rut.length - 9) + '.' + rut.substring(rut.length - 9);
    }
    e.target.value = rut;
});
</script>
@endpush
