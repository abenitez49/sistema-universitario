<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfesorController extends Controller
{
    public function index(Request $request)
    {
        $termino = $request->get('buscar');
        $query = Profesor::with('persona')->latest();

        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('titulo', 'like', '%' . $termino . '%')
                ->orWhereHas('persona', function($qPersona) use ($termino) {
                    $qPersona->where('nombres', 'like', '%' . $termino . '%')
                            ->orWhere('apellidos', 'like', '%' . $termino . '%')
                            ->orWhere('documento', 'like', '%' . $termino . '%');
                });
            });
        }

        $profesores = $query->paginate(10)->appends(['buscar' => $termino]);
        return view('profesores.index', compact('profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|unique:personas,documento',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'titulo' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $persona = Persona::create($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            
            Profesor::create([
                'persona_id' => $persona->id,
                'titulo' => $request->titulo,
            ]);
        });

        return redirect()->back()->with('success', 'Profesor registrado correctamente.');
    }

    public function update(Request $request, Profesor $profesore)
    {
        // Nota: Laravel nombra la variable $profesore automáticamente por la "s" final en resource
        $request->validate([
            'documento' => 'required|string|unique:personas,documento,' . $profesore->persona_id,
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'titulo' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $profesore) {
            $profesore->persona->update($request->only(['documento', 'nombres', 'apellidos', 'fecha_nacimiento']));
            $profesore->update(['titulo' => $request->titulo]);
        });

        return redirect()->back()->with('success', 'Profesor actualizado correctamente.');
    }

    public function destroy(Profesor $profesore)
    {
        $profesore->persona->delete();
        return redirect()->back()->with('success', 'Profesor eliminado correctamente.');
    }
}