@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: rgba(255,255,255,0.9);">
                <div class="card-header text-center fw-bold" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Asignar roles</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('roles.assign.update') }}">
                        @csrf

                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="user_id" class="form-label">Usuario</label>
                                <select id="user_id" name="user_id" class="form-select">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" @if(isset($selectedUser) && $selectedUser->id == $u->id) selected @endif>{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label for="role" class="form-label">Rol</label>
                                <select id="role" name="role" class="form-select">
                                    <option value="">-- Ninguno --</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r }}" @if(isset($currentRole) && $currentRole == $r) selected @endif>{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 text-end">
                                @php $isSelf = isset($selectedUser) && auth()->check() && $selectedUser->id === auth()->id(); @endphp
                                <button class="btn btn-primary" @if($isSelf) disabled title="No puedes asignarte un rol" @endif>Asignar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
