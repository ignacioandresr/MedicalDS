<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedicalDS</title>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg py-3">
            <div class="container d-flex align-items-center">
                @auth
                    <a class="navbar-brand fw-bold custom home-btn" href="/home" style="font-size: 1.5rem;">MedicalDS</a>
                @else
                    <a class="navbar-brand fw-bold custom home-btn" href="#" onclick="location.reload(); return false;" style="font-size: 1.5rem;">MedicalDS</a>
                @endauth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @auth
                    <div class="position-absolute start-50 translate-middle-x d-none d-lg-block">
                        <a class="" href="{{ url('/home') }}" aria-label="Inicio"><i class="bi bi-house-door-fill btn home-btn"></i></a>
                    </div>
                @endauth

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item mx-lg-1 my-1 d-lg-none">
                                    <a class="" href="{{ url('/home') }}" aria-label="Inicio"><i class="bi bi-house-door-fill btn home-btn"></i></a>
                                </li>
                                <li  class="nav-item dropdown mx-lg-2 my-1">
                                    <a id="navbarDropdown" class="login-btn nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('user'))
                                            <div class="pe-2">
                                                <i class="bi bi-person-circle"></i>
                                            </div>
                                        @endif
                                        <div class="">{{ Auth::user()->name }}
                                            @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                                <i style="font-size: small" class="bi bi-star-fill text-warning ms-2" title="Admin"></i>
                                            @endif
                                        </div>
                                    </a>

                                    <div style="background-color: #57B7F2 !important" class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a style="background-color: #57B7F2 !important; width: 20%;" class="dropdown-item login-btn" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Cerrar sesión') }}
                                        </a>
                                    </div>
                                </li>                                
                            @else
                                <li class="nav-item mx-lg-1 my-1">
                                    <a class="btn fw-bold login-btn" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item mx-lg-1 my-1">
                                        <a class="btn fw-bold login-btn" href="{{ route('register') }}">Registrarse</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <main class="container py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
