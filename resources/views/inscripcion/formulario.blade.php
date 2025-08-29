@extends('layouts.app')

@section('title', 'Formulario de Inscripción')

@section('content')
@vite(['resources/css/formulario.css'])

<div class="container-fluid p-0">
    <div class="row no-gutters">
        <div class="col-12">
            <div class="form-wrapper">
                <h1 class="text-center form-title">🎄DATOS DEL TUTOR🎄<span style="color:red; text-transform: uppercase;"> TUTOR</span> </h1>
                <p class="text-center form-subtitle">Por favor, complete el formulario con los datos del tutor responsable.</p>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('inscripcion.guardar') }}" enctype="multipart/form-data" novalidate id="form-inscripcion">
                    @csrf

                    <div class="form-group">
                        <label for="nombres" class="form-label">Nombres del tutor</label>
                        <input type="text" class="form-control" id="nombres" name="nombres"
                               value="{{ old('nombres') }}" minlength="2" maxlength="50" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$">
                        <div class="invalid-feedback">Ingrese un nombre válido (solo letras).</div>
                    </div>

                    <div class="form-group">
                        <label for="ap_paterno" class="form-label">Apellido Paterno del tutor</label>
                        <input type="text" class="form-control" id="ap_paterno" name="ap_paterno"
                               value="{{ old('ap_paterno') }}" minlength="2" maxlength="50" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$">
                        <div class="invalid-feedback">Ingrese un apellido válido (solo letras).</div>
                    </div>

                    <div class="form-group">
                        <label for="ap_materno" class="form-label">Apellido Materno del tutor</label>
                        <input type="text" class="form-control" id="ap_materno" name="ap_materno"
                               value="{{ old('ap_materno') }}" minlength="2" maxlength="50" required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$">
                        <div class="invalid-feedback">Ingrese un apellido válido (solo letras).</div>
                    </div>

                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono del tutor (+569XXXXXXXX)</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono"
                               value="{{ old('telefono', '+569') }}" minlength="12" maxlength="12" required pattern="^\+569\d{8}$">
                        <div class="invalid-feedback">Ingrese un teléfono válido en formato +569XXXXXXXX.</div>
                    </div>

                    <div class="form-group">
                        <label for="direccion" class="form-label">Dirección del tutor</label>
                        <input type="text" class="form-control" id="direccion" name="direccion"
                               value="{{ old('direccion') }}" maxlength="50" required>
                        <div class="invalid-feedback">Ingrese una dirección (máximo 50 caracteres).</div>
                    </div>

                    <div class="form-group">
                        <label for="rut" class="form-label">RUT del tutor (Ej: 12.345.678-9)</label>
                        <input type="text" class="form-control" id="rut" name="rut"
                               value="{{ old('rut') }}" maxlength="12" required>
                        <div class="invalid-feedback" id="rut-error">RUT inválido. Revise el formato y dígito verificador.</div>
                    </div>

                    <div class="form-group">
                        <label for="registro_social" class="form-label">Registro Social de Hogares del tutor</label>
                        <input type="file" class="form-control" id="registro_social" name="registro_social"
                               accept=".pdf,.jpg,.png" required>
                        <div class="invalid-feedback">Debe subir un archivo válido (PDF/JPG/PNG).</div>
                    </div>

                    <div class="form-group">
                        <label for="embarazada" class="form-label">¿Está embarazada o solo desea agregar un niño?</label>
                        <select class="form-select" name="embarazada" id="embarazada" required>
                            <option value="" disabled selected>Seleccione</option>
                            <option value="si">Sí, estoy embarazada.</option>
                            <option value="no">No, solo deseo inscribir un niño.</option>
                        </select>
                        <div class="invalid-feedback">Por favor, seleccione una opción.</div>
                    </div>

                    <div id="datos-embarazo" style="display:none;">
                        <div class="form-group">
                            <label for="meses_gestacion" class="form-label">Meses de Gestación</label>
                            <select class="form-select" id="meses_gestacion" name="meses_gestacion" disabled>
                                <option value="" disabled selected>Seleccione</option>
                                @for ($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="carnet_gestacion" class="form-label">Carnet de Gestación</label>
                            <input type="file" class="form-control" id="carnet_gestacion"
                                   name="carnet_gestacion" accept=".pdf,.jpg,.png" disabled>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Enviar Datos Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/rut.js@1.0.2/dist/rut.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-inscripcion');
    const sel = document.getElementById('embarazada');
    const box = document.getElementById('datos-embarazo');
    const meses = document.getElementById('meses_gestacion');
    const carnet = document.getElementById('carnet_gestacion');
    const rutInput = document.getElementById('rut');
    const rutError = document.getElementById('rut-error');
    const telefono = document.getElementById('telefono');

    // Fondo completo
    document.body.style.backgroundImage = "url('{{ asset('img/fondo1.jpg') }}')";
    document.body.style.backgroundSize = "cover";
    document.body.style.backgroundPosition = "center";
    document.body.style.backgroundRepeat = "no-repeat";
    document.body.style.backgroundAttachment = "fixed";

    // Teléfono
    telefono.addEventListener('input', function() {
        if (!this.value.startsWith('+569')) this.value = '+569';
        this.value = this.value.replace(/(?!^\+569)\D/g, '');
    });

    // Nombres y apellidos solo letras
    ['nombres', 'ap_paterno', 'ap_materno'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
        });
    });

    // Mostrar/ocultar embarazo
    sel.addEventListener('change', function () {
        if (this.value === 'si') {
            box.style.display = 'block';
            meses.disabled = false;
            carnet.disabled = false;
        } else {
            box.style.display = 'none';
            meses.disabled = true;
            carnet.disabled = true;
            meses.value = '';
            carnet.value = '';
        }
    });

    // Validación RUT
    rutInput.addEventListener('input', function () {
        let clean = this.value.replace(/[^0-9kK]/g, '');
        if (clean.length > 1) {
            clean = clean.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '-' + clean.slice(-1);
        }
        this.value = clean;
        if (RUT.isValid(this.value)) {
            this.classList.remove('is-invalid'); this.classList.add('is-valid'); rutError.style.display = 'none';
        } else {
            this.classList.remove('is-valid'); this.classList.add('is-invalid'); rutError.style.display = 'block';
        }
    });

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection
