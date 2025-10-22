@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Casos Clínicos</h3>
            <a href="{{ route('clinical_cases.create') }}" class="btn btn-primary">Nuevo caso</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table" >
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Lenguaje</th>
                    <th>Creado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cases as $c)
                    <tr>
                        <td>{{ $c->title_es ?: $c->title }}</td>
                        <td>{{ $c->language }}</td>
                        <td>{{ $c->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('clinical_cases.edit', $c) }}" class="btn btn-sm btn-secondary">Editar</a>
                            <form action="{{ route('clinical_cases.destroy', $c) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro que deseas eliminar?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $cases->links() }}
    </div>
@endsection
