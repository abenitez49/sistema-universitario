<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::with('persona')->latest()->paginate(10);
        return view('alumnos.index', compact('alumnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|unique:personas,documento',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'legajo' => 'required|string|unique:alumnos,legajo',
        ]);

        DB::transaction(function () use ($request) {
            $persona = Persona::create($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            
            Alumno::create([
                'persona_id' => $persona->id,
                'legajo' => $request->legajo,
            ]);
        });

        return redirect()->back()->with('success', 'Alumno registrado correctamente.');
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'documento' => 'required|string|unique:personas,documento,' . $alumno->persona_id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'legajo' => 'required|string|unique:alumnos,legajo,' . $alumno->id,
        ]);

        DB::transaction(function () use ($request, $alumno) {
            $alumno->persona->update($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            $alumno->update(['legajo' => $request->legajo]);
        });

        return redirect()->back()->with('success', 'Alumno actualizado correctamente.');
    }

    public function destroy(Alumno $alumno)
    {
        // Al eliminar la persona base, el alumno se elimina automáticamente por cascada
        $alumno->persona->delete();
        return redirect()->back()->with('success', 'Alumno eliminado correctamente.');
    }
}