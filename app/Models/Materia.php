<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    // 1. Autorizar estos campos para la asignación masiva
    protected $fillable = [
        'nombre',
        'codigo',
    ];

    // 2. Ya que estamos aquí, dejamos preparadas las relaciones 
    // para cuando hagamos la asignación de materias a profesores y alumnos
    public function profesores() 
    {
        return $this->belongsToMany(Profesor::class, 'materia_profesor');
    }

    public function alumnos() 
    {
        return $this->belongsToMany(Alumno::class, 'alumno_materia');
    }
}