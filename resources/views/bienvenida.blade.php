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
            <p>Extensión de <a href="https://www.municoinco.cl" target="_blank" class="bienvenida-link">municoinco.cl</a> para inscribir a familias en la campaña navideña de Coinco.</p>
            <div class="text-center mt-3">
                <a href="{{ route('inscripcion.formulario') }}" class="bienvenida-btn-primary">
                    <i class="bi bi-pencil-square me-2"></i> Iniciar Inscripción
                </a>
            </div>
        </div>
    </section>

    <!-- Secciones abajo: Info + Revisar Inscripción -->
    <section class="bienvenida-info-section">
        <div class="container">
            <div class="row justify-content-center g-4">

                <!-- Izquierda: Información -->
                <div class="col-12 col-lg-6">
                    <div class="bienvenida-info-card">
                        <h3 class="bienvenida-info-title">Información Importante</h3>
                        <div class="bienvenida-highlight">
                            <p class="mb-0 text-center fw-bold">¡Atención! Período de inscripciones:</p>
                            <p class="mb-0 text-center">Desde el 25 de Agosto hasta el 30 de Octubre de 2025</p>
                        </div>
                        <ul class="bienvenida-info-list">
                            <li><i class="bi bi-check-circle-fill"></i> Dirigido a embarazadas, niños y niñas de 0 a 5 años residentes en la comuna de Coinco</li>
                            <li><i class="bi bi-check-circle-fill"></i> Los regalos serán entregados según la edad del menor</li>
                            <li><i class="bi bi-check-circle-fill"></i> Es necesario presentar documentación que acredite la edad del menor</li>
                            <li><i class="bi bi-check-circle-fill"></i> Las inscripciones se realizarán exclusivamente a través de este sistema en línea</li>
                            <li><i class="bi bi-check-circle-fill"></i> La entrega de regalos se realizará entre el 15 y 23 de diciembre</li>
                        </ul>
                    </div>
                </div>

                <!-- Derecha: Revisar Inscripción -->
                <div class="col-12 col-lg-6">
                    <div class="bienvenida-info-card text-center">
                        <h3 class="bienvenida-info-title">Revisar Inscripción</h3>
                        @livewire('revisar-inscripcion')
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Crear copos de nieve
    function createSnowflakes() {
        const snowContainer = document.querySelector('.bienvenida-snowflakes');
        const snowflakeCount = window.innerWidth < 768 ? 30 : 50; 
        
        for (let i = 0; i < snowflakeCount; i++) {
            const snowflake = document.createElement('div');
            snowflake.classList.add('bienvenida-snowflake');
            snowflake.innerHTML = '❄';
            snowflake.style.fontSize = `${Math.random() * 0.8 + 0.7}rem`;
            snowflake.style.left = `${Math.random() * 100}vw`;
            snowflake.style.animationDuration = `${Math.random() * 5 + 5}s`;
            snowflake.style.animationDelay = `${Math.random() * 5}s`;
            snowContainer.appendChild(snowflake);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        createSnowflakes();
        if (window.innerWidth < 768) {
            document.querySelector('.bienvenida-hero').style.minHeight = '85vh';
        }
    });
</script>
@endsection
