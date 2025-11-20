<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedicalDS</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    <style>
        /* Reserve space for fixed navbar so page content is not hidden */
        .page-content { padding-top: calc(56px + 1rem); }
        @media (min-width: 992px) {
            .page-content { padding-top: calc(64px + 1rem); }
        }
        /* When guest hero-section exists below navbar, give it extra top margin */
        .hero-section { margin-top: calc(56px + 0.5rem); }
        /* Ensure visitor language dropdown is on top and clickable */
        .visitor-lang-dropdown { position: relative; z-index: 3000; }
        .visitor-lang-dropdown .visitor-lang-btn { pointer-events: auto; z-index: 3010; }
        .visitor-lang-dropdown .dropdown-menu { z-index: 3050; }

        /* Visitor language dropdown colors (uses colores -> verde #BAF241) */
        .visitor-lang-dropdown .visitor-lang-btn {
            background-color: #BAF241;
            color: #000;
            border-color: rgba(0,0,0,0.08);
        }
        .visitor-lang-dropdown .dropdown-menu {
            /* force the inner dropdown background to the requested green */
            background-color: #BAF241 !important;
            min-width: 8rem;
            border: none;
            box-shadow: none;
            /* ensure the menu fills with the color including rounded corners */
            background-clip: padding-box;
            padding: 0 !important; /* remove extra padding so items align perfectly */
        }
        /* Make dropdown items also show the green background so the entire inside looks uniform */
        .visitor-lang-dropdown .dropdown-menu .dropdown-item {
            background-color: #BAF241 !important;
            color: #000;
            padding-top: .5rem;
            padding-bottom: .5rem;
            padding-left: 1rem;
            padding-right: 1rem;
            margin: 0 !important;
            display: block;
            width: 100%;
            box-sizing: border-box;
            border-radius: 0 !important; /* avoid individual item rounding causing visual seams */
        }
            /* Visitor logout dropdown (only in visitor view) */
            .visitor-logout-dropdown {
                background-color: #BAF241 !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .visitor-logout-dropdown .visitor-logout-item,
            .visitor-logout-dropdown .dropdown-item {
                background-color: #BAF241 !important;
                color: #000 !important;
                width: 100%;
                padding-top: .5rem;
                padding-bottom: .5rem;
                padding-left: 1rem;
                padding-right: 1rem;
                border-radius: 0 !important;
                box-sizing: border-box;
            }
            .visitor-logout-dropdown .dropdown-divider {
                border-color: rgba(0,0,0,0.06) !important;
                background-color: rgba(0,0,0,0.02) !important;
                height: 1px;
                margin: .25rem 0;
            }
            .visitor-logout-dropdown .visitor-logout-item:hover,
            .visitor-logout-dropdown .dropdown-item:hover {
                background-color: #9fd82a !important;
                color: #000 !important;
            }
            /* More specific override to beat compiled .navbar .dropdown-menu .dropdown-item.login-btn rules */
            .navbar .visitor-logout-dropdown .dropdown-item.login-btn,
            .navbar .visitor-logout-dropdown .dropdown-item {
                background-color: #BAF241 !important;
                width: 100% !important;
                color: #000 !important;
            }
        /* Keep rounded corners on the menu by rounding first/last items only */
        .visitor-lang-dropdown .dropdown-menu .dropdown-item:first-child {
            border-top-left-radius: .375rem !important;
            border-top-right-radius: .375rem !important;
        }
        .visitor-lang-dropdown .dropdown-menu .dropdown-item:last-child {
            border-bottom-left-radius: .375rem !important;
            border-bottom-right-radius: .375rem !important;
        }
        /* Hover: slightly darker green for feedback without changing layout */
        .visitor-lang-dropdown .dropdown-menu .dropdown-item:hover,
        .visitor-lang-dropdown .dropdown-menu .dropdown-item:focus {
            background-color: #9fd82a !important;
            color: #000;
            transform: none !important;
        }
        /* Ajuste: asegurar que el botón toggler esté por encima del dropdown de idioma en móvil */
        .navbar-toggler { position: relative; z-index: 3500; }
        .visitor-lang-dropdown { z-index: 3000; }
        /* Evitar que el dropdown de idioma cubra el área del toggler */
        @media (max-width: 991.98px) {
            .visitor-lang-dropdown { margin-right: .5rem; }
        }
    </style>
</head>
<body class="{{ request()->routeIs('visitor.*') ? 'route-visitor' : '' }}">
    <div id="app">
    <nav class="navbar navbar-expand-lg py-3 fixed-top" style="@if(request()->routeIs('visitor.*'))background: linear-gradient(90deg,rgba(186, 242, 65, 1) 50%, rgba(196, 225, 242, 1) 100%);@endif">
            <div class="container d-flex align-items-center">
                @if(session()->get('locale') === 'ru')
                    {{-- Mostrar nombre en ruso cuando el idioma es ruso --}}
                    @if(session()->get('visitor_authenticated'))
                        <a class="navbar-brand fw-bold custom home-btn" href="{{ route('visitor.home.ru') }}" style="font-size: 1.5rem;">МедицинскийDS</a>
                    @elseif(auth()->check())
                        <a class="navbar-brand fw-bold custom home-btn" href="{{ url('/home') }}" style="font-size: 1.5rem;">МедицинскийDS</a>
                    @else
                        <a class="navbar-brand fw-bold custom home-btn" href="/" style="font-size: 1.5rem;">МедицинскийDS</a>
                    @endif
                @elseif(auth()->check())
                    {{-- When logged in, brand goes to the authenticated home --}}
                    <a class="navbar-brand fw-bold custom home-btn" href="{{ url('/home') }}" style="font-size: 1.5rem;">MedicalDS</a>
                @else
                    <a class="navbar-brand fw-bold custom home-btn" href="/" style="font-size: 1.5rem;">MedicalDS</a>
                @endif
                @php $showVisitorLang = request()->routeIs('visitor.*'); $isVisitor = session()->get('visitor_authenticated') || request()->routeIs('visitor.*'); @endphp
                @if($showVisitorLang)
                    <div class="ms-3 d-flex align-items-center visitor-lang-dropdown" style="z-index:2000;">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle visitor-lang-btn" type="button" id="visitorLang" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="visitorLang">
                                @if(request()->routeIs('visitor.welcome.ru'))
                                    <li><a class="dropdown-item" href="{{ route('visitor.welcome.es') }}">Español</a></li>
                                @elseif(request()->routeIs('visitor.welcome.es'))
                                    <li><a class="dropdown-item" href="{{ route('visitor.welcome.ru') }}">Русский</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'es') }}">Español</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'ru') }}">Русский</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                @unless(auth()->check())
                    <div class="position-absolute start-50 translate-middle-x d-none d-lg-flex align-items-center">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="martian-interface-switch" {{ request()->routeIs('visitor.*') ? 'checked' : '' }}>
                            <label class="form-check-label ms-2 fw-bold" style="color: #fff !important;" for="martian-interface-switch">Интерфейс марсианина</label>
                        </div>
                    </div>
                @endunless

                <div class="position-absolute start-50 translate-middle-x d-none d-lg-flex align-items-center">
                    @if(auth()->check() && !(session()->get('visitor_authenticated') || request()->routeIs('visitor.*')))
                        @php $u = Auth::user(); @endphp
                        @if(method_exists($u,'hasRole') && $u->hasRole('admin'))
                            <a class="btn fw-bold home-nav-btn" href="{{ route('lang.switch','es') }}?redirect={{ urlencode(route('home')) }}" aria-label="Casa">
                                <i class="bi bi-house-door-fill btn home-btn"></i>
                            </a>
                        @else
                            <a class="btn fw-bold home-nav-btn" href="{{ url('/home') }}" aria-label="Casa">
                                <i class="bi bi-house-door-fill btn home-btn"></i>
                            </a>
                        @endif
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        @unless(auth()->check())
                            {{-- Switch de interfaz marciana solo visible en móvil (dentro del collapse) --}}
                            <li class="nav-item my-1 d-lg-none">
                                <div class="form-check form-switch mb-0 px-3">
                                    <input class="form-check-input" type="checkbox" id="martian-interface-switch-mobile" {{ request()->routeIs('visitor.*') ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 fw-bold" for="martian-interface-switch-mobile">Интерфейс марсианина</label>
                                </div>
                            </li>
                        @endunless
                        @if(auth()->check() && session()->get('visitor_authenticated') && method_exists(Auth::user(),'hasRole') && Auth::user()->hasRole('visitor') && !request()->routeIs('visitor.welcome.ru'))
                            <li class="nav-item my-1 d-flex align-items-center me-2">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="simulate-offline-switch" {{ request()->cookie('simulate_offline') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="simulate-offline-switch">{{ __('messages.simulate_offline') }}</label>
                                </div>
                            </li>
                        @endif
                        @if (Route::has('login'))
                            @auth
                                @if(request()->routeIs('visitor.*'))
                                    <li class="nav-item dropdown mx-lg-2 my-1">
                                        <a id="navbarDropdownVisitor" class="login-btn nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <div class="">{{ Auth::user()->name }}</div>
                                        </a>

                                                <div style="background-color: #BAF241 !important;" class="dropdown-menu dropdown-menu-end visitor-logout-dropdown" aria-labelledby="navbarDropdownVisitor">
                                                    <a class="dropdown-item login-btn visitor-logout-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        {{ __('Cerrar sesión') }}
                                                    </a>
                                                </div>
                                    </li>
                                @else
                                    <li class="nav-item dropdown mx-lg-1 my-1">
                                        <a id="navbarDropdownUser" class="login-btn nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                                <a class="dropdown-item login-btn" href="{{ route('profile.show') }}">Perfil</a>
                                                <a class="dropdown-item login-btn" href="{{ route('profile.edit') }}">Editar</a>
                                                @if(method_exists(Auth::user(), 'hasRole') && Auth::user()->hasRole('admin'))
                                                    <div class="dropdown-divider" style="border-color: rgba(0,0,0,0.1);"></div>
                                                    <a class="dropdown-item login-btn" href="{{ route('admin.users') }}">Usuarios</a>
                                                    <a class="dropdown-item login-btn" href="{{ route('roles.assign') }}">Asignar roles</a>
                                                @endif
                                                <div class="dropdown-divider" style="border-color: rgba(0,0,0,0.1);"></div>
                                                <a class="dropdown-item login-btn" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    {{ __('Cerrar sesión') }}
                                                </a>
                                                </div>
                                    </li>
                                @endif
                            @else
                                @guest
                                    @php
                                        $isVisitor = session()->get('visitor_authenticated') || request()->routeIs('visitor.*');
                                    @endphp
                                    @if($isVisitor)
                                    @endif
                                @endguest
                            @endauth
                            @if(!auth()->check() && !request()->routeIs('visitor.*') && (request()->is('/') || request()->routeIs('login') || request()->routeIs('register')) )
                                <li class="nav-item my-1">
                                    <a class="btn fw-bold login-btn" href="{{ route('lang.switch','es') }}?redirect={{ urlencode(route('login')) }}">{{ __('messages.login') }}</a>
                                </li>
                                @if(Route::has('register'))
                                    <li class="nav-item my-1">
                                        <a class="btn fw-bold login-btn" href="{{ route('lang.switch','es') }}?redirect={{ urlencode(route('register')) }}">{{ __('messages.register') }}</a>
                                    </li>
                                @endif
                            @endif
                        @endif
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>
    {{-- Aviso offline eliminado --}}
    {{-- Se ha eliminado la sección de héroe con botones de registrar e iniciar sesión marciano --}}

        <main>
            <div class="page-content">
                <div class="container py-4">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        window.__visitorLeaveUrl = "{{ route('visitor.leave') }}";
        window.__visitorWelcomeUrl = "{{ route('visitor.welcome.ru') }}";
    </script>
    @if($isVisitor ?? false)
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg) {
                    console.log('ServiceWorker registered:', reg);
                }).catch(function(err) {
                    console.warn('SW register failed:', err);
                });
            });
        }
    </script>

    <script>
                    (function(){
            function showToast(message){
                try {
                    var t = document.getElementById('offline-toast');
                    if (!t) {
                        t = document.createElement('div');
                        t.id = 'offline-toast';
                        t.className = 'offline-toast';
                        t.innerHTML = '<div class="toast-body"></div>';
                        document.body.appendChild(t);
                        var style = document.createElement('style');
                        style.innerHTML = '.offline-toast { position: fixed; top: 1rem; left: 50%; transform: translateX(-50%) translateY(-6px); background: rgba(0,0,0,0.85); color: #fff; padding: .6rem .9rem; border-radius: .375rem; z-index: 5000; opacity: 0; transition: opacity .2s, transform .2s; } .offline-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }';
                        document.head.appendChild(style);
                    }
                    var body = t.querySelector('.toast-body');
                    if (body) body.textContent = message;
                    t.classList.add('show');
                    setTimeout(function(){ t.classList.remove('show'); }, 3000);
                } catch(e) { console.warn(e); }
            }
            window.showToast = showToast;
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var checkbox = document.getElementById('simulate-offline-switch');
                if (!checkbox) return;

                function isSim() { return document.cookie.indexOf('simulate_offline=1') !== -1; }
                function setCookieOn() { document.cookie = 'simulate_offline=1; path=/;'; }
                function setCookieOff() { document.cookie = 'simulate_offline=; Max-Age=0; path=/;'; }
                function updateOfflineUI() {
                    var banner = document.getElementById('offline-banner');
                    var toolbar = document.getElementById('offline-toolbar');
                    var ind = document.getElementById('offline-indicator');
                    var simulate = isSim();
                    if (banner) { banner.classList.toggle('d-none', !simulate); }
                    if (toolbar) { toolbar.classList.toggle('d-none', !simulate); }
                    if (ind) { ind.classList.toggle('d-none', !simulate); }
                }

                function showOfflineModalOnce() {
                    try {
                        var shown = sessionStorage.getItem('offline_modal_shown');
                        if (shown) return;
                        var modalEl = document.getElementById('offlineModal');
                        if (!modalEl) return;
                        var modal = new bootstrap.Modal(modalEl);
                        modal.show();
                        sessionStorage.setItem('offline_modal_shown', '1');
                    } catch (e) { console.warn(e); }
                }

                checkbox.checked = isSim();
                updateOfflineUI();

                checkbox.addEventListener('change', function (e) {
                    if (e.target.checked) {
                        setCookieOn();
                        updateOfflineUI();
                        showOfflineModalOnce();
                        showToast('{{ addslashes(__('messages.offline_activated')) }}');
                    } else {
                        setCookieOff();
                        updateOfflineUI();
                        showToast('{{ addslashes(__('messages.offline_deactivated')) }}');
                    }
                    setTimeout(function(){}, 0);
                });
            } catch (err) { console.warn(err); }
        });
    </script>
    @endif
    
        @if($isVisitor ?? false)
        <div class="modal fade" id="offlineModal" tabindex="-1" aria-labelledby="offlineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="offlineModalLabel">{{ __('messages.offline_modal_title') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('messages.offline_modal_body') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.offline_modal_close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
                document.addEventListener('DOMContentLoaded', function () {
                        try {
                                var isSim = document.cookie.indexOf('simulate_offline=1') !== -1;
                                if (isSim) {
                                        var shown = sessionStorage.getItem('offline_modal_shown');
                                        if (!shown) {
                                                var modalEl = document.getElementById('offlineModal');
                                                if (modalEl) {
                                                        var modal = new bootstrap.Modal(modalEl);
                                                        modal.show();
                                                        sessionStorage.setItem('offline_modal_shown', '1');
                                                }
                                        }
                                }
                        } catch (e) {}
                });
        </script>
        @endif
    
            @if($isVisitor ?? false)
            <div id="offline-toolbar" class="offline-toolbar d-none">
                <div class="offline-toolbar-inner">
                    <span class="offline-dot" aria-hidden="true"></span>
                    <span class="offline-toolbar-text">{{ __('messages.offline_toolbar_text') }}</span>
                    {{-- Botón ver casos mock ocultado según solicitud --}}
                </div>
            </div>
            <style>
                /* Estilos del banner offline eliminados */

                .offline-toolbar { position: fixed; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index:4500; }
                .offline-toolbar-inner { background: rgba(255,255,255,0.95); padding:.4rem .6rem; border-radius:999px; display:flex; align-items:center; gap:.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.12); }
                .offline-dot { width:10px; height:10px; border-radius:50%; background:#BAF241; display:inline-block; box-shadow:0 0 0 rgba(186,242,65,0.6); animation: pulse 1.6s infinite; }
                @keyframes pulse { 0% { box-shadow:0 0 0 0 rgba(186,242,65,0.6);} 70% { box-shadow:0 0 0 10px rgba(186,242,65,0);} 100% { box-shadow:0 0 0 0 rgba(186,242,65,0);} }
                .offline-toolbar-text { font-weight:600; color:#000; }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    try {
                        var showOfflineUI = document.cookie.indexOf('simulate_offline=1') !== -1;
                        var banner = document.getElementById('offline-banner');
                        var toolbar = document.getElementById('offline-toolbar');
                        var indicator = document.getElementById('offline-indicator');
                        if (showOfflineUI) {
                            if (banner) banner.classList.remove('d-none');
                            if (toolbar) toolbar.classList.remove('d-none');
                            if (indicator) indicator.classList.remove('d-none');
                            // banner eliminado: no ajustamos padding dinámico
                        }

                        function showToast(message) {
                            try {
                                var t = document.getElementById('offline-toast');
                                if (!t) return;
                                t.querySelector('.toast-body').textContent = message;
                                t.classList.add('show');
                                setTimeout(function () { t.classList.remove('show'); }, 3000);
                            } catch (e) { console.warn(e); }
                        }

                        var disableBtn = document.getElementById('offline-disable-btn');
                        if (disableBtn) {
                            disableBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                document.cookie = 'simulate_offline=; Max-Age=0; path=/;';
                                if (banner) banner.classList.add('d-none');
                                if (toolbar) toolbar.classList.add('d-none');
                                if (indicator) indicator.classList.add('d-none');
                                // banner eliminado: no revertimos padding
                                var simCheckbox = document.getElementById('simulate-offline-switch');
                                if (simCheckbox) {
                                    simCheckbox.checked = false;
                                }
                                showToast('{{ addslashes(__('messages.offline_deactivated')) }}');
                            });
                        }
                    } catch (e) { console.warn(e); }
                });
            </script>
            @endif
            
            <div id="offline-toast" class="offline-toast" role="status" aria-live="polite" aria-atomic="true">
                <div class="toast-body"></div>
            </div>
            <style>
                .offline-toast { position: fixed; top: 1rem; left: 50%; transform: translateX(-50%) translateY(-6px); background: rgba(0,0,0,0.85); color: #fff; padding: .6rem .9rem; border-radius: .375rem; z-index: 5000; opacity: 0; transition: opacity .2s, transform .2s; }
                .offline-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
                /* padding dinámico eliminado con el banner */
            </style>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            try {
                // Cerrar navbar colapsada tras elegir opción de dropdown o enlace en móvil
                var collapseEl = document.getElementById('navbarSupportedContent');
                if (collapseEl) {
                    // Cerrar collapse al hacer click en items del dropdown
                    document.querySelectorAll('#navbarSupportedContent .dropdown-menu .dropdown-item').forEach(function(item){
                        item.addEventListener('click', function(){
                            if (collapseEl.classList.contains('show')) {
                                var c = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, {toggle: false});
                                c.hide();
                            }
                        });
                    });
                    // Cerrar al hacer click en cualquier enlace del nav en móvil
                    document.querySelectorAll('#navbarSupportedContent a.nav-link, #navbarSupportedContent a.btn').forEach(function(link){
                        link.addEventListener('click', function(){
                            if (window.innerWidth < 992 && collapseEl.classList.contains('show')) {
                                var c = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, {toggle: false});
                                c.hide();
                            }
                        });
                    });
                }
            } catch(e) { console.warn(e); }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var logoutForm = document.getElementById('logout-form');
                if (!logoutForm) return;

                logoutForm.addEventListener('submit', function (e) {
                    try {
                        if (document.cookie.indexOf('simulate_offline=1') !== -1) {
                            document.cookie = 'simulate_offline=; Max-Age=0; path=/;';
                            // Hide offline UI immediately so user sees feedback
                            var banner = document.getElementById('offline-banner'); if (banner) banner.classList.add('d-none');
                            var toolbar = document.getElementById('offline-toolbar'); if (toolbar) toolbar.classList.add('d-none');
                            var indicator = document.getElementById('offline-indicator'); if (indicator) indicator.classList.add('d-none');
                            // banner eliminado: no quitamos clase
                        }
                    } catch (err) { console.warn(err); }
                }, true);
            } catch (err) { /* ignore */ }
        });
    </script>
    @stack('scripts')
</body>
</html>
