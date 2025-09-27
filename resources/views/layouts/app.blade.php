<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedicalDS</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div id="app">
    <nav class="navbar navbar-expand-lg py-3" style="background-color: #C4E1F2;">
            <div class="container">
                <div class="w-100 d-flex justify-content-center row">
                    <a class="navbar-brand fw-bold mx-auto col-auto text-center" href="/" style="font-size: 1.5rem;">MedicalDS</a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end col-6" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item mx-1">
                                    <a class="btn btn-primary" href="{{ url('/home') }}">Inicio</a>
                                </li>
                                <li class="nav-item mx-1">
                                    <a class="btn btn-primary" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @else
                                <li class="nav-item mx-1">
                                    <a class="btn btn-primary" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item mx-1">
                                        <a class="btn btn-primary" href="{{ route('register') }}">Registrarse</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="">
            @yield('content')
        </main>
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
