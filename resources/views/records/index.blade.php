@extends('layouts.app')

@section('content')
<div class="container-fluid pt-5 records-index">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container">
        <div class="row mb-3">
            <div class="col-10">
                <h1 class="fw-bold">Historial Médico</h1>
            </div>
            <div class="col-2 text-end">
                <a href="{{ route('records.create') }}" class="btn btn-primary">Historial Adicional</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12" >
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Diagnóstico</th>
                            <th>Antecedentes</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td>{{ $record->id_historial }}</td>
                                <td>{{ $record->patient->rut ?? '' }}</td>
                                <td>{{ optional($record->diagnostic)->description ?? 'Sin diagnóstico' }}</td>
                                <td>{{ !empty($record->antecedentes_salud) ? $record->antecedentes_salud : 'Sin antecedentes' }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->fecha)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('records.show', $record->id_historial) }}" class="btn btn-primary btn-sm">Mostrar</a>
                                    <a href="{{ route('records.edit', $record->id_historial) }}" class="btn btn-secondary btn-sm">Modificar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Sin antecedentes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
