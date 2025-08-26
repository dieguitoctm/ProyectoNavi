@extends('layouts.app')

@section('title', 'Agregar Menores al Tutor')

@section('content')
@vite(['resources/css/menor.css'])

<div class="container-fluid p-0">
    <div class="form-wrapper">
        <h1 class="form-title">Agregar Menores para Tutor: <br>{{ $usuario->nombres }} {{ $usuario->ap_paterno }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p class="mb-4 text-center" style="font-size: 1.2rem;">¿Desea agregar un menor ligado a este tutor?</p>

        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mb-4">
            <button id="btn-yes" class="btn btn-primary">Sí, agregar menor</button>
            <a href="{{ route('inscripcion.despedida', $usuario->hash_id) }}" class="btn btn-success">No, finalizar inscripción</a>
        </div>

        <form id="form-menor" method="POST" action="{{ route('menor.guardar', $usuario->hash_id) }}"
            enctype="multipart/form-data" novalidate style="display: none;">
            @csrf

            <div class="form-group">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" class="form-control"
                    required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" minlength="2" maxlength="50" autocomplete="off">
                <div class="invalid-feedback">Ingrese un nombre válido (solo letras).</div>
            </div>

            <div class="form-group">
                <label for="ap_paterno" class="form-label">Apellido Paterno</label>
                <input type="text" name="ap_paterno" id="ap_paterno" value="{{ old('ap_paterno') }}"
                    class="form-control" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" minlength="2" maxlength="50"
                    autocomplete="off">
                <div class="invalid-feedback">Ingrese un apellido válido (solo letras).</div>
            </div>

            <div class="form-group">
                <label for="ap_materno" class="form-label">Apellido Materno</label>
                <input type="text" name="ap_materno" id="ap_materno" value="{{ old('ap_materno') }}"
                    class="form-control" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$" minlength="2" maxlength="50"
                    autocomplete="off">
                <div class="invalid-feedback">Ingrese un apellido válido (solo letras).</div>
            </div>

            <div class="form-group">
                <label for="rut" class="form-label">RUT (Ej: 12.345.678-9)</label>
                <input type="text" name="rut" id="rut" value="{{ old('rut') }}" class="form-control" maxlength="12"
                    required autocomplete="off">
                <div class="invalid-feedback" id="rut-error">RUT inválido. Revise el formato y dígito verificador.</div>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input
                    type="text"
                    name="fecha_nacimiento"
                    id="fecha_nacimiento"
                    value="{{ old('fecha_nacimiento') }}"
                    class="form-control"
                    required
                    autocomplete="off"
                >
                <div class="valid-feedback">✓ Fecha válida</div>
                <div class="invalid-feedback">El menor debe haber nacido entre el 04 de enero de 2021 y el 31 de diciembre de 2025.</div>
            </div>

            <div class="form-group">
                <label for="genero" class="form-label">Género</label>
                <select name="genero" id="genero" class="form-select" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
                <div class="invalid-feedback">Seleccione un género.</div>
            </div>

            <div class="form-group">
                <label for="carnet_control_sano" class="form-label">Carnet de Control de Salud (PDF/JPG/PNG)</label>
                <input type="file" name="carnet_control_sano" id="carnet_control_sano" class="form-control"
                    accept=".pdf,.jpg,.png" required>
                <div class="invalid-feedback">Debe subir un archivo válido (PDF/JPG/PNG).</div>
            </div>

            <div class="form-group">
                <label for="certificado_nacimiento" class="form-label">Certificado de Nacimiento (PDF/JPG/PNG)</label>
                <input type="file" name="certificado_nacimiento" id="certificado_nacimiento" class="form-control"
                    accept=".pdf,.jpg,.png" required>
                <div class="invalid-feedback">Debe subir un archivo válido (PDF/JPG/PNG).</div>
            </div>

            <div class="d-flex flex-column flex-md-row gap-2">
                <button type="submit" class="btn btn-primary">Agregar Menor</button>
                <button type="button" id="btn-cancel" class="btn btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script src="https://cdn.jsdelivr.net/npm/rut.js@1.0.2/dist/rut.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rutInput = document.getElementById('rut');
    const rutError = document.getElementById('rut-error');
    const form = document.getElementById('form-menor');
    const btnYes = document.getElementById('btn-yes');
    const btnCancel = document.getElementById('btn-cancel');
    const fechaNacimiento = document.getElementById('fecha_nacimiento');

    btnYes.addEventListener('click', () => {
        form.style.display = 'block';
        btnYes.style.display = 'none';
        window.scrollTo(0, 0);
    });

    btnCancel.addEventListener('click', () => {
        form.style.display = 'none';
        btnYes.style.display = 'block';
    });

    rutInput.addEventListener('input', function () {
        let clean = this.value.replace(/[^0-9kK]/g, '');
        if (clean.length > 1) {
            clean = clean.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + clean.slice(-1);
        }
        this.value = clean;

        if (RUT.isValid(this.value)) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            rutError.style.display = 'none';
        } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
            rutError.style.display = 'block';
        }
    });

    ['nombres', 'ap_paterno', 'ap_materno'].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener('input', function () {
            this.value = this.value.replace(/[0-9]/g, '');
            if (this.checkValidity()) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    });

    const minFecha = new Date("2021-01-05");
    const maxFecha = new Date("2025-12-31");

    flatpickr("#fecha_nacimiento", {
        dateFormat: "Y-m-d",
        locale: "es",
        minDate: minFecha,
        maxDate: maxFecha,
        disableMobile: true,
        altInput: true,
        altFormat: "d \\de F \\de Y",
    });

    form.addEventListener('submit', function (event) {
        const selectedDate = new Date(fechaNacimiento.value);
        if (isNaN(selectedDate.getTime()) || selectedDate < minFecha || selectedDate > maxFecha) {
            fechaNacimiento.setCustomValidity('El menor debe haber nacido entre el 04 de enero de 2021 y el 31 de diciembre de 2025.');
        } else {
            fechaNacimiento.setCustomValidity('');
        }

        if (fechaNacimiento.checkValidity()) {
            fechaNacimiento.classList.add('is-valid');
            fechaNacimiento.classList.remove('is-invalid');
        } else {
            fechaNacimiento.classList.add('is-invalid');
            fechaNacimiento.classList.remove('is-valid');
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection
