<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoMateriaController extends Controller
{
    public function index()
    {
        // Traemos las materias y sus alumnos matriculados
        $materias = Materia::with('alumnos.persona')->paginate(10);
        
        // Traemos todos los alumnos disponibles para el selector
        $alumnos = Alumno::with('persona')->get();
        
        return view('asignaciones.alumnos.index', compact('materias', 'alumnos'));
    }

    public function store(Request $request, Materia $materia)
    {
        $request->validate([
            'alumno_id' => 'required|exists:alumnos,id'
        ]);

        // Evita matricular al mismo alumno dos veces en la misma materia
        $materia->alumnos()->syncWithoutDetaching([$request->alumno_id]);

        return redirect()->back()->with('success', 'Alumno matriculado correctamente.');
    }

    public function destroy(Materia $materia, Alumno $alumno)
    {
        $materia->alumnos()->detach($alumno->id);
        
        return redirect()->back()->with('success', 'Alumno dado de baja de la materia.');
    }
}