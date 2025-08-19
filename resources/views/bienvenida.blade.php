@extends('layouts.app')

@section('title', 'Bienvenida Navidad Coinco')

@vite(['resources/css/bienvenida.css'])

@section('content')
<div class="bienvenida-container" style="background: url('{{ asset('img/fondo1.jpg') }}') no-repeat center center; background-size: cover;">
    <!-- Hero Section -->
    <section class="bienvenida-hero">
        <div class="bienvenida-snowflakes" aria-hidden="true"></div>
        
        <div class="bienvenida-hero-content">
            <h1>🎄 Bienvenidos a la Inscripción Navideña 2025 🎅</h1>
            <p>Extensión de <a href="https://www.municoinco.cl" target="_blank" class="text-warning fw-bold">municoinco.cl</a> para inscribir a familias en la campaña navideña de Coinco.</p>
            <a href="{{ route('inscripcion.formulario') }}" class="bienvenida-btn-primary">
                <i class="bi bi-pencil-square me-2"></i> Iniciar Inscripción
            </a>
        </div>
    </section>

    <!-- Programas Navideños -->
    <section class="bienvenida-section bienvenida-features bg-white">
        <div class="container">
            <h2 class="bienvenida-section-title">Nuestros Programas Navideños</h2>
            <div class="row">
                <div class="col-12 col-md-4 mb-4">
                    <div class="bienvenida-card">
                        <div class="bienvenida-card-body">
                            <i class="bi bi-gift-fill bienvenida-icon"></i>
                            <h3 class="bienvenida-card-title">Regalos para Niños</h3>
                            <p class="bienvenida-card-text">Inscribe a los más pequeños para recibir un regalo especial esta Navidad.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <div class="bienvenida-card">
                        <div class="bienvenida-card-body">
                            <i class="bi bi-heart-fill bienvenida-icon"></i>
                            <h3 class="bienvenida-card-title">Apoyo a Familias</h3>
                            <p class="bienvenida-card-text">Participa de nuestros programas solidarios de fin de año.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <div class="bienvenida-card">
                        <div class="bienvenida-card-body">
                            <i class="bi bi-calendar-event-fill bienvenida-icon"></i>
                            <h3 class="bienvenida-card-title">Eventos Navideños</h3>
                            <p class="bienvenida-card-text">Infórmate de todas las actividades navideñas en Coinco.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

@section('scripts')
<script>
    // Toggle para FAQ
    document.querySelectorAll('.bienvenida-faq-header').forEach(header => {
        header.addEventListener('click', () => {
            header.parentElement.classList.toggle('active');
        });
    });
    

</script>
@endsection
@endsection
