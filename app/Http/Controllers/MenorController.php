<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatosUsuario;
use App\Models\Menor;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MenorController extends Controller
{
    // Mostrar formulario para agregar menores ligados a tutor
    public function formulario(DatosUsuario $usuario)
    {
        return view('inscripcion.menor', compact('usuario'));
    }

    // Guardar menores ligados a tutor
    public function guardar(Request $request, DatosUsuario $usuario)
    {
        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'rut' => 'required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:menores,rut',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'carnet_control_sano' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certificado_nacimiento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'ap_paterno.required' => 'El apellido paterno es obligatorio.',
            'ap_materno.required' => 'El apellido materno es obligatorio.',
            'rut.required' => 'El RUT es obligatorio.',
            'rut.regex' => 'El formato del RUT no es válido.',
            'rut.unique' => 'Este RUT ya está registrado.',
            'fecha_nacimiento.required' => 'Debe ingresar la fecha de nacimiento.',
            'genero.required' => 'Debe seleccionar un género.',
            'carnet_control_sano.required' => 'Debe subir el carnet de control sano.',
            'certificado_nacimiento.required' => 'Debe subir el certificado de nacimiento.',
        ]);

        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $edadAnios = $fechaNacimiento->age;

        // Validación: máximo 4 años 11 meses 29 días
        if ($edadAnios >= 5) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe tener menos de 5 años.'])->withInput();
        }

        $archivoControlSano = $request->file('carnet_control_sano')->store('archivos', 'public');
        $archivoCertificado = $request->file('certificado_nacimiento')->store('archivos', 'public');

        Menor::create([
            'usuario_id' => $usuario->id,
            'nombres' => $request->nombres,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'rut' => $request->rut,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'edad' => $edadAnios,
            'carnet_control_sano' => $archivoControlSano,
            'certificado_nacimiento' => $archivoCertificado,
        ]);

        return redirect()->route('menor.formulario', $usuario->id)
            ->with('success', 'Menor registrado correctamente. Puede agregar otro o finalizar.');
    }

    // Actualizar menor
    public function update(Request $request, Menor $menor)
    {
        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'rut' => "required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:menores,rut,{$menor->id}",
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'carnet_control_sano' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'certificado_nacimiento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $edadAnios = $fechaNacimiento->age;

        if ($edadAnios >= 5) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe tener menos de 5 años.'])->withInput();
        }

        $menor->update([
            'nombres' => $request->nombres,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'rut' => $request->rut,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'edad' => $edadAnios,
        ]);

        if ($request->hasFile('carnet_control_sano')) {
            if ($menor->carnet_control_sano) Storage::disk('public')->delete($menor->carnet_control_sano);
            $menor->carnet_control_sano = $request->file('carnet_control_sano')->store('archivos', 'public');
        }

        if ($request->hasFile('certificado_nacimiento')) {
            if ($menor->certificado_nacimiento) Storage::disk('public')->delete($menor->certificado_nacimiento);
            $menor->certificado_nacimiento = $request->file('certificado_nacimiento')->store('archivos', 'public');
        }

        $menor->save();

        return redirect()->back()->with('success', 'Menor actualizado correctamente.');
    }

    // Eliminar menor
    public function destroy(Menor $menor)
    {
        if ($menor->carnet_control_sano) Storage::disk('public')->delete($menor->carnet_control_sano);
        if ($menor->certificado_nacimiento) Storage::disk('public')->delete($menor->certificado_nacimiento);

        $menor->delete();

        return redirect()->back()->with('success', 'Menor eliminado correctamente.');
    }
}
