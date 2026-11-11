@extends('layouts.resort')

@section('title', 'Iniciar sesión')

@section('content')
    <h1>Recepción · Iniciar sesión</h1>

    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email') <p class="field-error">{{ $message }}</p> @enderror

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Entrar</button>
    </form>

    <p class="hint">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>.</p>
@endsection
