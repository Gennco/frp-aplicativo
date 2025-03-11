<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/material.css') }}" rel="stylesheet">
</head>
<body data-route="{{ request()->path() }}" class="content-box">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand">
                    <img src="{{ asset('images/logo.jpg') }}" alt="{{ config('app.name', 'Laravel') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    </ul>

                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('empleado.login') }}"></a>
                            </li>
                        @else
                            <li class="nav-item user-section">
                                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="power-btn" title="Cerrar sesión">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <form id="logout-form" action="{{ route('empleado.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
           
            @yield('content')
            @include('sweetalert::alert')
            @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
            @stack('scripts')
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <img src="{{ asset('favicon.ico') }}" alt="favicon" class="footer-icon">
                    <p class="footer-text">&copy; {{ date('Y') }} {{ config('app.name') }} - Todos los derechos reservados</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>