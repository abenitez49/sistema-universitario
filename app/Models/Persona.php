<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model {
    protected $guarded = [];

    protected $fillable = [
        'documento',
        'nombres',
        'apellidos',
        'fecha_nacimiento'
    ];

    public function user() {
        return $this->hasOne(User::class);
    }
    public function alumno() { return $this->hasOne(Alumno::class); }
    public function profesor() { return $this->hasOne(Profesor::class); }
}
