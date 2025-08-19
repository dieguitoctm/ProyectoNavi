<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatosUsuario;
use App\Models\Menor;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MenorController extends Controller
{
    public function formulario(DatosUsuario $usuario)
    {
        return view('inscripcion.menor', compact('usuario'));
    }

    public function guardar(Request $request, DatosUsuario $usuario)
    {
        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u',
            'rut' => 'required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:menores,rut',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'carnet_control_sano' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'certificado_nacimiento' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $hoy = Carbon::now();

        $edadAnios = $fechaNacimiento->diffInYears($hoy); // Edad en años exacta

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
            'edad' => $edadAnios, // Guardamos en años
            'carnet_control_sano' => $archivoControlSano,
            'certificado_nacimiento' => $archivoCertificado,
        ]);

        return redirect()->route('menor.formulario', $usuario->id)
            ->with('success', 'Menor registrado correctamente. Puede agregar otro o finalizar.');
    }

    public function update(Request $request, Menor $menor)
    {
        $request->validate([
            'nombres' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_paterno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'ap_materno' => 'required|regex:/^[\pL\s]+$/u|min:2|max:50',
            'rut' => "required|regex:/^\d{1,2}\.?\d{3}\.?\d{3}-[\dkK]$/|unique:menores,rut,{$menor->id}",
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Masculino,Femenino,Otro',
            'carnet_control_sano' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'certificado_nacimiento' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $hoy = Carbon::now();

        $edadAnios = $fechaNacimiento->diffInYears($hoy);

        if ($edadAnios >= 5) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe tener menos de 5 años.'])->withInput();
        }

        $menor->nombres = $request->nombres;
        $menor->ap_paterno = $request->ap_paterno;
        $menor->ap_materno = $request->ap_materno;
        $menor->rut = $request->rut;
        $menor->fecha_nacimiento = $request->fecha_nacimiento;
        $menor->genero = $request->genero;
        $menor->edad = $edadAnios; // Guardamos en años

        if ($request->hasFile('carnet_control_sano')) {
            if ($menor->carnet_control_sano) {
                Storage::delete($menor->carnet_control_sano);
            }
            $menor->carnet_control_sano = $request->file('carnet_control_sano')->store('archivos', 'public');
        }

        if ($request->hasFile('certificado_nacimiento')) {
            if ($menor->certificado_nacimiento) {
                Storage::delete($menor->certificado_nacimiento);
            }
            $menor->certificado_nacimiento = $request->file('certificado_nacimiento')->store('archivos', 'public');
        }

        $menor->save();

        return redirect()->back()->with('success', 'Menor actualizado correctamente.');
    }

    public function destroy(Menor $menor)
    {
        if ($menor->carnet_control_sano) {
            Storage::delete($menor->carnet_control_sano);
        }
        if ($menor->certificado_nacimiento) {
            Storage::delete($menor->certificado_nacimiento);
        }

        $menor->delete();

        return redirect()->back()->with('success', 'Menor eliminado correctamente.');
    }
}
