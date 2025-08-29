<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DatosUsuario;

class RevisarInscripcion extends Component
{
    public $usuario_id;
    public $mensaje = null;
    public $tipoMensaje = null; // importante inicializar

    public function revisar()
    {
        // Validar que sea un número de máximo 4 dígitos
        if (!preg_match('/^\d{1,4}$/', $this->usuario_id)) {
            $this->mensaje = "Ingrese un número de atención válido (máximo 4 dígitos).";
            $this->tipoMensaje = 'error';
            return;
        }

        $usuario = DatosUsuario::with('menores')->find($this->usuario_id);

        //dd($usuario->menores->count());

        if ($usuario) {
            $cantidadMenores = $usuario->menores->count(); // contar menores ligados
            $this->mensaje = "Inscripción encontrada correctamente. Menores registrados: $cantidadMenores";
            $this->tipoMensaje = 'exito';
        } else {
            $this->mensaje = "Su número de atención no se encuentra registrado debido a un error de inscripción o falta de documentos, por favor contáctenos.";
            $this->tipoMensaje = 'error';
        }
    }

    public function render()
    {
        return view('livewire.revisar-inscripcion');
    }
}
