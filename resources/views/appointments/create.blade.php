@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-visitor" style="background-color: rgba(255, 255, 255, 0.8);">
                <div class="card-header text-center fw-bold card-visitor-header" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Crear Cita</div>

                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Paciente</label>
                            <select name="patient_id" id="patient_id" class="form-control">
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->rut ?? '' }} - {{ $p->name }} {{ $p->apellido_paterno ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Hora</label>
                            <input type="time" name="time" id="time" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection