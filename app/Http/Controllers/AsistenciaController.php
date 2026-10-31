<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    // Muestra la lista de materias para elegir
    public function index(Request $request)
    {
        $user = Auth::user();
        $termino = $request->get('buscar');

        // 1. Definimos el punto de partida de la consulta según el rol
        if ($user->hasRole('Profesor') && !$user->hasRole('Administrador')) {
            $profesor = $user->persona->profesor ?? null;
            if ($profesor) {
                $query = $profesor->materias(); // Relación directa de sus materias
            } else {
                $query = Materia::where('id', 0); // Vacío si hay un error de datos
            }
        } else {
            $query = Materia::query(); // Administradores ven todas
        }

        // 2. Si el usuario escribió algo en el buscador, filtramos esos resultados
        if ($termino) {
            $query->where(function($q) use ($termino) {
                $q->where('codigo', 'like', '%' . $termino . '%')
                  ->orWhere('nombre', 'like', '%' . $termino . '%');
            });
        }

        // Ejecutamos la consulta contando alumnos y paginando
        $materias = $query->withCount('alumnos')->paginate(10)->appends(['buscar' => $termino]);

        return view('asistencias.index', compact('materias'));
    }

    // Muestra la lista de alumnos de una materia específica en una fecha
    public function show(Request $request, Materia $materia)
    {
        // Si no se envía fecha por el buscador, usamos la de hoy
        $fecha = $request->fecha ?? date('Y-m-d');
        
        // Traemos a los alumnos matriculados ordenados por legajo
        $alumnos = $materia->alumnos()->with('persona')->orderBy('legajo')->get();
        
        // Buscamos si ya se tomó lista ese día para precargar los datos
        $asistencias = Asistencia::where('materia_id', $materia->id)
                                 ->where('fecha', $fecha)
                                 ->get()
                                 ->keyBy('alumno_id'); // Facilita la búsqueda por ID en la vista

        return view('asistencias.show', compact('materia', 'alumnos', 'fecha', 'asistencias'));
    }

    // Guarda el pase de lista de toda el aula
    public function store(Request $request, Materia $materia)
    {
        $request->validate([
            'fecha' => 'required|date',
            'asistencia' => 'required|array'
        ]);

        foreach ($request->asistencia as $alumno_id => $horas) {
            
            // Calcular el porcentaje (Asumimos que Presente y Justificado = Asistencia Válida)
            $asistenciasValidas = 0;
            foreach (['hora_1', 'hora_2', 'hora_3', 'hora_4'] as $hora) {
                if (isset($horas[$hora]) && in_array($horas[$hora], ['presente', 'justificado'])) {
                    $asistenciasValidas++;
                }
            }
            
            $porcentaje = ($asistenciasValidas / 4) * 100;

            // Actualiza el registro si ya existe ese día, o crea uno nuevo
            Asistencia::updateOrCreate(
                ['materia_id' => $materia->id, 'alumno_id' => $alumno_id, 'fecha' => $request->fecha],
                [
                    'hora_1' => $horas['hora_1'] ?? 'ausente',
                    'hora_2' => $horas['hora_2'] ?? 'ausente',
                    'hora_3' => $horas['hora_3'] ?? 'ausente',
                    'hora_4' => $horas['hora_4'] ?? 'ausente',
                    'porcentaje' => $porcentaje
                ]
            );
        }

        return redirect()->back()->with('success', 'Lista de asistencia y porcentajes guardados exitosamente.');
    }
}



