<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Profesor;
use Illuminate\Http\Request;

class MateriaProfesorController extends Controller
{
    public function index()
    {
        // Traemos las materias y sus profesores asignados
        $materias = Materia::with('profesores.persona')->paginate(10);
        
        // Traemos todos los profesores disponibles para llenar el <select>
        $profesores = Profesor::with('persona')->get();
        
        return view('asignaciones.profesores.index', compact('materias', 'profesores'));
    }

    public function store(Request $request, Materia $materia)
    {
        $request->validate([
            'profesor_id' => 'required|exists:profesores,id'
        ]);

        // syncWithoutDetaching vincula al profesor sin borrar a los demás y evita duplicados
        $materia->profesores()->syncWithoutDetaching([$request->profesor_id]);

        return redirect()->back()->with('success', 'Profesor asignado correctamente a la materia.');
    }

    public function destroy(Materia $materia, Profesor $profesor)
    {
        // detach rompe el vínculo en la tabla intermedia
        $materia->profesores()->detach($profesor->id);
        
        return redirect()->back()->with('success', 'Profesor desvinculado de la materia.');
    }
}