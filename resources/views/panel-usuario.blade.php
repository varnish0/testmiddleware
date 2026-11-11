@extends('layouts.resort')

@section('title', 'Mi habitación')

@section('content')
    <h1>Tu habitación</h1>
    <p>¡Bienvenido, {{ auth()->user()->name }}! Esta es tu zona privada dentro del resort.</p>

    <table>
        <tr><th>Email</th><td>{{ auth()->user()->email }}</td></tr>
        <tr><th>Edad</th><td>{{ auth()->user()->age }} años</td></tr>
        <tr><th>Rol</th><td>{{ auth()->user()->is_admin ? 'Administrador' : 'Cliente' }}</td></tr>
    </table>
@endsection
