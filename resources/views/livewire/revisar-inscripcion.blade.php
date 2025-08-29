<div class="bienvenida-info-card p-3 text-center">

    <input type="number"
           wire:model="usuario_id"
           placeholder="Número de atención"
           class="form-control mb-2"
           maxlength="4"
           oninput="this.value = this.value.slice(0, 4)">

    <button wire:click="revisar"
            class="bienvenida-btn-primary w-100 mb-2">
        Revisar
    </button>

    @if($mensaje)
        <div class="alert mt-2 {{ $tipoMensaje == 'exito' ? 'alert-success' : 'alert-danger' }}">
            {{ $mensaje }}
        </div>
    @endif
</div>
