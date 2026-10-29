<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->date('fecha');
            
            // Las 4 horas del día
            $table->enum('hora_1', ['presente', 'ausente', 'justificado'])->default('presente');
            $table->enum('hora_2', ['presente', 'ausente', 'justificado'])->default('presente');
            $table->enum('hora_3', ['presente', 'ausente', 'justificado'])->default('presente');
            $table->enum('hora_4', ['presente', 'ausente', 'justificado'])->default('presente');
            
            // El 5to registro: el porcentaje diario
            $table->decimal('porcentaje', 5, 2)->default(100);
            
            $table->timestamps();

            // Evitar duplicados del mismo alumno, en la misma materia, el mismo día
            $table->unique(['materia_id', 'alumno_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};