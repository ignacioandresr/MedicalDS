@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: rgba(255,255,255,0.95);">
                <div class="card-header text-center fw-bold" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Usuarios registrados</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->roles->pluck('name')->join(', ') }}</td>
                                    <td class="text-end">
                                        @if(auth()->id() === $u->id)
                                            <button class="btn btn-sm btn-secondary me-1" disabled title="No puedes asignarte un rol">Asignar</button>
                                        @else
                                            <a class="btn btn-sm btn-secondary me-1" href="{{ route('roles.assign') }}?user_id={{ $u->id }}">Asignar</a>
                                        @endif
                                        @if(auth()->id() !== $u->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('¿Eliminar usuario? Esta acción no se puede deshacer.');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        @else
                                            <span class="text-muted small">(Tu usuario)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
