<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatosUsuario;
use App\Models\Menor;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MenorController extends Controller
{
<<<<<<< HEAD
=======
    // Mostrar formulario para agregar menores ligados a tutor
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
    public function formulario(DatosUsuario $usuario)
    {
        return view('inscripcion.menor', compact('usuario'));
    }

<<<<<<< HEAD
=======
    // Guardar menores ligados a tutor
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
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

<<<<<<< HEAD
        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $hoy = Carbon::now();

        $edadAnios = $fechaNacimiento->diffInYears($hoy); // Edad en años exacta

        if ($edadAnios >= 5) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe tener menos de 5 años.'])->withInput();
=======
        $edad = Carbon::parse($request->fecha_nacimiento)->age;
        if ($edad >= 6) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe ser menor de 6 años'])->withInput();
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
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
<<<<<<< HEAD
            'edad' => $edadAnios, // Guardamos en años
=======
            'edad' => $edad,
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
            'carnet_control_sano' => $archivoControlSano,
            'certificado_nacimiento' => $archivoCertificado,
        ]);

        return redirect()->route('menor.formulario', $usuario->id)
            ->with('success', 'Menor registrado correctamente. Puede agregar otro o finalizar.');
    }

<<<<<<< HEAD
=======
    // Actualizar menor
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
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

<<<<<<< HEAD
        $fechaNacimiento = Carbon::parse($request->fecha_nacimiento);
        $hoy = Carbon::now();

        $edadAnios = $fechaNacimiento->diffInYears($hoy);

        if ($edadAnios >= 5) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe tener menos de 5 años.'])->withInput();
=======
        $edad = Carbon::parse($request->fecha_nacimiento)->age;
        if ($edad >= 6) {
            return back()->withErrors(['fecha_nacimiento' => 'El menor debe ser menor de 6 años'])->withInput();
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
        }

        $menor->nombres = $request->nombres;
        $menor->ap_paterno = $request->ap_paterno;
        $menor->ap_materno = $request->ap_materno;
        $menor->rut = $request->rut;
        $menor->fecha_nacimiento = $request->fecha_nacimiento;
        $menor->genero = $request->genero;
<<<<<<< HEAD
        $menor->edad = $edadAnios; // Guardamos en años

=======
        $menor->edad = $edad;

        // Si envían nuevo carnet, eliminar el anterior y guardar el nuevo
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
        if ($request->hasFile('carnet_control_sano')) {
            if ($menor->carnet_control_sano) {
                Storage::delete($menor->carnet_control_sano);
            }
<<<<<<< HEAD
            $menor->carnet_control_sano = $request->file('carnet_control_sano')->store('archivos', 'public');
        }

=======
            $menor->carnet_control_sano = $request->file('carnet_control_sano')->store('public/archivos');
        }

        // Si envían nuevo certificado, eliminar el anterior y guardar el nuevo
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
        if ($request->hasFile('certificado_nacimiento')) {
            if ($menor->certificado_nacimiento) {
                Storage::delete($menor->certificado_nacimiento);
            }
<<<<<<< HEAD
            $menor->certificado_nacimiento = $request->file('certificado_nacimiento')->store('archivos', 'public');
=======
            $menor->certificado_nacimiento = $request->file('certificado_nacimiento')->store('public/archivos');
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
        }

        $menor->save();

        return redirect()->back()->with('success', 'Menor actualizado correctamente.');
    }

<<<<<<< HEAD
=======
    // Eliminar menor
>>>>>>> bc712e81460e84ab19c95f61c4a3575f8f9d22d6
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
