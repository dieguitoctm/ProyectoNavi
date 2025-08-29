<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatosUsuario;
use App\Models\Menor;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MenorController extends Controller
{
    // Mostrar formulario para agregar menores ligados a tutor (recibe hash público)
    public function formulario($hash)
    {
        $usuario = DatosUsuario::where('hash_id', $hash)->firstOrFail();
        return view('inscripcion.menor', compact('usuario'));
    }

    // Guardar menores ligados a tutor (recibe hash)
    public function guardar(Request $request, $hash)
    {
        $usuario = DatosUsuario::where('hash_id', $hash)->firstOrFail();

        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'rut' => 'required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:menores,rut',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'carnet_control_sano' => 'required|file|mimes:pdf,jpg,jpeg,png|',
            'certificado_nacimiento' => 'required|file|mimes:pdf,jpg,jpeg,png|',
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
        $minFecha = Carbon::create(2021, 1, 4);
        $maxFecha = Carbon::create(2025, 12, 31);

        // Validación de rango
        if ($fechaNacimiento->lt($minFecha) || $fechaNacimiento->gt($maxFecha)) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe haber nacido entre el 04 de enero de 2021 y el 31 de diciembre de 2025.'])->withInput();
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
            'edad' => $fechaNacimiento->age,
            'carnet_control_sano' => $archivoControlSano,
            'certificado_nacimiento' => $archivoCertificado,
        ]);

        return redirect()->route('menor.formulario', $usuario->hash_id)
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
            'carnet_control_sano' => 'nullable|file|mimes:pdf,jpg,jpeg,png|',
            'certificado_nacimiento' => 'nullable|file|mimes:pdf,jpg,jpeg,png|',
        ]);

        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $minFecha = Carbon::create(2021, 1, 4);
        $maxFecha = Carbon::create(2025, 12, 31);

        if ($fechaNacimiento->lt($minFecha) || $fechaNacimiento->gt($maxFecha)) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe haber nacido entre el 04 de enero de 2021 y el 31 de diciembre de 2025.'])->withInput();
        }

        $menor->update([
            'nombres' => $request->nombres,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'rut' => $request->rut,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'edad' => $fechaNacimiento->age,
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
