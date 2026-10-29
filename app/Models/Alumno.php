<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $fillable = [
        'persona_id',
        'legajo'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'alumno_materia');
    }
}