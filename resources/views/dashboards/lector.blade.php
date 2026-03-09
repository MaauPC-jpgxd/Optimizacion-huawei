@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-80 py-5">
        <div class="col-xl-7 col-lg-8 col-md-10 text-center">

            <!-- Logo principal rectangular -->
            <div class="mb-5">
                <img src="{{ asset('img/Recurso 18@3x.png') }}"
                     alt="Logo Corporativo"
                     class="img-fluid logo-principal">
            </div>

            <!-- Saludo -->
            <h1 class="display-4 fw-bold mb-3"
                style="color: #003366; letter-spacing: -1px;">
                ¡Bienvenid@, {{ auth()->user()->name }}!
            </h1>

            <p class="lead text-muted mb-4"
               style="font-size: 1.3rem; line-height: 1.7; max-width: 720px; margin: 0 auto;">
                Estamos listos para optimizar juntos tus procesos.<br>
                Eficiencia redefinida — todo comienza aquí.
            </p>

            <!-- Frase institucional -->
            <div class="mb-5">
                <h3 class="fw-semibold" style="color: #003366; font-size: 1.6rem;">
                    Optimización Corporativa
                </h3>
                <p class="text-muted fst-italic" style="font-size: 1.15rem;">
                    eficiencia redefinida
                </p>
            </div>

            <!-- Línea decorativa -->
            <div class="w-50 mx-auto mb-5"
                 style="height: 4px; background: linear-gradient(to right, transparent, #003366, transparent); border-radius: 2px;">
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .min-vh-80 {
        min-height: 80vh;
    }

    .logo-principal {
        max-width: 380px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .logo-principal:hover {
        transform: scale(1.04);
    }
</style>
@stop