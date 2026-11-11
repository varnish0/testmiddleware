<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') · Resort Digital</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <a class="brand" href="{{ route('home') }}">🏝️ Resort Digital</a>
        <nav>
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('panel') }}">Mi habitación</a>
            <a href="{{ route('casino') }}">Casino</a>
            <a href="{{ route('admin') }}">Dirección</a>

            @auth
                <span class="user">{{ auth()->user()->name }} ({{ auth()->user()->age }} años{{ auth()->user()->is_admin ? ', admin' : '' }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="link-button">Cerrar sesión</button>
                </form>
            @else
                <span class="user"></span>
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </nav>
    </header>

    <main>
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
