@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.9);">
                <div class="card-header text-center fw-bold" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Acceso para crear administrador</div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.gate.validate') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="secret" class="col-md-4 col-form-label text-md-end fw-medium" style="color: #000;">Clave</label>

                            <div class="col-md-8">
                                <input id="secret" type="password" class="form-control @error('secret') is-invalid @enderror" name="secret" required autofocus>

                                @error('secret')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Validar y continuar
                                </button>
                                <a href="{{ route('register') }}" class="btn btn-secondary">Volver</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
