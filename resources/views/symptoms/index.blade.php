@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <div class="row mb-3">
        <div class="col-10">
            <h1 class="fw-bold">Síntomas</h1>
        </div>
        <div class="col-2 text-end">
            <a href="{{ route('symptoms.create') }}" class="btn btn-primary">Agregar Síntoma</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($symptoms as $symptom)
                <tr>
                    <td>{{ $symptom->id }}</td>
                    <td>{{ $symptom->name }}</td>
                    <td>{{ $symptom->description }}</td>
                    <td>
                        <a href="{{ route('symptoms.edit', $symptom) }}" class="btn btn-secondary btn-sm">Editar</a>
                        <form action="{{ route('symptoms.destroy', $symptom) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
