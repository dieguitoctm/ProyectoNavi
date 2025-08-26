@extends('layouts.app')

@section('title', 'Gracias por Inscribirte')

@section('content')
@vite(['resources/css/despedida.css'])

<div class="thank-you-container">
    <h1>¡Gracias, {{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}!</h1>
    <p>Tu inscripción ha sido registrada correctamente.</p>

    <!-- Mostrar el ID real del usuario -->
    <p>Tu número de inscripción es el <strong id="usuario-id">{{ $usuario->id }}</strong>.</p>

    <p>Has agregado <strong id="cantidad-menores">{{ $cantidadMenores }}</strong> menor{{ $cantidadMenores != 1 ? 'es' : '' }}.</p>

    <p>¡Felices fiestas!</p>

    <button class="btn-print-ticket" id="btnPrintTicket">🖨 Imprimir Ticket</button>
    <a href="{{ route('inscripcion.bienvenida') }}" class="btn-back-home">← Volver al Inicio</a>
</div>

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.getElementById('btnPrintTicket').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: "portrait",
        unit: "mm",
        format: [80, 150]
    });

    const usuarioId = document.getElementById('usuario-id').textContent;
    const cantidadMenores = document.getElementById('cantidad-menores').textContent;
    const nombreUsuario = "{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}";

    doc.setFontSize(12);
    doc.text("Inscripción Navidad Coinco", 40, 15, {align: "center"});
    doc.setFontSize(10);
    doc.text(`Gracias, ${nombreUsuario}!`, 40, 25, {align: "center"});
    doc.text(`Número de Atención: ${usuarioId}`, 40, 35, {align: "center"});
    doc.text(`Cantidad de Menores: ${cantidadMenores}`, 40, 45, {align: "center"});
    doc.text("¡Felices fiestas!", 40, 55, {align: "center"});

    doc.setFontSize(8);
    doc.text("------------------------------", 40, 60, {align: "center"});
    doc.text("Generado automáticamente", 40, 65, {align: "center"});
    
    doc.save(`ticket_${usuarioId}.pdf`);
});
</script>
@endsection
