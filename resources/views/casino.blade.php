@extends('layouts.resort')

@section('title', 'Casino')

@section('content')
    <h1>🎰 Casino del Resort</h1>
    <p>Bienvenido a la sala de juegos, {{ auth()->user()->name }}. Zona exclusiva para mayores de edad: ruleta, póker y tragaperras te esperan.</p>
@endsection
