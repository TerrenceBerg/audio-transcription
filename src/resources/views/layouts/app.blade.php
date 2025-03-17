<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Styles -->

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('css/quantum.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @yield('styles')
    @livewireStyles
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg bg-dark border-bottom border-primary" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('transcribe.form') }}">
                <i class="bi bi-cpu-fill text-primary me-2 ai-icon"></i>
                AI Audio Transcribe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transcribe.form') ? 'active' : '' }}" href="{{ route('transcribe.form') }}">
                            <i class="bi bi-cloud-upload me-1"></i> Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transcribe.list') ? 'active' : '' }}" href="{{ route('transcribe.list') }}">
                            <i class="bi bi-list-ul me-1"></i> Transcriptions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Animation container -->
    <div class="quantum-background"></div>

    <!-- Main content -->
    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="{{ asset('js/quantum-animation.js') }}"></script>
    <script>
        window.addEventListener('load', () => {
            try {
                new QuantumAnimation();
            } catch (e) {
                console.error('Animation failed to initialize:', e);
            }
        });
    </script>
    @yield('scripts')
    @livewireScripts
</body>
</html>
