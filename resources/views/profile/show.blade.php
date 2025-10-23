@extends('layouts.app')

@push('styles')
<style>
    .profile-card { background-color: rgba(255,255,255,0.9); }
    .avatar-circle { width: 96px; height:96px; border-radius:50%; background:#57B7F2; display:flex; align-items:center; justify-content:center; color:#fff; font-size:36px; }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="card-header text-center fw-bold card-visitor-header" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Perfil de usuario</div>

                <div class="card-body d-flex gap-4 flex-column flex-md-row align-items-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <div class="avatar-circle mb-3">{{ strtoupper(substr($user->name,0,1)) }}</div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-muted">@if(method_exists($user, 'hasRole') && $user->hasRole('admin'))Administrador @else Usuario @endif</small>
                        <div class="mt-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">Editar perfil</a>
                        </div>
                    </div>

                    <div class="flex-fill">
                        <h6>Información</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Nombre</dt>
                            <dd class="col-sm-8">{{ $user->name }}</dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8">{{ $user->email }}</dd>

                            <dt class="col-sm-4">Creado</dt>
                            <dd class="col-sm-8">{{ optional($user->created_at)->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
