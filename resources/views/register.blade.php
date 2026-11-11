@extends('layouts.resort')

@section('title', 'Registro')

@section('content')
    <h1>Registro de huésped</h1>

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name') <p class="field-error">{{ $message }}</p> @enderror

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email') <p class="field-error">{{ $message }}</p> @enderror

        <label for="age">Edad</label>
        <input type="number" id="age" name="age" min="1" max="120" value="{{ old('age') }}" required>
        @error('age') <p class="field-error">{{ $message }}</p> @enderror

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
        @error('password') <p class="field-error">{{ $message }}</p> @enderror

        <label for="password_confirmation">Repite la contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>

        <button type="submit">Crear cuenta</button>
    </form>
@endsection
