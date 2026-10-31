<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Profesor;
use Illuminate\Http\Request;

class MateriaProfesorController extends Controller
{
    public function index(Request $request)
    {
        $termino = $request->get('buscar');
        
        $query = Materia::with('profesores.persona');

        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('codigo', 'like', '%' . $termino . '%')
                ->orWhere('nombre', 'like', '%' . $termino . '%');
            });
        }

        $materias = $query->paginate(10)->appends(['buscar' => $termino]);
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