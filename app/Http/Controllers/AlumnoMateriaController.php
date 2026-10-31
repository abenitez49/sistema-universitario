<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoMateriaController extends Controller
{
    public function index(Request $request)
    {
        $termino = $request->get('buscar');
        
        $query = Materia::with('alumnos.persona');

        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('codigo', 'like', '%' . $termino . '%')
                ->orWhere('nombre', 'like', '%' . $termino . '%');
            });
        }

        $materias = $query->paginate(10)->appends(['buscar' => $termino]);
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