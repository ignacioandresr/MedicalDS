@extends('layouts.app')

@section('content')
<div class="container pt-5">
    <h1>Editar Síntoma</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('symptoms.update', $symptom) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $symptom->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descripción</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $symptom->description) }}</textarea>
        </div>

        <button class="btn btn-primary" type="submit">Actualizar</button>
    </form>
</div>
@endsection
