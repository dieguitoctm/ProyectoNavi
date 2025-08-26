<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatosUsuario;
use App\Models\Embarazada;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsuariosMenoresExport;
use Maatwebsite\Excel\Facades\Excel;

class InscripcionController extends Controller
{
    // Página de bienvenida
    public function bienvenida()
    {
        return view('bienvenida');
    }

    // Mostrar formulario tutor (usuario + embarazo)
    public function formulario()
    {
        return view('inscripcion.formulario'); // formulario solo tutor + embarazo
    }

    // Guardar tutor + embarazo y redirigir a agregar menores (usa hash_id en la URL pública)
    public function guardar(Request $request)
    {
        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u',
            'telefono' => 'required|regex:/^\+569\d{8}$/',
            'direccion' => 'required|max:50',
            'rut' => 'required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:datos_usuarios,rut',
            'registro_social' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'embarazada' => 'nullable|in:si,no',
            'meses_gestacion' => 'required_if:embarazada,si|integer|min:1|max:9',
            'carnet_gestacion' => 'required_if:embarazada,si|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $archivoRegistroSocial = $request->file('registro_social')->store('archivos', 'public');

        $usuario = DatosUsuario::create([
            'nombres' => $request->nombres,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'rut' => $request->rut,
            'registro_social' => $archivoRegistroSocial,
        ]);

        if ($request->embarazada === 'si') {
            $archivoCarnet = $request->file('carnet_gestacion')->store('archivos', 'public');
            Embarazada::create([
                'usuario_id' => $usuario->id,
                'meses_gestacion' => $request->meses_gestacion,
                'carnet_gestacion' => $archivoCarnet,
            ]);
        }

        // Redirige a /menor/{hash_id} para agregar menores
        return redirect()->route('menor.formulario', $usuario->hash_id)
            ->with('success', 'Tutor registrado correctamente. Ahora puede agregar menores o finalizar.');
    }

    // Listar usuarios en admin
    public function index()
    {
        $usuarios = DatosUsuario::with(['embarazada', 'menores'])->paginate(10);
        return view('admin', compact('usuarios'));
    }

    // Actualizar usuario (admin) - usamos id numérico como antes
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => ['required', 'regex:/^[\pL\s]+$/u', 'min:2', 'max:50'],
            'ap_paterno' => ['required', 'regex:/^[\pL\s]+$/u', 'min:2', 'max:50'],
            'ap_materno' => ['required', 'regex:/^[\pL\s]+$/u', 'min:2', 'max:50'],
            'telefono' => ['required', 'regex:/^\+569\d{8}$/'],
            'direccion' => ['required', 'max:50'],
            'rut' => ['required', 'regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/', "unique:datos_usuarios,rut,{$id}"],
        ], [
            'nombres.regex' => 'Solo letras y espacios en Nombres.',
            'ap_paterno.regex' => 'Solo letras y espacios en Apellido Paterno.',
            'ap_materno.regex' => 'Solo letras y espacios en Apellido Materno.',
            'telefono.regex' => 'El teléfono debe comenzar con +569 seguido de 8 números.',
            'rut.regex' => 'El formato del RUT es inválido.',
            'rut.unique' => 'El RUT ya está registrado en otro usuario.',
        ]);

        $usuario = DatosUsuario::findOrFail($id);

        $usuario->nombres = $request->nombres;
        $usuario->ap_paterno = $request->ap_paterno;
        $usuario->ap_materno = $request->ap_materno;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->rut = $request->rut;

        $usuario->save();

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    // Método para eliminar usuario (admin)
    public function destroy($id)
    {
        $usuario = DatosUsuario::findOrFail($id);

        // Borrar archivos asociados si existen
        if ($usuario->registro_social) {
            Storage::delete($usuario->registro_social);
        }

        // Borrar embarazo y sus archivos asociados si existen
        if ($usuario->embarazada) {
            if ($usuario->embarazada->carnet_gestacion) {
                Storage::delete($usuario->embarazada->carnet_gestacion);
            }
            $usuario->embarazada->delete();
        }

        // Borrar menores asociados (opcional: borrar sus archivos también)
        foreach ($usuario->menores as $menor) {
            if ($menor->carnet_control_sano) {
                Storage::delete($menor->carnet_control_sano);
            }
            if ($menor->certificado_nacimiento) {
                Storage::delete($menor->certificado_nacimiento);
            }
            $menor->delete();
        }

        $usuario->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    // Página despedida -> ahora busca por hash público
    public function despedida($hash = null)
    {
        if (!$hash) {
            return redirect()->route('inscripcion.bienvenida');
        }

        $usuario = DatosUsuario::with('menores')->where('hash_id', $hash)->first();

        if (!$usuario) {
            return redirect()->route('inscripcion.bienvenida')->with('error', 'Usuario no encontrado.');
        }

        $cantidadMenores = $usuario->menores->count();

        return view('despedida', compact('usuario', 'cantidadMenores'));
    }



    
    public function exportExcel()
    {
        return Excel::download(new UsuariosMenoresExport, 'usuarios_menores.xlsx');
    }
}
