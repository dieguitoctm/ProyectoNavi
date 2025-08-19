@extends('layouts.app')

@section('title', 'Bienvenida Navidad Coinco')

@vite(['resources/css/bienvenida.css'])

@section('content')
<div class="bienvenida-container" style="background: url('{{ asset('img/fondo1.jpg') }}') no-repeat center center; background-size: cover;">
    <!-- Hero Section -->
    <section class="bienvenida-hero">
        <div class="bienvenida-snowflakes" aria-hidden="true">
            <!-- Los copos de nieve se generarán con JavaScript -->
        </div>
        
        <div class="bienvenida-hero-content">
            <h1>🎄 Bienvenidos a la Inscripción Navideña 2025 🎅</h1>
            <p>Extensión de <a href="https://www.municoinco.cl" target="_blank" class="bienvenida-link">municoinco.cl</a> para inscribir a familias en la campaña navideña de Coinco.</p>
            <a href="{{ route('inscripcion.formulario') }}" class="bienvenida-btn-primary">
                <i class="bi bi-pencil-square me-2"></i> Iniciar Inscripción
            </a>
        </div>
    </section>

    <!-- Información adicional -->
    <section class="bienvenida-info-section">
        <div class="container">
            <h2 class="bienvenida-section-title">Información Importante</h2>
            
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="bienvenida-info-card">
                        <h3 class="bienvenida-info-title">Inscripción para Menores de 5 Años</h3>
                        
                        <div class="bienvenida-highlight">
                            <p class="mb-0 text-center fw-bold">¡Atención! Período de inscripciones:</p>
                            <p class="mb-0 text-center">Desde el 25 de agosto hasta el 30 de noviembre de 2025</p>
                        </div>
                        
                        <ul class="bienvenida-info-list">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Dirigido a niños y niñas de 0 a 5 años residentes en la comuna de Coinco</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Los regalos serán entregados según la edad del menor</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Es necesario presentar documentación que acredite la edad del menor</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Las inscripciones se realizarán exclusivamente a través de este sistema en línea</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>La entrega de regalos se realizará entre el 15 y 23 de diciembre</span>
                            </li>
                        </ul>
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
        const snowflakeCount = window.innerWidth < 768 ? 30 : 50; // Menos copos en móviles
        
        for (let i = 0; i < snowflakeCount; i++) {
            const snowflake = document.createElement('div');
            snowflake.classList.add('bienvenida-snowflake');
            snowflake.innerHTML = '❄';
            
            // Tamaño aleatorio
            const size = Math.random() * 0.8 + 0.7;
            snowflake.style.fontSize = `${size}rem`;
            
            // Posición inicial aleatoria
            const startPosition = Math.random() * 100;
            snowflake.style.left = `${startPosition}vw`;
            
            // Duración y delay aleatorios
            const duration = Math.random() * 5 + 5;
            const delay = Math.random() * 5;
            snowflake.style.animationDuration = `${duration}s`;
            snowflake.style.animationDelay = `${delay}s`;
            
            snowContainer.appendChild(snowflake);
        }
    }
    
    // Iniciar cuando el documento esté listo
    document.addEventListener('DOMContentLoaded', function() {
        createSnowflakes();
        
        // Ajustar altura del hero en móviles
        if (window.innerWidth < 768) {
            document.querySelector('.bienvenida-hero').style.minHeight = '85vh';
        }
    });
</script>
@endsection