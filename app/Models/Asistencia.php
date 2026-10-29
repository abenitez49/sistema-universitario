<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $fillable = [
        'materia_id', 'alumno_id', 'fecha',
        'hora_1', 'hora_2', 'hora_3', 'hora_4', 
        'porcentaje'
    ];

    public function alumno() { return $this->belongsTo(Alumno::class); }
    public function materia() { return $this->belongsTo(Materia::class); }
}