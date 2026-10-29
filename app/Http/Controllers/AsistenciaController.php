<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    // Muestra la lista de materias para elegir
    public function index()
    {
        // Traemos las materias contando cuántos alumnos tienen
        $materias = Materia::withCount('alumnos')->paginate(10);
        return view('asistencias.index', compact('materias'));
    }

    // Muestra la lista de alumnos de una materia específica en una fecha
    public function show(Request $request, Materia $materia)
    {
        // Si no se envía fecha por el buscador, usamos la de hoy
        $fecha = $request->fecha ?? date('Y-m-d');
        
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