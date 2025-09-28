@extends('layouts.app')

@section('content')
<div class="container-fluid patients-index pt-5">
    <h1 class="text-center fw-bold mb-4">Editar Paciente</h1>
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
                <form action="{{ route('patients.update', $patient) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT:</label>
                        <input type="text" class="form-control" id="rut" name="rut" value="{{ $patient->rut }}">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $patient->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="{{ $patient->apellido_paterno }}">
                    </div>
                    <div class="mb-3">
                        <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="{{ $patient->apellido_materno }}">
                    </div>
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Fecha de Nacimiento:</label>
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ \Carbon\Carbon::parse($patient->birth_date)->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Género:</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="" disabled>Seleccione una opción</option>
                            <option value="Hombre" {{ $patient->gender == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="Mujer" {{ $patient->gender == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                            <option value="No especifica" {{ $patient->gender == 'No especifica' ? 'selected' : '' }}>No especifica</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="adress" class="form-label">Dirección:</label>
                        <input type="text" class="form-control" id="adress" name="adress" value="{{ $patient->adress }}">
                    </div>
                    <button type="submit" class="btn btn-primary text-center">Actualizar</button>
                </form>
            </div>
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
        rut = rut.substring(0, rut.length - 4) + '.' + rut.substring(rut.length - 4);
    }
    if (rut.length > 8) {
        rut = rut.substring(0, rut.length - 8) + '.' + rut.substring(rut.length - 8);
    }
    e.target.value = rut;
});
</script>
@endpush
