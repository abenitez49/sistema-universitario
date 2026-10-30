<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $termino = $request->get('buscar');
        $query = Materia::latest();

        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('codigo', 'like', '%' . $termino . '%')
                ->orWhere('nombre', 'like', '%' . $termino . '%');
            });
        }

        $materias = $query->paginate(10)->appends(['buscar' => $termino]);
        return view('materias.index', compact('materias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:materias,codigo|max:50',
        ]);

        Materia::create($validated);
        
        // Redirigimos hacia atrás enviando un mensaje de éxito a la vista
        return redirect()->back()->with('success', 'Materia registrada correctamente.');
    }

    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:materias,codigo,' . $materia->id,
        ]);

        $materia->update($validated);
        
        return redirect()->back()->with('success', 'Materia actualizada correctamente.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();
        
        return redirect()->back()->with('success', 'Materia eliminada correctamente.');
    }
}