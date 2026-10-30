<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $termino = $request->get('buscar');

        // Iniciamos la consulta base (incluyendo la relación 'persona')
        $query = Alumno::with('persona')->latest();

        // Si se envió un término de búsqueda, aplicamos los filtros
        if ($termino) {
            $query->where(function($q) use ($termino) {
                // Buscamos coincidencia en el legajo del alumno...
                $q->where('legajo', 'like', '%' . $termino . '%')
                // ... o buscamos coincidencia en nombres o apellidos de su persona relacionada
                ->orWhereHas('persona', function($qPersona) use ($termino) {
                    $qPersona->where('nombres', 'like', '%' . $termino . '%')
                            ->orWhere('apellidos', 'like', '%' . $termino . '%');
                });
            });
        }

        // Paginamos los resultados. 'appends' mantiene el parámetro 'buscar' al cambiar de página.
        $alumnos = $query->paginate(10)->appends(['buscar' => $termino]);

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