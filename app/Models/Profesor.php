<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table = 'profesores'; // Forzamos el nombre en español

    protected $fillable = [
        'persona_id',
        'titulo'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_profesor');
    }
}