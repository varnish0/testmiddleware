@extends('layouts.resort')

@section('title', 'Dirección')

@section('content')
    <h1>🗂️ Oficina de Dirección</h1>
    <p>Hola, {{ auth()->user()->name }}. Desde aquí se gestiona todo el complejo. Solo el personal administrador tiene acceso.</p>
@endsection
