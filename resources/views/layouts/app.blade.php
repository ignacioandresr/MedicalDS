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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="{{ request()->routeIs('visitor.*') ? 'route-visitor' : '' }}">
    <div id="app">
    <nav class="navbar navbar-expand-lg py-3" style="@if(request()->routeIs('visitor.*'))background: linear-gradient(90deg,rgba(186, 242, 65, 1) 50%, rgba(196, 225, 242, 1) 100%);@endif">
            <div class="container d-flex align-items-center">
                @if(session()->get('visitor_authenticated') && session()->get('locale') === 'ru')
                    <a class="navbar-brand fw-bold custom home-btn" href="{{ route('visitor.home.ru') }}" style="font-size: 1.5rem;">МедицинскийDS</a>
                @elseif(auth()->check())
                    {{-- When logged in, brand goes to the authenticated home --}}
                    <a class="navbar-brand fw-bold custom home-btn" href="{{ url('/home') }}" style="font-size: 1.5rem;">MedicalDS</a>
                @else
                    <a class="navbar-brand fw-bold custom home-btn" href="/" style="font-size: 1.5rem;">MedicalDS</a>
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                @unless(auth()->check())
                    <div class="position-absolute start-50 translate-middle-x d-none d-lg-flex align-items-center">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="martian-interface-switch" {{ (request()->routeIs('visitor.*') || session()->get('visitor_authenticated')) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 fw-bold" style="color: #fff !important;" for="martian-interface-switch">Интерфейс марсианина</label>
                        </div>
                    </div>
                @endunless

                <div class="position-absolute start-50 translate-middle-x d-none d-lg-flex align-items-center">
                    @if(request()->routeIs('visitor.*') || session()->get('visitor_authenticated'))
                        <a class="btn fw-bold home-nav-btn" href="{{ route('visitor.home.ru') }}" aria-label="Casa">
                            <i class="bi bi-house-door-fill btn home-btn"></i>
                        </a>
                    @elseif(auth()->check())
                        <a class="btn fw-bold home-nav-btn" href="{{ url('/home') }}" aria-label="Casa">
                            <i class="bi bi-house-door-fill btn home-btn"></i>
                        </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        @if (Route::has('login'))
                            @auth
                                @if(request()->routeIs('visitor.*'))
                                    <li class="nav-item dropdown mx-lg-2 my-1">
                                        <a id="navbarDropdown" class="login-btn nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div class="">{{ Auth::user()->name }}</div>
                                        </a>

                                        <div style="background-color: #57B7F2 !important" class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a style="background-color: #57B7F2 !important; width: 20%;" class="dropdown-item login-btn" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Cerrar sesión') }}
                                            </a>
                                        </div>
                                    </li>
                                @else
                                    <li class="nav-item mx-lg-1 my-1 d-none d-lg-block">
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
                                                <a class="dropdown-item login-btn" href="{{ route('profile.show') }}">Perfil</a>
                                                <a class="dropdown-item login-btn" href="{{ route('profile.edit') }}">Editar</a>
                                                @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                                    <div class="dropdown-divider" style="border-color: rgba(0,0,0,0.1);"></div>
                                                    <a class="dropdown-item login-btn" href="{{ route('admin.users') }}">Usuarios</a>
                                                    <a class="dropdown-item login-btn" href="{{ route('roles.assign') }}">Asignar roles</a>
                                                @endif
                                                <div class="dropdown-divider" style="border-color: rgba(0,0,0,0.1);"></div>
                                                <a style="background-color: #57B7F2 !important; width: 20%;" class="dropdown-item login-btn" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{ __('Cerrar sesión') }}
                                                </a>
                                            </div>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item my-1">
                                    <a class="btn fw-bold login-btn" href="{{ route('login') }}">Iniciar Sesión</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item my-1">
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
    @guest
    <div class="container-fluid py-2 hero-section">
        <div class="row">
            <div class="col-12 text-center d-flex flex-column flex-md-row justify-content-center gap-2 px-3">
                <a class="btn-alien btn fw-bold px-3 visitor-link" href="{{ route('visitor.register') }}">Зарегистрируйте марсианина</a>
                <a class="btn-alien btn fw-bold visitor-link" href="{{ route('visitor.login.form') }}">Войти как марсианин</a>
            </div>
        </div>
    </div>
    @endguest

        <main>
            <div class="page-content">
                <div class="container py-4">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const switchEl = document.getElementById('martian-interface-switch');
            if (! switchEl) return;

            switchEl.addEventListener('change', function (e) {
                if (e.target.checked) {
                    window.location.href = "{{ route('visitor.welcome.ru') }}";
                    return;
                }

                fetch("{{ route('visitor.leave') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(function (resp) {
                    window.location.href = '/';
                }).catch(function () {
                    window.location.href = '/';
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
